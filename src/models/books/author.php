<?php

final class Author{
    private int $id, $birth_year;
    private string $name;

    private function __construct($name, $birth_year, $id = 0) {
        $this->id = $id;
        $this->birth_year = $birth_year;
        $this->set_name($name);
    }

    public static function fromArray(array $data): Author{
        return new Author(
            $data['name'], $data['birth_year'], $data['id']
        );
    }

    public function toArray(): array{
        return (array) $this;
    }

    private function isNameValid($nameToTest): bool{
        return preg_match("/^[A-zÀ-ÿ][A-zÀ-ÿ']+\s([A-zÀ-ÿ']\s?)*[A-zÀ-ÿ][A-zÀ-ÿ']+$/", $nameToTest);
    }   

    public function get_id() {return $this->id;}
    public function get_name() {return $this->name;}
    public function get_birth_year() {return $this->$birth_year;}

    public function set_name(string $name) {
      if (self::isNameValid($name))
            $this->name = $name;
      else throw new UnexpectedValueException("Invalid name format.");
    }
    
  public function set_birth_year(int $birth_year) {
        $this->$birth_year = $birth_year;
  }
}
?>