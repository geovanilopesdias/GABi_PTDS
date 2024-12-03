<?php

require_once (__DIR__ . '/../../managers/security_mng.php');

final class Edition{
    private ?int $id, $volume, $collection_id, $edition_number, $pages, $publishing_year;
    private ?string $isbn, $cover_colors, $translators;
    private int $opus_id, $publisher_id;

    private function __construct(
        int $opus_id, int $publisher_id,
        ?int $collection_id = null, ?int $pages = null, ?int $editionNumber = null,
        ?int $publishing_year = null,
        ?string $cover_colors = null, ?int $volume = 1, ?int $id = null, ?string $translators = null) {
        $this->opus_id = $opus_id;
        $this->publisher_id = $publisher_id;
        $this->collection_id = $collection_id;
        $this->edition_number = $editionNumber;
        $this->publishing_year = $publishing_year;
        $this->pages = $pages;
        $this->volume = $volume;
        $this->cover_colors = $cover_colors;
        $this->translators = $translators;
        $this->id = $id;
    }

    public function toArray(){
        return [
            'opus_id' => $this->opus_id,
            'publisher_id' => $this->publisher_id,
            'id' => $this->id ?? null,
            'edition_number' => $this->edition_number ?? null,
            'publishing_year' => $this->publishing_year ?? null,
            'pages' => $this->pages ?? null,
            'cover_colors' => $this->cover_colors ?? null,
            'translators' => $this->translators ?? null,
            'collection_id' => $this->collection_id ?? null,
            'isbn' => $this->isbn ?? null,
            'volume' => $this->volume ?? 1
        ];
    }

    public static function fromArray(array $data, bool $for_fetching): Edition{
        $e = new Edition(
            $data['opus_id'], $data['publisher_id'],
            $data['collection_id'] ?? null, $data['pages'] ?? null,
            $data['edition_number'] ?? null, 
            $data['publishing_year'] ?? null, 
            $data['cover_colors'] ?? null,
            $data['volume'] ?? 1,
            $data['id'] ?? null
        );
        
        if ($for_fetching) {
            $e -> isbn = $data['isbn'];
            $e -> translators = $data['translators'];
        }
        else {
            $e -> set_isbn($data['isbn']);
            $e -> set_translators(!empty($data['translators']) ? $data['translators'] : null);
        }
        
        return $e;
    }

    public function get_id(){return $this->id;}
    public function get_isbn(){return $this->isbn;}
    public function get_opus_id(){return $this->opus_id;}
    public function get_publisher_id(){return $this->publisher_id;}
    public function get_edition_number(){return $this -> edition_number;}
    public function get_volume(){return $this->volume;}
    public function get_collection_id(){return $this->collection_id;}
    public function get_pages(){return $this->pages;}
    public function get_publishing_year(){return $this->publishing_year;}
    public function get_translators(){return $this->translators;}
    public function get_cover_colors(){return $this->cover_colors;}


    public function set_isbn($isbn){
        if (is_null($isbn))
            {$this -> isbn = null;}
        else {
            if(SecurityManager::is_isbn_valid($isbn)) $this -> isbn = $isbn;
            else throw new UnexpectedValueException("$isbn is an invalid ISBN code.");
        }        
    }

    public function set_translators(?string $translators){
        if(is_null($translators) or trim($translators) === '')
            {$this -> translators = null;}
        else {
            $translators_as_array = explode(',', $translators);
            foreach($translators_as_array as $t) {
                if(!SecurityManager::is_name_valid(trim($t))) {
                    throw new UnexpectedValueException("Translators' names may have invalid characters.");}
                }
            
            $this -> translators = $translators;
        }
    }

    public function set_opus_id($opus_id){$this->opus_id = $opus_id;}
    public function set_publisher_id($publisher_id){$this->publisher_id = $publisher_id;}
    public function set_edition_number($edition_number){$this -> edition_number = $edition_number;}
    public function set_volume($volume){$this->volume = $volume;}
    public function set_collection_id($collection_id){$this->collection_id = $collection_id;}
    public function set_pages($pages){$this->pages = $pages;}
    public function set_publishing_year($publishing_year){$this->publishing_year = $publishing_year;}
    public function set_cover_colors($cover_colors){$this->cover_colors = $cover_colors;}
   
}
?>