<?php
// De USPDev: https://github.com/uspdev/cutter/ sob GNU
namespace Uspdev;

class Cutter{
    public static function find($search){
        $search = trim($search);
        $search = strtolower(Cutter::removeAccents($search));
        $list = Cutter::load(__DIR__ . '/cutter.csv');
        return Cutter::recursiveSearch($search, $list, 0);
    }

    protected static function removeAccents($string){
        return preg_replace('/[`^~\'"]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $string));
    }

    public static function load($file){
        $csv = file_get_contents($file);
        $csv = strtolower(preg_replace('/#.*.\n/', '', $csv));
        $csv = str_replace(' ', '', $csv);
        $arr = array_map(function ($v) {return str_getcsv($v, ";");}, explode("\n", $csv)); 
        return $arr;
    }

    protected static function recursiveSearch($search, $list, $i){
        $new_list = [];

        foreach ($list as $tuple) {
            if ($i >= strlen($search)) 
                return $list[0][0];
            if ($i > strlen($tuple[1])) 
                break;
            if (!empty($tuple[1][$i]) && $search[$i] == $tuple[1][$i]) 
                array_push($new_list, $tuple);
        }

        if (!empty($new_list)) 
            return Cutter::recursiveSearch($search, $new_list, $i + 1);
        else 
            return $list[0][0];
    }
}