<?php
final class Authorship{
    private int $opus_id;
    private int $writer_id;

    private function __construct($opus_id, $writer_id) {
        $this->opus_id = $opus_id;
        $this->writer_id = $writer_id;
    }

    public static function fromArray(array $data): Authorship{
        return new Authorship(
            $data['opus_id'], $data['writer_id']
        );
    }

    public function toArray(): array{
        return [
            'opus_id' => $this->opus_id,
            'writer_id' => $this->writer_id
        ];
    }

    public function get_opus_id() {return $this->opus_id;}
    public function get_writer_id() {return $this->writer_id;}
    
}

?>