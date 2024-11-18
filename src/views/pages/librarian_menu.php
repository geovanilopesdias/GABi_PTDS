<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

final class LibrarianMenu{
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

    const MENU_ICON_SRC = [
        'loan_registration' => '/../images/loan_register.png',
        'loan_search' => '/../images/loan_search.png',
        'book_registration' => '/../images/book_register.png',
        'book_search' => '/../images/book_search.png',
        'user_registration' => '/../images/user_register.png',
        'user_search' => '/../images/user_search.png',
        'report_debt' => '/../images/report_debt.png',
        'report_open_loans' => '/../images/report_open_loans.png',
        'report_loan_history' => '/../images/report_loan_history.png',
        'report_outdate_user' => '/../images/report_report_outdate_user.png'];


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
                <a href='".self::MENU_HREF['report_pages']."'>
                <img class='report_icon' src='".self::MENU_ICON_SRC['report_pages']."' />
                </td>
            </tr>
            <tr>
                <td class='labels'>Históricos</td>
                <td class='labels'>Desatualizações</td>
            </tr>
            <tr>
                <td>
                <a href='".self::MENU_HREF['report_loan_history']."'>
                <img class='report_icon' src='".self::MENU_ICON_SRC['report_loan_history']."' />
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
        InterfaceManager::echo_html_head($title, 'menu');
        InterfaceManager::echo_menu_greetings($_SESSION['user_id']);
        self::echo_menu_table();
        InterfaceManager::echo_logout_button();
        InterfaceManager::echo_html_tail();
    }

    
}

LibrarianMenu::echo_structure();