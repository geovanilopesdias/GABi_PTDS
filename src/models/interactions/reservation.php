<?php
final class Reservation{
    private int $opus_id;
    private int $reader_id;
    private string $operation_date;

    private function __construct(
        int $opus_id, int $reader_id, string $operation_date) {
        $this->opus_id = $opus_id;
        $this->reader_id = $reader_id;
        $this->operation_date = $operation_date;
    }

    public function toArray(){
        return (array) $this;
    }

    public static function fromArray(array $data){
        return new Reservation(
            $data['opus_id'],
            $data['reader_id'],
            $data['operation_date']
        );
    }

    public function get_opus_id(){return $this->opus_id;}
    public function get_reader_id(){return $this->reader_id;}
    public function get_operation_date(){return $this->operation_date;}
}

?>