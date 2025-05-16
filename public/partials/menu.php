<nav id="menu" class="menu">
  <ul>
    <li><a href="perfil.php"><i class="fas fa-user"></i> Perfil</a></li>
    <li><a href="config.php"><i class="fas fa-cog"></i> Configurações</a></li>
    <li><a href="ranking.php"><i class="fas fa-trophy"></i> Ranking</a></li>
    <li><a href="loja.php"><i class="fas fa-store"></i> Loja</a></li>
    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
  </ul>
</nav>

<style>
  .menu {
    display: none;
    position: absolute;
    top: 60px;
    right: 20px;
    background-color: #333;
    padding: 10px;
    border-radius: 5px;
    z-index: 999;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: opacity 0.3s ease, transform 0.3s ease;
  }

  .menu.show {
    display: block;
    opacity: 1;
    transform: translateY(0);
  }

  .menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .menu ul li {
    padding: 10px;
    display: flex;
    align-items: center;
  }

  .menu ul li a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .menu ul li a:hover {
    color: #ddd;
  }
</style>

<script>
  function toggleMenu() {
    const menu = document.getElementById('menu');
    menu.classList.toggle('show');
  }

  document.addEventListener('click', function(event) {
    const menu = document.getElementById('menu');
    const profileBtn = document.querySelector('.profile-btn');

    if (!menu.contains(event.target) && !profileBtn?.contains(event.target)) {
      menu.classList.remove('show');
    }
  });
</script>
