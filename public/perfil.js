document.addEventListener('DOMContentLoaded', function() {
    // Elementos
    const usernameElement = document.getElementById('username');
    const usernameEditInput = document.getElementById('username-edit-input');
    const editUsernameBtn = document.querySelector('.edit-username');
    const usernameModal = document.getElementById('username-modal');
    const saveUsernameBtn = document.getElementById('save-username');
    const cancelUsernameBtn = document.getElementById('cancel-username');

    const bioText = document.getElementById('bio-text');
    const bioEditInput = document.getElementById('bio-edit-input');
    const editBioBtn = document.querySelector('.edit-bio');
    const bioModal = document.getElementById('bio-modal');
    const saveBioBtn = document.getElementById('save-bio');
    const cancelBioBtn = document.getElementById('cancel-bio');

    const promoInput = document.getElementById('promo-input');
    const promoEditInput = document.getElementById('promo-edit-input');
    const editPromoBtn = document.querySelector('.edit-promo');
    const promoModal = document.getElementById('promo-modal');
    const savePromoBtn = document.getElementById('save-promo');
    const cancelPromoBtn = document.getElementById('cancel-promo');

    const changePhotoBtn = document.getElementById('change-photo-btn');
    const photoModal = document.getElementById('photo-modal');
    const fileInput = document.getElementById('file-input');
    const uploadArea = document.getElementById('upload-area');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const savePhotoBtn = document.getElementById('save-photo');
    const cancelPhotoBtn = document.getElementById('cancel-photo');
    const profilePicture = document.getElementById('profile-picture');

    const contactEmail = document.getElementById('contact-email');
    const editContactBtn = document.querySelector('.edit-contact');
    const emailModal = document.getElementById('email-modal');
    const emailEditInput = document.getElementById('email-edit-input');
    const saveEmailBtn = document.getElementById('save-email');
    const cancelEmailBtn = document.getElementById('cancel-email');

    const backButton = document.getElementById('back-button');

    // Editar Nome de Usuário
    editUsernameBtn.addEventListener('click', () => {
        usernameEditInput.value = usernameElement.firstChild.textContent.trim();
        usernameModal.style.display = 'flex';
    });

    saveUsernameBtn.addEventListener('click', () => {
        usernameElement.firstChild.textContent = usernameEditInput.value;
        usernameModal.style.display = 'none';
    });

    cancelUsernameBtn.addEventListener('click', () => {
        usernameModal.style.display = 'none';
    });

    // Editar Biografia
    editBioBtn.addEventListener('click', () => {
        bioEditInput.value = bioText.textContent;
        bioModal.style.display = 'flex';
    });

    saveBioBtn.addEventListener('click', () => {
        bioText.textContent = bioEditInput.value;
        bioModal.style.display = 'none';
    });

    cancelBioBtn.addEventListener('click', () => {
        bioModal.style.display = 'none';
    });

    // Editar Texto Promocional
    editPromoBtn.addEventListener('click', () => {
        promoEditInput.value = promoInput.value;
        promoModal.style.display = 'flex';
    });

    promoInput.addEventListener('click', () => {
        promoEditInput.value = promoInput.value;
        promoModal.style.display = 'flex';
    });

    savePromoBtn.addEventListener('click', () => {
        promoInput.value = promoEditInput.value;
        promoModal.style.display = 'none';
    });

    cancelPromoBtn.addEventListener('click', () => {
        promoModal.style.display = 'none';
    });

    // Trocar Foto de Perfil
    changePhotoBtn.addEventListener('click', () => {
        photoModal.style.display = 'flex';
    });

    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            if (file.type.includes('image')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                alert('Por favor, selecione uma imagem válida!');
            }
        }
    });

    savePhotoBtn.addEventListener('click', () => {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (file.type.includes('image')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    profilePicture.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
        photoModal.style.display = 'none';
    });

    cancelPhotoBtn.addEventListener('click', () => {
        fileInput.value = '';
        imagePreview.style.display = 'none';
        photoModal.style.display = 'none';
    });

    // Editar Email
    editContactBtn.addEventListener('click', () => {
        emailEditInput.value = contactEmail.firstChild.textContent.trim();
        emailModal.style.display = 'flex';
    });

    saveEmailBtn.addEventListener('click', () => {
        contactEmail.firstChild.textContent = emailEditInput.value;
        emailModal.style.display = 'none';
    });

    cancelEmailBtn.addEventListener('click', () => {
        emailModal.style.display = 'none';
    });

    // Botão Voltar
    backButton.addEventListener('click', () => {
        window.location.href = "telaini.html"; // Altere para sua página inicial
    });

    // Fechar modais ao clicar fora
    window.addEventListener('click', (event) => {
        if (event.target === usernameModal) usernameModal.style.display = 'none';
        if (event.target === bioModal) bioModal.style.display = 'none';
        if (event.target === promoModal) promoModal.style.display = 'none';
        if (event.target === photoModal) photoModal.style.display = 'none';
        if (event.target === emailModal) emailModal.style.display = 'none';
    });
});