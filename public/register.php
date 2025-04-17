<?php
$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('conexao.php');

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $senha = $_POST['senha'];
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $query = "SELECT * FROM usuarios WHERE email = '$email' OR username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $erro = "E-mail ou nome de usuário já existe!";
    } else {
        $query = "INSERT INTO usuarios (email, username, senha) VALUES ('$email', '$username', '$senha_hash')";
        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit();
        } else {
            $erro = "Erro ao cadastrar usuário!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Doce Vida - Cadastro</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-200 h-screen flex items-center justify-center">
  <div class="flex w-full max-w-4xl">

    <!-- LADO ESQUERDO - Formulário -->
    <div class="w-1/2 flex flex-col items-center justify-center">
      <div class="text-center">
        <div class="flex items-center justify-center mb-4">
          <img alt="Blue ribbon logo for diabetes awareness" class="h-16 w-30" src="img/fita doce vida.png">
        </div>
        <h1 class="text-3xl font-bold text-blue-900">Doce Vida - Cadastro</h1>
        <p class="text-blue-900 text-sm mt-2">Crie sua conta para iniciar sua jornada</p>
      </div>

      <form method="POST" action="register.php" class="mt-8 w-3/4">
        <input name="email" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" type="email" placeholder="E-mail" required>
        <input name="username" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Usuário" type="text" required>
        <input name="senha" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Senha" type="password" required>

        <?php if (!empty($erro)): ?>
          <p class="text-red-600 text-sm mb-4 text-center"><?= $erro ?></p>
        <?php endif; ?>

        <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">CADASTRAR</button>

        <a href="index.php" class="text-blue-900 text-sm underline block text-center mt-4">Já tem conta? Faça login</a>
      </form>

    </div>

    <!-- LADO DIREITO - Ilustração -->
    <div class="w-1/2 flex items-center justify-center">
      <div class="relative">
        <div class="bg-blue-300 rounded-full h-64 w-64 flex items-center justify-center">
          <div class="bg-blue-400 rounded-full h-48 w-48 flex items-center justify-center">
            <img alt="Hand holding a glucometer icon" class="h-128 w-64" src="img/login doce vida.png">
          </div>
        </div>
      </div>
    </div
