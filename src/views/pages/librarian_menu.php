<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/menu.php');

final class LibrarianMenu extends Menu{
    const MENU_TYPE = 'librarian';
    const MENU_HREF = [
        'loan_register' => 'loan_register.php',
        'loan_search' => 'loan_search.php',
        'book_register' => 'book_register_options.php',
        'book_search' => 'book_search.php',
        'user_register' => 'user_register.php',
        'user_search' => 'user_search.php',
        'report_debt' => 'report_debt.php',
        'report_pages' => 'report_pages.php',
        'report_loan_history' => 'report_loan_history.php',
        'report_outdate_user' => 'report_outdate_user.php',
        'classroom_register' => 'classroom_register.php',
        'classroom_search' => 'classroom_search.php'];

    const MENU_ICON_SRC = [
        'loan_register' => self::IMAGE_DIR.'loan_register.png',
        'loan_search' => self::IMAGE_DIR.'loan_search.png',
        'book_register' => self::IMAGE_DIR.'book_register.png',
        'book_search' => self::IMAGE_DIR.'book_search.png',
        'user_register' => self::IMAGE_DIR.'user_register.png',
        'user_search' => self::IMAGE_DIR.'user_search.png',
        'report_debt' => self::IMAGE_DIR.'report_debt.png',
        'report_open_loans' => self::IMAGE_DIR.'report_open_loans.png',
        'report_user_loan_history' => self::IMAGE_DIR.'report_user_loan_history.png',
        'report_outdate_user' => self::IMAGE_DIR.'report_outdate_user.png',
        'classroom_register' => self::IMAGE_DIR.'classroom_register.png',
        'classroom_search' => self::IMAGE_DIR.'classroom_search.png',
        'library_settings' => self::IMAGE_DIR.'library_settings.png'];


    
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
                    Menu Bibliotecário
                </caption>
                <tr>
                    <td class='super_label' colspan='2'>Empréstimos</td>
                    <td class='super_label' colspan='2'>Livros</td>
                </tr>

                <tr>
                    <td class='sub_labels'>Novos</td>
                    <td class='sub_labels'>Consulta</td>
                    <td class='sub_labels'>Cadastro</td>
                    <td class='sub_labels'>Consulta</td>
                </tr>
        
                <tr>
                    <td>
                    <a href='".self::MENU_HREF['loan_register']."'>
                        <img class='menu_icon' src='".self::MENU_ICON_SRC['loan_register']."' />
                    </a>
                    </td>

                    <td>
                    <a href='".self::MENU_HREF['loan_search']."'>
                        <img class='menu_icon' src='".self::MENU_ICON_SRC['loan_search']."' />
                    </a>
                    </td>
                    
                    <td>
                    <a href='".self::MENU_HREF['book_register']."'>
                        <img class='menu_icon' src='".self::MENU_ICON_SRC['book_register']."' />
                    </a>
                    </td>
                    
                    <td>
                    <a href='".self::MENU_HREF['book_search']."'>
                        <img class='menu_icon' src='".self::MENU_ICON_SRC['book_search']."' />
                    </a>
                    </td>
                </tr>

                <tr>
                    <td class='super_label' colspan='2'>Usuários</td>
                    <td class='super_label' colspan='2'>Relatórios</td>
                </tr>

                <tr>
                    <td class='sub_labels'>Cadastro</td>
                    <td class='sub_labels'>Busca</td>
                    <td class='sub_labels'>Débitos</td>
                    <td class='sub_labels'>Empréstimos</td>
                </tr>
                <tr>
                    <td rowspan='3'>
                    <a href='".self::MENU_HREF['user_register']."'>
                        <img class='menu_icon' src='".self::MENU_ICON_SRC['user_register']."' />
                    </a>
                    </td>
                    
                    <td rowspan='3'>
                    <a href='".self::MENU_HREF['user_search']."'>
                        <img class='menu_icon' src='".self::MENU_ICON_SRC['user_search']."' />
                    </td>
                    
                    <td>
                    <a href='".self::MENU_HREF['report_debt']."'>
                        <img class='report_icon' src='".self::MENU_ICON_SRC['report_debt']."' />
                    </a>
                    </td>
                    <td>
                    <a href='".self::MENU_HREF['report_open_loans']."'>
                    <img class='report_icon' src='".self::MENU_ICON_SRC['report_open_loans']."' />
                    </td>
                </tr>
                <tr>
                    <td class='sub_labels'>Históricos</td>
                    <td class='sub_labels'>Desatualizações</td>
                </tr>
                <tr>
                    <td>
                    <a href='".self::MENU_HREF['report_user_loan_history']."'>
                    <img class='report_icon' src='".self::MENU_ICON_SRC['report_user_loan_history']."' />
                    </td>
                    <td>
                    <a href='' title='NÃO IMPLEMENTADO'>
                    <img class='report_icon' src='".self::MENU_ICON_SRC['report_outdate_user']."' />
                    </td>
                </tr>

                <tr>
                    <td class='super_label' colspan='2'>Turmas</td>
                    <td class='super_label' colspan='2'>Configurações</td>
                </tr>

                <tr>
                    <td class='sub_labels'>Cadastro</td>
                    <td class='sub_labels'>Busca</td>
                    <td class='sub_labels' colspan='2'>Visualizar/Editar</td>
                </tr>
                <tr>
                    <td>
                    <a href='".self::MENU_HREF['classroom_register']."'>
                    <img class='report_icon' src='".self::MENU_ICON_SRC['classroom_register']."' />
                    </td>
                    <td>
                    <a href='".self::MENU_HREF['classroom_search']."'>
                    <img class='report_icon' src='".self::MENU_ICON_SRC['classroom_search']."' />
                    </td>
                    <td colspan='2'>
                    <a href='".self::MENU_HREF['library_settings']."'>
                    <img class='report_icon' src='".self::MENU_ICON_SRC['library_settings']."' />
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

$menu = new LibrarianMenu();
$menu -> echo_structure();