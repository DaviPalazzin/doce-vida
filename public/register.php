<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $senha = $_POST['senha'];

    // Verifica se o usuário já existe
    $check_user = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = mysqli_prepare($conn, $check_user);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['error' => 'Nome de usuário já existe.']);
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $query = "INSERT INTO usuarios (username, senha) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $senha_hash);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => 'Cadastro realizado com sucesso!']);
        } else {
            echo json_encode(['error' => 'Erro ao cadastrar!']);
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doce Vida - Cadastro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-200 h-screen flex items-center justify-center">

<div class="flex w-full max-w-4xl">
    <!-- Left Section -->
    <div class="w-1/2 flex flex-col items-center justify-center">
        <div class="text-center">
            <div class="flex items-center justify-center mb-4">
                <img src="img/fita doce vida.png" alt="Blue ribbon logo for diabetes awareness" class="h-16 w-30">
            </div>
            <h1 class="text-3xl font-bold text-blue-900">Doce Vida</h1>
            <p class="text-blue-900 text-sm mt-2">Sua jornada na prevenção à diabetes</p>
        </div>

        <form id="registerForm" class="mt-8 w-3/4" method="POST">
            <input class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none"
                   type="text" name="username" id="username" placeholder="Nome" required>
            <div id="usernameError" class="text-red-600 text-sm mb-2"></div>

            <input class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none"
                   type="password" name="senha" placeholder="Senha" required>

            <div id="registerMessage" class="text-center text-sm font-semibold mb-4"></div>

            <button type="submit"
                    class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500 transition">
                CADASTRAR
            </button>

            <a href="index.php" class="text-blue-900 text-sm underline block text-center mt-4">
                Já tem conta? Faça o login
            </a>
        </form>
    </div>

    <!-- Right Section -->
    <div class="w-1/2 flex items-center justify-center">
        <div class="relative">
            <div class="bg-blue-300 rounded-full h-64 w-64 flex items-center justify-center">
                <div class="bg-blue-400 rounded-full h-48 w-48 flex items-center justify-center">
                    <img src="img/login doce vida.png" alt="Imagem de login" class="h-128 w-64">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Verifica se o nome de usuário já existe ao sair do campo
        $('#username').on('blur', function () {
            const username = $(this).val();
            if (username) {
                $.get('register.php', {check_username: username}, function (data) {
                    const response = JSON.parse(data);
                    if (response.exists) {
                        $('#usernameError').text('Nome de usuário já existe.');
                    } else {
                        $('#usernameError').text('');
                    }
                });
            }
        });

        // Envia o formulário via AJAX
        $('#registerForm').submit(function (e) {
            e.preventDefault();

            const formData = $(this).serialize();
            $.post('register.php', formData, function (data) {
                const response = JSON.parse(data);
                const messageDiv = $('#registerMessage');

                if (response.error) {
                    messageDiv.text(response.error).removeClass('text-green-600').addClass('text-red-600');
                } else if (response.success) {
                    messageDiv.text(response.success).removeClass('text-red-600').addClass('text-green-600');
                    $('#registerForm')[0].reset(); // limpa os campos
                }
            });
        });
    });
</script>
</body>
</html>
