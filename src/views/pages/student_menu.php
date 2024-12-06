<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/menu.php');

final class StudentMenu extends Menu{
    const MENU_TYPE = 'student';
    const MENU_HREF = [
        'loan_search' => 'loan_search_readers.php',
        'book_search' => 'bookcopy_search_readers.php',
        ];

    const MENU_ICON_SRC = [
        'loan_search' => self::IMAGE_DIR.'loan_search.png',
        'book_search' => self::IMAGE_DIR.'book_search.png',
        'synopsis' => self::IMAGE_DIR.'synopsis.png',
        ];


    
    protected function echo_logo_greeting(){
        echo "
            <div id='logo'>".
                InterfaceManager::system_logo(self::PAGE_TYPE).
            "</div>
            <div id='greeting'>".
                InterfaceManager::menu_greetings($_SESSION['user_name']).
            "</div>
            <div id='logout'>".
                InterfaceManager::logout_button().
            "</div>
        ";
        
    }

    public function __construct() {}

    protected function echo_menu_table(){
        echo "<div id='table'>
            <table class='menu_table'>
                <caption>
                    Menu de Estudante
                </caption>
                <tr>
                    <td class='super_label'>Empréstimos</td>
                    <td class='super_label'>Livros</td>
                    <td class='super_label'>Sinopses</td>
                </tr>
        
                <tr>
                    <td>
                    <a href='".self::MENU_HREF['loan_search']."'>
                        <img class='menu_icon' src='".self::MENU_ICON_SRC['loan_search']."' />
                    </a>
                    </td>

                    <td>
                    <a href='".self::MENU_HREF['book_search']."'>
                        <img class='menu_icon' src='".self::MENU_ICON_SRC['book_search']."' />
                    </a>
                    </td>
                    
                    <td>
                    <a href='' title='NÃO IMPLEMENTADO'>
                        <img class='menu_icon' src='".self::MENU_ICON_SRC['synopsis']."' />
                    </a>
                    </td>
                </tr>

                
            </table>
            <a
                id='flaticon_reference'
                href='https://www.flaticon.com/'
                title='Flaticon Website'
            >Ícones do Portal Flaticon</a>
        </div>
        ";
    }

    public function echo_structure(
        string $menu_type = self::MENU_TYPE){
            parent::echo_structure($menu_type);
    }

    
}

$menu = new StudentMenu();
$menu -> echo_structure();