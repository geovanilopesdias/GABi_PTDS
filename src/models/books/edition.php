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

    private function isIsbnValid(string $isbnToTest): bool{
        // ISBN need to be 13 characters long and purely numeric:
        if (strlen($isbnToTest) != 13) 
            throw new UnexpectedValueException("ISBN has more (or less) than 13 digits.");
        if (!ctype_digit($isbnToTest))
            throw new UnexpectedValueException("ISBN has non-numeric digits.");
        $digits = str_split($isbnToTest);
        $sum = 0;
        // Iterate over the ISBN, but the last digit:
        for ($d = 0; $d < strlen($isbnToTest)-1; $d++) {
            if ($d == 0 or $d % 2 == 0) $sum += $digits[$d];
            else $sum += $digits[$d] * 3;
        };
        $remainder = $sum % 10;
        
        // If the (sum%10) = 0, last digit should be 0; (10-remainder) otherwise:
        if ($isbnToTest[strlen($isbnToTest)-1] == 0 and $remainder == 0) return true;
        if ($isbnToTest[strlen($isbnToTest)-1] == 10 - $remainder) return true;
        else return false;
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