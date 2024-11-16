<?php

require_once ('connection.php');
require_once (__DIR__ . '/../models/people/reader.php');

final class DB {
    const READER_TABLE = 'readers';
    const READER_FIELDS = ['id', 'login', 'passphrase', 'name', 'phone', 'role', 'debt', 'can_borrow', 'can_register', 'last_login'];
    const CLASSROOM_TABLE = 'classrooms';
    const CLASSROOM_FIELDS = ['id', 'name', 'year'];
    const ENROLLMENT_TABLE = 'enrollments';
    const ENROLLMENT_FIELDS = ['student_id', 'classroom_id'];
    const TEACHING_TABLE = 'teachings';
    const TEACHING_FIELDS = ['teacher_id', 'classroom_id'];
    const LIBRARY_SETTING_TABLE = 'Library_Settings';
    const LIBRARY_SETTING_FIELDS = ['loan_deadline', 'late_fee', 'late_fee_period', 'head_reserve_queue_deadline'];
    const OPUS_TABLE = 'opuses';
    const OPUS_FIELDS = ['id', 'title', 'original_year', 'ddc', 'cutter_sanborn', 'alternative_url'];
    const WRITER_TABLE = 'writers';
    const WRITER_FIELDS = ['id', 'name', 'birth_year'];
    const AUTHORSHIP_TABLE = 'authorship';
    const AUTHORSHIP_FIELDS = ['author_id', 'opus_id'];
    const PUBLISHER_TABLE = 'publishers';
    const PUBLISHER_FIELDS = ['id', 'name'];
    const EDITION_TABLE = 'editions';
    const EDITION_FIELDS = ['id', 'isbn', 'opus_id', 'publisher_id', 'translators', 'edition_number', 'volume', 'collection_id', 'pages', 'publishing_year', 'cover_colors'];
    const COLLECTION_TABLE = 'collections';
    const COLLECTION_FIELDS = ['id', 'name', 'publisher_id'];
    const BOOK_COPY_TABLE = 'bookshelf';
    const BOOK_COPY_FIELDS = ['id', 'edition_id', 'asset_code', 'status'];
    const LOAN_TABLE = 'loans';
    const LOAN_FIELDS = ['id', 'copy_id', 'reader_id', 'opener_id', 'opening_date', 'closer_id', 'closing_date', 'loan_date', 'return_date', 'debt', 'debt_receiver_id'];
    const RECOMENDATION_TABLE = 'recomendations';
    const RECOMENDATION_FIELDS = ['opus_id', 'student_id', 'teacher_id', 'operation_date'];
    const RESERVATION_TABLE = 'reservations';
    const RESERVATION_FIELDS = ['opus_id', 'reader_id', 'operation_date'];
    const SYNOPSIS_TABLE = 'synopses';
    const SYNOPSIS_FIELDS = ['id', 'opus_id', 'student_id', 'teacher_id', 'status', 'updating_date'];
    const LIKE_TABLE = 'likes';
    const LIKE_FIELDS = ['synopsis_id', 'liker_id'];

    const TABLES_WITHOUT_ID = [
        self::ENROLLMENT_TABLE, 
        self::TEACHING_TABLE,
        self::LIBRARY_SETTING_TABLE,
        self::AUTHORSHIP_TABLE,
        self::RECOMENDATION_TABLE,
        self::RESERVATION_TABLE,
        self::LIKE_TABLE];
}

enum DML_OPS {
    case INSERT;
    case UPDATE;
    case DELETE;
}

final class DAOManager{
    private $pdo;

    public function __construct(){$this -> pdo = Connection::connect();}

    /**
     * Test if the given id in the readers table is authorized to registrations. 
     * @param int $id 
     * @var Reader $u 
     * @return bool
     * */
    public function can_user_register(int $id): bool{ 
        /** @var Reader $u  */
        $u = Reader::fromArray(
            $this -> fetch_record_by_id_from(DB::READER_TABLE, $id), 
            true); // Factory used truly for fetching
        return $u -> get_can_register();
    }

    public static function can_teacher_loan(int $teacher_id): bool{ 
        /** @var Reader $u  */
        $u = Reader::fromArray(
            self::fetch_record_by_id_from(DB::READER_TABLE, $teacher_id), 
            true); // Factory used truly for fetching
        return $u -> get_can_loan();
    }

    // >>> DML
    private function get_dml_clause_for(DML_OPS $op, string $t_name, array $t_fields): string{
        if ($op === DML_OPS::UPDATE){
            $set_clauses = array();
            foreach ($t_fields as $f) $set_clauses[] = "$f = :$f";
        }

        return match ($op){
            DML_OPS::INSERT => "INSERT INTO $t_name (" . implode(', ', $t_fields) .") VALUES (:" . implode(', :', $t_fields) . ")",
            DML_OPS::UPDATE => "UPDATE $t_name  SET " . implode(', ', $set_clauses) . " WHERE id = :id",
            DML_OPS::DELETE => "DELETE FROM $t_name WHERE id = :id",
        };
    }

    // ----- Insertion:
    public function insert_record_in(string $t_name, array $data): bool {
        //Send to insertion clause only non-empty fields:
        $t_fields = [];
        foreach ($data as $field => $value) 
            if (isset($value) || is_bool($value))  // allow bools to pass even if they are false
                $t_fields[] = $field;       

        $sql = self::get_dml_clause_for(DML_OPS::INSERT, $t_name, $t_fields);
        $stmt = $this -> pdo -> prepare($sql);
        
        foreach ($t_fields as $field) {
            try {
                if (is_bool($data[$field])) {
                    $stmt->bindValue(":$field", $data[$field], PDO::PARAM_BOOL);
                } else {
                    $stmt->bindValue(":$field", $data[$field]);
                }
            }   
            catch (PDOException $e) {die("Connection failed: " . $e->getMessage() . $field . 'SQL Statement: '. $sql);}
        }
        
        try {return $stmt->execute();}
        catch (PDOException $e) {die("Connection failed: " . $e->getMessage());}
    }   
    
    // ----- Updating:
    /**
     * States the standard update behaviour for tables with an integer id.
     */
    public function update_entity_in(string $t_name, array $data): bool {
        if (!isset($data['id'])) 
            throw new InvalidArgumentException("An entity ID is required for update.");
        
        foreach ($data as $field => $value) $t_fields[] = $field;
    
        $dml = self::get_dml_clause_for(DML_OPS::UPDATE, $t_name, $t_fields);
        $stmt = $this -> pdo -> prepare($dml);
        foreach ($data as $f => $value){
            if (is_bool($value)) $stmt->bindValue(":$f", $value, PDO::PARAM_BOOL);
            else $stmt->bindValue(":$f", $value);
        }
        try {return $stmt->execute();}
        catch (PDOException $e) {die("Connection failed: " . $e->getMessage(). "DML: $dml");}
    }

    /**
     * States the standard update behaviour for tables without id as
     * their primary key.
     */
    public function update_relationship(
        string $element_table, string $relation_table,
         string $element_fk_field, string $relation_fk_field,
        int $relation_fk_value, array $element_data): bool {
            if (!in_array($relation_table, DB::TABLES_WITHOUT_ID)) 
                throw new InvalidArgumentException("You should be using the method update_entity_in in table $relation_table.");

            $sql = "UPDATE $relation_table rt
                    SET $relation_fk_field = :relation_fk_value
                    FROM $element_table et
                    WHERE rt.$element_fk_field = :element_fk_value";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":element_fk_value", $element_data['id']);
            $stmt->bindValue(":relation_fk_value", $relation_fk_value);
            try {return $stmt->execute();}
            catch (PDOException $e) {die("Connection failed: " . $e->getMessage(). "DML: $sql");}
    }

    
    
    // ----- Deleting:
    public function delete_record_in(string $t_name, int $id): bool {
        $sql = self::get_dml_clause_for(DML_OPS::DELETE, $t_name, array());
        $stmt = $this -> pdo -> prepare($sql);
        $stmt->bindValue(":id", $id);
        try {return $stmt->execute();}
        catch (PDOException $e) {die("Connection failed: " . $e->getMessage(). "DML: $sql");}
    }
    
    public function delete_relationship(
        string $relation_table,
        string $relation_fk_field, string $element_fk_field,
        int $relation_fk_value, int $element_fk_value): bool {
            if (!in_array($relation_table, DB::TABLES_WITHOUT_ID)) 
                throw new InvalidArgumentException("You should be using the method delete_record_in table $relation_table.");
            
            $sql = "DELETE FROM $relation_table 
                    WHERE $relation_fk_field = :relation_fk_value
                    AND $element_fk_field = :element_fk_value";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":relation_fk_value", $relation_fk_value);
            $stmt->bindValue(":element_fk_value", $element_fk_value);
            return  $stmt->execute();
    }
    
    
    // >>> DQL
    // ----- DQL Clause builders:
    private function get_base_select_clause(string $t_name, array $fields, bool $distinct = false): string {
        $field_clause = empty($fields) ? "*" : implode(', ', $fields);
        $distinct_clause = $distinct ? "DISTINCT " : "";
        return "SELECT $distinct_clause $field_clause FROM $t_name";
    }

    /**
     * Generate elaborated where clauses based conditions and logic passed.
     * @var conditions: An array of arrays, whose keys should be 'field' and 'operator'.
     */
    private function get_where_clause(
        array $conditions, string $logic = 'AND', bool $is_second_clause = false): string {
        if (empty($conditions)) return ""; // No conditions, no WHERE clause
        
        foreach ($conditions as $c) {
            $field = $c['field'];
            $operator = strtoupper($c['operator'] ?? '=');
            $valuePlaceholder = ':'.str_replace('.', '_', $c['field']);
            $clauses[] = "$field $operator $valuePlaceholder";
        }
        if ($is_second_clause) return "AND " . implode(" $logic ", $clauses);
        else return "WHERE " . implode(" $logic ", $clauses);
    }
    
    private function get_grouping_clause(?string $grouping_key): string {
        return isset($grouping_key) ? "GROUP BY $grouping_key" : "";
    }

    private function get_ordering_clause(?string $ordering_key): string {
        return isset($ordering_key) ? "ORDER BY $ordering_key" : "";
    }
    
    private function get_on_clause(array $conditions, string $logic = 'AND'): string {
        $on_clauses = [];
        foreach ($conditions as $condition) {
            $field1 = $condition['field1'] ?? null;
            $operator = $condition['operator'] ?? '=';
            $field2 = $condition['field2'] ?? null;
    
            if (!$field1 || !$field2) {
                throw new InvalidArgumentException("Both 'field1' and 'field2' are required for an ON clause condition.");
            }
    
            $on_clauses[] = "$field1 $operator $field2";
        }
    
        return implode(" $logic ", $on_clauses);
    }
    
    private function build_select_query(
        string $t_name,
        array $fields = [],
        array $where_conditions = [],
        string $logic = 'AND',
        ?string $ordering_key = null,
        bool $distinct = false): string {
        $base_query = $this->get_base_select_clause($t_name, $fields, $distinct);
        $where_clause = $this->get_where_clause($where_conditions, $logic);
        $ordering_clause = $this->get_ordering_clause($ordering_key);
        return "$base_query $where_clause $ordering_clause";
    }

    private function build_joint_query(
        string $t1_name, string $t2_name,
        array $t1_fields = [], array $t2_fields = [],
        array $on_conditions, string $on_logic = 'AND',
        array $t1_where_conditions = [], array $t2_where_conditions = [],
        string $t1_where_logic = 'AND', string $t2_where_logic = 'AND',
        ?string $ordering_key = null, bool $distinct = false): string {
            $select_clause = ($distinct ? "DISTINCT " : "") .
                (empty($t1_fields) ? "$t1_name.*" : implode(', ', $t1_fields)) . ", " .
                (empty($t2_fields) ? "$t2_name.*" : implode(', ', $t2_fields));
        
            $t1_where_clause = $this->get_where_clause($t1_where_conditions, $t1_where_logic);
            $t2_where_clause = $this->get_where_clause($t2_where_conditions, $t2_where_logic, true);
            $on_clause = $this->get_on_clause($on_conditions, $on_logic);
            $ordering_clause = $this -> get_ordering_clause($ordering_key);
            $sql = "SELECT $select_clause FROM $t1_name JOIN $t2_name ON $on_clause $t1_where_clause $t2_where_clause $ordering_clause";
        
            return $sql;
    }
    
    private function build_elements_relation_joint_query(
        string $e1_table, string $e2_table, string $relation_table,
        array $e1_fields, array $e2_fields, array $rt_fields,
        string $grouping_key,
        array $e1_where_conditions = [], array $e2_where_conditions = [],
        string $e1_where_logic = 'AND', string $e2_where_logic = 'AND',
        ?string $ordering_key = null, bool $distinct = false): string {
            $select_clause = ($distinct ? "DISTINCT " : "").
                (empty($e1_fields) ? '': "$e1_table." . implode(", $e1_table.", $e1_fields)).', '.
                (empty($e1_fields) ? '':
                "json_agg(json_build_object(".
                    implode(", ", array_map(fn($f) => "'$f', $e2_table.$f", $e2_fields)).")) AS $e2_table");

            $e1_where_clause = $this->get_where_clause($e1_where_conditions, $e1_where_logic);
            $e2_where_clause = $this->get_where_clause($e2_where_conditions, $e2_where_logic, true);

            $on_clause1 = "$relation_table.".$rt_fields['e1_id']." = $e1_table.id";
            $on_clause2 = "$relation_table.".$rt_fields['e2_id']." = $e2_table.id";
            $grouping_clause = $this -> get_grouping_clause($grouping_key);
            $ordering_clause = $this -> get_ordering_clause($ordering_key);
            
            return "SELECT $select_clause FROM $relation_table
                JOIN $e1_table ON $on_clause1
                JOIN $e2_table ON $on_clause2
                $e1_where_clause $e2_where_clause $grouping_clause $ordering_clause";
    }
    

    // ----- Fetchers:
    public function fetch_all_records_from(string $t_name): mixed{ //OK
        $stmt = $this->pdo->prepare("SELECT * FROM " . $t_name);
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {die("Connection failed: " . $e->getMessage() . ' SQL Statement: ' . $stmt->queryString);}
    }
    
    public function fetch_record_by_id_from(string $t_name, int $id): mixed{
        $stmt = $this->pdo->prepare("SELECT * FROM " . $t_name . " WHERE id = :id");
        $stmt->execute([':id' => $id]); $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    /**
     * General purpose-searching in a single table.
     */
    public function fetch_records_from( //OK
        array $search,    
        string $t_name,
        array $t_fields,
        array $where_conditions = [],
        string $logic = 'AND',
        ?string $ordering_key = null,
        bool $distinct = false): array {
        $dql = self::build_select_query($t_name, $t_fields, $where_conditions, $logic, $ordering_key, $distinct);
        $stmt = $this->pdo->prepare($dql);

        foreach ($search as $field => $value) $stmt->bindValue(":$field", $value);      
    
        try {$stmt->execute(); $stmt->setFetchMode(PDO::FETCH_ASSOC);}
        catch (PDOException $e) {die("Connection failed: " . $e->getMessage() . '\nDQL Statement: '. $dql);}
        return $stmt->fetchAll();
    }
    

    /**
     * General purpose searching in two joined tables.
     */
    public function fetch_jointed_records_from(
        array $search, string $t1_name, string $t2_name,
        array $t1_fields = [], array $t2_fields = [],
        array $on_conditions, string $on_logic = 'AND',
        array $t1_where_conditions = [], array $t2_where_conditions = [],
        string $t1_where_logic = 'AND', string $t2_where_logic = 'AND',
        ?string $ordering_key = null, bool $distinct = false): mixed{
        $dql = self::build_joint_query(
            $t1_name, $t2_name, $t1_fields, $t2_fields,
            $on_conditions, $on_logic, $t1_where_conditions, $t2_where_conditions,
            $t1_where_logic, $t2_where_logic, $ordering_key, $distinct
        );
        $stmt = $this -> pdo -> prepare($dql);
        foreach ($search as $f => $value) $stmt -> bindValue(":$f", $value);
        $stmt -> execute(); $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt -> fetchAll();
    }

    public function fetch_elements_relation_joint(
        array $search, string $e1_table, string $e2_table, string $relation_table,
        array $e1_fields, array $e2_fields, array $rt_fields,
        string $grouping_key,
        array $e1_where_conditions = [], array $e2_where_conditions = [],
        string $e1_where_logic = 'AND', string $e2_where_logic = 'AND',
        ?string $ordering_key = null, bool $distinct = false){
        $dql = self::build_elements_relation_joint_query(
            $e1_table, $e2_table, $relation_table,
            $e1_fields, $e2_fields, $rt_fields, $grouping_key,
            $e1_where_conditions, $e2_where_conditions,
            $e1_where_logic, $e2_where_logic,
            $ordering_key, $distinct);
        $stmt = $this -> pdo -> prepare($dql);
        foreach ($search as $f => $value) $stmt -> bindValue(":$f", $value);
        $stmt -> execute(); $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $results = $stmt -> fetchAll();
        foreach ($results as &$row) 
            if (isset($row[$e2_table])) 
                $row[$e2_table] = json_decode($row[$e2_table], true); // Decode writers field into an array
        return $results;
        
    }

    public function fetch_complex_join_dql(string $dql, array $search){
        $stmt = $this -> pdo -> prepare($dql);
        foreach ($search as $f => $value) $stmt -> bindValue(":$f", $value);
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {die("Connection failed: " . $e->getMessage() . ' SQL Statement: ' . $stmt->queryString);}
    }
}

?>