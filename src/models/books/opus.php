<?php

final class Opus{
    private int $id;
    private string $title;
    private int $originalYear;
    private string $ddc;
    private string $cutter_sanborn;
    private string $alternative_url;

    private function __construct(
        $id, $title, $originalYear, $alternative_url) {
        $this->id = $id;
        $this->title = $title;
        $this->originalYear = $originalYear;
        $this->alternative_url = $alternative_url;
    }

    public static function FetchedOpus(array $data){
        $opus = new Opus(
            $data['id'],
            $data['title'],
            $data['originalYear'],
            $data['alternativeURL']
        );
        $opus -> set_ddc($data['ddc']);
        $opus -> set_cutter_sanborn($data['cutter_sanborn']);

        return $opus;
    }

    private function isDdcValid($ddcToTest): bool{
        return true;
    }

    private function isCutterIsValid($cutterToTest): bool{
        return true;
    }
    

    public function get_id(){return $this->id;}
    public function get_title(){return $this->title;}
    public function get_originalYear(){return $this->originalYear;}
    public function get_ddc(){return $this->ddc;}
    public function get_cutter_sanborn(){return $this -> cutter_sanborn;}
    public function get_alternative_url(){return $this->alternative_url;}

    public function set_title($title){$this->title = $title;}
    public function set_originalYear($originalYear){$this->originalYear = $originalYear;}
    public function set_alternative_url($alternative_url){$this -> alternative_url = $alternative_url;}
    
    public function set_ddc($ddc){
        if(self::isDdcValid($ddc)) $this->ddc = $ddc;
        else throw new UnexpectedValueException("Invalid DDC Code.");
    }

    public function set_cutter_sanborn($cutter_sanborn){
        if(self::isCutterIsValid($cutter_sanborn))$this -> cutter_sanborn = $cutter_sanborn;
        else throw new UnexpectedValueException("Invalid Cutter-Sanborn.");
    }

}
?>