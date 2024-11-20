<?php

require_once (__DIR__ . '/../managers/dao_mng.php');
require_once (__DIR__ . '/../models/people/reader.php');
require_once (__DIR__ . '/../models/people/classroom.php');
require_once (__DIR__ . '/../models/people/teaching.php');
require_once (__DIR__ . '/../models/people/enrollment.php');
// require_once ('book_dao.php');

final class PeopleDAO{
    // Registration:
    public static function register_student(array $data, int $user_id){ //OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $s = Reader::Student($data['login'], $data['passphrase'], $data['name'], $data['phone']);
        $s -> set_last_login((new DateTimeImmutable())->format('Y-m-d H:i:s'));
        return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        
    }

    public static function register_loaner_teacher(array $data, int $user_id){ //OK
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $s = Reader::TeacherLoaner($data['login'], $data['passphrase'], $data['name'], $data['phone']);
            $s -> set_last_login((new DateTimeImmutable())->format('Y-m-d H:i:s'));
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public static function register_non_loaner_teacher(array $data, int $user_id){ //OK
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            $s = Reader::TeacherNonLoaner($data['login'], $data['passphrase'], $data['name'], $data['phone']);
            $s -> set_last_login((new DateTimeImmutable())->format('Y-m-d H:i:s'));
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public static function register_classroom(array $data, int $user_id){  //OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $c = Classroom::fromArray($data);
        return $db_man -> insert_record_in(DB::CLASSROOM_TABLE, $c -> toArray());
    }

    public static function register_enrollment(array $data, int $user_id){ //OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $e = Enrollment::fromArray($data);
        return $db_man -> insert_record_in(DB::ENROLLMENT_TABLE, $e -> toArray());
    }

    public static function register_teaching(array $data, int $user_id){ // OK
        $db_man = new DAOManager();
        if (!$db_man -> can_user_register($user_id)) return false;
        $t = Teaching::fromArray($data);
        return $db_man -> insert_record_in(DB::TEACHING_TABLE, $t -> toArray());
    }

    // Updating:
    public static function edit_reader(int $id_to_update, array $data, int $user_id): bool { //OK
        if (isset($data['can_register']) or isset($data['can_borrow']))
            throw new InvalidArgumentException("Permissions shouldn't be lightly updated: use des/authorize methods instead.");
        
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $current_reader_data = self::fetch_reader_by_id($id_to_update, false) -> toArray();
        if (empty($current_reader_data)) return false;
        
        // Edit current reader data in order to scan them through checkers into model setters:
        foreach($current_reader_data as $field => &$v)
            if(isset($data[$field]) and $v !== $data[$field]) $v = $data[$field];
        
        try{
            $reader = Reader::fromArray($current_reader_data, false);
            $data['id'] = $reader -> get_id();
            $data['passphrase'] = $reader -> get_passphrase();
        }
        catch(Exception $e) {echo ("Reader setting failed: " . $e->getMessage());}
        return $db_man->update_entity_in(DB::READER_TABLE, $data);
    }
    
    public static function edit_classroom(int $id_to_update, array $data, int $user_id){ // OK
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id) or
            empty($db_man->fetch_record_by_id_from(DB::CLASSROOM_TABLE, $id_to_update))) return false;
        
        $data['id'] = $id_to_update;
        return $db_man->update_entity_in(DB::CLASSROOM_TABLE, $data);
    }

    public static function authorize_teacher_as_loaner(int $teacher_id, int $user_id){ // OK
        /** @var Reader $teacher  */
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $teacher = self::fetch_reader_by_id($teacher_id, false);
        if($teacher -> get_role() == 'student')
            throw new InvalidArgumentException("Students aren't allowed to permit borrow/loan at all.");
        
        $data['id'] = $teacher -> get_id();
        $data['can_borrow'] = true;
        return $db_man -> update_entity_in(DB::READER_TABLE, $data);
    }

    public static function disallow_teacher_as_loaner(int $teacher_id, int $user_id){ // OK
        $db_man = new DAOManager();
        if (!$db_man->can_user_register($user_id)) return false;

        $teacher = self::fetch_reader_by_id($teacher_id, false);
        if($teacher -> get_role() == 'student')
            throw new InvalidArgumentException("Students aren't allowed to permit borrow/loan at all.");
        
        $data['id'] = $teacher -> get_id();
        $data['can_borrow'] = false;
        return $db_man -> update_entity_in(DB::READER_TABLE, $data);
    }

    public static function transfer_students_to_classroom( // OK
        array $students, int $classroom_id, int $user_id): bool{
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id)){
            foreach($students as $s){
                $db_man -> update_relationship(
                    DB::READER_TABLE, DB::ENROLLMENT_TABLE, 
                    'student_id', 'classroom_id',
                    $classroom_id, $s -> toArray());
            }
            return true;
        }
        return false;
    }

    public static function re_enroll_student_to(int $classroom_id, int $student_id, int $user_id): bool{
        $db_man = new DAOManager();
        $fetched_student = self::fetch_reader_by_id($student_id, true);
        if (!$db_man -> can_user_register($user_id)) return false;
        if (empty($fetched_student -> toArray()) or empty(self::fetch_classroom_by_id($classroom_id) -> toArray()))
            return false;
        return $db_man -> update_relationship(
            DB::READER_TABLE, DB::ENROLLMENT_TABLE, 
            'student_id', 'classroom_id',
            $classroom_id, $fetched_student -> toArray());        
    }

    
    
    public static function update_library_debts(){
        // Escrever após book_dao.php estar pronta!
    }
    


    // Deleting:
    public static function delete_reader(int $reader_id, int $user_id){  //OK
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id))
            return $db_man -> delete_record_in(DB::READER_TABLE, $reader_id);
        
        else return false;
    }

    public static function delete_classroom(int $classroom_id, int $user_id){ // OK
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id))
            return $db_man -> delete_record_in(DB::CLASSROOM_TABLE, $classroom_id);
        else return false;
    }

    public static function delete_enrollment(int $classroom_id, int $student_id, int $user_id){ //OK
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id))
            return $db_man -> delete_relationship(
                DB::ENROLLMENT_TABLE, 'classroom_id', 'student_id',
                $classroom_id, $student_id);
        
        else return false;
    }

    public static function delete_teaching(int $classroom_id, int $teacher_id, int $user_id){ // OK
        $db_man = new DAOManager();
        if ($db_man -> can_user_register($user_id))
            return $db_man -> delete_relationship(
                DB::TEACHING_TABLE, 'classroom_id', 'teacher_id',
                $classroom_id, $teacher_id);
        
        else return false;
    }
    

    // ----- Reading:
    // --- Searching:
    // ID-fetchers:
    public static function fetch_reader_by_id(int $id, bool $is_for_search): ?Reader {  // OK
        $db_man = new DAOManager();
        $reader_array = $db_man -> fetch_record_by_id_from(DB::READER_TABLE, $id);
        if (empty($reader_array)) return null;
        if ($is_for_search) $reader_array['passphrase'] = ''; // Clear passphrase field for search or linking
        return Reader::fromArray($reader_array, true); // Factory used truly for fetching
    }

    public static function fetch_classroom_by_id(int $id) {  //OK
        $db_man = new DAOManager();
        $class_array = $db_man -> fetch_record_by_id_from(DB::CLASSROOM_TABLE, $id);
        if (empty($class_array)) return null;
        return Classroom::fromArray($class_array);
    }

    public static function fetch_all_classrooms(): ?array {  //OK
        $db_man = new DAOManager();
        $classroom_instances = array();
        $fetched_classrooms = $db_man -> fetch_all_records_from(DB::CLASSROOM_TABLE);
        foreach($fetched_classrooms as $c) $classroom_instances[] = Classroom::fromArray($c);
        return $classroom_instances;
    }

    public static function fetch_reader_by_login(string $cleaned_login): ?Reader { 
        $db_man = new DAOManager();
        $reader_instance = array();
        $search = ['login' => "$cleaned_login"];
        $where_conditions = [['field' => 'login', "operator" => '=']];
        $reader_instance = $db_man -> fetch_records_from(
            $search, DB::READER_TABLE, DB::READER_FIELDS,
            $where_conditions, 'AND', null, false, true); // Call unique raw
        
        if (!$reader_instance) return null;
        
        return Reader::fromArray($reader_instance, true);
    }

    public static function fetch_readers_by_name(string $cleaned_name) { //OK
        $db_man = new DAOManager();
        $reader_instances = array();
        $search = ['name' => "%$cleaned_name%"];
        $where_conditions = [['field' => 'name', "operator" => 'ILIKE']];
        return $db_man -> fetch_records_from(
            $search, DB::READER_TABLE, DB::READER_FIELDS,
            $where_conditions, 'AND', 'name', false);
        
        // if (!$fetched_readers) return null;
        
        // foreach($fetched_readers as $r) $reader_instances[] = Reader::fromArray($r, true);
        // return $reader_instances;
    }

    public static function fetch_students_by_name(string $cleaned_name) { //OK
        $db_man = new DAOManager();
        $student_instances = array();
        $search = ['name' => "%$cleaned_name%", 'role' => 'student'];
        $where_conditions = [
            ['field' => 'name', "operator" => 'ILIKE'],
            ['field' => 'role', "operator" => '=']];
        return $db_man -> fetch_records_from(
            $search, DB::READER_TABLE, DB::READER_FIELDS,
            $where_conditions, 'AND', 'name', false);
        
        // if (!$fetched_students) return null;
        
        // foreach($fetched_students as $s) $student_instances[] = Reader::fromArray($s, true);
        // return $student_instances;
    }

    public static function fetch_teachers_by_name(string $cleaned_name) { //OK
        $db_man = new DAOManager();
        $search = ['name' => "%$cleaned_name%", 'role' => 'teacher'];
        $where_conditions = [
            ['field' => 'name', "operator" => 'ILIKE'],
            ['field' => 'role', "operator" => '=']];
        return $db_man -> fetch_records_from(
            $search, DB::READER_TABLE, DB::READER_FIELDS,
            $where_conditions, 'AND', 'name', false);

        // if (!$fetched_teachers) return null;
        // foreach($fetched_teachers as $t) $teacher_instances[] = Reader::fromArray($t, true);
        // return $teacher_instances;
    }

    public static function fetch_all_students_from_classroom(int $classroom_id) { //Test again
        $db_man = new DAOManager();
        $search = ["classroom_id" => $classroom_id];
        $on_conditions = [['field1' => DB::READER_TABLE.'.id', 'operator' => '=', 'field2' => DB::ENROLLMENT_TABLE.'.student_id']];
        $enrollment_where_conditions = [['field' => 'classroom_id', 'operator' => '=']];
        return $db_man->fetch_jointed_records_from(
            $search, DB::ENROLLMENT_TABLE,
            DB::READER_TABLE, DB::ENROLLMENT_TABLE, DB::READER_FIELDS, DB::ENROLLMENT_FIELDS,
            $on_conditions, 'AND', array(),  // No conditions for READER_TABLE
            $enrollment_where_conditions, 'AND', 'AND',
            DB::READER_TABLE.'.name', false // Order by reader's names
        );
        // if (!$fetched_students) return null;
        // foreach($fetched_students as $s) $student_instances[] = Reader::fromArray($s, true);
        // return $student_instances;
    }
    

    public static function fetch_all_teachers_from_classroom(int $classroom_id) {//OK
        $db_man = new DAOManager();
        $search = ["classroom_id" => $classroom_id];
        $on_conditions = [['field1' => DB::READER_TABLE.'.id', 'operator' => '=', 'field2' => DB::TEACHING_TABLE.'.teacher_id']];
        $teaching_where_conditions = [['field' => 'classroom_id', 'operator' => '=']];
        $fetched_teachers = $db_man -> fetch_jointed_records_from(
            $search, DB::TEACHING_TABLE,
            DB::READER_TABLE, DB::TEACHING_TABLE, DB::READER_FIELDS, DB::TEACHING_FIELDS,
            $on_conditions, 'AND', array(), $teaching_where_conditions, 'AND', 'AND',
            DB::READER_TABLE.'.name', false);
        if (!$fetched_teachers) return null;
        foreach($fetched_teachers as $t) $teacher_instances[] = Reader::fromArray($t, true);
        return $teacher_instances;
    }
     
}

?>