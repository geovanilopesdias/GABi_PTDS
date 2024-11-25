<?php

require_once (__DIR__ . '/../../managers/security_mng.php');

final class Writer{
    private ?int $id, $birth_year;
    private ?string $name;

    private function __construct(?int $id = null, ?int $birth_year = null, ?string $name = null) {
        $this -> id = $id;
        $this -> birth_year = $birth_year;
        $this -> name = $name;
    }

    public function toArray(): array{
        return [
            'id' => $this->id ?? null,
            'birth_year' => $this->birth_year ?? null,
            'name' => $this->name ?? null
        ];
    }

    /**
     * Static factory for Writer from an array.
     * 
     * Differently from homonym methods in other classes, the boolean 
     * confirmation of its role inside a fetching call is meant to avoid
     * validation instrisic to some setters, as arrays generated from
     * DQL only contain data already validated.
     * 
     * @param array $data The array containing the data to instantiation.
     * @param bool $for_fetching The confirmation if the usage is or not for fetching.
     * @return Writer
     */
    public static function fromArray(array $data, bool $for_fetching): Writer{
        if ($for_fetching)
            return new Writer(
                $data['id'],
                $data['birth_year'],
                $data['name']
            );
        else{
            $w = new Writer($data['id'], $data['birth_year']);
            $w -> set_name($data['name']);
            return $w;
        }
    }

    public function get_id() {return $this->id;}
    public function get_name() {return $this->name;}
    public function get_birth_year() {return $this->birth_year;}

    public function set_name(string $name) {
        if (SecurityManager::is_name_valid(ucfirst($name)))
            $this->name = $name;
        else throw new UnexpectedValueException("$name is an invalid name format for an writer.");
    }
    
  public function set_birth_year(int $birth_year) {
        $this->$birth_year = $birth_year;
  }
}
?>