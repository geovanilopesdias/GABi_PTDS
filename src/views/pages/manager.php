<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');

abstract class ViewManager{
    const PAGE_TYPE = 'manager';

    protected abstract function persist_post_to_session($errors);
    protected abstract function handle_errors();
    
    public function manage_post_variable(){
        session_start();
    }

    protected function get_user(): ?Reader{
        return PeopleDAO::fetch_reader_by_login(trim(htmlspecialchars($_POST['login'] ?? '')));
    }

    protected function operation_failed(
        string $error_detail, $errors=[], string $register_type, string $fail_title, string $error_warning){
        InterfaceManager::echo_html_head("GABi | $fail_title", self::PAGE_TYPE);
        echo InterfaceManager::system_logo(self::PAGE_TYPE);
        echo "<h2>$error_warning</h2>";
        echo "<h4>O que houve: $error_detail<h4>";
        echo "<h3>Em instantes, serás redirecionado para tentar novamente</h3>";
        $this -> persist_post_to_session($errors);
        foreach($errors as $error) echo "
            <p>Erros encontrados:</p>
            <ul>
                <li>$error</li>
            </ul>
        ";
        header("refresh:10; url=$register_type.php");
        InterfaceManager::echo_html_tail();
        exit;
    }

    protected function operation_succeed(array $args) {
        InterfaceManager::echo_html_head("GABi | ".$args['success_title'], self::PAGE_TYPE);
        echo InterfaceManager::system_logo(self::PAGE_TYPE);
        echo "<h2>".$args['success_message']."</h2>";
        echo $args['success_body'] ?? '';
        InterfaceManager::back_to_register_button($args['register_type']);
        InterfaceManager::back_to_menu_button();
        InterfaceManager::echo_html_tail();
        exit;
    }
}
      

?>