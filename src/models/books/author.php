<?php

final class Author{
    private int $id;
    private string $name;
    private int $birth_date;
    

    private function __construct(
        $id, $name, $birth_date) {
        $this->id = $id;
        $this->birth_date = $birth_date;
        $this->set_name($name);
    }

    // Would constructors to fetching and inserting both be needed
    public static function FetchedAuthor(array $data){
        $author = new Author(
            $data['id'],
            $data['name'],
            $data['birth_date']);
        return $author;
    }

    private function isNameValid($nameToTest): bool{
        return preg_match("/^[A-zÀ-ÿ][A-zÀ-ÿ']+\s([A-zÀ-ÿ']\s?)*[A-zÀ-ÿ][A-zÀ-ÿ']+$/", $nameToTest);
    }   

    public function get_id(){return $this->id;}
    public function get_name(){return $this->name;}
    public function get_birth_date(){return $this->birth_date;}

    public function set_birth_date($birth_date){$this -> birth_date = $birth_date;}
    
    public function set_name($name){
        if(self::isNameValid($name)) $this->name = $name;
        else throw new UnexpectedValueException("Invalid name format.");
    }

}
?>