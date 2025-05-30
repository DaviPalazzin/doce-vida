<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Doce Vida</title>

  <!-- CSS e Tailwind -->
  <link rel="stylesheet" href="public/css/style.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="public/script.js" defer></script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      display: flex;
      flex-direction: column;
    }

    main {
      flex: 1;
    }

    footer {
      background-color: #f5f5f5;
      padding: 1rem;
    }
  </style>
</head>

<body>
  <!-- Cabeçalho -->
  <header class="header">
    <div class="logo">
      <img src="img/logo.png" alt="logo doce vida" width="30%" />
    </div>

    <nav class="search-container">
      <input type="text" id="searchBar" placeholder="Buscar..." onkeyup="searchFunction()">
      <i class="fas fa-search search-icon"></i>
      <div id="searchResults"></div>
    </nav>

    <div class="profile-btn" onclick="toggleMenu()">
      <img class="user-avatar" src="img/perfil.jpg" alt="Foto de perfil" width="40px" />
    </div>
  </header>

  <!-- Menu suspenso -->
  <nav id="menu" class="menu">
    <ul>
      <li><a href="perfil.php">Perfil</a></li>
      <li><a href="config.php">Configurações</a></li>
      <li><a href="ranking.php">Ranking</a></li>
      <li><a href="loja.php">Loja</a></li>
      <li><a href="logout.php">Sair</a></li>
    </ul>
  </nav>

  <!-- Conteúdo principal -->
  <main class="main-content">
    <section class="grid-container">
      <article class="grid-item">
        <img class="grid-image" src="img/caça palavras.png" alt="Caça palavras" width="46%" />
        <p class="grid-title">Caça Palavras</p>
      </article>

      <article class="grid-item">
        <img class="grid-image" src="img/aprenda a comer.png" alt="Aprenda a comer" width="50%" />
        <p class="grid-title">Aprenda a comer</p>
      </article>

      <article class="grid-item">
        <img class="grid-image" src="img/complete as frases.png" alt="Complete as frases" width="46%" />
        <p class="grid-title">Complete as frases</p>
      </article>
    </section>
  </main>

  <!-- Imagem de fundo -->
  <div class="background-image">
    <img class="background-img" src="img/fundo.png" alt="Imagem de fundo com utensílios médicos" />
  </div>

  <!-- Rodapé -->
  <footer>
    <div class="social-media">
      <p>Siga Doce Vida</p>
      <a href="#"><i class="fab fa-facebook"></i></a>
      <a href="#"><i class="fab fa-x-twitter"></i></a>
      <a href="#"><i class="fab fa-youtube"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>

    <div class="footer-links">
      <div class="footer-column">
        <h3>Novidades</h3>
        <a href="#">Novos jogos</a>
        <a href="#">Atualizações</a>
        <a href="#">Blog</a>
      </div>
      <div class="footer-column">
        <h3>Jogos</h3>
        <a href="#">Caça Palavras</a>
        <a href="#">Aprenda a Comer</a>
        <a href="#">Mais jogos</a>
      </div>
      <div class="footer-column">
        <h3>Educação</h3>
        <a href="#">Dicas de Saúde</a>
        <a href="#">Nutrição</a>
        <a href="#">Prevenção</a>
      </div>
      <div class="footer-column">
        <h3>Sobre</h3>
        <a href="#">Quem Somos</a>
        <a href="#">Contato</a>
        <a href="#">Política de Privacidade</a>
      </div>
    </div>

    <button class="back-to-top" onclick="scrollToTop()">⬆ Voltar ao início</button>
  </footer>
</body>

</html>
