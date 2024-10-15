<?php
final class Recomendation{
    private int $opus_id;
    private int $student_id;
    private int $teacher_id;
    private string $operation_date;

    private function __construct(
        int $opus_id, int $student_id, int $teacher_id, string $operation_date) {
        $this->opus_id = $opus_id;
        $this->student_id = $student_id;
        $this->operation_date = $operation_date;
    }

    public static function FetchedRecomendation(array $data){
        $recomendation = new Recomendation(
            $data['opus_id'],
            $data['student_id'],
            $data['teacher_id'],
            $data['operation_date']);
        return $recomendation;
    }

    public function get_opus_id(){return $this->opus_id;}
    public function get_author_id(){return $this->student_id;}
    public function get_teacher_id(){return $this->teacher_id;}
    public function get_date_operation(){return $this->date_operation;}
}

?>