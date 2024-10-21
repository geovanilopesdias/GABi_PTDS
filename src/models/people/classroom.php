<?php
final class Classroom{
    private int $year;
    private string $name;

    private function __construct($name, $year) {
        $this->name = $name;
        $this->year = $year;
    }

    public function toArray(){
        return (array) $this;
    }

    public static function fromArray(array $data): Classroom{
        return new Classroom(
            $data['name'],
            $data['year']);
    }

    public function get_name(){return $this -> name;}
    public function get_year(){return $this->year;}

}

?>