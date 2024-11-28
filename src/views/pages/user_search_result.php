<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/search_result.php');


final class UserSearchResults extends SearchResults{
    const SEARCH_TYPE = 'user';
    const GET_FIELDS = ['name', 'classrooms'];

    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE,
        array $get_fields = self::GET_FIELDS){
            parent::echo_structure($search_type, $get_fields);
    }

    protected function echo_table_results(){
        $results = array();
        $keywords = array();
        // Search by name:
        $radio_option = htmlspecialchars($_GET['radio_search_for']);
        if (!empty($_GET['name'])){
            $search = htmlspecialchars(trim($_GET['name']));
            $keywords[] = $search;
            $results = match ($radio_option){
                'all' => PeopleDAO::fetch_readers_by_name($search),
                'stu' => PeopleDAO::fetch_students_by_name($search),
                'tea' => PeopleDAO::fetch_teachers_by_name($search),
            };
        }

        // Search by classroom:
        else {
            foreach ($_GET['classrooms'] as $c_id){
                $search = htmlspecialchars(trim($c_id));
                $keywords[] = $search;
                $results = match ($radio_option){
                    'stu' => PeopleDAO::fetch_all_students_from_classroom($search),
                    'tea' => PeopleDAO::fetch_all_teachers_from_classroom($search),
                    'all' => array(),
                };
                if ($radio_option === 'all') {
                    $stu = PeopleDAO::fetch_all_students_from_classroom($search);
                    $tea = PeopleDAO::fetch_all_teachers_from_classroom($search);
                    $results = array_merge($stu, $tea);
                }
            }
        }        
        
        // Disclaimer to no results:
        if (empty($results)){
            $disclaimer = "A busca por '".implode(', ', $keywords)."'
                  não retornou qualquer resultado" ;
            echo InterfaceManager::no_results_disclaimer($disclaimer);
        }
        
        // Table build:
        else{
            $caption = "Resultados da busca por '$search'";
            echo InterfaceManager::table_of_results(self::SEARCH_TYPE, $caption, $results);
        }
    }
    
}

$results = new UserSearchResults();
$results -> echo_structure();

?>