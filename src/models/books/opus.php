<?php

// require_once '../../../tools/cutter/cutter.php';
require_once (__DIR__ . '/../../managers/security_mng.php');

final class Opus{
    private string $title;
    private ?int $id, $original_year;
    private ?string $alternative_url, $ddc, $cutter_sanborn;

    private function __construct(string $title, ?int $id = null) {
        $this->title = $title; $this->id = $id;
    }

    public function toArray(){
        return [
            'id' => $this->id ?? null,
            'title' => $this->title,
            'original_year' => $this->original_year,
            'alternative_url' => $this->alternative_url,
            'ddc' => $this->ddc,
            'cutter_sanborn' => $this->cutter_sanborn ?? ''
        ];
    }

    /**
     * Static factory for Opus from an array.
     * 
     * Differently from homonym methods in other classes, the boolean 
     * confirmation of its role inside a fetching call is meant to avoid
     * validation instrisic to some setters, as arrays generated from
     * DQL only contain data already validated.
     * 
     * @param array $data The array containing the data to instantiation.
     * @param bool $for_fetching The confirmation if the usage is or not for fetching.
     * @return Opus
     */
    public static function fromArray(array $data, bool $for_fetching): Opus{
        $o = new Opus($data['title']);
        $fields_without_validation = ['id', 'original_year', 'cutter_sanborn'];
        foreach ($fields_without_validation as $f) {
            if (!empty($data[$f])) $o -> $f = $data[$f];
        }
        if ($for_fetching) {
            $o -> ddc = $data['ddc'];
            $o -> alternative_url = $data['alternative_url'];
        }
        else{
            $o -> set_ddc($data['ddc']);
            $o -> set_alternative_url($data['alternative_url']);
        }
        return $o;
    }

    public function get_id(): int{
        if (isset($this -> id)) return $this->id;
        else return 0;
    }

    public function get_title(){return $this->title;}
    public function get_original_year(){return $this->original_year;}
    public function get_ddc(){return $this->ddc;}
    public function get_cutter_sanborn(){return $this -> cutter_sanborn;}
    public function get_alternative_url(){return $this->alternative_url;}

    public function set_title(string $title){$this->title = $title;}
    public function set_original_year(int $original_year){$this->original_year = $original_year;}
    
    public function set_ddc(string $ddc){
        if(SecurityManager::is_ddc_valid($ddc)) $this->ddc = $ddc;
        else throw new UnexpectedValueException("Invalid DDC Code.");
    }

    public function set_alternative_url(?string $alternative_url){
        if(SecurityManager::is_url_valid($alternative_url))
            $this -> alternative_url = $alternative_url ?? '';
        else throw new UnexpectedValueException("Invalid URL.");
    }

    // public function set_cutter_sanborn(string $author_with_surname_first){
    //     // Adicionar preg_match para formato da variáveil $author_with_surname_first
    //     // Precisa ser algo como "Silva, João".
    //     // if(false) return false;
    //     $this->cutter_sanborn = Cutter::find($author_with_surname_first).PHP_EOL;
    // }
}
?>