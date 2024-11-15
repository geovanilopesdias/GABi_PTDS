<?php

final class Loan{
    private ?int $id, $closer_id, $debt_receiver_id;
    private int $copy_id, $reader_id, $opener_id;
    private ?string $closing_date, $return_date;
    private string $opening_date, $loan_date;
    private float $debt;

    private function __construct(
        int $copy_id, int $reader_id, int $opener_id,
        string $opening_date, string $loan_date,
        float $debt = 0,
        ?int $id = null, ?int $closer_id = null, ?int $debt_receiver_id = null,
        ?string $closing_date = null, ?string $return_date = null) {
        $this->id = $id;
        $this->closer_id = $closer_id;
        $this->debt_receiver_id = $debt_receiver_id;       
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

    public static function fromArray(array $data, bool $for_fetching){
        if ($for_fetching)
            return new Loan(
                $data['copy_id'], $data['reader_id'], $data['opener_id'],
                $data['opening_date'], $data['loan_date'], $data['debt'],
                $data['id'], $data['closer_id'], $data['debt_receiver_id'],
                $data['closing_date'], $data['return_date']
            );
        else
            return new Loan(
                $data['copy_id'], $data['reader_id'], $data['opener_id'],
                $data['opening_date'], $data['loan_date']
            );
    }

    public function get_id(): int {return $this->id;}
    public function get_copy_id(): int {return $this->copy_id;}
    public function get_reader_id(): int {return $this->reader_id;}
    public function get_opener_id(): int {return $this->opener_id;}
    public function get_closer_id(): int {return $this->closer_id;}
    public function get_receiver_id(): int {return $this->debt_receiver_id;}
    public function get_opening_date(): string {return $this->opening_date;}
    public function get_closing_date(): string {return $this->closing_date;}
    public function get_loan_date(): string {return $this->loan_date;}
    public function get_return_date(): string {return $this->return_date;}
    public function get_debt(): float {return $this->debt;}
    
    public function set_closer_id(int $id_closer){$this->closer_id = $id_closer;}
    public function set_receiver_id(int $id_receiver){$this->debt_receiver_id = $id_receiver;}
    public function set_closing_date(string $date_loan){$this->loan_date = $date_loan;}
    public function set_return_date(string $date_return){$this->return_date = $date_return;}
    public function set_debt(float $debt){$this->debt = $debt;}
    

}
?>