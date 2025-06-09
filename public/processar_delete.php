<?php
session_start();
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $token = $_POST['delete_token'];

    // Verificar token
    $stmt = $conn->prepare("SELECT delete_token, delete_token_expira FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($user['delete_token'] === $token && strtotime($user['delete_token_expira']) > time()) {
            // Token válido - excluir conta
            $deleteStmt = $conn->prepare("DELETE FROM usuarios WHERE email = ?");
            $deleteStmt->bind_param("s", $email);
            
            if ($deleteStmt->execute()) {
                session_destroy();
                header("Location: index.php?account_deleted=1");
                exit();
            } else {
                header("Location: config.php?tab=seguranca&delete_error=delete_failed");
            }
        } else {
            header("Location: config.php?tab=seguranca&delete_error=invalid_token");
        }
    } else {
        header("Location: config.php?tab=seguranca&delete_error=user_not_found");
    }
}
?>