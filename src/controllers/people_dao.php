<?php

require 'dao_mng.php';
require '../models/people/reader.php';
require '../models/people/classroom.php';
require '../models/people/teaching.php';
require '../models/people/enrollment.php';

final class PeopleDAO{
    
    // Registration:
    public function register_student(array $data, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $s = Reader::Student($data['name'], $data['login'], $data['phone']);
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public function register_loaner_teacher(array $data, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $s = Reader::TeacherLoaner($data['name'], $data['login'], $data['phone']);
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public function register_non_loaner_teacher(array $data, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $s = Reader::TeacherNonLoaner($data['name'], $data['login'], $data['phone']);
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public function register_classroom(array $data, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $c = Classroom::fromArray($data);
            return $db_man -> insert_record_in(DB::CLASSROOM_TABLE, $c -> toArray());
        }
        else return false;
    }

    public function register_enrollment(array $data, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $e = Enrollment::fromArray($data);
            return $db_man -> insert_record_in(DB::ENROLLMENT_TABLE, $e -> toArray());
        }
        else return false;
    }

    public function register_teaching(array $data, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $t = Teaching::fromArray($data);
            return $db_man -> insert_record_in(DB::TEACHING_TABLE, $t -> toArray());
        }
        else return false;
    }
    
    // Updating:
    public function edit_reader(array $data, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $t = Reader::fromArray($data, false);
            return $db_man -> update_entity_with_id_in(DB::READER_TABLE, $t -> toArray());
        }
        else return false;
    }

    public function edit_classroom(array $data, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $c = Classroom::fromArray($data);
            return $db_man -> update_entity_with_id_in(DB::CLASSROOM_TABLE, $c -> toArray());
        }
        else return false;
    }

    public function authorize_teacher_as_loaner(int $teacher_id, int $user_id){
        /** @var Reader $teacher  */
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $teacher = self::fetch_reader_by_id($teacher_id);
            $teacher -> set_can_loan(true);
            return $db_man -> update_entity_with_id_in(DB::READER_TABLE, $teacher -> toArray());
        }
        return false;
    }

    public function disallow_teacher_as_loaner(int $teacher_id, int $user_id){
        /** @var Reader $teacher  */
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $teacher = self::fetch_reader_by_id($teacher_id);
            $teacher -> set_can_loan(false);
            return $db_man -> update_entity_with_id_in(DB::READER_TABLE, $teacher -> toArray());
        }
        return false;
    }

    public function transfer_students_to_classroom(array $students, int $classroom_id, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
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
    

// update_library_debts
// transferStudentsToClassroom

// deleteReader ==> Fazer delete_register em DAOManager primeiro!
// deleteClassroom
// deleteTeaching
// deleteEnrollment

    // Security: ===> Should this be in security maneger?
    private function sanitize_passphrase(string $psw){}

    private function encrypt_passphrase(string $psw){}

    private function salt_passphrase(string $psw){}

    public function protect_passphrase(string $psw){
        //Usa sanitize_passphrase, encrypt_passphrase e salt_passphrase
    }

    public function generate_first_passphrase(string $psw){
        //Usa sanitize_passphrase, encrypt_passphrase e salt_passphrase
    }


    public static function fetch_reader_for_access(int $login, string $psw) {
        $db_man = new DAOManager();
        // Fazer
        //Sanitizar psw! ==> Escrever sanitize_passphrase() primeiro.
    }

    // Searching:
    // ID-fetchers:
    public static function fetch_reader_by_id(int $id): Reader {
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