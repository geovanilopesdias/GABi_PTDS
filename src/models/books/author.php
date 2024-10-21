<?php

final class Author{
    private ?int $id;
    private int $birth_year;
    private string $name;

    private function __construct(string $name, int $birth_year, ?int $id = null) {
        $this -> id = $id;
        $this -> birth_year = $birth_year;
        $this -> name = $name;
    }

    public function toArray(): array{
        return (array) $this;
    }

    /**
     * Static factory for Author from an array.
     * 
     * Differently from homonym methods in other classes, the boolean 
     * confirmation of its role inside a fetching call is meant to avoid
     * validation instrisic to some setters, as arrays generated from
     * DQL only contain data already validated.
     * 
     * @param array $data The array containing the data to instantiation.
     * @param bool $for_fetching The confirmation if the usage is or not for fetching.
     * @return Author
     */
    public static function fromArray(array $data, bool $for_fetching): Author{
        if ($for_fetching)
            return new Author(
                $data['name'],
                $data['birth_year'],
                $data['id']
            );
        else
            if (Author::isNameValid($data['name']))
                return new Author(
                    $data['name'],
                    $data['birth_year'],
                    $data['id']
                );
    }

    private function isNameValid($nameToTest): bool{
        return preg_match("/^[A-zÀ-ÿ][A-zÀ-ÿ']+\s([A-zÀ-ÿ']\s?)*[A-zÀ-ÿ][A-zÀ-ÿ']+$/", $nameToTest);
    }   

    public function get_id() {return $this->id;}
    public function get_name() {return $this->name;}
    public function get_birth_year() {return $this->birth_year;}

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