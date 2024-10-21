<?php
final class Like{
    private int $synopsis_id, $liker_id;

    private function __construct(int $synopsis_id, int $liker_id) {
        $this -> synopsis_id = $synopsis_id;
        $this -> liker_id = $liker_id;
    }

    public function toArray(){
        return (array) $this;
    }

    public static function fromArray(array $data): Like{
        return new Like($data['synopsis_id'], $data['liker_id']);
    }

    public function get_synopsis_id(): int {return $this -> synopsis_id;}
    public function get_liker_id(): int {return $this -> liker_id;}    
}

?>