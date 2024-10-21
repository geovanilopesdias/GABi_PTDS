<?php
final class Enrollment{
    private int $student_id;
    private string $classroom_id;

    private function __construct($student_id, $classroom_id) {
        $this->student_id = $student_id;
        $this->classroom_id = $classroom_id;
    }
    
    public function toArray(){
        return (array) $this;
    }

    public static function fromArray(array $data): Enrollment{
        return new Enrollment(
            $data['student_id'],
            $data['classroom_id']);
    }

    public function get_student_id(){return $this->student_id;}
    public function get_classroom_id(){return $this->classroom_id;}
}

?>