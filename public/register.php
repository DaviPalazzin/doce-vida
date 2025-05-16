<?php
use PHPMailer\PHPMailer\PHPMailer;
require __DIR__ . '/../vendor/autoload.php';

$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('conexao.php');

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $senha = $_POST['senha'];
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Verificar se o e-mail já está cadastrado
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $erro = "E-mail já cadastrado!";
    } else {
        // Gerar código de verificação (numérico de 6 dígitos)
        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Inserir usuário com e-mail não verificado
        $query = "INSERT INTO usuarios (email, username, senha, email_verificado, codigo_verificacao) VALUES ('$email', '$username', '$senha_hash', 0, '$codigo')";
        if (mysqli_query($conn, $query)) {

            // Enviar o e-mail com o código
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'suporteprojetodocevida@gmail.com';
                $mail->Password = 'bxabihpegwyxxpxi'; // Use senha de app aqui
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('suporteprojetodocevida@gmail.com', 'Doce Vida');
                $mail->addAddress($email);
                $mail->isHTML(true);

                  // 🔧 Define o charset correto
        $mail->CharSet = 'UTF-8';

                $mail->Subject = 'Confirmação de Cadastro - Doce Vida';
                $mail->Body = "
        <div style='max-width: 600px; margin: auto; font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; padding: 20px; border-radius: 10px;'>
          <div style='text-align: center;'>
            <h1 style='color: #2563EB; font-size: 28px;'>✅ Verificação de Conta</h1>
            <p style='font-size: 16px;'>Obrigado por criar uma conta no <strong>Doce Vida</strong>!</p>
          </div>
          <div style='background-color: white; padding: 30px; margin: 20px 0; border-radius: 8px; text-align: center; box-shadow: 0 0 10px rgba(0,0,0,0.05);'>
            <p style='font-size: 16px; margin-bottom: 10px;'>Use o código abaixo para verificar sua conta:</p>
            <div style='font-size: 24px; color: #2563EB; font-weight: bold; letter-spacing: 2px; margin: 20px 0;'>$codigo</div>
            <p style='font-size: 14px; color: #888;'>Este código expira em <strong>15 minutos</strong>.</p>
          </div>
          <div style='background-color: #fff8e1; padding: 15px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #ffc107;'>
            <p style='font-size: 14px; color: #666; margin: 0;'>
              <strong>Importante:</strong> Se você não solicitou a criação desta conta, por favor entre em contato imediatamente com nosso <a href='mailto:suporteprojetodocevida@gmail.com' style='color: #2563EB;'>suporte</a> ou ignore este e-mail.
            </p>
          </div>
          <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
          <div style='text-align: center; font-size: 12px; color: #aaa;'>
            &copy; " . date('Y') . " Doce Vida • Todos os direitos reservados.
          </div>
        </div>
        ";

                $mail->send();

                header("Location: verificar_codigo.php?email=" . urlencode($email));
                exit();

            } catch (Exception $e) {
                $erro = "Erro ao enviar e-mail: " . $mail->ErrorInfo;
            }

        } else {
            $erro = "Erro ao cadastrar usuário!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Doce Vida - Cadastro</title>
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
        <h1 class="text-3xl font-bold text-blue-900">Doce Vida - Cadastro</h1>
        <p class="text-blue-900 text-sm mt-2">Crie sua conta para iniciar sua jornada</p>
      </div>

      <form method="POST" action="register.php" class="mt-8 w-3/4">
        <input name="email" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" type="email" placeholder="E-mail" required>
       <input name="username" class="w-full px-4 py-2 mb-2 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Nome completo" type="text" required>
<p class="text-xs text-blue-800 mb-4 text-center">⚠️ O nome completo não poderá ser alterado depois do cadastro.</p>

        <input name="senha" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Senha" type="password" required>

        <?php if (!empty($erro)): ?>
          <p class="text-red-600 text-sm mb-4 text-center"><?= $erro ?></p>
        <?php endif; ?>

        <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">CADASTRAR</button>

        <a href="index.php" class="text-blue-900 text-sm underline block text-center mt-4">Já tem conta? Faça login</a>
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
