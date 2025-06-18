<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['email'])) {
    header("Location: home.php");
    exit();
}

$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('conexao.php');

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = $_POST['senha'];

    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['email'] = $user['email'];
            
            // Função para obter informações do cliente
            function getClientInfo() {
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $ip_address = $_SERVER['REMOTE_ADDR'];
                
                // Detectar dispositivo e SO
                $dispositivo = 'Computador';
                $so = 'Desconhecido';
                
                if (strpos($user_agent, 'Mobile') !== false) {
                    $dispositivo = 'Mobile';
                } elseif (strpos($user_agent, 'Tablet') !== false) {
                    $dispositivo = 'Tablet';
                }
                
                if (strpos($user_agent, 'Windows') !== false) {
                    $so = 'Windows';
                } elseif (strpos($user_agent, 'Mac') !== false) {
                    $so = 'MacOS';
                } elseif (strpos($user_agent, 'Linux') !== false) {
                    $so = 'Linux';
                } elseif (strpos($user_agent, 'Android') !== false) {
                    $so = 'Android';
                } elseif (strpos($user_agent, 'iOS') !== false) {
                    $so = 'iOS';
                }
                
                // Detectar navegador
                $navegador = 'Desconhecido';
                if (strpos($user_agent, 'Chrome') !== false) {
                    $navegador = 'Chrome';
                } elseif (strpos($user_agent, 'Firefox') !== false) {
                    $navegador = 'Firefox';
                } elseif (strpos($user_agent, 'Safari') !== false) {
                    $navegador = 'Safari';
                } elseif (strpos($user_agent, 'Edge') !== false) {
                    $navegador = 'Edge';
                } elseif (strpos($user_agent, 'Opera') !== false) {
                    $navegador = 'Opera';
                }
                
                // Obter localização (simplificado - na prática use uma API como ipstack)
                $pais = 'Brasil'; // Substitua por chamada de API real
                $estado = 'São Paulo'; // Substitua por chamada de API real
                $cidade = 'São Paulo'; // Substitua por chamada de API real
                
                return [
                    'user_agent' => $user_agent,
                    'ip_address' => $ip_address,
                    'dispositivo' => $dispositivo,
                    'sistema_operacional' => $so,
                    'navegador' => $navegador,
                    'pais' => $pais,
                    'estado' => $estado,
                    'cidade' => $cidade
                ];
            }

            // Gerar token de sessão
            $token_sessao = bin2hex(random_bytes(32));

            // Registrar a sessão no banco de dados
            $client_info = getClientInfo();
            $query = "INSERT INTO sessoes (usuario_id, token_sessao, user_agent, ip_address, pais, estado, cidade, dispositivo, sistema_operacional, navegador, esta_atual) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "isssssssss", $user['id'], $token_sessao, $client_info['user_agent'], $client_info['ip_address'], 
                $client_info['pais'], $client_info['estado'], $client_info['cidade'], $client_info['dispositivo'], 
                $client_info['sistema_operacional'], $client_info['navegador']);
            mysqli_stmt_execute($stmt);

            // Definir cookie de sessão
            setcookie('session_token', $token_sessao, time() + 60 * 60 * 24 * 30, '/', '', true, true); // 30 dias

            header("Location: home.php");
            exit();
        } else { 
            $erro = "Senha incorreta!";
        }
    } else {
      $erro = 'Usuário não encontrado! <br>Caso não tenha conta, <a href="register.php" class="text-inherit no-underline hover:no-underline">clique aqui!</a>.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Doce Vida - Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .feedback-card {
      animation: fadeIn 0.5s ease-out;
      transform-origin: top;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .pulse {
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
  </style>
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
        <p class="text-blue-900 text-sm mt-2">Sua jornada na prevenção à diabetes</p>
      </div>

      <form method="POST" action="index.php" class="mt-8 w-3/4">
        <input name="email" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="E-mail" type="email" required>
        <input name="senha" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Senha" type="password" required>

        <?php if (!empty($erro)): ?>
          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
              </svg>
              <p><?= $erro ?></p>
            </div>
          </div>
        <?php endif; ?>

        <?php if (isset($_GET['account_deleted'])): ?>
          <div class="feedback-card bg-white p-6 rounded-lg shadow-lg mb-6 border-l-4 border-green-500 pulse">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-lg font-medium text-green-800">Conta Excluída com Sucesso</h3>
                <div class="mt-2 text-sm text-green-700">
                  <p>Sua conta foi permanentemente removida do nosso sistema.</p>
                  <p class="mt-2">Obrigado por ter feito parte da comunidade Doce Vida. Sentiremos sua falta!</p>
                  <p class="mt-2 text-xs text-gray-500">Código de confirmação: #<?= bin2hex(random_bytes(3)) ?></p>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500 transition duration-300 transform hover:scale-105">LOGIN</button>

        <a href="register.php" class="text-blue-900 text-sm underline block text-center mt-4 hover:text-blue-700 transition">Cadastrar</a>
        <a href="resetar_senha.php" class="text-blue-900 text-sm underline block text-center mb-4 hover:text-blue-700 transition">Esqueceu a senha?</a>
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
    </div>
  </div>
</body>
</html>