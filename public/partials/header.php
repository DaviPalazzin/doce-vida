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
  <link rel="stylesheet" href="public/css/style.css"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="public/script.js" defer></script>
  <style>
    html, body { height: 100%; margin: 0; }
    body { display: flex; flex-direction: column; }
    main { flex: 1; }
    footer { background-color: #f5f5f5; padding: 1rem; }
  </style>
</head>
<body>
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