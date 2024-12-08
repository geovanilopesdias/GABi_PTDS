<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/form_manager.php');

final class ClassroomRegisterManager extends FormManager{
    const REGISTER_TYPE = 'classroom';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de turma!';

    public function __construct() {}

    protected function persist_post_to_session($errors) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['errors'] = $errors;
    }

    protected function operation_failed(
        array $errors,
        string $register_type = self::REGISTER_TYPE,
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING)
            {parent::operation_failed($errors, $register_type, $fail_title, $error_warning);}

    protected function operation_succeed(&$args){
        try{
            $classroom_data = $args['classroom_data'];
            $classroom_names = explode(',', $classroom_data['names']);
            foreach ($classroom_names as $n)   
                PeopleDAO::register_classroom(['name' => htmlspecialchars($n), 'year' => $classroom_data['year']], $_SESSION['user_id']);
            $args['success_body'] = $this -> unordered_register_data($classroom_data);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!\n".$e -> getMessage();
        }
    }

    protected function handle_errors() : array {
        $errors = array();
        $classroom_names = explode(',', $_POST['names']);
        foreach ($classroom_names as $n) {
            $classroom_instance = PeopleDAO::fetch_classroom_by_name($n);
            if (!is_null($classroom_instance) and $classroom_instance -> get_year() == $_POST['year'])
                {$errors['invalid_name'] = "Uma turma \"".htmlspecialchars($n)."\" já existe para ".htmlspecialchars($_POST['year']).".";}
        }
        
        if ($_POST['year'] < date("Y")-1 or $_POST['year'] > date("Y")+1)
            {$errors['invalid_year'] = 'O ano '.htmlspecialchars($_POST['year']).' é muito antigo ou futuro.';}
        
        return $errors;
    }

    protected function unordered_register_data(array $classroom_data): string {
        $list = "
            <p><span class='classroom_data_header'>Turmas cadastradas:</span></p></br><ul>";
    
        foreach ($classroom_data['names'] as $n) $list .= "<li>" . htmlspecialchars($n) . "</li>";
        $list .= "</ul>";
        return $list;
    }
    

    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)){
                $args = [
                    'register_type' => self::REGISTER_TYPE,
                    'success_title' => 'Cadastro aceito',
                    'success_message' => 'Cadastro de turma realizado com sucesso'
                ];
                
                $args['classroom_data'] = [
                    'names' => $_POST['names'],
                    'year' => htmlspecialchars($_POST['year'])
                ];
                $this->operation_succeed($args);
                
            } 
            else $this->operation_failed($errors);
        }
    }
    

}

$management = new ClassroomRegisterManager();
$management -> manage_post_variable();   

?>