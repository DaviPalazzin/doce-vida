<?php
$pageTitle = "Página Inicial";
$mostrarVoltar = false;
require './partials/header.php';
require './partials/menu.php';
?>
<link rel="stylesheet" href="css/home.css">

<main class="main-content">
  <section class="grid-container">
    <article class="grid-item">
      <a href="jogos/caça-palavras/caça-palavras.html"><img class="grid-image" src="img/caça-palavras.png" alt="Caça palavras" width="46%" /></a>
      <p class="grid-title">Caça Palavras</p>
    </article>
    <article class="grid-item">
      <a href="jogos/quiz/quiz.html"><img class="grid-image" src="img/quiz.png" alt="Aprenda a comer" width="50%" /></a>
      <p class="grid-title">Quiz</p>
    </article>
    <article class="grid-item">
      <a href="jogos/complete as frases/complete-as-frases.html"><img class="grid-image" src="img/complete-as-frases.png" alt="Complete as frases" width="46%" /></a>
      <p class="grid-title">Complete as frases</p>
    </article>
  </section>
</main>

<div class="background-image">
  <img class="background-img" src="img/fundo.png" alt="Imagem de fundo" />
</div>

<?php require './partials/footer.php'; ?>