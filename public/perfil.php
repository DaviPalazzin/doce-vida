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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>

<div class="container">
    <h2>Editar Perfil</h2>
    
    <!-- Exibir Foto de Perfil -->
    <div class="profile-pic">
        <img src="<?= $usuario['foto_perfil'] ?>" alt="Foto de Perfil">
    </div>

    <form method="POST" enctype="multipart/form-data">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= $usuario['nome'] ?>" required>

        <label>E-mail:</label>
        <input type="email" name="email" value="<?= $usuario['email'] ?>" required>

        <label>Nova Senha (opcional):</label>
        <input type="password" name="senha">

        <label>Alterar Foto de Perfil:</label>
        <input type="file" name="foto_perfil">

        <button type="submit">Salvar Alterações</button>
        
        <a href="home.php" class="voltar"><button class="Voltar">Voltar</button></a>
    </form>
</div>

</body>
</html>
