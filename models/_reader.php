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
    private string $psw;
    private string $phone;
    private ReaderType $readerType;
    private bool $canLoan;
    private bool $canRegister;
    private float $debt;
    private string $lastLogin;

    function __construct($id, $name, $login, $psw, $phone, ReaderType $type, $canLoan, $canRegister, $lastLogin) {
        $this->id = $id;
        $this->name = $name;
        $this->login = $login;
        $this->psw = $psw;
        $this->phone = $phone;
        $this->readerType = $type;
        $this->canLoan = $canLoan;
        $this->canRegister = $canRegister;
        $this->lastLogin = $lastLogin;
    }


    private function isNameValid(): bool{
        return true;
    }

    private function isPasswordValid(): bool{
        return true;
    }

    private function isPhoneValid(): bool{
        return true;
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
    }

    public function set_phone($phone){
        if(self::isPhoneValid($phone))
            $this->phone = $phone;
    }
}
?>