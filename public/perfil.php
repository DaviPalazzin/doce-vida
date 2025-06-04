<?php
$pageTitle = "Perfil - javvzzy";
$mostrarVoltar = true;
require './partials/header.php';
require './partials/menu.php';
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
                <img src="https://i.imgur.com/JqYeS5n.jpg" alt="Avatar" class="profile-picture" id="profile-picture">
                <button class="change-photo-btn" id="change-photo-btn">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div class="profile-identity">
                <h2 id="username">javvzzy <i class="fas fa-pencil-alt edit-username"></i></h2>
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
<div class="modal" id="username-modal">
    <div class="modal-content">
        <h3>EDITAR NICKNAME</h3>
        <input type="text" id="username-edit-input" maxlength="20" value="javvzzy">
        <div class="modal-buttons">
            <button id="cancel-username">Cancelar</button>
            <button id="save-username">Salvar</button>
        </div>
    </div>
</div>

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
    <div class="modal-content photo-modal-content">
        <div class="modal-header">
            <h3>ALTERAR AVATAR</h3>
            <div class="header-divider"></div>
        </div>

        <div class="image-preview" id="image-preview" style="display: none;">
            <div class="preview-header">
                <i class="fas fa-check-circle preview-icon"></i>
                <span>PRÉ-VISUALIZAÇÃO</span>
            </div>
            <div class="preview-image-container">
                <img id="preview-img" src="#" alt="Pré-visualização">
            </div>
        </div>
        
        <div class="upload-container">
            <div class="upload-area" id="upload-area">
                <div class="upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <p class="upload-text">Clique para selecionar ou arraste uma imagem</p>
                <p class="file-requirements">Formatos suportados: JPG, PNG (Máx. 5MB)</p>
                
                <label class="file-input-label">
                    Selecionar Arquivo
                    <input type="file" id="file-input" accept="image/*">
                </label>
                
                <div class="file-name-display" id="file-name-display"></div>
            </div>
            
            
        </div>
        
        <div class="modal-footer">
            <button class="cancel-btn" id="cancel-photo">Cancelar</button>
            <button class="confirm-btn" id="save-photo">Aplicar Alteração</button>
        </div>
    </div>
</div>

<script src="./perfil.js"></script>

<?php
require './partials/footer.php';
?>