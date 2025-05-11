<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

session_start();
include('conexao.php'); // Conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Protege contra SQL Injection
    $senha = $_POST['senha'];

    // Consulta o banco de dados para buscar o usuário e o cap associado a ele
    $query_user = "SELECT * FROM usuarios WHERE username = '$username'";
    $result_user = mysqli_query($conn, $query_user);
    $user = mysqli_fetch_assoc($result_user);

    // Verifica se o usuário existe
    if ($user) {
        // Verifica se a senha está correta
        if (password_verify($senha, $user['senha'])) {
                // Se tudo estiver correto, inicia a sessão do usuário
                $_SESSION['username'] = $username;
                header("Location: home.php");
                exit();
            } 
        } else {
            // Senha incorreta
            echo "<script>alert('Usuário ou senha incorretos!'); window.location.href = 'index.php';</script>";
        }
    } else {
        // Usuário não encontrado
        echo "<script>alert('Usuário não encontrado!'); window.location.href = 'index.php';</script>";
    }

?>

<?php
// login.php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $senha = $_POST['senha'];


    // Verificação do banco de dados (usuário e senha)
    include 'conexao.php';
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            // Login bem-sucedido
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
}
?>

