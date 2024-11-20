<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');

session_start();

final class UserSearchResults{
    const PAGE_TYPE = 'result_list';

    static function echo_logo_back(){
        echo "
            <div id='logo'>".
                InterfaceManager::system_logo(self::PAGE_TYPE).
            "</div>
            <div id='back_to_menu'>".
                InterfaceManager::back_to_menu_button().
            "</div>
        ";
    }

    static function echo_table_results(){
        $results = array();
        // Search by name:
        if (!empty($_GET['name'])){
            $search = "por: ".htmlspecialchars($_GET['name']);
            $results = match (htmlspecialchars($_GET['radio_search_for'])){
                'all' => PeopleDAO::fetch_readers_by_name($search),
                'stu' => PeopleDAO::fetch_students_by_name($search),
                'tea' => PeopleDAO::fetch_teachers_by_name($search),
                default => array(),
            };
        }

        // Search by classroom:
        else {
            $search = 'pelas turmas selecionadas são:';
            foreach($_GET['classrooms'] as $id){
                $c_students = PeopleDAO::fetch_all_students_from_classroom(htmlspecialchars($id));
                $results = array_merge($results, $c_students);
            }
        }        
        
        // Disclaimer to no results:
        if (empty($results)){
            $disclaimer = "A busca por ".htmlspecialchars($_GET['name'])."
                  não retornou qualquer resultado" ;
            echo InterfaceManager::no_results_disclaimer($disclaimer);
        }
        
        // Table build:
        else {
            $caption = "Resultados da busca $search";
            echo InterfaceManager::table_of_results($caption, $results);
        }
    }

    static function echo_structure(){
        session_start();
        if (!isset($_SESSION['user_id']) and $_SESSION['user_role'] !== 'librarian') {
            header('Location: login.php'); exit;
        }
        $search = htmlspecialchars($_GET['name']).htmlspecialchars($_GET['classroom']);
        $title = "GABi | Busca por: $search";
        InterfaceManager::echo_html_head($title, self::PAGE_TYPE);
        echo "<div class='results_grid'>";
        self::echo_logo_back();
        self::echo_table_results();
        echo "</div>";
        InterfaceManager::echo_html_tail();
    }
    
}

UserSearchResults::echo_structure();

?>