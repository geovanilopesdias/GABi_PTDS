<?php
final class Authorship{
    private int $opus_id;
    private string $author_id;

    private function __construct($opus_id, $author_id) {
        $this->opus_id = $opus_id;
        $this->author_id = $author_id;
    }

    public static function FetchedEnrollment(array $data){
        $authorship = new Authorship(
            $data['opus_id'],
            $data['author_id']);
        return $authorship;
    }

    public function get_opus_id(){return $this->opus_id;}
    public function get_author_id(){return $this->author_id;}
}

?>