<?php
session_start();
$pageTitle = "Perfil - javvzzy";
$mostrarVoltar = true;
require './partials/header.php';
require './partials/menu.php';

// Busca os dados do usuário
require 'conexao.php';
$username = "Usuário"; // Valor padrão

if (isset($_SESSION['email'])) {
    $stmt = $conn->prepare("SELECT username FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $username = $user_data['username'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto_perfil'])) {
    $pasta_upload = "uploads/perfil/";
    $extensao = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
    $nome_arquivo = "user_" . $_SESSION['user_id'] . "_" . uniqid() . "." . $extensao;
    $caminho_final = $pasta_upload . $nome_arquivo;

    // Validações
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['foto_perfil']['type'], $allowed_types)) {
        die("Apenas imagens JPG, PNG ou GIF são permitidas!");
    }

    if ($_FILES['foto_perfil']['size'] > 5 * 1024 * 1024) {
        die("O arquivo deve ter menos de 5MB!");
    }

    if (!file_exists($pasta_upload)) {
        mkdir($pasta_upload, 0777, true);
    }

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_final)) {
        // Atualiza no banco de dados
        $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
        $stmt->bind_param("si", $caminho_final, $_SESSION['user_id']);
        $stmt->execute();
        
        // Atualiza na sessão
        $_SESSION['foto_perfil'] = $caminho_final;

        echo json_encode([
            'success' => true,
            'foto' => $caminho_final,
            'message' => 'Foto atualizada com sucesso!'
        ]);
        exit;
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao salvar a foto.'
        ]);
        exit;
    }
}
?>

<link rel="stylesheet" href="css/perfil.css">
<div class="fullscreen-profile">
    <div class="profile-header-container">
        <h1 class="profile-title">MEU PERFIL</h1>
    </div>

    <div class="profile-content">
        <!-- Seção do Avatar -->
        <div class="avatar-section">
            <div class="profile-picture-container">
                <img src="img/perfil.jpg" alt="Avatar" class="profile-picture" id="profile-picture">
                <button class="change-photo-btn" id="change-photo-btn">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div class="profile-identity">
                <h2 id="username"><?= htmlspecialchars($username) ?> <span class="text-sm text-gray-500 ml-2"><i class="fas fa-lock"></i></span></h2>
            </div>
        </div>

        <!-- Seção de Informações -->
        <div class="player-info-section">
            <div class="info-card bio-card">
                <h3><i class="fas fa-book-open"></i> BIO</h3>
                <p id="bio-text">Oi! Estou aprendendo sobre prevenção da diabetes! <i class="fas fa-pencil-alt edit-bio"></i></p>
            </div>
        </div>

        <!-- Seção de Conquistas -->
        <div class="achievements-section">
            <h3><i class="fas fa-trophy"></i> CONQUISTAS</h3>
            <div class="achievements-grid">
                <div class="achievement unlocked">
                    <div class="achievement-icon" style="background-color: #4CAF50;">
                        <i class="fas fa-apple-alt"></i>
                    </div>
                    <div class="achievement-info">
                        <h4>Alimentação Saudável</h4>
                        <p>Completou 10 lições sobre nutrição</p>
                    </div>
                </div>
                <div class="achievement unlocked">
                    <div class="achievement-icon" style="background-color: #2196F3;">
                        <i class="fas fa-running"></i>
                    </div>
                    <div class="achievement-info">
                        <h4>Ativo</h4>
                        <p>30 dias de exercícios registrados</p>
                    </div>
                </div>
                <div class="achievement locked">
                    <div class="achievement-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="achievement-info">
                        <h4>Pesquisador</h4>
                        <p>Desbloqueie completando 50 lições</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu de Ações -->
        <div class="actions-menu">
            <button class="action-btn">
                <i class="fas fa-gamepad"></i> Meus Jogos
            </button>
            <button class="action-btn">
                <i class="fas fa-cog"></i> Configurações
            </button>
        </div>
    </div>
</div>

<!-- Modais -->
<div class="modal" id="bio-modal">
    <div class="modal-content">
        <h3>EDITAR BIO</h3>
        <textarea id="bio-edit-input" maxlength="100">Oi! Estou aprendendo sobre prevenção da diabetes!</textarea>
        <div class="modal-buttons">
            <button id="cancel-bio">Cancelar</button>
            <button id="save-bio">Salvar</button>
        </div>
    </div>
</div>

<div class="modal" id="photo-modal">
  <div class="modal-compact-content">
    <h3>ALTERAR FOTO</h3>
    
    <div class="compact-upload-area" id="upload-area">
      <div class="upload-icon-sm">
        <i class="fas fa-camera"></i>
      </div>
      <label class="compact-file-input">
        <input type="file" id="file-input" accept="image/*">
        <span>Selecionar Imagem</span>
      </label>
      <p class="file-info-sm">JPG ou PNG (até 5MB)</p>
    </div>
    
    <div class="compact-preview" id="image-preview" style="display:none">
      <div class="preview-circle-sm">
        <img id="preview-img" src="#" alt="Pré-visualização">
      </div>
    </div>
    
    <div class="compact-modal-footer">
      <button class="btn-sm cancel" id="cancel-photo">Cancelar</button>
      <button class="btn-sm confirm" id="save-photo">Salvar</button>
    </div>
  </div>
</div>

<script src="./perfil.js"></script>

<?php
require './partials/footer.php';
?>