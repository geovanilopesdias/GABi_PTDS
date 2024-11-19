<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

final class LibrarianMenu{
    const PAGE_TYPE = 'menu';
    const MENU_HREF = [
        'loan_registration' => 'loan_registration.php',
        'loan_search' => 'loan_search.php',
        'book_registration' => 'book_registration.php',
        'book_search' => 'book_search.php',
        'user_registration' => 'user_registration.php',
        'user_search' => 'user_search.php',
        'report_debt' => 'report_debt.php',
        'report_pages' => 'report_pages.php',
        'report_loan_history' => 'report_loan_history.php',
        'report_outdate_user' => 'report_outdate_user.php'];

    const IMAGE_DIR = '/code/src/views/images/';     
    const MENU_ICON_SRC = [
        'loan_registration' => self::IMAGE_DIR.'loan_registration.png',
        'loan_search' => self::IMAGE_DIR.'loan_search.png',
        'book_registration' => self::IMAGE_DIR.'book_register.png',
        'book_search' => self::IMAGE_DIR.'book_search.png',
        'user_registration' => self::IMAGE_DIR.'user_register.png',
        'user_search' => self::IMAGE_DIR.'user_search.png',
        'report_debt' => self::IMAGE_DIR.'report_debt.png',
        'report_open_loans' => self::IMAGE_DIR.'report_open_loans.png',
        'report_user_loan_history' => self::IMAGE_DIR.'report_user_loan_history.png',
        'report_outdate_user' => self::IMAGE_DIR.'report_outdate_user.png'];

    
    static function echo_menu_hat(){
        echo "
            <div id='menu_hat'>
                <div id='logo_menu_hat'>".
                    InterfaceManager::system_logo(self::PAGE_TYPE).
                "</div>
                <div id='greeting_menu_hat'>".
                    InterfaceManager::menu_greetings($_SESSION['user_name']).
                    InterfaceManager::logout_button().
                "</div>
            </div>
        ";
        
    }

    static function echo_menu_table(){
        echo "
        <table class='menu_table'>
            <caption>
                Menu Bibliotecário
            </caption>
            <tr>
                <td class='fields' colspan='2'>Empréstimos</td>
                <td class='fields' colspan='2'>Livros</td>
            </tr>

            <tr>
                <td class='labels'>Novos</td>
                <td class='labels'>Consulta</td>
                <td class='labels'>Cadastro</td>
                <td class='labels'>Consulta</td>
            </tr>
      
            <tr>
                <td>
                <a href='".self::MENU_HREF['loan_registration']."'>
                    <img class='menu_icon' src='".self::MENU_ICON_SRC['loan_registration']."' />
                </a>
                </td>

                <td>
                <a href='".self::MENU_HREF['loan_search']."'>
                    <img class='menu_icon' src='".self::MENU_ICON_SRC['loan_search']."' />
                </a>
                </td>
                
                <td>
                <a href='".self::MENU_HREF['book_registration']."'>
                    <img class='menu_icon' src='".self::MENU_ICON_SRC['book_registration']."' />
                </a>
                </td>
                
                <td>
                <a href='".self::MENU_HREF['book_search']."'>
                    <img class='menu_icon' src='".self::MENU_ICON_SRC['book_search']."' />
                </a>
                </td>
            </tr>

            <tr>
                <td class='fields' colspan='2'>Usuários</td>
                <td class='fields' colspan='2'>Relatórios</td>
            </tr>

            <tr>
                <td class='labels'>Cadastro</td>
                <td class='labels'>Busca</td>
                <td class='labels'>Débitos</td>
                <td class='labels'>Empréstimos</td>
            </tr>
            <tr>
                <td rowspan='3'>
                <a href='".self::MENU_HREF['user_registration']."'>
                    <img class='menu_icon' src='".self::MENU_ICON_SRC['user_registration']."' />
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
                <td class='labels'>Históricos</td>
                <td class='labels'>Desatualizações</td>
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
        </table>
        <a
            id='flaticon_reference'
            href='https://www.flaticon.com/'
            title='Flaticon Website'
        >Ícones do Portal Flaticon</a></br>
        ";
    }

    static function echo_structure(){
        session_start();
        if (!isset($_SESSION['user_id']) or $_SESSION['user_role'] !== 'librarian') {
            header('Location: login.php'); exit;
        }
        $title = "GABi | Menu Bibliotecário";
        InterfaceManager::echo_html_head($title, self::PAGE_TYPE);
        self::echo_menu_hat();
        self::echo_menu_table();
        InterfaceManager::echo_html_tail();
    }

    
}

LibrarianMenu::echo_structure();