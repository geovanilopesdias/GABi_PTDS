<?php

final class Opus{
    private string $title;
    private ?int $id, $original_year;
    private ?string $alternative_url, $ddc, $cutter_sanborn;

    private function __construct(string $title) {$this->title = $title;}

    public function toArray(){
        return (array) $this;
    }

    public static function fromArray(array $data, bool $for_fetching){
        $o = new Opus($data['title']);
        $fields_without_valiation = ['id', 'original_year', 'cutter_sanborn'];
        foreach ($fields_without_valiation as $f) {
            if (!empty($data[$f])) {
                $o -> $f = $data[$f];
            }
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

    private function isDdcValid(string $ddcToTest): bool{
        return preg_match('/^\d{1,3}(\.\d+)?$/', $ddcToTest);
    }

    private function isUrlValid($urlToTest): bool{
        return filter_var($urlToTest, FILTER_VALIDATE_URL);
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
    public function set_cutter_sanborn(string $cutter_sanborn){$this->cutter_sanborn = $cutter_sanborn;}
    public function set_original_year(int $original_year){$this->original_year = $original_year;}
    
    public function set_ddc(string $ddc){
        if(self::isDdcValid($ddc)) $this->ddc = $ddc;
        else throw new UnexpectedValueException("Invalid DDC Code.");
    }

    public function set_alternative_url(string $alternative_url){
        if(self::isUrlValid($alternative_url))
            $this -> alternative_url = $alternative_url;
        else throw new UnexpectedValueException("Invalid URL.");
    }

}
?>