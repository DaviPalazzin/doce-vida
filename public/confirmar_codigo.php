<?php
// Iniciando a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$erro = '';
$mensagem = '';

// Verificação de código após envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $codigo_digitado = trim($_POST['codigo'] ?? '');

    // Conexão com banco
    $conn = new mysqli("db", "root", "rootpass", "sistema_login");

    // Verifica campos
    if (empty($email) || empty($codigo_digitado)) {
        $erro = "❌ Dados incompletos. Tente novamente.";
    } else {
        // Consulta código de verificação
        $stmt = $conn->prepare("SELECT codigo_verificacao FROM usuarios WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($row['codigo_verificacao'] === $codigo_digitado) {
                // Atualiza banco (e-mail verificado) e limpa código
                $stmt = $conn->prepare("UPDATE usuarios SET email_verificado=1, codigo_verificacao=NULL WHERE email=?");
                $stmt->bind_param("s", $email);
                $stmt->execute();

                // Armazena e-mail na sessão
                $_SESSION['email'] = $email;

                // Mostra tela de carregamento e redireciona
                echo '
                <!DOCTYPE html>
                <html lang="pt-BR">
                <head>
                    <meta charset="UTF-8">
                    <title>Redirecionando...</title>
                    <script src="https://cdn.tailwindcss.com"></script>
                    <script>
                        setTimeout(function() {
                            window.location.href = "home.php";
                        }, 3000);
                    </script>
                </head>
                <body class="bg-blue-200 flex flex-col items-center justify-center h-screen text-blue-900">
                    <img src="img/fita doce vida.png" alt="Logo Doce Vida" class="mb-4 h-16">
                    <h1 class="text-2xl font-bold mb-2">Doce Vida</h1>
                    <p class="mb-6 text-lg">✅ Código verificado com sucesso!</p>
                    <div class="flex items-center justify-center space-x-2">
                        <div class="h-6 w-6 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-blue-700">Redirecionando...</span>
                    </div>
                </body>
                </html>';
                exit();
            } else {
                $erro = "❌ Código incorreto. Tente novamente.";
            }
        } else {
            $erro = "❌ Conta não encontrada. Tente novamente.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
