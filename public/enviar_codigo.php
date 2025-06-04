<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$email = $_POST['email'] ?? '';

if (!empty($email)) {
    // Gera o código aleatório
    $codigo = rand(100000, 999999);
    $_SESSION['codigo_verificacao'] = $codigo;

    // Conexão com o banco
    $conn = new mysqli("db", "root", "rootpass", "sistema_login");

    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Atualiza o código no banco de dados
    $stmt = $conn->prepare("UPDATE usuarios SET codigo_verificacao=? WHERE email=?");
    $stmt->bind_param("ss", $codigo, $email);
    $stmt->execute();

    // Envio do e-mail
    $assunto = "Seu Código de Verificação - Doce Vida";
    $mensagem = "Olá!\n\nSeu código de verificação é: $codigo\n\nDigite este código na testepágina de verificação.";
    $cabecalhos = "From: suporte@docevida.com.br";

    if (mail($email, $assunto, $mensagem, $cabecalhos)) {
        header("Location: verificar_codigo.php?email=" . urlencode($email));
        exit();
    } else {
        echo "Erro ao enviar o código. Verifique as configurações do servidor de e-mail.";
    }
} else {
    echo "E-mail inválido. Volte e preencha o campo corretamente.";
}
?>
