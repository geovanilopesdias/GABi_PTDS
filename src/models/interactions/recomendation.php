<?php
final class Recomendation{
    private int $opus_id, $student_id, $teacher_id;
    private string $operation_date;

    private function __construct(
        int $opus_id, int $student_id, int $teacher_id, string $operation_date) {
        $this->opus_id = $opus_id;
        $this->student_id = $student_id;
        $this->teacher_id = $teacher_id;
        $this->operation_date = $operation_date;
    }

    public function toArray(){
        return (array) $this;
    }

    public static function fromArray(array $data){
        return new Recomendation(
            $data['opus_id'],
            $data['student_id'],
            $data['teacher_id'],
            $data['operation_date']
        );
    }

    public function get_opus_id(){return $this->opus_id;}
    public function get_author_id(){return $this->student_id;}
    public function get_teacher_id(){return $this->teacher_id;}
    public function get_operation_date(){return $this->operation_date;}
}

?>