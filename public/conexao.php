<?php
$host = 'db';  // Nome do serviço MySQL no docker-compose
$user = 'appuser';  // Usuário configurado no docker-compose
$pass = 'apppass';  // Senha configurada no docker-compose
$dbname = 'sistema_login';  // Nome do banco de dados configurado

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}


?>
