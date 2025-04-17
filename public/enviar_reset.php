<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("db", "root", "rootpass", "sistema_login");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(4)); // Exemplo de token aleatório
    $expira = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    // Salva no banco de dados
    $stmt = $conn->prepare("UPDATE usuarios SET reset_token=?, reset_token_expira=? WHERE email=?");
    $stmt->bind_param("sss", $token, $expira, $email);
    $stmt->execute();

    // Envia o e-mail
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'davipalazzin14@gmail.com';
        $mail->Password = 'oeygqshaumcqnabj'; // Use a senha de aplicativo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('davipalazzin14@gmail.com', 'Doce Vida');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Redefinição de Senha - Doce Vida';
        $mail->Body = "
            <h2 style='color:#2563EB;'>Redefinição de Senha</h2>
            <p>Você solicitou a redefinição da sua senha. Use o código abaixo:</p>
            <h3 style='color:#2563EB;'>$token</h3>
            <p>Este código expira em 15 minutos.</p>
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

      <form method="POST" action="enviar_reset.php" class="mt-8 w-3/4">
        <input name="email" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="E-mail" type="email" required>
        <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">Enviar Código</button>
      </form>
    </div>
  </div>
</body>
</html>
