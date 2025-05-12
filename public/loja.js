document.addEventListener('DOMContentLoaded', function() {
    // Variáveis globais
    let userCoins = 500; // Deve vir do backend na implementação real
    let selectedItem = null;
    let currentQuantity = 1;

    // Elementos do DOM
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decrease-quantity');
    const increaseBtn = document.getElementById('increase-quantity');
    const itemNameSpan = document.getElementById('item-name');
    const itemQuantitySpan = document.getElementById('item-quantity');
    const totalAmountSpan = document.getElementById('total-amount');
    const confirmBtn = document.getElementById('confirmBtn');
    const modal = document.getElementById('confirmationModal');

    // Atualiza o display de moedas
    function updateCoinsDisplay() {
        document.getElementById('user-coins').textContent = userCoins;
        
        // Atualiza disponibilidade de todos os itens
        document.querySelectorAll('.buy-btn').forEach(btn => {
            const price = parseInt(btn.dataset.price);
            btn.disabled = userCoins < price;
        });
    }

    // Controles de quantidade
    function updateQuantity() {
        quantityInput.value = currentQuantity;
        itemQuantitySpan.textContent = currentQuantity;
        
        if (selectedItem) {
            const totalPrice = currentQuantity * selectedItem.price;
            totalAmountSpan.textContent = totalPrice;
            
            // Verifica se o usuário tem moedas suficientes
            confirmBtn.disabled = userCoins < totalPrice;
        }
    }

    decreaseBtn.addEventListener('click', () => {
        if (currentQuantity > 1) {
            currentQuantity--;
            updateQuantity();
        }
    });

    increaseBtn.addEventListener('click', () => {
        currentQuantity++;
        updateQuantity();
    });

    quantityInput.addEventListener('change', () => {
        const newQuantity = parseInt(quantityInput.value) || 1;
        currentQuantity = Math.max(1, newQuantity);
        updateQuantity();
    });

    // Lógica de compra
    document.querySelectorAll('.buy-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            selectedItem = {
                element: e.target,
                name: e.target.parentElement.parentElement.querySelector('.item-title').textContent,
                price: parseInt(e.target.dataset.price),
                id: e.target.dataset.item
            };
            
            currentQuantity = 1;
            showConfirmationModal();
        });
    });

    function showConfirmationModal() {
        itemNameSpan.textContent = selectedItem.name;
        updateQuantity();
        modal.style.display = 'flex';
    }

    // Confirmação de compra
    confirmBtn.addEventListener('click', () => {
        if (selectedItem) {
            const totalPrice = currentQuantity * selectedItem.price;
            
            if (userCoins >= totalPrice) {
                userCoins -= totalPrice;
                updateCoinsDisplay();
                
                // Aqui você enviaria para o backend a compra
                console.log(`Comprado ${currentQuantity} ${selectedItem.name} por ${totalPrice} moedas`);
                
                // Feedback visual
                alert(`Compra realizada! ${currentQuantity} ${selectedItem.name} adquirido(s).`);
                
                // Fecha o modal
                modal.style.display = 'none';
            }
        }
    });

    // Fechar modal
    document.getElementById('cancelBtn').addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Filtro de categorias
    document.querySelectorAll('.category-btn').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
            
            const category = button.dataset.category;
            document.querySelectorAll('.shop-item').forEach(item => {
                item.style.display = (category === 'all' || item.dataset.category === category) ? 'block' : 'none';
            });
        });
    });

    // Inicialização
    updateCoinsDisplay();
});