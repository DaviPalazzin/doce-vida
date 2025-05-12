document.addEventListener('DOMContentLoaded', function() {
    // Elementos principais
    const backButton = document.getElementById('back-button');
    const changePhotoBtn = document.getElementById('change-photo-btn');
    const editUsernameBtn = document.querySelector('.edit-username');
    const editBioBtn = document.querySelector('.edit-bio');
    const healthBtn = document.getElementById('health-btn');
    
    // Modais
    const modals = {
        username: document.getElementById('username-modal'),
        bio: document.getElementById('bio-modal'),
        photo: document.getElementById('photo-modal'),
        health: document.getElementById('health-modal')
    };
    
    // Elementos de formulário
    const usernameInput = document.getElementById('username-edit-input');
    const bioInput = document.getElementById('bio-edit-input');
    const fileInput = document.getElementById('file-input');
    const previewImg = document.getElementById('preview-img');
    const imagePreview = document.getElementById('image-preview');
    const profilePicture = document.getElementById('profile-picture');
    
    // Botões de salvar
    const saveUsernameBtn = document.getElementById('save-username');
    const saveBioBtn = document.getElementById('save-bio');
    const savePhotoBtn = document.getElementById('save-photo');
    
    // Botões de cancelar
    const cancelButtons = {
        username: document.getElementById('cancel-username'),
        bio: document.getElementById('cancel-bio'),
        photo: document.getElementById('cancel-photo'),
        health: document.getElementById('close-health')
    };
    
    // Avatar options
    const avatarOptions = document.querySelectorAll('.avatar-option');
    
    // Event Listeners
    backButton.addEventListener('click', () => {
        window.history.back();
    });
    
    // Abrir modais
    changePhotoBtn.addEventListener('click', () => {
        modals.photo.style.display = 'flex';
    });
    
    editUsernameBtn.addEventListener('click', () => {
        usernameInput.value = document.getElementById('username').textContent.replace(' javvzzy', '').trim();
        modals.username.style.display = 'flex';
    });
    
    editBioBtn.addEventListener('click', () => {
        bioInput.value = document.getElementById('bio-text').textContent.trim();
        modals.bio.style.display = 'flex';
    });
    
    healthBtn.addEventListener('click', () => {
        modals.health.style.display = 'flex';
    });
    
    // Selecionar avatar
    avatarOptions.forEach(option => {
        option.addEventListener('click', function() {
            avatarOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            imagePreview.style.display = 'none';
        });
    });
    
    // Upload de imagem
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                imagePreview.style.display = 'block';
                avatarOptions.forEach(opt => opt.classList.remove('selected'));
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Salvar alterações
    saveUsernameBtn.addEventListener('click', function() {
        const usernameElement = document.getElementById('username');
        usernameElement.textContent = usernameInput.value + ' ';
        
        // Recria o ícone de edição
        const editIcon = document.createElement('i');
        editIcon.className = 'fas fa-pencil-alt edit-username';
        usernameElement.appendChild(editIcon);
        
        // Reatacha o event listener
        editIcon.addEventListener('click', () => {
            usernameInput.value = usernameElement.textContent.replace(' javvzzy', '').trim();
            modals.username.style.display = 'flex';
        });
        
        modals.username.style.display = 'none';
    });
    
    saveBioBtn.addEventListener('click', function() {
        const bioTextElement = document.getElementById('bio-text');
        bioTextElement.textContent = bioInput.value + ' ';
        
        // Recria o ícone de edição
        const editIcon = document.createElement('i');
        editIcon.className = 'fas fa-pencil-alt edit-bio';
        bioTextElement.appendChild(editIcon);
        
        // Reatacha o event listener
        editIcon.addEventListener('click', () => {
            bioInput.value = bioTextElement.textContent.trim();
            modals.bio.style.display = 'flex';
        });
        
        modals.bio.style.display = 'none';
    });
    
    savePhotoBtn.addEventListener('click', function() {
        // Verifica se tem uma imagem enviada
        if (previewImg.src && previewImg.src !== '#') {
            profilePicture.src = previewImg.src;
        } 
        // Se não, verifica se tem um avatar selecionado
        else {
            const selectedAvatar = document.querySelector('.avatar-option.selected img');
            if (selectedAvatar) {
                profilePicture.src = selectedAvatar.src;
            }
        }
        
        modals.photo.style.display = 'none';
        fileInput.value = '';
        imagePreview.style.display = 'none';
    });
    
    // Fechar modais
    Object.keys(cancelButtons).forEach(key => {
        if (cancelButtons[key]) {
            cancelButtons[key].addEventListener('click', () => {
                modals[key].style.display = 'none';
                
                // Resetar o upload de foto ao cancelar
                if (key === 'photo') {
                    fileInput.value = '';
                    imagePreview.style.display = 'none';
                }
            });
        }
    });
    
    // Fechar ao clicar fora
    window.addEventListener('click', function(event) {
        Object.values(modals).forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
                
                // Resetar o upload de foto ao clicar fora
                if (modal.id === 'photo-modal') {
                    fileInput.value = '';
                    imagePreview.style.display = 'none';
                }
            }
        });
    });
    
    // Efeitos visuais
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Animar progresso
    setTimeout(() => {
        const xpFill = document.getElementById('xp-fill');
        if (xpFill) {
            xpFill.style.width = '65%';
        }
    }, 500);
    
    // Animar conquistas
    const achievements = document.querySelectorAll('.achievement');
    achievements.forEach((achievement, index) => {
        setTimeout(() => {
            achievement.style.opacity = '1';
            achievement.style.transform = 'translateY(0)';
        }, index * 150);
    });
    
    // Efeito de brilho na barra de XP
    setInterval(() => {
        const xpBar = document.querySelector('.xp-bar');
        if (xpBar) {
            xpBar.style.boxShadow = '0 0 15px rgba(58, 91, 255, 0.7)';
            setTimeout(() => {
                xpBar.style.boxShadow = 'none';
            }, 1000);
        }
    }, 3000);
});