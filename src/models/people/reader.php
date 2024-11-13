<?php

enum ReaderRole : string{
    case LIBRARIAN = 'librarian';
    case STUDENT = 'student';
    case TEACHER = 'teacher';
}

final class Reader{
    private ?int $id;
    private string $name, $login, $passphrase, $phone, $last_login;
    private ReaderRole $role;
    private bool $can_borrow, $can_register;
    private ?float $debt;

    private function __construct(
        string $login, ReaderRole $role, bool $can_borrow, bool $can_register,
        ?int $id = null, ?float $debt = null) {
        $this->login = $login;
        $this->role = $role;
        $this->can_borrow = $can_borrow;
        $this->can_register = $can_register;
        $this->id = $id;
        $this->debt = $debt;
    }
    
    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'login' => $this->login,
            'passphrase' => $this->passphrase,
            'phone' => $this->phone,
            'last_login' => $this->last_login,
            'role' => $this->role->value,
            'can_borrow' => $this->can_borrow,
            'can_register' => $this->can_register,
            'debt' => $this->debt,
        ];
    }
    

    /**
     * Static factory for Reader from an array.
     * 
     * Differently from homonym methods in other classes, the boolean 
     * confirmation of its role inside a fetching call is meant to avoid
     * validation instrisic to some setters, as arrays generated from
     * DQL only contain data already validated.
     * 
     * @param array $data The array containing the data to instantiation.
     * @param bool $for_fetching The confirmation if the usage is or not for fetching.
     * @return Reader
     */
    public static function fromArray(array $data, bool $for_fetching): Reader{
        $r = new Reader(
            $data['login'],
            ReaderRole::from($data['role']),
            $data['can_borrow'],
            $data['can_register'],
            $data['id'],
            $data['debt']
        );
        if ($for_fetching) {
            $r -> name = $data['name'];
            $r -> phone = $data['phone'];
            $r -> last_login = $data['last_login'];
            $r -> passphrase = $data['passphrase'];
        }
        else{
            $r -> set_name(['name']);
            $r -> set_phone($data['phone']);
            $r -> set_passphrase($data['passphrase']);
        }
        return $r;
    }

    public static function Librarian(string $login, string $passphrase, string $name, string $phone){
        $r = new Reader($login, ReaderRole::LIBRARIAN, true, true);
        $r -> set_passphrase($passphrase);
        $r -> set_name($name);
        $r -> set_phone($phone);
        return $r;
    }

    public static function TeacherLoaner(string $login, string $passphrase, string $name, string $phone){
        $r = new Reader($login, ReaderRole::TEACHER, true, false);
        $r -> set_passphrase($passphrase);
        $r -> set_name($name);
        $r -> set_phone($phone);
        return $r;
    }

    public static function TeacherNonLoaner(string $login, string $passphrase, string $name, string $phone){
        $r = new Reader($login, ReaderRole::TEACHER, false, false);
        $r -> set_passphrase($passphrase);
        $r -> set_name($name);
        $r -> set_phone($phone);
        return $r;
    }

    public static function Student(string $login, string $passphrase, string $name, string $phone){
        $r = new Reader($login, ReaderRole::STUDENT, false, false);
        $r -> set_passphrase($passphrase);
        $r -> set_name($name);
        $r -> set_phone($phone);
        return $r;
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
    public function get_passphrase(){return $this->passphrase;}
    public function get_phone(){return $this->phone;}
    public function get_role(): string {return $this -> role -> value;}
    public function get_can_loan(){return $this->can_borrow;}
    public function get_can_register(){return $this->can_register;}
    public function get_debt(){return $this->debt;}
    public function get_last_login(){return $this->last_login;}
    
    public function set_login($login){$this->login = $login;}
    public function set_can_loan(bool $can_borrow){$this->can_borrow = $can_borrow;}
    public function set_last_login($last_login){$this->last_login = $last_login;}

    // Modificar para inserir salga etc.
    public function set_passphrase(string $passphrase): void {
        $this->passphrase = hash('sha256', $passphrase); // Assign the hashed value to the property
    }
    
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