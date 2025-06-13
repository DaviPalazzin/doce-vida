<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $pageTitle ?? 'Doce Vida' ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html, body { height: 100%; margin: 0; }
    body { display: flex; flex-direction: column; }
    main { flex: 1; }
    footer { background-color: #f5f5f5; padding: 1rem; }

    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 2rem;
      background-color: #4fa3f7;
      border-bottom: 1px solid #ccc;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 1rem;
      position: relative;
    }

    .logo img {
      max-height: 60px;
    }

    .botao-voltar {
      position: relative;
      background-color: rgba(255, 255, 255, 0.15);
      color: white;
      width: 120px;
      height: 40px;
      border-radius: 9999px;
      font-weight: 600;
      text-decoration: none;
      backdrop-filter: blur(4px);
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      opacity: 0.8;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
    }

    .botao-voltar:hover {
      opacity: 1;
      transform: translateY(-1px);
      background-color: rgba(255, 255, 255, 0.25);
    }

    .botao-voltar i {
      margin-right: 0.4rem;
    }

    .search-container {
      position: relative;
      flex-grow: 1;
      margin: 0 2rem;
      display: flex;
      align-items: center;
    }

    #searchBar {
      width: 100%;
      padding: 0.5rem 1rem;
      border-radius: 9999px;
      border: 1px solid #ccc;
      padding-right: 2rem;
    }

    .search-icon {
      position: absolute;
      right: 1rem;
      color: #888;
    }

    #searchResults {
      position: absolute;
      top: 110%;
      background: white;
      width: 100%;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
      z-index: 10;
      display: none;
    }

    .search-item {
      padding: 0.5rem 1rem;
      border-bottom: 1px solid #eee;
    }

    .search-item:hover {
      background-color: #f0f0f0;
    }

    .profile-btn {
      cursor: pointer;
    }

    .user-avatar {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #4fa3f7;
        box-shadow: 0 0 10px rgba(79, 163, 247, 0.5);
    }
  </style>

  <script>
    const siteFunctions = [
      { name: "Caça Palavras", link: "#" },
      { name: "Aprenda a Comer", link: "#" },
      { name: "Configurações", link: "config.php" },
      { name: "Perfil", link: "perfil.php" },
      { name: "Sobre Nós", link: "sobre.html" },
      { name: "Sair", link: "logout.php" }
    ];

    function searchFunction() {
      let input = document.getElementById("searchBar").value.toLowerCase();
      let resultsDiv = document.getElementById("searchResults");
      resultsDiv.innerHTML = "";
      resultsDiv.style.display = "none";

      if (input.length > 0) {
        let filteredResults = siteFunctions.filter(item =>
          item.name.toLowerCase().includes(input)
        );

        resultsDiv.style.display = "block";
        if (filteredResults.length > 0) {
          filteredResults.forEach(function(result) {
            let div = document.createElement("div");
            div.classList.add("search-item");
            div.innerHTML = `<a href="${result.link}">${result.name}</a>`;
            resultsDiv.appendChild(div);
          });
        } else {
          resultsDiv.innerHTML = "<div class='search-item'>Nenhum resultado encontrado</div>";
        }
      }
    }
  </script>
</head>
<body>
  
  <header class="header">
    <div class="logo">
      <img src="img/logo.png" alt="logo doce vida" />
      <?php if (!empty($mostrarVoltar) && $mostrarVoltar): ?>
        <a href="home.php" class="botao-voltar">
          <i class="fas fa-arrow-left"></i> Voltar
        </a>
      <?php endif; ?>
    </div>

    <nav class="search-container">
      <?php if (empty($mostrarVoltar) || !$mostrarVoltar): ?>
        <input type="text" id="searchBar" placeholder="Buscar..." onkeyup="searchFunction()">
        <i class="fas fa-search search-icon"></i>
      <?php endif; ?>
      <div id="searchResults"></div>
    </nav>

    <div class="profile-btn" onclick="toggleMenu()">
      <?php
        require 'conexao.php';

        // Busca a foto mais recente do banco
        if (isset($_SESSION['user_id'])) {
          $stmt = $conn->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
          $stmt->bind_param("i", $_SESSION['user_id']);
          $stmt->execute();
          $result = $stmt->get_result();
          $_SESSION['foto_perfil'] = $result->fetch_assoc()['foto_perfil'];
        }
      ?>
      <img src="<?= $_SESSION['foto_perfil'] ?? 'img/perfil.jpg' ?>" id="header-avatar" class="user-avatar" onerror="this.src='/public/img/perfil.jpg'"> <!-- Fallback se a foto não existir -->
    </div>
  </header>

  