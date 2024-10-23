<?php

require_once 'connection.php';
require '../models/people/reader.php';

final class DB {
    const READER_TABLE = 'readers';
    const READER_FIELDS = ['id', 'login', 'passphrase', 'name', 'phone', 'role', 'debt', 'can_borrow', 'can_register'];
    const CLASSROOM_TABLE = 'classrooms';
    const CLASSROOM_FIELDS = ['name', 'year'];
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

enum DBOps : string {
    case INSERT = 'INSERT';
    //case DELETE = 'DELETE';
    case UPDATE = 'UPDATE';
    //case SELECT = 'SELECT';
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

    private function get_crud_clause_for(DBOps $op, string $t_name, array $t_fields): string{
        if ($op === DBOps::UPDATE)
            $set_clauses = array();
            foreach ($t_fields as $f) 
                $set_clauses[] = "$f = :$f";
        
        return match ($op){
            // Clause after "INSERT INTO ":
            DBOps::INSERT => $t_name . " (" . implode(', ', $t_fields) .") VALUES (" . implode(', :', $t_fields) . ")",
            // Clause after "UPDATE ", with SET:
            DBOps::UPDATE => $t_name . " SET " . implode(', ', $set_clauses) . " WHERE id = :id",
        };
    }

    public function insert_record_in(string $t_name, array $data){
        //Send to insertion clause only non-empty fields:
        foreach ($data as $field => $value)
            if (isset($value)) 
                $t_fields[] = $field;

        $clause = self::get_crud_clause_for(DBOps::INSERT, $t_name, $t_fields);
        $sql = "INSERT INTO " . $clause;
        $stmt = $this -> pdo -> prepare($sql);
        foreach ($t_fields as $field) 
            $stmt -> bindValue(":$field", $data[$field]);
        return $stmt->execute();
    }

    /**
     * States the standard update behaviour for tables with an integer id.
     */
    public function update_entity_with_id_in(string $t_name, array $data){
        if (!isset($data['id'])) 
            throw new InvalidArgumentException("An entity ID is required for update.");
        
        //Send to updating clause only non-empty fields:
        foreach ($data as $field => $value)
            if (isset($value)) 
                $t_fields[] = $field;
        $clause = self::get_crud_clause_for(DBOps::UPDATE, $t_name, $t_fields);

        $sql = "UPDATE " . $clause;
        $stmt = $this -> pdo -> prepare($sql);
        foreach ($t_fields as $f) $stmt -> bindValue(":$f", $data[$f]);
        return $stmt->execute();
    }

    /**
     * States the standard update behaviour for tables without id as
     * their primary key.
     */
    public function update_entity_without_id_in(string $t_name, array $data){
        if (isset($data['id']) or !in_array($t_name, DB::TABLES_WITHOUT_ID)) 
            throw new InvalidArgumentException("You should be using the method update_entity_with_id_in for $t_name table.");

    }


    public function delete_record_in(){
        
    }

    public function fetch_record_by_id_from(string $t_name, int $id): array{
        $stmt = $this->pdo->prepare("SELECT * FROM " . $t_name . " WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // É REALMENTE NECESSÁRIA???
    public function fetch_where_from(string $t_name, string $condition){
        $stmt = $this->pdo->prepare("SELECT * FROM " . $t_name . " WHERE " . $condition);
        $stmt->execute();
        return $stmt->fetch();
    }

}

?>