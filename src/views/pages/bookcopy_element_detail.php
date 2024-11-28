<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/element_detail.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/../../controllers/loan_dao.php');

final class BookcopyDetail extends ElementDetail{
    const ELEMENT_TYPE = 'bookcopy';

    public function __construct(){}

    public function echo_structure(string $element_type = self::ELEMENT_TYPE){
        parent::echo_structure($element_type);
    }

    protected function detail_element($element): string{
        if(is_null($element)) return "<h1>Puxa vida! Erro ao encontrar o exemplar...</h1>";
        $bookcopy = BookDAO::fetch_bookcopy_holistically_by_asset_code($element -> get_asset_code());
        if(empty($bookcopy)) return "<h1>Puxa vida! Erro ao construir o exemplar...</h1>";
        
        // CORRIGIR:
        // $loan_link = ($bookcopy['status'] === 'loaned') ?
        //     "<form method='post' action='loan_element_detail.php'>
        //         <input type='hidden' value='".$bookcopy['id']."'>
        //         <input type='submit' class='element_detail_link' value='&#128065;'>
        //     </form>" : "";
        $weblink = (!is_null($bookcopy['alternative_url'])) ?
            "<p><strong>Disponível online:</strong>  
                <a id='opus_weblink' target='blank' href='" . htmlspecialchars($bookcopy['alternative_url']) . "'>&#128279;</a>
            </p>" :
            "";
        $original_year = (!is_null($bookcopy['original_year'])) ? '(de '.$bookcopy['original_year'].')' : '';
        $publishing_year = (!is_null($bookcopy['publishing_year'])) ? '(de '.$bookcopy['publishing_year'].')' : '';
        $writers = implode(', ', array_map(function($author) {
                    return htmlspecialchars($author['name']) . " (" . htmlspecialchars($author['birth_year']) . ")";},
                    json_decode($bookcopy['writers'], true)));
        $colors = $bookcopy['cover_colors'];
        // $edition_fields = ['', '', '', '', '', ''];
        // $opus_fields = ['', '', '', '', ''];

        $detail = "
            <p><strong>Patrimônio:</strong> " . ucwords($bookcopy['asset_code']).
                " (".InterfaceManager::translate_book_status($bookcopy['status']).")</p>
            <p><strong>Título:</strong> " . ucwords($bookcopy['title'])." $original_year</p>
            $weblink
            <p><strong>CDD:</strong> " . $bookcopy['ddc']."</p>
            <p><strong>Cutter-Sanborn:</strong> " . $bookcopy['cutter_sanborn']."</p>
            <p><strong>Autores:</strong> $writers</p>
            <p><strong>Editora:</strong> " . ucwords($bookcopy['publisher'])."</p>
            <p><strong>Tradutores:</strong> " . ucwords($bookcopy['translators'])."</p>
            <p><strong>Coleção:</strong> " . ucwords($bookcopy['collection'])."</p>
            <p><strong>Volume:</strong> " . $bookcopy['volume']."</p>
            <p><strong>Edição:</strong> " . $bookcopy['edition_number']." $publishing_year</p>
            <p><strong>Páginas:</strong> " . $bookcopy['pages']." $publishing_year</p>
            <p><strong>Cores da capa:</strong> $colors</p>
        ";
        
        return $detail;
    }

    protected function data_table($element): string {
        return "<p>[Aqui, futuramente discriminar-se-ão informações de reservas]</p>";
        
    }


}

$element = new BookcopyDetail();
$element -> echo_structure();
      

?>