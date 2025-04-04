<?php
session_start();
$conn = new mysqli("localhost", "root", "", "seu_banco_de_dados");

// Verifica conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Simula um ID de usuário logado (substitua isso pelo seu sistema de login)
$user_id = 1;

// Obtém dados do usuário
$sql = "SELECT * FROM usuarios WHERE id = $user_id";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();

// Atualiza os dados do usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    
    // Atualiza a senha apenas se foi alterada
    if (!empty($_POST["senha"])) {
        $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
        $sql_update = "UPDATE usuarios SET nome='$nome', email='$email', senha='$senha' WHERE id=$user_id";
    } else {
        $sql_update = "UPDATE usuarios SET nome='$nome', email='$email' WHERE id=$user_id";
    }

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Perfil atualizado com sucesso!');</script>";
        header("Refresh:0");
    } else {
        echo "Erro: " . $conn->error;
    }
}

// Upload da foto de perfil
if (isset($_FILES["foto_perfil"]) && $_FILES["foto_perfil"]["error"] == 0) {
    $diretorio = "uploads/";
    $arquivo = $diretorio . basename($_FILES["foto_perfil"]["name"]);

    if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $arquivo)) {
        $conn->query("UPDATE usuarios SET foto_perfil='$arquivo' WHERE id=$user_id");
        echo "<script>alert('Foto de perfil atualizada!');</script>";
        header("Refresh:0");
    }
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
    </form>
</div>

</body>
</html>
