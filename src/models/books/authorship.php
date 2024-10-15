<?php
final class Authorship{
    private int $opus_id;
    private int $author_id;

    private function __construct($opus_id, $author_id) {
        $this->opus_id = $opus_id;
        $this->author_id = $author_id;
    }

    public static function fromArray(array $data): Authorship{
        return new Authorship(
            $data['opus_id'], $data['author_id']
        );
    }

    public function toArray(): array{
        return (array) $author;
    }

    public function get_opus_id() {return $this->opus_id;}
    public function get_author_id() {return $this->author_id;}
    
}

?>