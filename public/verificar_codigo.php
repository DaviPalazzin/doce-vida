<?php
// Iniciando a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$erro = '';
$mensagem = '';

// Verificação de código
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $codigo_digitado = trim($_POST['codigo'] ?? '');

    // Conexão com banco
    $conn = new mysqli("db", "root", "rootpass", "sistema_login");

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

                $_SESSION['email'] = $email;

                $mensagem = "✅ Código verificado com sucesso! Redirecionando...";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'home.php';
                        }, 3000);
                      </script>";
            } else {
                $erro = "❌ Código incorreto. Tente novamente.";
            }
        } else {
            $erro = "❌ Conta não encontrada. Tente novamente.";
        }

        $stmt->close();
        $conn->close();
    }
}

// Cancelar a criação de conta
if (isset($_GET['cancelar'])) {
    // Excluir tentativa de criação
    $email_cancelado = $_GET['email_cancelado'];
    $conn = new mysqli("db", "root", "rootpass", "sistema_login");

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email_cancelado);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    // Redirecionar para a página inicial
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Doce Vida - Verificar Código</title>
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
                <h1 class="text-3xl font-bold text-blue-900">Doce Vida</h1>
                <p class="text-blue-900 text-sm mt-2">Verifique seu código de verificação</p>
            </div>

            <form method="POST" class="mt-8 w-3/4">
                <input name="codigo" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Código de Verificação" type="text" required>
                <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">

                <?php if (!empty($erro)): ?>
                    <p class="text-red-600 text-sm mb-4 text-center"><?= $erro ?></p>
                <?php endif; ?>

                <?php if (!empty($mensagem)): ?>
                    <p class="text-green-600 text-sm mb-4 text-center"><?= $mensagem ?></p>
                <?php endif; ?>

                <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">VERIFICAR</button>
            </form>

            <form method="GET" action="verificar_codigo.php" class="mt-4 w-3/4">
                <input type="hidden" name="email_cancelado" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                <button type="submit" name="cancelar" class="w-full bg-gray-300 text-blue-900 py-2 rounded-full hover:bg-gray-400">CANCELAR</button>
            </form>
        </div>

        <!-- Lado direito - Ilustração -->
        <div class="w-1/2 flex items-center justify-center">
            <div class="relative">
                <div class="bg-blue-300 rounded-full h-64 w-64 flex items-center justify-center">
                    <div class="bg-blue-400 rounded-full h-48 w-48 flex items-center justify-center">
                        <img alt="Hand holding a glucometer icon" class="h-128 w-64" src="img/login doce vida.png">
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
