<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/updater.php');

final class UserUpdater extends Updater{ 
    const UPDATER_TYPE = 'user';
    
    function __construct(){}
    
    public function echo_structure(
        string $register_type = self::UPDATER_TYPE){
            parent::echo_structure($register_type);
    }

    protected function echo_updater_form(){
        $form_data = $_SESSION['form_data'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['form_data'], $_SESSION['errors']);
        $user_instance = PeopleDAO::fetch_reader_by_id(htmlspecialchars($_POST['id']), true);
        if (is_null($user_instance)) 
            {echo "Puxa vida! Houve um problema no acesso à edição de leitores...";}
        
        else {
            echo "
                <div id='register_form'>
                    <form class='register' action='".self::UPDATER_TYPE."_update_manager.php' method='post'>
                        <input type='hidden' name='id' value='".$_POST['id']."'>".
                        (($user_instance -> get_role() == 'teacher') ?  
                            InterfaceManager::input_checkbox_single('can_borrow', 'Poderá emprestar', '', false) : '')."</br>

                        <input type='text' name='name' placeholder='Nome' value='".
                            (htmlspecialchars($form_data['name'] ?? $user_instance -> get_name()))."' autofocus required/><br>".
                        ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_name']) : '') ."
                        
                        <input type='text' id='phone' name='phone' placeholder='Telefone' value='".
                            (htmlspecialchars($form_data['phone'] ?? $user_instance -> get_phone()))."' required/><br>".
                        ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_phone']) : '') .
                        InterfaceManager::updater_button().
                    "</form>
                </div>
            ";
        }
        
    }
    
}

$search = new UserUpdater();
$search -> echo_structure();

?>