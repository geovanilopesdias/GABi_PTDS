<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class EditionRegister extends Register{
    const REGISTER_TYPE = 'edition';
    
    public function __construct(){}
    
    public function echo_structure(
        string $register_type = self::REGISTER_TYPE){
            parent::echo_structure($register_type);
    }

    protected function echo_register_form(){
        $form_data = $_SESSION['form_data'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['form_data'], $_SESSION['errors']);
        echo "
            <div id='register_form'>
                <form class='register' action='".self::REGISTER_TYPE."_register_manager.php' method='post'>".
                    InterfaceManager::opus_selector()."<br>".
                    InterfaceManager::publisher_selector()."<br>".
                    InterfaceManager::collection_selector()."<br>

                    <input type='text' name='isbn' placeholder='ISBN' value='".(htmlspecialchars($form_data['isbn']) ?? '')."'/></br>".
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_isbn']) : '') ."

                    <input type='number' min='1' name='edition_number' placeholder='Edição (e.g.: 2)' value='".(htmlspecialchars($form_data['edition_number']) ?? '')."'/></br>".
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_edition_number']) : '') ."

                    <input type='number' name='publishing_year' placeholder='Ano da edição' value='".(htmlspecialchars($form_data['publishing_year']) ?? '')."' /></br>".
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_publishing_year']) : '') ."

                    <input type='number' min='1' name='pages' placeholder='Número de páginas' value='".(htmlspecialchars($form_data['pages']) ?? '')."'/></br>".
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_pages']) : '') ."

                    <input type='number' min='1' name='volume' placeholder='Volume' value='".(htmlspecialchars($form_data['volume']) ?? '')."'/></br>".
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_volume']) : '') ."

                    <fieldset><legend>Para mais de uma opção, separa com vírgulas:</legend>
                        <input type='text' name='cover_colors' placeholder='Cores da capa (azul, branco)' value='".(htmlspecialchars($form_data['cover_colors']) ?? '')."'/></br>".
                        ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_cover_colors']) : '') ."

                        <input type='text' name='translators' placeholder='Tradutores' value='".(htmlspecialchars($form_data['translators']) ?? '')."'/></br>".
                        ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_translators']) : '') ."
                    </fieldset>".

                    InterfaceManager::register_button()."
                </form>
            </div>
        ";
    }
    
}

$search = new EditionRegister();
$search -> echo_structure();

?>