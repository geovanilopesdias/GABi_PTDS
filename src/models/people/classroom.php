<?php
final class Classroom{
    private int $year;
    private string $name;

    private function __construct($name, $year) {
        $this->name = $name;
        $this->year = $year;
    }

    public static function FetchedClassroom(array $data){
        $classroom = new Classroom(
            $data['name'],
            $data['year']);
        return $classroom;
    }

    public function get_name(){return $this -> name;}
    public function get_year(){return $this->year;}

}

?>