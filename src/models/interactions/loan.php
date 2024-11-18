<?php

final class Loan{
    private ?int $id, $closer_id, $debt_receiver_id;
    private int $book_copy_id, $loaner_id, $opener_id;
    private ?DateTime $closing_date, $return_date;
    private DateTime $opening_date, $loan_date;
    private float $debt;

    private function __construct(
        int $book_copy_id, int $loaner_id, int $opener_id,
        DateTime $opening_date, DateTime $loan_date,
        ?float $debt = 0,
        ?int $id = null, ?int $closer_id = null, ?int $debt_receiver_id = null,
        ?DateTime $closing_date = null, ?DateTime $return_date = null) {
        $this->id = $id;
        $this->closer_id = $closer_id;
        $this->debt_receiver_id = $debt_receiver_id;       
        $this->book_copy_id = $book_copy_id;
        $this->loaner_id = $loaner_id;
        $this->opener_id = $opener_id;
        $this->closing_date = $closing_date;
        $this->return_date = $return_date;
        $this->opening_date = $opening_date;
        $this->loan_date = $loan_date;
        $this->debt = $debt;

    }

    public function toArray(){
        return [
            'id' => $this->id ?? null,
            'closer_id' => $this->closer_id ?? null,
            'debt_receiver_id' => $this->debt_receiver_id ?? null,
            'book_copy_id' => $this->book_copy_id,
            'loaner_id' => $this->loaner_id,
            'opener_id' => $this->opener_id,
            'closing_date' => $this->closing_date ?? null,
            'return_date' => $this->return_date ?? null,
            'opening_date' => $this->opening_date,
            'loan_date' => $this->loan_date,
            'debt' => $this->debt,
        ];
    }

    public static function fromArray(array $data, bool $for_fetching){
        if ($for_fetching)
            return new Loan(
                $data['book_copy_id'], $data['loaner_id'], $data['opener_id'],
                $data['opening_date'], $data['loan_date'], 
                $data['debt'],
                $data['id'], $data['closer_id'], $data['debt_receiver_id'],
                $data['closing_date'], $data['return_date'], 
            );
        else
            return new Loan(
                $data['book_copy_id'], $data['loaner_id'], $data['opener_id'],
                $data['opening_date'], $data['loan_date'], 
            );
    }

    public function get_id(): int {return $this->id;}
    public function get_book_copy_id(): int {return $this->book_copy_id;}
    public function get_reader_id(): int {return $this->loaner_id;}
    public function get_opener_id(): int {return $this->opener_id;}
    public function get_closer_id(): int {return $this->closer_id;}
    public function get_receiver_id(): int {return $this->debt_receiver_id;}
    public function get_opening_date(): DateTime {return $this->opening_date;}
    public function get_closing_date(): DateTime {return $this->closing_date;}
    public function get_loan_date(): DateTime {return $this->loan_date;}
    public function get_return_date(): DateTime {return $this->return_date;}
    public function get_debt(): float {return $this->debt;}
    
    public function set_closer_id(int $closer_id){$this->closer_id = $closer_id;}
    public function set_debt_receiver_id(int $debt_receiver_id){$this->debt_receiver_id = $debt_receiver_id;}
    public function set_closing_date(DateTime $closing_date){$this->closing_date = $closing_date;}
    public function set_debt(float $debt){$this->debt = $debt;}

    public function set_return_date(DateTime $return_date){
        if ($return_date <= $this->loan_date) 
            throw new InvalidArgumentException('Return date must be after loan date.');
        $this->return_date = $return_date;
    }
    
}
?>