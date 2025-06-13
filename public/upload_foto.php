<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Usuário não logado']));
}

// Verifica se o arquivo foi enviado
if (!isset($_FILES['foto_perfil'])) {
    die(json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado']));
}

// Configurações
$user_id = $_SESSION['user_id'];
$pasta_upload = "uploads/perfil/";
$extensoes_permitidas = ['jpg', 'jpeg', 'png'];
$tamanho_maximo = 5 * 1024 * 1024; // 5MB

// Cria a pasta se não existir
if (!file_exists($pasta_upload)) {
    mkdir($pasta_upload, 0777, true);
}

// Obtém informações do arquivo
$arquivo = $_FILES['foto_perfil'];
$extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

// Validações
if (!in_array($extensao, $extensoes_permitidas)) {
    die(json_encode(['success' => false, 'message' => 'Apenas JPG, JPEG e PNG são permitidos']));
}

if ($arquivo['size'] > $tamanho_maximo) {
    die(json_encode(['success' => false, 'message' => 'Arquivo muito grande (máx. 5MB)']));
}

// Remove a foto antiga (se não for a padrão)
$stmt = $conn->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$foto_antiga = $result->fetch_assoc()['foto_perfil'];

if ($foto_antiga && $foto_antiga != 'img/perfil.jpg' && file_exists($foto_antiga)) {
    unlink($foto_antiga);
}

// Gera novo nome para o arquivo
$nome_arquivo = "user_" . $user_id . "_" . time() . "." . $extensao;
$caminho_final = $pasta_upload . $nome_arquivo;

// Move o arquivo
if (!move_uploaded_file($arquivo['tmp_name'], $caminho_final)) {
    die(json_encode(['success' => false, 'message' => 'Erro ao salvar o arquivo']));
}

// Atualiza o banco de dados
$stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
$stmt->bind_param("si", $caminho_final, $user_id);

if (!$stmt->execute()) {
    unlink($caminho_final); // Remove a foto se der erro no banco
    die(json_encode(['success' => false, 'message' => 'Erro ao atualizar o banco de dados']));
}

// Atualiza a sessão
$_SESSION['foto_perfil'] = $caminho_final;

// Retorna sucesso
echo json_encode([
    'success' => true,
    'foto' => $caminho_final,
    'message' => 'Foto atualizada com sucesso!'
]);
?>