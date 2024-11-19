<?php

final class InterfaceManager{
    const PAGE_TYPE = [
        'login',
        'menu',
        'registration',
        'searching',
        'element_detail',
        'result_list'
    ];
    
    private static function is_page_type_valid($page_type): bool{
        return in_array($page_type, self::PAGE_TYPE, true);
    }

    // Echoers:
    public static function echo_html_head(string $title, string $page_type){
        if (!self::is_page_type_valid($page_type))
            throw new Exception('Page type should be one of the following: '.
                implode(',', self::PAGE_TYPE));

        $base_sheet_path = "/code/src/views/stylesheets/basesheet.css";
        $stylesheet_path = "/code/src/views/stylesheets/$page_type.css";
        $script_path = "/code/src/views/scripts/$page_type.js";
        
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
        if (!self::is_page_type_valid($page_type))
            throw new Exception('Page type should be one of the following: '.
                implode(',', self::PAGE_TYPE));
        
        $logo_path = "/code/src/views/images/gabi_logo.png";
        return "<img id='gabi_logo_$page_type' class='gabi_logo' src='$logo_path'/></br>";
    }
    
    public static function menu_greetings(string $user_name): string{
        $today = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));
        $today = $today -> format('d/m/y');
        return "
            <h1>Olá, $user_name!<h1>
            <h2><em>Hoje é $today</em></h2>
        ";
    }

    public static function logout_button(): string{
        return "
            <form method='post' action='logout.php'>
                <input 
                id='logout_button' 
                class='back_buttons'
                type='submit' 
                value='&#x25c0; | SAIR'>
            </form>
        ";
    }

    public static function back_to_menu_button(){
        return "
            <form method='post' action='logout.php'>
                <input 
                    id='back_to_menu_button' 
                    class='back_buttons'
                    type='submit' 
                    value='&#x25c0; | MENU'>
            </form>
        ";
    }
}

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