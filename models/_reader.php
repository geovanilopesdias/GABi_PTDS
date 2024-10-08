<?php

enum ReaderType{
    case Librarian;
    case Student;
    case Teacher;
}

class Reader{
    private int $id;
    private string $name;
    private string $login;
    private string $phone;
    private ReaderType $readerType;
    private bool $canLoan;
    private bool $canRegister;
    private float $debt;
    private string $lastLogin;

    function __construct($id, $name, $login, $phone, ReaderType $type, $canLoan, $canRegister, $lastLogin) {
        if (self::isNameValid($name)) $this->name = $name;
        if (self::isPhoneValid($phone)) $this->phone = $phone;
        $this->id = $id;
        $this->login = $login;
        $this->readerType = $type;
        $this->canLoan = $canLoan;
        $this->canRegister = $canRegister;
        $this->lastLogin = $lastLogin;
    }


    private function isNameValid($nameToTest): bool{
        return preg_match("/^[A-zÀ-ÿ][A-zÀ-ÿ']+\s([A-zÀ-ÿ']\s?)*[A-zÀ-ÿ][A-zÀ-ÿ']+$/", $nameToTest);
    }

    private function isPhoneValid($phoneToTest): bool{
        return preg_match("/^[1-9]{2}9[0-9]{8}$/", $phoneToTest);
    }
    
    public function get_id(){return $this->id;}
    public function get_name(){return $this->name;}
    public function get_login(){return $this->login;}
    public function get_phone(){return $this->phone;}
    public function get_readerType(){return $this->readerType;}
    public function get_canLoan(){return $this->canLoan;}
    public function get_canRegister(){return $this->canRegister;}
    public function get_debt(){return $this->debt;}
    public function get_lastLogin(){return $this->lastLogin;}
    
    public function set_login($login){$this->login = $login;}
    public function set_readerType(ReaderType $type){$this->readerType = $type;}
    public function set_canLoan($canLoan){$this->canLoan = $canLoan;}

    public function set_debt($debt){
        if($debt >= 0)
            $this->debt = $debt;
    }

    public function set_name($name){
        if(self::isNameValid($name))
            $this->name = $name;
        else
            throw new UnexpectedValueException("Invalid name format.");
    }

    public function set_phone($phone){
        if(self::isPhoneValid($phone))
            $this->phone = $phone;
        else
            throw new UnexpectedValueException("Invalid phone format.");
    }
}
?>