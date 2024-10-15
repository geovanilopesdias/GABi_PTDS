<?php

enum SynopsisStatus : string{
    case SKETCH = 'sketch';
    case SENT = 'sent';
    case RETURNED = 'returned';
    case POSTED = 'posted';
}

final class Synopsis{
    private int $id;
    private int $opus_id;
    private int $student_id;
    private int $teacher_id;
    private SynopsisStatus $status;
    private string $last_updating_date;

    private function __construct(
        int $opus_id, int $student_id, int $teacher_id, SynopsisStatus $status, string $last_updating_date) {
        $this -> $opus_id;
        $this -> $student_id;
        $this -> $teacher_id;
        $this -> $status;
        $this -> $last_updating_date;
    }

    public static function FetchedSynopsis(array $data){
        $synopsis = new Synopsis(
            $data['opus_id'],
            $data['student_id'],
            $data['teacher_id'],
            SynopsisStatus::from($data['status']),
            $data['last_updating_date']);
        $synopsis -> set_id($data['id']);
        return $synopsis;
    }

    public function get_opus_id(){return $this -> opus_id;}
    public function get_student_id(){return $this -> student_id;}
    public function get_teacher_id(){return $this -> teacher_id;}
    public function get_status(): string {return $this -> status -> value;}
    public function get_last_updating_date(){return $this -> last_updating_date;}

    private function set_id(int $id){$this->id = $id;}
    public function set_status(string $status){$this->last_updating_date = SynopsisStatus::from($status);}
    public function set_last_updating_date(string $date){$this->last_updating_date = $date;}
    
}

?>