<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        http_response_code(400);
        echo 'email_not_found';
        exit();
    }

    $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $expira = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    $stmt = $conn->prepare("UPDATE usuarios SET delete_token=?, delete_token_expira=? WHERE email=?");
    $stmt->bind_param("sss", $token, $expira, $email);
    $stmt->execute();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'suporteprojetodocevida@gmail.com';
        $mail->Password = 'bxabihpegwyxxpxi';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('suporteprojetodocevida@gmail.com', 'Doce Vida');
        $mail->addAddress($email);

 $mail->isHTML(true);
        $mail->Subject = 'Confirmação de Exclusão de Conta - Doce Vida';
$mail->Body = "
<div style='max-width: 600px; margin: auto; font-family: Arial, sans-serif; color: #333; background-color: #fef2f2; padding: 20px; border-radius: 10px;'>
  <div style='text-align: center;'>
    <h1 style='color: #dc2626; font-size: 28px;'>⚠️ Confirmação de Exclusão de Conta</h1>
    <p style='font-size: 16px;'>Recebemos uma solicitação para excluir sua conta no <strong>Doce Vida</strong>.</p>
  </div>
  <div style='background-color: white; padding: 30px; margin: 20px 0; border-radius: 8px; text-align: center; box-shadow: 0 0 10px rgba(0,0,0,0.05);'>
    <p style='font-size: 16px; margin-bottom: 10px;'>Use o código abaixo para confirmar a exclusão da sua conta:</p>
    <div style='font-size: 24px; color: #dc2626; font-weight: bold; letter-spacing: 2px; margin: 20px 0;'>$token</div>
    <p style='font-size: 14px; color: #888;'>Este código expira em <strong>15 minutos</strong>.</p>
    <p style='font-size: 14px; color: #dc2626; font-weight: bold;'>⚠ Esta ação é irreversível!</p>
  </div>
  <p style='font-size: 14px; color: #666;'>Se você não solicitou esta exclusão, recomendamos que altere sua senha imediatamente.</p>
  <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
  <div style='text-align: center; font-size: 12px; color: #aaa;'>
    &copy; " . date('Y') . " Doce Vida • Todos os direitos reservados.
  </div>
</div>
";

        $mail->send();

        echo 'ok';
    } catch (Exception $e) {
        http_response_code(500);
        echo 'email_failed';
    }
}
?>
