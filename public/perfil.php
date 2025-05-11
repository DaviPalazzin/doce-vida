<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - javvzzy</title>
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="fullscreen-profile">
        <div class="profile-content">
            <!-- Botão Voltar -->
            <button class="back-button" id="back-button">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>

            <!-- Seção da Foto de Perfil -->
            <div class="profile-picture-container">
                <img src="https://i.imgur.com/JqYeS5n.jpg" alt="Foto de perfil" id="profile-picture" class="profile-picture">
                <button class="change-photo-btn" id="change-photo-btn">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div class="profile-header">
                <h1 id="username">javvzzy <i class="fas fa-pencil-alt edit-username"></i></h1>
                <div class="status-badge">DEV</div>
            </div>

            <!-- Apenas 1 email com opção de edição -->
            <div class="contact-info">
                <p id="contact-email">admin@messages.com <i class="fas fa-pencil-alt edit-contact"></i></p>
            </div>

            <div class="bio-section">
                <p class="bio" id="bio-text">oi</p>
                <i class="fas fa-pencil-alt edit-bio"></i>
            </div>

            <div class="promo-section">
                <div class="promo-text">
                    <textarea id="promo-input" readonly>Texto promocional aqui</textarea>
                    <i class="fas fa-pencil-alt edit-promo"></i>
                </div>
            </div>

            <div class="menu-options">
                <div class="section-title">Outras opções</div>
                <button class="menu-btn">
                    <span>Modo Ausente</span>
                </button>
                <button class="menu-btn">
                    <span>Mudar de conta</span>
                </button>
                <button class="menu-btn">
                    <span>Copiar ID</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Editar Nome -->
    <div class="modal" id="username-modal">
        <div class="modal-content">
            <h3>Editar Nome de Usuário</h3>
            <input type="text" id="username-edit-input" maxlength="20" value="javvzzy">
            <div class="modal-buttons">
                <button id="cancel-username">Cancelar</button>
                <button id="save-username">Salvar</button>
            </div>
        </div>
    </div>

    <!-- Modal Editar Biografia -->
    <div class="modal" id="bio-modal">
        <div class="modal-content">
            <h3>Editar Biografia</h3>
            <textarea id="bio-edit-input" maxlength="100">naiiiiiiiiiiiiiiiisssssssssssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii</textarea>
            <div class="modal-buttons">
                <button id="cancel-bio">Cancelar</button>
                <button id="save-bio">Salvar</button>
            </div>
        </div>
    </div>

    <!-- Modal Editar Texto Promocional -->
    <div class="modal" id="promo-modal">
        <div class="modal-content">
            <h3>Editar Texto Promocional</h3>
            <textarea id="promo-edit-input" maxlength="100">Texto promocional aqui</textarea>
            <div class="modal-buttons">
                <button id="cancel-promo">Cancelar</button>
                <button id="save-promo">Salvar</button>
            </div>
        </div>
    </div>

    <!-- Modal para Trocar Foto de Perfil -->
    <div class="modal" id="photo-modal">
        <div class="modal-content">
            <h3>Alterar Foto de Perfil</h3>
            <div class="upload-area" id="upload-area">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <p>Arraste uma imagem ou clique para selecionar</p>
                <input type="file" id="file-input" accept="image/*" style="display: none;">
            </div>
            <div class="image-preview" id="image-preview">
                <img id="preview-img" src="#" alt="Pré-visualização">
            </div>
            <div class="modal-buttons">
                <button id="cancel-photo">Cancelar</button>
                <button id="save-photo">Salvar</button>
            </div>
        </div>
    </div>

    <!-- Modal Editar Email -->
    <div class="modal" id="email-modal">
        <div class="modal-content">
            <h3>Editar Email</h3>
            <input type="email" id="email-edit-input" placeholder="Seu email" value="admin@messages.com">
            <div class="modal-buttons">
                <button id="cancel-email">Cancelar</button>
                <button id="save-email">Salvar</button>
            </div>
        </div>
    </div>

    <script src="perfil.js"></script>
</body>
</html>