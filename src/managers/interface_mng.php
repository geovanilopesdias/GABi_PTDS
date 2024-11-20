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

    // ----- Special tags:
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

    public static function back_to_menu_button(): string{
        return "
            <form method='post' action='".
                htmlspecialchars($_SESSION['user_role'])."_menu.php'>
                <input 
                    id='back_to_menu_button' 
                    class='back_buttons'
                    type='submit' 
                    value='&#x25c0; | MENU'>
            </form>
        ";
    }

    public static function search_button(): string {
        return "<input class='search_button' type='submit' value='&#x1F50D;'>";
    }

    public static function search_input_disclaimer($disclaimer): string{
        return "<p class='search_disclaimer'>".
            htmlspecialchars($disclaimer)."</p></br>";
    }

    /**
     * Ajustar para retornar à respectiva página de busca
     * com a mensagem acima da div de busca.
     */
    public static function no_results_disclaimer($disclaimer): string{
        return "<p class='no_results_disclaimer'>".
            htmlspecialchars($disclaimer)."</p></br>";
    }

    /**
     * Returns a serie of input-labels.
     * 
     * @param array $selector: An 2D array containing more than two inner associative arrays, which
     * contains the data to the input construction: the inner array's keys are named after the 'value' and 'id'
     * properties needed for the tag; the inner array's values refer to the content of their labels.
     * Example: $selector = [['id' => 'male', 'content' => 'Masculino], ...
     * @param string $group_name: The 'name' property of all input tags that'll groups them.
     * @param string $field_set_legend: Value of the legend of the fieldset nesting the input-radio group.
     * @param ?string $div_name: Optional name for a div to stylize it in css. Letting it null
     * won't nest the input-radio group in any div tags.
     * @param bool $first_option_checked: if true, inserts the 'checked' property in the first option.
     */
    public static function input_radio_group(
        array $selector, string $group_name, string $field_set_legend,
        ?string $div_name = null, bool $first_option_checked = true): string {
            if (count($selector) < 1) throw new InvalidArgumentException('More than one option is needed');
            
            $radio_group = '';
            for ($i = 0; $i < count($selector); $i++){
                if (!isset($selector[$i]['id'], $selector[$i]['content'])) 
                    throw new InvalidArgumentException('Each option must have "id" and "content" keys.');
                $id = htmlspecialchars(trim($selector[$i]['id']));
                $content = htmlspecialchars(trim($selector[$i]['content']));
                $group = htmlspecialchars($group_name);
                $optional_check = ($i == 0 and $first_option_checked) ? 'checked' : '';
                $radio_group .= "
                    <input type='radio' id='$id' name='$group' value='$id' $optional_check/>
                    <label for='$id'>$content</label>
                ";
            }
            return $div_name ?
                "<div id='".htmlspecialchars($div_name)."'>
                    <fieldset>
                        <legend>".htmlspecialchars($field_set_legend)."</legend>
                        $radio_group
                    </fieldset>
                </div>" :

                "<fieldset>
                    <legend>".htmlspecialchars($field_set_legend)."</legend>
                    $radio_group
                </fieldset>";
    }

    /**
     * Selector tag with all the registered classrooms.
     * 
     * The values of the options are the respective classrooms' ids
     *  to properly fetch them.
     */
    public static function classroom_selector(bool $is_required = true): string{
        $classroom_intances = PeopleDAO::fetch_all_classrooms();
        $required = ($is_required) ? 'required' : '';
        $selector = "
            <select name='classrooms[]' class='selector' $required multiple size='3'>
                <option value=''>Seleciona uma turma</option>";
        foreach ($classroom_intances as $c)
            $selector .= "<option value='".$c->get_id()."'>".
                              $c->get_name().'/'.$c->get_year()."
                          </option>";
        
        return "$selector</select>";
    }

    /**
     * Generates an HTML table string from a set of results.
     *
     * @param string $caption The caption for the table.
     * @param array $results An array of associative arrays containing the data to populate the table.
     * @return string The HTML string for the table.
     * @throws InvalidArgumentException If $results is empty or not formatted correctly.
     */
    public static function table_of_results(string $caption, array $results): string {
        if (empty($results)) 
            throw new InvalidArgumentException('The results array must not be empty.');
        
        foreach ($results as $row) 
            if (!is_array($row) || array_values($row) === $row) 
                throw new InvalidArgumentException('Each result row must be an associative array.');
        
        // Caption and header
        $headers = array_keys($results[0]);
        $table = "<table>\n<caption>" . htmlspecialchars($caption) . "</caption>\n<thead>\n<tr>";
        foreach ($headers as $header) $table .= "<th>" . htmlspecialchars($header) . "</th>";
        $table .= "</tr>\n</thead>\n<tbody>";

        // Rows
        foreach ($results as $row) {
            $table .= "\n<tr>";
            foreach ($headers as $header) {
                $table .= "<td>" . htmlspecialchars($row[$header]) . "</td>";
            }
            $table .= "</tr>";
        }

        $table .= "\n</tbody>\n</table>";

        return $table;
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