<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$conn = new mysqli("db", "root", "rootpass", "sistema_login");

$username = $_POST['username'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$senha_hash = password_hash($senha, PASSWORD_BCRYPT);
$codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT); // código numérico de 6 dígitos

// Salva no banco com o email_verificado = FALSE
$stmt = $conn->prepare("INSERT INTO usuarios (username, email, senha, codigo_verificacao) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $senha_hash, $codigo);
$stmt->execute();

// Envia o código por e-mail
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'suporteprojetodocevida@gmail.com';
    $mail->Password = 'bxabihpegwyxxpxi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('suporteprojetodocevida@gmail.com', 'Doce Vida');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Confirmação de Cadastro - Doce Vida';
    $mail->Body = "
        <h2>Bem-vindo ao Doce Vida!</h2>
        <p>Use o código abaixo para confirmar seu cadastro:</p>
        <h1>$codigo</h1>
        <p>Este código expira em 15 minutos.</p>
    ";
    $mail->send();

    header("Location: verificar_codigo.php?email=" . urlencode($email));
    exit();
} catch (Exception $e) {
    echo "Erro ao enviar e-mail: " . $mail->ErrorInfo;
}
?>

<?php
include('header.php'); // Inclui o cabeçalho

// Aqui você pode processar os dados do cadastro (banco de dados, validação, etc.)

?>

<div class="flex w-full max-w-4xl">
    <div class="w-1/2 flex flex-col items-center justify-center">
        <div class="text-center">
            <div class="flex items-center justify-center mb-4">
                <img alt="Blue ribbon logo for diabetes awareness" class="h-16 w-30" src="img/fita doce vida.png">
            </div>
            <h1 class="text-3xl font-bold text-blue-900">Doce Vida</h1>
            <p class="text-blue-900 text-sm mt-2">Cadastro realizado com sucesso!</p>
        </div>

        <div class="text-center">
            <p class="text-blue-900 mb-4">Seu cadastro foi concluído com sucesso. Agora você pode fazer login na sua conta.</p>
            <a href="login.php" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">FAZER LOGIN</a>
        </div>
    </div>

    <!-- Lado direito - ilustração -->
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

<?php include('footer.php'); // Inclui o rodapé ?>
