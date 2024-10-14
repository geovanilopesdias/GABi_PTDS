<?php

final class Edition{
    private int $id;
    private string $isbn;
    private int $id_opus;
    private int $id_publisher;
    private int $edition_number;
    private int $volume;
    private int $id_collection;
    private int $pages;
    private int $publishing_year;
    private array $cover_colors;

    private function __construct(
        $id, $isbn, $idOpus, $idPublisher, $editionNumber, $volume,
        $idCollection, $pages, $publishingYear, $cover_colors) {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->id_opus = $idOpus;
        $this->id_publisher = $idPublisher;
        $this->edition_number = $editionNumber;
        $this->volume = $volume;
        $this->id_collection = $idCollection;
        $this->pages = $pages;
        $this->publishing_year = $publishingYear;
        $this->cover_colors = $cover_colors;
    }

    public static function FetchedEdition(array $data){
        $edition = new Edition(
            $data['id'],
            $data['isbn'],
            $data['id_opus'],
            $data['id_publisher'],
            $data['edition_number'],
            $data['volume'],
            $data['id_collection'],
            $data['pages'],
            $data['publishing_year'],
            $data['cover_colors'],
        );
        return $edition;
    }

    private function isIsbnValid($isbnToTest): bool{
        return true;
    }    

    public function get_id(){return $this->id;}
    public function get_isbn(){return $this->isbn;}
    public function get_id_opus(){return $this->id_opus;}
    public function get_id_publisher(){return $this->id_publisher;}
    public function get_edition_number(){return $this -> edition_number;}
    public function get_volume(){return $this->volume;}
    public function get_id_collection(){return $this->id_collection;}
    public function get_pages(){return $this->pages;}
    public function get_publishing_year(){return $this->publishing_year;}
    public function get_cover_colors(){return $this->cover_colors;}

    public function set_id_opus(){return $this->id_opus;}
    public function set_id_publisher(){return $this->id_publisher;}
    public function set_edition_number(){return $this -> edition_number;}
    public function set_volume(){return $this->volume;}
    public function set_id_collection(){return $this->id_collection;}
    public function set_pages(){return $this->pages;}
    public function set_publishing_year(){return $this->publishing_year;}
    public function set_cover_colors(){return $this->cover_colors;}
    
    public function set_isbn($isbn){
        if(self::isIsbnValid($isbn))$this -> isbn = $isbn;
        else throw new UnexpectedValueException("Invalid ISBN code.");
    }

}
?>