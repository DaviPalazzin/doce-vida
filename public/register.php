<?php
include('conexao.php'); // Inclua a conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $username = $_POST['username'];
    $senha = $_POST['senha'];

    // Verifica se o usuário já existe
    $check_user = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = mysqli_prepare($conn, $check_user);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Usuário já existe
        echo json_encode(['error' => 'Nome de usuário já existe.']);
    } else {
        // Se não existe, cria o novo usuário
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $query = "INSERT INTO usuarios (username, senha) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $senha_hash);

        if (mysqli_stmt_execute($stmt)) {
            // Cadastro bem-sucedido
            echo json_encode(['success' => 'Cadastro realizado com sucesso!']);
        } else {
            echo json_encode(['error' => 'Erro ao cadastrar!']);
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit();
}

// Verifica se a requisição é para checar o nome de usuário
if (isset($_GET['check_username'])) {
    $username = $_GET['check_username'];
    $check_user = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = mysqli_prepare($conn, $check_user);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <canvas id="backgroundCanvas"></canvas>

    <div class="container">
        <form id="registerForm" method="POST" class="register-form">
            <h1>Crie sua conta</h1>
            <p>Preencha os campos abaixo para se registrar.</p>

            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Nome de usuário" required>
                <div id="usernameError" class="error-message"></div>
            </div>

            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="senha" id="senha" placeholder="Senha" required>
            </div>

            <button type="submit">Cadastrar</button>

            <!-- Exibe a mensagem de erro ou sucesso abaixo do botão -->
            <div id="registerMessage" class="message"></div>

            <p class="forgot-password">Já tem uma conta? <a href="index.php" class="register-link">Faça login</a></p>
        </form>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Verifica se o nome de usuário já existe enquanto o usuário digita
            $('#username').on('blur', function() {
                var username = $(this).val();
                
                if (username) {
                    $.get('register.php', { check_username: username }, function(data) {
                        var response = JSON.parse(data);
                        if (response.exists) {
                            $('#usernameError').text('Nome de usuário já existe.').show();
                        } else {
                            $('#usernameError').text('').hide();
                        }
                    });
                }
            });

            // Submissão do formulário
            $('#registerForm').submit(function(e) {
                e.preventDefault(); // Impede a submissão tradicional

                var formData = $(this).serialize(); // Coleta os dados do formulário
                $.post('register.php', formData, function(data) {
                    var response = JSON.parse(data);
                    if (response.error) {
                        $('#registerMessage').text(response.error).removeClass('success').addClass('error').show();
                    } else if (response.success) {
                        $('#registerMessage').text(response.success).removeClass('error').addClass('success').show();
                    }
                });
            });
        });
    </script>
</body>
</html>
