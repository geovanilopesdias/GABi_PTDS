<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/search_result.php');


final class UserSearchResults extends SearchResults{
    const SEARCH_TYPE = 'user';
    const GET_FIELDS = ['name', 'classrooms_ids'];

    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE,
        array $get_fields = self::GET_FIELDS){
            parent::echo_structure($search_type, $get_fields);
    }

    protected function echo_table_results(){
        $results = array();
        // Search by name:
        if (!empty($_GET['name'])){
            $search = htmlspecialchars(trim($_GET['name']));
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
            foreach($_GET['classrooms_ids'] as $id){
                $c_students = PeopleDAO::fetch_all_students_from_classroom(htmlspecialchars($id));
                $results = array_merge($results, $c_students);
            }
        }        
        
        // Disclaimer to no results:
        if (empty($results)){
            $disclaimer = "A busca por '".htmlspecialchars($_GET['name'])."'
                  não retornou qualquer resultado" ;
            echo InterfaceManager::no_results_disclaimer($disclaimer);
        }
        
        // Table build:
        else{
            $caption = "Resultados da busca por '$search'";
            echo InterfaceManager::table_of_results($caption, $results);
        }
    }
    
}

$results = new UserSearchResults();
$results -> echo_structure();

?>