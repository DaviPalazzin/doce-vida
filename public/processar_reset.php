<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se as variáveis estão definidas no POST
    if (isset($_POST['email'], $_POST['token'], $_POST['nova_senha'])) {
        // Recebe os dados do formulário
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $token = mysqli_real_escape_string($conn, $_POST['token']);
        $nova_senha = $_POST['nova_senha'];
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Verifica se o token de redefinição existe no banco de dados para o e-mail fornecido
        $query = "SELECT * FROM usuarios WHERE email = '$email' AND reset_token = '$token' AND reset_token_expira > NOW()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Atualiza a senha no banco de dados
            $query_update = "UPDATE usuarios SET senha = '$nova_senha_hash', reset_token = NULL, reset_token_expira = NULL WHERE email = '$email'";
            if (mysqli_query($conn, $query_update)) {
                // Se tudo deu certo, redireciona para o login com sucesso
                header("Location: index.php?sucesso=1");
                exit();
            } else {
                echo "Erro ao atualizar a senha.";
            }
        } else {
            echo "Código de redefinição inválido ou expirado.";
        }
    } else {
        echo "Dados inválidos ou ausentes.";
    }
}
?>
