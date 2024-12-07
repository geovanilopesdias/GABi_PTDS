<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/search.php');

final class BookSearchReaders extends Search{
    const SEARCH_TYPE = 'book';
    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE){
            parent::echo_structure($search_type);
    }

    protected function echo_search_form(){

        echo "
            <div id='search_form'>
                <form class='search' action='".self::SEARCH_TYPE."_search_result.php' method='get'>    
                    <fieldset><legend>Busca por palavra-chave</legend> 
                        <input type='text' name='title' placeholder='Título' autofocus/></br>
                        <input type='text' name='writer' placeholder='Autor'/></br>
                        <input type='text' name='collection' placeholder='Coleção'/></br>
                        <input type='text' name='cover_colors' placeholder='Cor de capa (azul, branco)'/><br>".
                        InterfaceManager::search_button()."
                    </fieldset>
                </form>                    
            </div>
        ";
    }

    
}


$search = new BookSearchReaders();
$search -> echo_structure();

?>