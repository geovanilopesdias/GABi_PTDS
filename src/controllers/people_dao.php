<?php

require 'dao_mng.php';
require '../models/people/reader.php';
require '../models/people/classroom.php';
require '../models/people/teaching.php';
require '../models/people/enrollment.php';

final class PeopleDAO{
    private $pdo;

    public function __construct(){$this -> pdo = Connection::connect();}

    // Registration:
    public function register_student(array $data, int $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $s = Reader::Student($data['name'], $data['login'], $data['phone']);
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public function register_loaner_teacher(array $data, string $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $s = Reader::TeacherLoaner($data['name'], $data['login'], $data['phone']);
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public function register_non_loaner_teacher(array $data, string $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $s = Reader::TeacherNonLoaner($data['name'], $data['login'], $data['phone']);
            return $db_man -> insert_record_in(DB::READER_TABLE, $s -> toArray());
        }
        else return false;
    }

    public function register_classroom(array $data, string $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $c = Classroom::fromArray($data);
            return $db_man -> insert_record_in(DB::CLASSROOM_TABLE, $c -> toArray());
        }
        else return false;
    }

    public function register_enrollment(array $data, string $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $e = Enrollment::fromArray($data);
            return $db_man -> insert_record_in(DB::ENROLLMENT_TABLE, $e -> toArray());
        }
        else return false;
    }

    public function register_teaching(array $data, string $user_id){
        if (DAOManager::can_user_register($user_id)){
            $db_man = new DAOManager();
            $t = Teaching::fromArray($data);
            return $db_man -> insert_record_in(DB::TEACHING_TABLE, $t -> toArray());
        }
        else return false;
    }
    
// encryptPassword
// saltPassword
// protectPassword
// editReader
// chargeReaderDebt
// payReaderDebt
// editClassroom
// deleteReader
// deleteClassroom
// deleteTeaching
// deleteEnrollment
// fetchReaderById
// fetchStudentByName
// fetchAuthorityByName
// fetchStudentsOfClassrrom
// fetchAuthoritiesOfClassrrom
// fetchClassroomById
// fetchAllClassroom
// fetchClassroomByName
// authorizeTeacherAsBookLoaner
// disallowTeacherAsBookLoaner
// transferStudentsToClassroom
     
}

?>