<?php
session_start();
require 'conexao.php';

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $foto = $stmt->fetchColumn();
    
    echo json_encode(['foto' => $foto ?: 'img/perfil.jpg']);
    exit;
}

echo json_encode(['foto' => 'img/perfil.jpg']);
?>