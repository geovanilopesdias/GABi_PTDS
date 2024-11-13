<?php
final class Classroom{
    private ?int $id;
    private int $year;
    private string $name;

    private function __construct($name, $year, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->year = $year;
    }

    public function toArray(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => $this->year
        ];
    }

    public static function fromArray(array $data): Classroom{
        $id = (isset($data['id'])) ? $data['id'] : null;
        return new Classroom($data['name'], $data['year'], $id);
    }

    public function get_id(){return $this -> id;}
    public function get_name(){return $this -> name;}
    public function get_year(){return $this->year;}

}

?>