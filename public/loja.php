<?php
$pageTitle = "Página Inicial";
$mostrarVoltar = true;
require './partials/header.php';
require './partials/menu.php';
?>


<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Loja - Doce Vida</title>
  <link rel="stylesheet" href="/public/css/style.css" />
  <link rel="stylesheet" href="/public/css/loja.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="/public/script.js" defer></script>
  <script src="/public/loja.js" defer></script>
</head>

<body>

  <!-- Display de moedas fixo no topo -->
  <div class="fixed-coins-display">
    <div class="currency-display">
      <i class="fas fa-coins"></i>
      <span id="user-coins">500</span>
    </div>
  </div>

  <nav id="menu" class="menu">
    <ul>
      <li><a href="perfil.php">Perfil</a></li>
      <li><a href="config.php">Configurações</a></li>
      <li><a href="ranking.php">Ranking</a></li>
      <li><a href="loja.php">Loja</a></li>
      <li><a href="logout.php">Sair</a></li>
    </ul>
  </nav>

  <main class="shop-container">
    <div class="shop-header">
      <h1 class="shop-title">Loja Doce Vida</h1>
    </div>
    
    <div class="shop-categories">
      <button class="category-btn active" data-category="all">Todos</button>
      <button class="category-btn" data-category="vidas">Vidas</button>
      <button class="category-btn" data-category="boosts">Boosts</button>
      <button class="category-btn" data-category="powerups">Power-ups</button>
      <button class="category-btn" data-category="cosmeticos">Cosméticos</button>
    </div>
    
    <div class="shop-items">
      <!-- Item 1 - Vidas -->
      <div class="shop-item" data-category="vidas">
        <img src="img/vida.png" alt="Pacote de Vidas" class="item-image">
        <div class="item-details">
          <h3 class="item-title">Pacote de Vidas</h3>
          <p class="item-description">5 vidas extras para continuar jogando quando errar</p>
          <div class="item-footer">
            <div class="item-price">
              <i class="fas fa-coins"></i>
              <span>100</span>
            </div>
            <button class="buy-btn" data-item="vidas" data-price="100">Comprar</button>
          </div>
        </div>
      </div>
      
      <!-- Item 2 - Boost de Tempo -->
      <div class="shop-item" data-category="boosts">
        <img src="img/boost-tempo.png" alt="Boost de Tempo" class="item-image">
        <div class="item-details">
          <h3 class="item-title">Boost de Tempo</h3>
          <p class="item-description">+30 segundos em jogos contra o tempo</p>
          <div class="item-footer">
            <div class="item-price">
              <i class="fas fa-coins"></i>
              <span>75</span>
            </div>
            <button class="buy-btn" data-item="boost_tempo" data-price="75">Comprar</button>
          </div>
        </div>
      </div>
      
      <!-- Item 3 - Dica Caça-Palavras -->
      <div class="shop-item" data-category="boosts">
        <img src="img/dica-caca-palavras.png" alt="Dica Caça-Palavras" class="item-image">
        <div class="item-details">
          <h3 class="item-title">Dica Caça-Palavras</h3>
          <p class="item-description">Revela uma palavra escondida no caça-palavras</p>
          <div class="item-footer">
            <div class="item-price">
              <i class="fas fa-coins"></i>
              <span>50</span>
            </div>
            <button class="buy-btn" data-item="dica_caca_palavras" data-price="50">Comprar</button>
          </div>
        </div>
      </div>
      
      <!-- Item 4 - Avatar Especial -->
      <div class="shop-item" data-category="cosmeticos">
        <img src="img/avatar-especial.png" alt="Avatar Especial" class="item-image">
        <div class="item-details">
          <h3 class="item-title">Avatar Especial</h3>
          <p class="item-description">Avatar exclusivo para seu perfil</p>
          <div class="item-footer">
            <div class="item-price">
              <i class="fas fa-coins"></i>
              <span>200</span>
            </div>
            <button class="buy-btn" data-item="avatar_especial" data-price="200">Comprar</button>
          </div>
        </div>
      </div>
      
      <!-- Item 5 - Multiplicador de Pontos -->
      <div class="shop-item" data-category="powerups">
        <img src="img/multiplicador2x.png" alt="Multiplicador de Pontos" class="item-image">
        <div class="item-details">
          <h3 class="item-title">Multiplicador 2x</h3>
          <p class="item-description">Dobra os pontos ganhos por 1 hora</p>
          <div class="item-footer">
            <div class="item-price">
              <i class="fas fa-coins"></i>
              <span>150</span>
            </div>
            <button class="buy-btn" data-item="multiplicador" data-price="150">Comprar</button>
          </div>
        </div>
      </div>
      
      <!-- Item 6 - Pacote de Vidas Grande -->
      <div class="shop-item" data-category="vidas">
        <img src="img/vida-grande.png" alt="Pacote de Vidas Grande" class="item-image">
        <div class="item-details">
          <h3 class="item-title">Pacote Grande de Vidas</h3>
          <p class="item-description">15 vidas extras para continuar jogando</p>
          <div class="item-footer">
            <div class="item-price">
              <i class="fas fa-coins"></i>
              <span>250</span>
            </div>
            <button class="buy-btn" data-item="vidas_grande" data-price="250">Comprar</button>
          </div>
        </div>
      </div>
    </div>
  </main>
  
  <!-- Modal de confirmação -->
  <div class="modal" id="confirmationModal">
    <div class="modal-content">
      <h2>Confirmar Compra</h2>
      <p id="modal-message">Deseja comprar <span id="item-quantity">1</span> unidade(s) de <span id="item-name"></span>?</p>
      
      <div class="quantity-controls">
        <button id="decrease-quantity">-</button>
        <input type="number" id="quantity" min="1" value="1">
        <button id="increase-quantity">+</button>
      </div>
      
      <p id="total-price">Total: <span id="total-amount"></span> moedas</p>
      
      <div class="modal-buttons">
        <button class="modal-btn cancel-btn" id="cancelBtn">Cancelar</button>
        <button class="modal-btn confirm-btn" id="confirmBtn">Confirmar</button>
      </div>
    </div>
  </div>

  <!-- Imagem de fundo -->
  <div class="background-image">
    <img class="background-img" src="img/fundo.png" alt="Imagem de fundo com utensílios médicos" />
  </div>

</body>
</html>
<?php require './partials/footer.php'; ?>