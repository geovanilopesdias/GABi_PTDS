<?php

final class Publisher{
    private int $id;
    private string $name;
    
    private function __construct(
        $id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    // Would constructors to fetching and inserting both be needed
    public static function FetchedAuthor(array $data){
        $publisher = new Publisher(
            $data['id'],
            $data['name']);
        return $publisher;
    }

    public function get_id(){return $this->id;}
    public function get_name(){return $this->name;}
    public function set_name($name){$this -> name = $name;}
    

}
?>