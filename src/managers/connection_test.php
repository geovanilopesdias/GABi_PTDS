<?php
$config = include('config.php');
$conn = pg_connect("host={$config['host']} port={$config['port']} dbname={$config['db_name']} user={$config['db_user']} password={$config['db_pass']}");

if ($conn) {
echo "Conexão bem-sucedida!";
print_r($config);
} else {
echo "Erro na conexão: " . pg_last_error();
}
?>