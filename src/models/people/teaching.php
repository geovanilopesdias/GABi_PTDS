<?php
final class Teaching{
    private int $teacher_id;
    private string $classroom_id;

    private function __construct($teacher_id, $classroom_id) {
        $this->teacher_id = $teacher_id;
        $this->classroom_id = $classroom_id;
    }

    public static function FetchedTeaching(array $data){
        $teaching = new Teaching(
            $data['teacher_id'],
            $data['classroom_id']);
        return $teaching;
    }

    public function get_teacher_id(){return $this->teacher_id;}
    public function get_classroom_id(){return $this->classroom_id;}
}

?>