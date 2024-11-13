<?php
final class Enrollment{
    private int $student_id, $classroom_id;

    private function __construct(int $student_id, int $classroom_id) {
        $this->student_id = $student_id;
        $this->classroom_id = $classroom_id;
    }
    
    public function toArray(){
        return [
            'student_id' => $this->student_id,
            'classroom_id' => $this->classroom_id
        ];
    }

    public static function fromArray(array $data): Enrollment{
        return new Enrollment(
            $data['student_id'],
            $data['classroom_id']);
    }

    public function get_student_id(): int{return $this->student_id;}
    public function get_classroom_id(): int{return $this->classroom_id;}
}

?>