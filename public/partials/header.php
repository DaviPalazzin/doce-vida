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

    .logo img {
      max-height: 60px;
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
      border-radius: 9999px;
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
