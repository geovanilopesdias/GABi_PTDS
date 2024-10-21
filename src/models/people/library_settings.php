<?php
final class LibrarySettings{
    private int $loan_deadline;
    private float $late_fee;
    private int $late_fee_period;
    private int $head_reserve_queue_deadline;

    private function __construct($loan_deadline, $late_fee, $late_fee_period, $head_reserve_queue_deadline) {
        $this->loan_deadline = $loan_deadline;
        $this->late_fee = $late_fee;
        $this->late_fee_period = $late_fee_period;
        $this->head_reserve_queue_deadline = $head_reserve_queue_deadline;
    }

    public function toArray(){
        return (array) $this;
    }

    public static function fromArray(array $data): LibrarySettings{
        return new LibrarySettings(
            $data['loan_deadline'],
            $data['late_fee'],
            $data['late_fee_period'],
            $data['head_reserve_queue_deadline']);
    }

    public function get_loan_deadline(){return $this -> loan_deadline;}
    public function get_late_fee(){return $this->late_fee;}
    public function get_late_fee_period(){return $this->late_fee_period;}
    public function get_head_reserve_queue_deadline(){return $this->head_reserve_queue_deadline;}

}

?>