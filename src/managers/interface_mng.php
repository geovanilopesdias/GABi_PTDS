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
    private static function echo_system_logo(){
        $logo_path = "/code/src/views/images/gabi_logo.png";
        echo "<img id=\"gabi_logo\" src=\"$logo_path\"/></br>";
    }

    public static function echo_html_head(string $title, string $page_type){
        try {
            $page_type = PAGE_TYPE::from($page_type);
            $base_sheet_path = "code/src/views/stylsheets/basesheet.css";
            $stylesheet_path = "code/src/views/stylesheets/$page_type->value.css";
            $script_path = "code/src/views/scripts/$page_type->value.js";
        }
        catch (Exception $e) {die("Echo HTML Head Tag failed: " . $e->getMessage());}
        
        echo "
            <!DOCTYPE html>
                <html>
                    <head>
                        <title>$title</title>
                        <link href=\"$base_sheet_path\" rel=\"stylesheet\" page_type=\"text/css\" />
                        <link href=\"$stylesheet_path\" rel=\"stylesheet\" page_type=\"text/css\" />
                        <script src=\"$script_path\" type=\"module\"></script>
                        <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\" />
                        <meta charset='utf-8'>
                    </head>
                    <body>
        ";
        self::echo_system_logo();
    }

    

    public static function echo_html_tail(){
        echo "
                <footer>
                    GABi | Desenvolvido por Geovani L. Dias
                </footer>
                </body>
            </html>
        ";
    }

    public static function echo_logout_button(){
        
    }

    public static function echo_return_button(){
        
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