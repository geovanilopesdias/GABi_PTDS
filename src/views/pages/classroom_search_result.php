<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/search_result.php');


final class ClassroomSearchResults extends SearchResults{
    const SEARCH_TYPE = 'classroom';

    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE,
        array $get_fields = []){
            parent::echo_structure($search_type, $get_fields);
    }

    protected function echo_table_results() {
        $results = [];
        $keywords = [];
        $classroom_names = explode(',', trim($_POST['classrooms']));

        foreach ($classroom_names as $name) {
            $name = trim($name);
            if (empty($name)) continue;
            $classroom = PeopleDAO::fetch_classroom_by_name($name);
            if (!$classroom) continue; 

            $keywords[] = $name;
            $classroom_id = $classroom->get_id();
            $search_type = htmlspecialchars($_GET['radio_search_for'] ?? 'all');
            
            $fetched_results = match ($search_type) {
                'stu' => PeopleDAO::fetch_all_students_from_classroom($classroom_id),
                'tea' => PeopleDAO::fetch_all_teachers_from_classroom($classroom_id),
                default => array_merge(
                    PeopleDAO::fetch_all_students_from_classroom($classroom_id),
                    PeopleDAO::fetch_all_teachers_from_classroom($classroom_id)
                ),
            };

            $results = array_merge($results, $fetched_results);
        }

        // Handle results
        if (empty($results)) {
            $disclaimer = "Que pena: nenhum indivíduo encontrado...";
            echo InterfaceManager::no_results_disclaimer($disclaimer);
        } else {
            $caption = "Resultados da busca por '" . implode(', ', $keywords) . "'";
            echo InterfaceManager::table_of_results(self::SEARCH_TYPE, $caption, $results);
        }
    }

    
}

$results = new ClassroomSearchResults();
$results -> echo_structure();

?>