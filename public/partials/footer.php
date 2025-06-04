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

<style>
  footer {
    background-color: #e0f2fe;
    padding: 2rem;
    text-align: center;
    border-top: 1px solid #ddd;
  }

  .footer-links {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    margin-top: 2rem;
  }

  .footer-column h3 {
    color: #1e3a8a;
    font-weight: bold;
    margin-bottom: 0.5rem;
  }

  .footer-column a {
    display: block;
    margin: 0.25rem 0;
    text-decoration: none;
    color: #444;
  }

  .footer-column a:hover {
    text-decoration: underline;
  }

  .social-media i {
    color: #3b82f6;
    margin: 0 0.5rem;
    color: #333;
    font-size: 1.2rem;
  }

  .back-to-top {
    color: #3b82f6;
    margin-top: 2rem;
    padding: 0.5rem 1rem;
    background-color: #eee;
    border: none;
    cursor: pointer;
    border-radius: 4px;
  }

  .back-to-top:hover {
    background-color: #ddd;
  }
</style>

<script>
  function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
</script>
</body>
</html>
