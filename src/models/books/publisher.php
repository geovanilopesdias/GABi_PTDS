<?php

final class Publisher{
    private ?int $id;
    private string $name;
    
    private function __construct(
        string $name, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
    }

    public function toArray(){
        return [
            'id' => $this->id ?? null,
            'name' => $this->name
        ];
    }

    public static function fromArray(array $data){
        return new Publisher(
            $data['name'],
            $data['id'] ?? null
        );
    }

    public function get_id(){return $this->id;}
    public function get_name(){return $this->name;}
    public function set_name($name){$this -> name = $name;}
    
}
?>