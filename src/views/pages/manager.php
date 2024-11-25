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
        echo "<div id'failed'>";
        echo "<h2>$error_warning</h2>";
        echo "<p>O que houve: $error_detail</p>";
        echo "<p>Em instantes, serás redirecionado para tentar novamente</p>";
        $this -> persist_post_to_session($errors);
        echo "<h2>Erros encontrados:</h2>";
        foreach($errors as $error) echo "
            <ul>
                <li>$error</li>
            </ul>
        ";
        echo "</div>";
        header("refresh:10; url=$register_type.php");
        InterfaceManager::echo_html_tail();
        exit;
    }

    protected function operation_succeed(mixed &$args) {
        InterfaceManager::echo_html_head("GABi | ".$args['success_title'], self::PAGE_TYPE);
        echo InterfaceManager::system_logo(self::PAGE_TYPE);
        echo "<div id'success'>";
        echo "<h2>".$args['success_message']."</h2>";
        echo $args['success_body'] ?? '';
        echo InterfaceManager::back_to_register_button($args['register_type']);
        echo InterfaceManager::back_to_menu_button();
        echo "</div>";
        InterfaceManager::echo_html_tail();
        exit;
    }
}
      

?>