<?php

require_once (__DIR__ . '/../managers/dao_mng.php');
require_once (__DIR__ . '/../models/people/reader.php');
require_once (__DIR__ . '/../models/people/classroom.php');
require_once (__DIR__ . '/../models/people/teaching.php');
require_once (__DIR__ . '/../models/people/enrollment.php');
// require_once ('book_dao.php');

final class PeopleDAO{
    // Registration:
    public static function register_student(array $data, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $s = Reader::Student($data['name'], $data['login'], $data['phone']);
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public static function register_loaner_teacher(array $data, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $s = Reader::TeacherLoaner($data['name'], $data['login'], $data['phone']);
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public static function register_non_loaner_teacher(array $data, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $s = Reader::TeacherNonLoaner($data['name'], $data['login'], $data['phone']);
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public static function register_classroom(array $data, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $c = Classroom::fromArray($data);
            return $db_man -> insert_record_in(DB::CLASSROOM_TABLE, $c -> toArray());
        }
        else return false;
    }

    public static function register_enrollment(array $data, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $e = Enrollment::fromArray($data);
            return $db_man -> insert_record_in(DB::ENROLLMENT_TABLE, $e -> toArray());
        }
        else return false;
    }

    public static function register_teaching(array $data, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $t = Teaching::fromArray($data);
            return $db_man -> insert_record_in(DB::TEACHING_TABLE, $t -> toArray());
        }
        else return false;
    }
    


    // Updating:
    public static function edit_reader(array $data, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $t = Reader::fromArray($data, false);
            return $db_man -> update_entity_in(DB::READER_TABLE, $t -> toArray());
        }
        else return false;
    }

    public static function edit_classroom(array $data, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $c = Classroom::fromArray($data);
            return $db_man -> update_entity_in(DB::CLASSROOM_TABLE, $c -> toArray());
        }
        else return false;
    }

    public static function authorize_teacher_as_loaner(int $teacher_id, int $user_id){
        /** @var Reader $teacher  */
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            /** @var Reader $teacher  */
            $teacher = self::fetch_reader_by_id($teacher_id);
            $teacher -> set_can_loan(true);
            return $db_man -> update_entity_in(DB::READER_TABLE, $teacher -> toArray());
        }
        return false;
    }

    public static function disallow_teacher_as_loaner(int $teacher_id, int $user_id){       
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            /** @var Reader $teacher  */
            $teacher = self::fetch_reader_by_id($teacher_id);
            $teacher -> set_can_loan(false);
            return $db_man -> update_entity_in(DB::READER_TABLE, $teacher -> toArray());
        }
        return false;
    }

    public static function transfer_students_to_classroom(array $students, int $classroom_id, int $user_id): bool{
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            foreach($students as $s){
                $db_man -> update_relationship(
                    DB::READER_TABLE, DB::ENROLLMENT_TABLE, 
                    'classroom_id', 'student_id',
                    $classroom_id, $s -> toArray());
            }
            //
        }
        return false;
    }
    
    public static function update_library_debts(){
        // Escrever após book_dao.php estar pronta!
    }
    


    // Deleting:
    public static function delete_reader(int $reader_id, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            return $db_man -> delete_record_in(DB::READER_TABLE, $reader_id);
        }
        else return false;
    }

    public static function delete_classroom(int $classroom_id, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            return $db_man -> delete_record_in(DB::CLASSROOM_TABLE, $classroom_id);
        }
        else return false;
    }

    public static function delete_enrollment(int $classroom_id, int $student_id, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            return $db_man -> delete_relationship(
                DB::ENROLLMENT_TABLE, 'classroom_id', 'student_id',
                $classroom_id, $student_id);
        }
        else return false;
    }

    public static function delete_teaching(int $classroom_id, int $teacher_id, int $user_id){
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            return $db_man -> delete_relationship(
                DB::TEACHING_TABLE, 'classroom_id', 'teacher_id',
                $classroom_id, $teacher_id);
        }
        else return false;
    }
    


    // Security: ===> Should this be in security maneger?
    private function sanitize_passphrase(string $psw){}

    private function encrypt_passphrase(string $psw){}

    private function salt_passphrase(string $psw){}

    public static function protect_passphrase(string $psw){
        //Usa sanitize_passphrase, encrypt_passphrase e salt_passphrase
    }

    public static function generate_first_passphrase(string $psw){
        //Usa sanitize_passphrase, encrypt_passphrase e salt_passphrase
    }



    // Reading:
    public static function fetch_reader_for_access(int $login, string $psw) {
        $db_man = new DAOManager();
        // Fazer
        //Sanitizar psw! ==> Escrever sanitize_passphrase() primeiro.
    }

    // Searching:
    // ID-fetchers:
    
    public static function fetch_reader_by_id(int $id) {
        $db_man = new DAOManager();
        $reader_array = $db_man -> fetch_record_by_id_from(DB::READER_TABLE, $id);
        $reader_array['passphrase'] = ''; // Clear passphrase field for search or linking
        return Reader::fromArray($reader_array, true); // Factory used truly for fetching
    }

    public static function fetch_classroom_by_id(int $id): Classroom {
        $db_man = new DAOManager();
        $class_array = $db_man -> fetch_record_by_id_from(DB::CLASSROOM_TABLE, $id);
        return Classroom::fromArray($class_array);
    }

    public static function fetch_all_classrooms() {
        $db_man = new DAOManager();
        $classroom_instances = array();
        $fetched_classrooms = $db_man -> fetch_all_records_from(DB::CLASSROOM_TABLE);
        foreach($fetched_classrooms as $c) $classroom_instances[] = Classroom::fromArray($c);
        return $classroom_instances;
    }

    public static function fetch_students_by_name(string $cleaned_name) {
        $db_man = new DAOManager();
        $student_instances = array();
        $search = ['name' => $cleaned_name, 'role' => 'student'];
        $where_conditions = [
            ['field' => 'name', "operator" => '='],
            ['field' => 'role', "operator" => '=']];
        $fetched_students = $db_man -> fetch_records_from(
            $search, DB::READER_TABLE, DB::READER_FIELDS,
            $where_conditions, 'name', false);
        foreach($fetched_students as $s) $student_instances[] = Reader::fromArray($s, true);
        return $student_instances;
    }

    public static function fetch_teachers_by_name(string $cleaned_name) {
        $db_man = new DAOManager();
        $teacher_instances = array();
        $search = ['name' => $cleaned_name, 'role' => 'teacher'];
        $where_conditions = [
            ['field' => 'name', "operator" => '='],
            ['field' => 'role', "operator" => '=']];
        $fetched_students = $db_man -> fetch_records_from(
            $search, DB::READER_TABLE, DB::READER_FIELDS,
            $where_conditions, 'name', false);
        foreach($fetched_students as $t) $teacher_instances[] = Reader::fromArray($t, true);
        return $teacher_instances;
    }

    public static function fetch_all_students_from_classroom(int $classroom_id) {
        $db_man = new DAOManager();
        $search = ["enrollment.classroom_id" => $classroom_id];
        $on_conditions = [['field1' => 'id'], ['field2' => 'student_id']];
        $enrollment_where_conditions = ['field' => 'student.id'];
        $student_instances = array();
        $on_conditions = [['field1' => 'id'], ['field2' => 'student_id']];
        $fetched_students = $db_man -> fetch_jointed_records_from(
            $search, DB::READER_TABLE, DB::ENROLLMENT_TABLE, DB::READER_FIELDS, DB::ENROLLMENT_FIELDS,
            $on_conditions, 'AND', array(), $enrollment_where_conditions, 'AND', 'AND',
            'reader.name', false);
        foreach($fetched_students as $s) $student_instances[] = Reader::fromArray($s, true);
        return $student_instances;
    }

    public static function fetch_all_teachers_from_classroom(int $classroom_id) {
        $db_man = new DAOManager();
        $teacher_instances = array();
        $search = ["teaching.classroom_id" => $classroom_id];
        $on_conditions = [['field1' => 'id'], ['field2' => 'teacher_id']];
        $teaching_where_conditions = ['field' => 'teaching.id'];
        $fetched_teachers = $db_man -> fetch_jointed_records_from(
            $search, DB::READER_TABLE, DB::TEACHING_TABLE, DB::READER_FIELDS, DB::TEACHING_FIELDS,
            $on_conditions, 'AND', array(), $teaching_where_conditions, 'AND', 'AND',
            'reader.name', false);
        foreach($fetched_teachers as $t) $teacher_instances[] = Reader::fromArray($t, true);
        return $teacher_instances;
    }
     
}

?>