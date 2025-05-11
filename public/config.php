<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/config.css">
    <title>Loja</title>
</head>
<body>
    <div>
        <img src="img/erro.png" alt="erro">
        <h1> ERRO, PROGRAMAÇÃO EM PROCESSO! </h1>
    </div>
</body>
</html>