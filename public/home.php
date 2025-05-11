<?php
$pageTitle = "Página Inicial";
require './partials/header.php';
require './partials/menu.php';
?>

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

<div class="background-image">
  <img class="background-img" src="img/fundo.png" alt="Imagem de fundo" />
</div>

<?php require './partials/footer.php'; ?>