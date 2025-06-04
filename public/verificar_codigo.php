<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$erro = '';
$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $codigo_digitado = trim($_POST['codigo'] ?? '');

    $conn = new mysqli("db", "root", "rootpass", "sistema_login");

    if (empty($email) || empty($codigo_digitado)) {
        $erro = "❌ Dados incompletos. Tente novamente.";
    } else {
        $stmt = $conn->prepare("SELECT codigo_verificacao FROM usuarios WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($row['codigo_verificacao'] === $codigo_digitado) {
                $stmt = $conn->prepare("UPDATE usuarios SET email_verificado=1, codigo_verificacao=NULL WHERE email=?");
                $stmt->bind_param("s", $email);
                $stmt->execute();

                $_SESSION['email'] = $email;
                $mensagem = "✅ Código verificado com sucesso! Redirecionando...";

                // Mostrar tela de carregamento premium com progresso real
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('conteudo-principal').style.display = 'none';
                        const loadingScreen = document.getElementById('tela-carregamento');
                        const progressBar = document.getElementById('progress-bar');
                        
                        loadingScreen.classList.remove('hidden');
                        
                        // Inicia o acompanhamento de progresso
                        let progress = 0;
                        const interval = setInterval(() => {
                            // Atualiza a barra de progresso
                            progress += 5 + Math.random() * 10;
                            if (progress > 100) progress = 100;
                            progressBar.style.width = progress + '%';
                            
                            // Quando chegar a 100%, redireciona
                            if (progress >= 100) {
                                clearInterval(interval);
                                window.location.href = 'home.php';
                            }
                        }, 300);
                        
                        // Verifica o carregamento real (opcional)
                        window.addEventListener('load', function() {
                            progress = 90; // Salta para 90% quando a página estiver carregada
                        });
                    });
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

if (isset($_GET['cancelar'])) {
    $email_cancelado = $_GET['email_cancelado'];
    $conn = new mysqli("db", "root", "rootpass", "sistema_login");

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email_cancelado);
    $stmt->execute();

    $stmt->close();
    $conn->close();

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
    <link rel="stylesheet" href="./css/tela_carregamento.css">
</head>
<body class="bg-blue-200 h-screen flex items-center justify-center">
    <!-- TELA DE CARREGAMENTO PREMIUM (hidden por padrão) -->
    <div id="tela-carregamento" class="fixed inset-0 bg-gradient-to-br from-blue-50 to-blue-100 flex flex-col items-center justify-center z-50 space-y-6 hidden">
        <!-- Logo animado - Ondas concêntricas -->
        <div class="relative">
            <!-- Círculo central -->
            <div class="absolute inset-0 m-auto rounded-full h-20 w-20 bg-blue-600 flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </div>
            
            <!-- Anéis animados -->
            <div class="relative h-32 w-32">
                <div class="absolute inset-0 border-4 border-blue-400 rounded-full animate-ripple-1 opacity-0"></div>
                <div class="absolute inset-0 border-4 border-blue-500 rounded-full animate-ripple-2 opacity-0"></div>
                <div class="absolute inset-0 border-4 border-blue-600 rounded-full animate-ripple-3 opacity-0"></div>
            </div>
        </div>

        <!-- Texto com efeito de digitação -->
        <div class="text-center px-6">
            <h2 class="text-2xl font-bold text-blue-900 mb-2">Bem-vindo a Doce Vida!</h2>
            <p class="text-blue-700 font-medium relative inline-block">
                <span id="typing-text" class="relative after:absolute after:right-0 after:top-0 after:h-full after:w-1 after:bg-blue-600 after:animate-blink"></span>
            </p>
        </div>

        <!-- Barra de progresso real -->
        <div class="w-64 h-2 bg-blue-200 rounded-full overflow-hidden">
            <div id="progress-bar" class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full progress-bar" style="width: 0%"></div>
        </div>
    </div>

    <div id="conteudo-principal" class="flex w-full max-w-4xl">
        <!-- LADO ESQUERDO -->
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

            <?php if (empty($mensagem)): ?>
                <form method="GET" action="verificar_codigo.php" class="mt-4 w-3/4">
                    <input type="hidden" name="email_cancelado" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                    <button type="submit" name="cancelar" class="w-full bg-gray-300 text-blue-900 py-2 rounded-full hover:bg-gray-400">CANCELAR</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- LADO DIREITO -->
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