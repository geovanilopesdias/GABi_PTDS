<?php

require_once 'connection.php';
require '../models/people/reader.php';

final class DB {
    const READER_TABLE = 'readers';
    const READER_FIELDS = ['id', 'login', 'passphrase', 'name', 'phone', 'role', 'debt', 'can_borrow', 'can_register'];
    const CLASSROOM_TABLE = 'classrooms';
    const CLASSROOM_FIELDS = ['id', 'name', 'year'];
    const ENROLLMENT_TABLE = 'enrollments';
    const ENROLLMENT_FIELDS = ['student_id', 'classroom_id'];
    const TEACHING_TABLE = 'teachings';
    const TEACHING_FIELDS = ['teacher_id', 'classroom_id'];
    const LIBRARY_SETTING_TABLE = 'Library_Settings';
    const LIBRARY_SETTING_FIELDS = ['loan_deadline', 'late_fee', 'late_fee_period', 'head_reserve_queue_deadline'];
    const OPUS_TABLE = 'opuses';
    const OPUS_FIELDS = ['id', 'title', 'original_year', 'ddc', 'cutter_sunborn', 'alternative_url'];
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
    const BOOK_COPY_FIELDS = ['id', 'edition_id', 'asset_number', 'status'];
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
    public static function can_user_register(int $id): bool{ 
        /** @var Reader $u  */
        $u = Reader::fromArray(
            self::fetch_record_by_id_from(DB::READER_TABLE, $id), 
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
            DML_OPS::INSERT => "INSERT INTO $t_name (" . implode(', ', $t_fields) .") VALUES (" . implode(', :', $t_fields) . ")",
            DML_OPS::UPDATE => "UPDATE $t_name  SET " . implode(', ', $set_clauses) . " WHERE id = :id",
            DML_OPS::DELETE => "DELETE FROM $t_name WHERE id = :id",
        };
    }

    // ----- Insertion:
    public function insert_record_in(string $t_name, array $data): bool {
        //Send to insertion clause only non-empty fields:
        foreach ($data as $field => $value)
            if (isset($value)) 
                $t_fields[] = $field;

        $sql = self::get_dml_clause_for(DML_OPS::INSERT, $t_name, $t_fields);
        $stmt = $this -> pdo -> prepare($sql);
        foreach ($t_fields as $field) 
            $stmt -> bindValue(":$field", $data[$field]);
        return $stmt->execute();
    }

    
    
    // ----- Updating:
    /**
     * States the standard update behaviour for tables with an integer id.
     */
    public function update_entity_in(string $t_name, array $data): bool {
        if (!isset($data['id'])) 
            throw new InvalidArgumentException("An entity ID is required for update.");
        
        //Send to updating clause only non-empty fields:
        $t_fields = array();
        foreach ($data as $field => $value)
            if (isset($value)) 
                $t_fields[] = $field;

        $dml = self::get_dml_clause_for(DML_OPS::UPDATE, $t_name, $t_fields);
        $stmt = $this -> pdo -> prepare($dml);
        foreach ($t_fields as $f) $stmt -> bindValue(":$f", $data[$f]);
        return $stmt->execute();
    }

    /**
     * States the standard update behaviour for tables without id as
     * their primary key.
     */
    public function update_relationship(
        string $element_table, string $relation_table,
        string $container_fk_field, string $element_fk_field,
        int $container_fk_value, array $element_data): bool {
            if (!in_array($relation_table, DB::TABLES_WITHOUT_ID)) 
                throw new InvalidArgumentException("You should be using the method update_entity_in in table $relation_table.");
            
            $sql = "UPDATE $relation_table AS rt JOIN $element_table AS et
                    ON rt.$element_fk_field = :et.id
                    SET rt.$element_fk_field = :element_fk_value
                    WHERE rt.$container_fk_field = :container_fk_value";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":element_fk_value", $element_data['id']);
            $stmt->bindValue(":container_fk_value", $container_fk_value);
            return  $stmt->execute();
    }

    
    
    // ----- Deleting:
    public function delete_record_in(string $t_name, int $id): bool {
        $sql = self::get_dml_clause_for(DML_OPS::DELETE, $t_name, array());
        $stmt = $this -> pdo -> prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }
    
    public function delete_relationship(
        string $relation_table,
        string $container_fk_field, string $element_fk_field,
        int $container_fk_value, int $element_fk_value): bool {
            if (!in_array($relation_table, DB::TABLES_WITHOUT_ID)) 
                throw new InvalidArgumentException("You should be using the method delete_record_in table $relation_table.");
            
            $sql = "DELETE FROM $relation_table 
                    WHERE $container_fk_field = :container_fk_value
                    AND $element_fk_field = :element_fk_value";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":container_fk_value", $container_fk_value);
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

    private function get_where_clause(array $conditions, string $logic = 'AND'): string {
        if (empty($conditions)) return ""; // No conditions, no WHERE clause
        $clauses = [];
        foreach ($conditions as $condition) {
            $field = $condition['field'];
            $operator = $condition['operator'] ?? '='; // Default to '=' if not specified
            $valuePlaceholder = ":$field";
    
            // Adjust the clause based on operator
            if (strtoupper($operator) === 'LIKE' || strtoupper($operator) === '!=') {
                $clauses[] = "$field $operator $valuePlaceholder";
            } else {
                $clauses[] = "$field = $valuePlaceholder";
            }
        }    
        return "WHERE " . implode(" $logic ", $clauses);
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
            $t2_where_clause = $this->get_where_clause($t2_where_conditions, $t2_where_logic);
            $on_clause = $this->get_on_clause($on_conditions, $on_logic);
            $sql = "SELECT $select_clause FROM $t1_name JOIN $t2_name ON $on_clause";
            
            if ($t1_where_clause) $sql .= " WHERE " . $t1_where_clause;
            if ($t2_where_clause) $sql .= ($t1_where_clause ? " AND " : " WHERE ") . $t2_where_clause;
            if ($ordering_key) $sql .= " ORDER BY $ordering_key";
        
            return $sql;
    }
    
    

    // ----- Fetchers:
    public function fetch_all_records_from(string $t_name): array{
        $stmt = $this->pdo->prepare("SELECT * FROM " . $t_name);
        return $stmt->fetchAll();
    }
    
    public function fetch_record_by_id_from(string $t_name, int $id): array{
        $stmt = $this->pdo->prepare("SELECT * FROM " . $t_name . " WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Excluir e aplicar fetch_records_from em people_dao.php
    public function fetch_records_by_text_from(string $t_name, string $field, string $keyword): array{
        $stmt = $this->pdo->prepare("SELECT * FROM " . $t_name . " WHERE $field LIKE :%$field%");
        $stmt->execute([':$field' => $keyword]);
        return $stmt->fetchAll();
    }

    /**
     * General purpose-searching in a single table.
     */
    public function fetch_records_from(
            array $search,    
            string $t_name,
            array $t_fields,
            array $where_conditions = [],
            string $logic = 'AND',
            ?string $ordering_key = null,
            bool $distinct = false
        ): array{
        $dql = self::build_select_query($t_name, $t_fields, $where_conditions, $logic, $ordering_key, $distinct);
        $stmt = $this -> pdo -> prepare($dql);
        foreach ($search as $f => $value) $stmt -> bindValue(":$f", $value);
        $stmt -> execute();
        return $stmt -> fetchAll();
    }

    /**
     * General purpose searching in thwo joined tables.
     */
    public function fetch_jointed_records_from(
        array $search, string $t1_name, string $t2_name,
        array $t1_fields = [], array $t2_fields = [],
        array $on_conditions, string $on_logic = 'AND',
        array $t1_where_conditions = [], array $t2_where_conditions = [],
        string $t1_where_logic = 'AND', string $t2_where_logic = 'AND',
        ?string $ordering_key = null, bool $distinct = false): array{
        $dql = self::build_joint_query(
            $t1_name, $t2_name, $t1_fields, $t2_fields,
            $on_conditions, $on_logic, $t1_where_conditions, $t2_where_conditions,
            $t1_where_logic, $t2_where_logic, $ordering_key, $distinct
        );
        $stmt = $this -> pdo -> prepare($dql);
        foreach ($search as $f => $value) $stmt -> bindValue(":$f", $value);
        $stmt -> execute();
        return $stmt -> fetchAll();
}



}

?>