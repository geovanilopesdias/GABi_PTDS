<?php

final class Loan{
    private ?int $id, $closer_id, $receiver_id;
    private int $copy_id, $reader_id, $opener_id;
    private ?string $closing_date, $return_date;
    private string $opening_date, $loan_date;
    private float $debt;

    private function __construct(
        int $copy_id, int $reader_id, int $opener_id,
        string $opening_date, string $loan_date,
        float $debt,
        ?int $id = null, ?int $closer_id = null, ?int $receiver_id = null,
        ?string $closing_date, ?string $return_date
        ) {
        $this->id = $id;
        $this->closer_id = $closer_id;
        $this->receiver_id = $receiver_id;       
        $this->copy_id = $copy_id;
        $this->reader_id = $reader_id;
        $this->opener_id = $opener_id;
        $this->closing_date = $closing_date;
        $this->return_date = $return_date;
        $this->return_date = $return_date;
        $this->opening_date = $opening_date;
        $this->closing_date = $closing_date;
        $this->loan_date = $loan_date;
        $this->debt = $debt;

    }

    public function toArray(){
        return (array) $this;
    }

    public static function fromArray(array $data){
        return new Loan(
            $data['copy_id'], $data['reader_id'], $data['opener_id'],
            $data['opening_date'], $data['loan_date'], $data['debt'],
            $data['id'], $data['closer_id'], $data['receiver_id'],
            $data['closing_date'], $data['return_date']
        );
    }

    public function get_id(): int {return $this->id;}
    public function get_id_copy(): int {return $this->id_copy;}
    public function get_id_reader(): int {return $this->id_reader;}
    public function get_id_opener(): int {return $this->id_opener;}
    public function get_id_closer(): int {return $this->id_closer;}
    public function get_id_receiver(): int {return $this->id_receiver;}
    public function get_date_opening(): string {return $this->date_opening;}
    public function get_date_closing(): string {return $this->date_closing;}
    public function get_date_loan(): string {return $this->date_loan;}
    public function get_date_return(): string {return $this->date_return;}
    public function get_debt(): float {return $this->debt;}
    
    public function set_id_closer(int $id_closer){$this->id_closer = $id_closer;}
    public function set_id_receiver(int $id_receiver){$this->id_receiver = $id_receiver;}
    public function set_date_closing(string $date_loan){$this->date_loan = $date_loan;}
    public function set_date_return(string $date_return){$this->date_return = $date_return;}
    public function set_debt(float $debt){$this->debt = $debt;}
    

}
?>