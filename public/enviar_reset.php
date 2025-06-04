<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("db", "root", "rootpass", "sistema_login");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verificar se o e-mail existe no banco de dados
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Se o e-mail não estiver registrado
    if ($result->num_rows == 0) {
        // Redirecionar para a página de redefinir senha com um parâmetro de erro
        header("Location: resetar_senha.php?erro=1");
        exit();
    }

    $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
 // Exemplo de token aleatório
    $expira = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    // Salva o token e a data de expiração no banco de dados
    $stmt = $conn->prepare("UPDATE usuarios SET reset_token=?, reset_token_expira=? WHERE email=?");
    $stmt->bind_param("sss", $token, $expira, $email);
    $stmt->execute();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'suporteprojetodocevida@gmail.com';
        $mail->Password = 'bxabihpegwyxxpxi'; // senha de app
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
    
        // 🔧 Define o charset correto
        $mail->CharSet = 'UTF-8';
    
        $mail->setFrom('suporteprojetodocevida@gmail.com', 'Doce Vida');
        $mail->addAddress($email);
    
        $mail->isHTML(true);
        $mail->Subject = 'Redefinição de Senha - Doce Vida';
        $mail->Body = "
        <div style='max-width: 600px; margin: auto; font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; padding: 20px; border-radius: 10px;'>
          <div style='text-align: center;'>
            <h1 style='color: #2563EB; font-size: 28px;'>🔒 Redefinição de Senha</h1>
            <p style='font-size: 16px;'>Recebemos uma solicitação para redefinir sua senha no <strong>Doce Vida</strong>.</p>
          </div>
          <div style='background-color: white; padding: 30px; margin: 20px 0; border-radius: 8px; text-align: center; box-shadow: 0 0 10px rgba(0,0,0,0.05);'>
            <p style='font-size: 16px; margin-bottom: 10px;'>Use o código abaixo para redefinir sua senha:</p>
            <div style='font-size: 24px; color: #2563EB; font-weight: bold; letter-spacing: 2px; margin: 20px 0;'>$token</div>
            <p style='font-size: 14px; color: #888;'>Este código expira em <strong>15 minutos</strong>.</p>
          </div>
          <p style='font-size: 14px; color: #666;'>Se você não solicitou essa redefinição, pode ignorar este e-mail com segurança.</p>
          <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
          <div style='text-align: center; font-size: 12px; color: #aaa;'>
            &copy; " . date('Y') . " Doce Vida • Todos os direitos reservados.
          </div>
        </div>
      ";
      

        $mail->send();
        header("Location: resetar_senha.php?sucesso=1&email=" . urlencode($email));
    } catch (Exception $e) {
        echo "Erro ao enviar o email: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Doce Vida - Redefinir Senha</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-200 h-screen flex items-center justify-center">
  <div class="flex w-full max-w-4xl">
    <!-- Lado Esquerdo -->
    <div class="w-1/2 flex flex-col items-center justify-center">
      <div class="text-center">
        <h1 class="text-3xl font-bold text-blue-900">Redefinir Senha</h1>
        <p class="text-blue-900 text-sm mt-2">Digite seu e-mail para receber o código de redefinição de senha.</p>
      </div>

      <!-- Exibição de erro se o e-mail não estiver cadastrado -->
      <?php if (isset($_GET['erro'])): ?>
        <p class="text-red-600 text-sm mt-2">E-mail não cadastrado. Tente novamente com um e-mail válido.</p>
      <?php endif; ?>

      <form method="POST" action="enviar_reset.php" class="mt-8 w-3/4">
        <input name="email" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="E-mail" type="email" required>
        <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">Enviar Código</button>
      </form>
    </div>
  </div>
</body>
</html>
