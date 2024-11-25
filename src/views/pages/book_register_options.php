<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/menu.php');

final class BookRegisterOptions extends Menu{
    const MENU_TYPE = 'book_register_options';
    const MENU_HREF = [
        'opus_register' => 'opus_register.php',
        'edition_register' => 'edition_register.php',
        'bookcopy_register' => 'bookcopy_register.php',
        'collection_register' => 'collection_register.php',
        'writer_register' => 'writer_register.php'];

    const IMAGE_DIR = '/code/src/views/images/';     
    const MENU_ICON_SRC = [
        'opus_register' => self::IMAGE_DIR.'opus_register.png',
        'edition_register' => self::IMAGE_DIR.'edition_register.png',
        'bookcopy_register' => self::IMAGE_DIR.'bookcopy_register.png',
        'collection_register' => self::IMAGE_DIR.'collection_register.png',
        'writer_register' => self::IMAGE_DIR.'writer_register.png'];
    
    function __construct(){}
    
    public function echo_structure(
        string $menu_type = self::MENU_TYPE){
            parent::echo_structure($menu_type);
    }

    protected function echo_logo_greeting(){
        echo "
            <div id='logo_for_book_register_options'>".
                InterfaceManager::system_logo(self::PAGE_TYPE).
            "</div>
            <div id='logout_for_book_register_options'>".
                InterfaceManager::back_to_menu_button().
            "</div>
        ";
        
    }

    protected function echo_menu_table(){
        echo "<div id='table'>
            <table class='menu_table'>
                <caption>
                    Cadastro de livros
                </caption>
                <tr>
                    <td class='sub_labels'>Obras</td>
                    <td class='sub_labels'>Edições</td>
                    <td class='sub_labels'>Exemplares</td>
                </tr>
                <tr>
                    <td>    
                        <a href='".self::MENU_HREF['opus_register']."'>
                            <img class='menu_icon' src='".self::MENU_ICON_SRC['opus_register']."' />
                        </a>
                    </td>
                    <td>    
                        <a href='".self::MENU_HREF['edition_register']."'>
                            <img class='menu_icon' src='".self::MENU_ICON_SRC['edition_register']."' />
                        </a>
                    </td>
                    <td>    
                        <a href='".self::MENU_HREF['bookcopy_register']."'>
                            <img class='menu_icon' src='".self::MENU_ICON_SRC['bookcopy_register']."' />
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class='description_labels'>Obra em si, abstrata, com título e autor(es)</td>
                    <td class='description_labels'>Publicação de certa editora em certo ano</td>
                    <td class='description_labels'>Cópia física, com patrimônio e que pode efetivamente ser emprestada</td>
                </tr>
                <tr>
                    <td class='sub_labels'>Autores</td>    
                    <td class='sub_labels' colspan='2'>Coleções</td>
                </tr>
                <tr>
                    <td>    
                        <a href='".self::MENU_HREF['writer_register']."'>
                            <img class='menu_icon' src='".self::MENU_ICON_SRC['writer_register']."' />
                        </a>
                    </td>
                    <td  colspan='2'>
                        <a href='".self::MENU_HREF['collection_register']."'>
                            <img class='menu_icon' src='".self::MENU_ICON_SRC['collection_register']."' />
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class='description_labels'>Cadastre autores antes da obra!</td>
                    <td class='description_labels' colspan='2'>Conjunto de obras que compõem uma coleção, como 'Vagalume' ou 'Harry Potter'.</td>
                </tr>
                
            </table></div>
        ";
    }
    
}

$search = new BookRegisterOptions();
$search -> echo_structure();

?>