<?php
session_start();
require 'conexao.php';

if (isset($_COOKIE['session_token']) && isset($_SESSION['email'])) {
    $token = $_COOKIE['session_token'];
    $email = $_SESSION['email'];
    
    $query = "UPDATE sessoes SET data_ultimo_acesso = NOW() 
              WHERE token_sessao = ? AND usuario_id = (SELECT id FROM usuarios WHERE email = ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $token, $email);
    mysqli_stmt_execute($stmt);
}
?>