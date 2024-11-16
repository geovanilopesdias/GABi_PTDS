<?php
final class Collection{
    private ?int $id;
    private string $name;
    private int $publisher_id;

    private function __construct(string $name, int $publisher_id, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->publisher_id = $publisher_id;
    }

    public function toArray(){
        return [
            'id' => $this->id ?? null,
            'name' => $this->name,
            'publisher_id' => $this->publisher_id
        ];
    }

    public static function fromArray(array $data){
        return new Collection(
            $data['name'],
            $data['publisher_id'],
            $data['id'] ?? null
        );
    }

    public function get_id(){return $this->id;}
    public function get_name(){return $this->name;}
    public function get_publisher_id(){return $this->publisher_id;}

    public function set_name($name){$this->name = $name;}
    public function set_publisher_id($publisher_id){$this->publisher_id = $publisher_id;}
}

?>