<?php

enum PAGE_TYPE: string{
    case LOGIN = 'login';
    case MENU = 'menu';
    case REGISTRATION = 'registration';
    case SEARCHING = 'searching';
    case ELEMENT_DETAIL = 'element detail';
    case RESULT_LIST = 'result list';
}

final class InterfaceManager{
    // Echoers:
    public static function echo_html_head(string $title, string $page_type){
        try {
            $page_type = PAGE_TYPE::from($page_type);
            $base_sheet_path = "/code/src/views/stylesheets/basesheet.css";
            $stylesheet_path = "/code/src/views/stylesheets/$page_type->value.css";
            $script_path = "/code/src/views/scripts/$page_type->value.js";
        }
        catch (Exception $e) {die("Echo HTML Head Tag failed: " . $e->getMessage());}
        
        echo "
            <!DOCTYPE html>
                <html lang='pt-br'>
                    <head>
                        <title>$title</title>
                        <link href='$base_sheet_path' rel='stylesheet' page_type='text/css' />
                        <link href='$stylesheet_path' rel='stylesheet' page_type='text/css' />
                        <script src='$script_path' type='module'></script>
                        <link rel='preconnect' href='https://fonts.googleapis.com' />
                        <meta charset='utf-8'>
                    </head>
                    <body>
        ";
    }

    public static function echo_html_tail(): void{
        echo "
                <footer>
                    GABi | Desenvolvido por Geovani L. Dias
                </footer>
                </body>
            </html>
        ";
    }

    // Special tags:
    public static function system_logo(string $page_type): string{
        $page_type = PAGE_TYPE::from($page_type);
        $logo_path = "/code/src/views/images/gabi_logo.png";
        if(in_array($page_type, [PAGE_TYPE::LOGIN, PAGE_TYPE::MENU]))
            return "<img id='gabi_logo_big' src='$logo_path'/></br>";
        else
            return "<img id='gabi_logo_medium' src='$logo_path'/></br>";
    }
    
    public static function menu_greetings(string $user_name): string{
        return "
            <h1>Olá, $user_name!<h1>
            <h2><em>Hoje é ".date("d/m/y")."</em></h2>
        ";
    }

    public static function logout_button(): string{
        return "
            <a href='logout.php'>
                <button id='logout_button' type='button'>
                    &#x25c0; | SAIR
                </button>
            </a>
        ";
    }

    public static function return_to_menu_button(){
        
    }
}

// echoHtmlHead
// echoSystemLogo
// echoHtmlTail
// echoLogoutButton
// echoReturnButton
// echoReaderTypeSelector
// echoClsrmSelectorForRegistration
// echoClsrmSelectorForStudent
// echoClsrmSelectorForTeacher
// echoClsrAndStudentSelectorForSearching
// echoClsrmSelectorForSearching
// echoStudentSelectorFromClsrm
// echoButtonGridForLoan
// echoRenovationButton
// echoReturnCopyButton
// echoSearchButton
// echoPayDebtButton
// echoEntityAnchor