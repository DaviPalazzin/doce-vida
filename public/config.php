<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Determina a aba ativa
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'conta';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Configurações - Doce Vida</title>

  <!-- CSS e Tailwind -->
  <link rel="stylesheet" href="public/css/style.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="public/script.js" defer></script>

  <style>
    :root {
      --primary: #3182ce;
      --primary-hover: #2c5282;
      --dark-bg: #1a202c;
      --dark-card: #2d3748;
    }
    
    body {
      background-color: #f8fafc;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    
    body.dark-mode {
      background-color: var(--dark-bg);
      color: #e2e8f0;
    }
    
    body.dark-mode .settings-card,
    body.dark-mode .settings-tabs {
      background-color: var(--dark-card);
      border-color: #4a5568;
    }
    
    body.dark-mode .form-control {
      background-color: #4a5568;
      border-color: #4a5568;
      color: #e2e8f0;
    }
    
    body.dark-mode .form-label {
      color: #e2e8f0;
    }
    
    body.dark-mode .settings-title {
      color: #e2e8f0;
      border-color: #4a5568;
    }

    /* Layout de abas */
    .settings-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
    }
    
    .settings-tabs {
      display: flex;
      border-bottom: 1px solid #e2e8f0;
      margin-bottom: 2rem;
    }
    
    body.dark-mode .settings-tabs {
      border-color: #4a5568;
    }
    
    .settings-tab {
      padding: 0.75rem 1.5rem;
      cursor: pointer;
      border-bottom: 3px solid transparent;
      transition: all 0.2s;
      font-weight: 500;
      color: #64748b;
    }
    
    .settings-tab:hover {
      color: var(--primary);
    }
    
    .settings-tab.active {
      color: var(--primary);
      border-bottom-color: var(--primary);
    }
    
    .settings-tab i {
      margin-right: 0.5rem;
      width: 20px;
      text-align: center;
    }
    
    .tab-content {
      display: none;
    }
    
    .tab-content.active {
      display: block;
    }
    
    .settings-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      padding: 2rem;
      margin-bottom: 2rem;
    }
    
    .settings-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 1.5rem;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid #e2e8f0;
    }
    
    .form-group {
      margin-bottom: 1.25rem;
    }
    
    .form-label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: #334155;
    }
    
    .form-control {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      transition: border 0.2s;
      background-color: #f8fafc;
      max-width: 500px;
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.2);
    }
    
    .btn-save {
      background-color: var(--primary);
      color: white;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 8px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s;
    }
    
    .btn-save:hover {
      background-color: var(--primary-hover);
    }
    
    .switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 24px;
    }
    
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #e2e8f0;
      transition: .4s;
      border-radius: 24px;
    }
    
    .slider:before {
      position: absolute;
      content: "";
      height: 16px;
      width: 16px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }
    
    input:checked + .slider {
      background-color: var(--primary);
    }
    
    input:checked + .slider:before {
      transform: translateX(26px);
    }
    
    .toggle-group {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .grid-cols-2 {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }
    
    .color-option {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
    }
    
    .color-sample {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      border: 1px solid #ccc;
    }
    
    /* Estilos específicos para a seção de redefinição de senha */
    .reset-password-container {
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
    }
    
    .reset-input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #3182ce;
      border-radius: 9999px;
      margin-bottom: 1rem;
    }
    
    .reset-btn {
      width: 100%;
      padding: 0.75rem;
      background-color: #3182ce;
      color: white;
      border: none;
      border-radius: 9999px;
      cursor: pointer;
    }
    
    .reset-btn:hover {
      background-color: #2c5282;
    }
    
    @media (max-width: 768px) {
      .grid-cols-2 {
        grid-template-columns: 1fr;
      }
      
      .settings-tabs {
        overflow-x: auto;
        padding-bottom: 0.5rem;
      }
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
  <main>
    <div class="settings-container">
      <!-- Navegação por abas -->
      <div class="settings-tabs">
        <div class="settings-tab <?= $active_tab === 'conta' ? 'active' : '' ?>" onclick="changeTab('conta')">
          <i class="fas fa-user-cog"></i>
          Conta
        </div>
        <div class="settings-tab <?= $active_tab === 'jogos' ? 'active' : '' ?>" onclick="changeTab('jogos')">
          <i class="fas fa-gamepad"></i>
          Jogos
        </div>
        <div class="settings-tab <?= $active_tab === 'acessibilidade' ? 'active' : '' ?>" onclick="changeTab('acessibilidade')">
          <i class="fas fa-universal-access"></i>
          Acessibilidade
        </div>
        <div class="settings-tab <?= $active_tab === 'seguranca' ? 'active' : '' ?>" onclick="changeTab('seguranca')">
          <i class="fas fa-shield-alt"></i>
          Segurança
        </div>
      </div>
      
      <!-- Conteúdo das abas -->
      <form method="POST" action="config.php">
        <!-- ABA CONTA -->
        <div id="conta-tab" class="tab-content <?= $active_tab === 'conta' ? 'active' : '' ?>">
          <div class="settings-card">
            <h2 class="settings-title">Informações Pessoais</h2>
            
            <div class="grid-cols-2">
              <div class="form-group">
                <label class="form-label">Nome completo</label>
                <input type="text" class="form-control" value="João Silva">
              </div>
              
              <div class="form-group">
                <label class="form-label">E-mail</label>
                <input type="email" class="form-control" value="joao@exemplo.com">
              </div>
            </div>
            
            <div class="form-group">
              <label class="form-label">Data de nascimento</label>
              <input type="date" class="form-control" value="1990-01-01">
            </div>
            
            <div class="form-group">
              <label class="form-label">Sexo</label>
              <select class="form-control">
                <option value="">Selecione</option>
                <option value="masculino">Masculino</option>
                <option value="feminino">Feminino</option>
                <option value="outro">Outro</option>
                <option value="nao_informar">Prefiro não informar</option>
              </select>
            </div>
          </div>
          
          <div class="flex justify-end">
            <button type="submit" class="btn-save">
              <i class="fas fa-save mr-2"></i>Salvar alterações
            </button>
          </div>
        </div>
        
        <!-- ABA JOGOS -->
        <div id="jogos-tab" class="tab-content <?= $active_tab === 'jogos' ? 'active' : '' ?>">
          <div class="settings-card">
            <h2 class="settings-title">Preferências de Jogo</h2>
            
            <div class="form-group">
              <label class="form-label">Volume dos efeitos sonoros</label>
              <input type="range" min="0" max="100" value="70" class="w-full max-w-xs">
            </div>
            
            <div class="form-group">
              <label class="form-label">Volume da música de fundo</label>
              <input type="range" min="0" max="100" value="50" class="w-full max-w-xs">
            </div>
          </div>
          
          <div class="flex justify-end">
            <button type="submit" class="btn-save">
              <i class="fas fa-save mr-2"></i>Salvar configurações
            </button>
          </div>
        </div>
        
        <!-- ABA ACESSIBILIDADE -->
        <div id="acessibilidade-tab" class="tab-content <?= $active_tab === 'acessibilidade' ? 'active' : '' ?>">
          <div class="settings-card">
            <h2 class="settings-title">Tema</h2>
            
            <div class="form-group">
              <div class="toggle-group">
                <label class="switch">
                  <input type="checkbox" id="darkModeToggle">
                  <span class="slider"></span>
                </label>
                <span>Modo escuro</span>
              </div>
            </div>
          </div>
          
          <div class="settings-card">
            <h2 class="settings-title">Modo para Daltônicos</h2>
            
            <div class="form-group">
              <div class="color-option">
                <input type="radio" id="daltonismo-none" name="daltonismo" value="none" checked>
                <label for="daltonismo-none">Padrão (sem ajuste)</label>
              </div>
              
              <div class="color-option">
                <input type="radio" id="daltonismo-protanopia" name="daltonismo" value="protanopia">
                <div class="color-sample" style="background: linear-gradient(135deg, #FF6B6B, #4ECDC4);"></div>
                <label for="daltonismo-protanopia">Protanopia (vermelho/verde)</label>
              </div>
              
              <div class="color-option">
                <input type="radio" id="daltonismo-deuteranopia" name="daltonismo" value="deuteranopia">
                <div class="color-sample" style="background: linear-gradient(135deg, #FFD166, #06D6A0);"></div>
                <label for="daltonismo-deuteranopia">Deuteranopia (verde/vermelho)</label>
              </div>
              
              <div class="color-option">
                <input type="radio" id="daltonismo-tritanopia" name="daltonismo" value="tritanopia">
                <div class="color-sample" style="background: linear-gradient(135deg, #A5B4FC, #F9A8D4);"></div>
                <label for="daltonismo-tritanopia">Tritanopia (azul/amarelo)</label>
              </div>
            </div>
          </div>
          
          <div class="settings-card">
            <h2 class="settings-title">Tamanho do Texto</h2>
            
            <div class="form-group">
              <select class="form-control">
                <option>Pequeno</option>
                <option selected>Médio</option>
                <option>Grande</option>
              </select>
            </div>
          </div>
        </div>
        
        <!-- ABA SEGURANÇA -->
        <div id="seguranca-tab" class="tab-content <?= $active_tab === 'seguranca' ? 'active' : '' ?>">
          <div class="settings-card">
            <h2 class="settings-title">Alterar Senha</h2>
            
            <div class="reset-password-container">
              <!-- Mensagem flutuante -->
              <div id="mensagemSucesso" class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white text-sm px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-500 pointer-events-none z-50">
                Código de redefinição enviado com sucesso! Verifique seu e-mail.
              </div>

              <!-- Parte 1: Enviar código -->
              <div id="enviarParte">
                <h2 class="text-2xl font-bold text-blue-900 text-center mb-4">Redefinir Senha</h2>
                <p class="text-blue-900 text-sm text-center">Digite o seu e-mail para receber o código de redefinição.</p>

                <form method="POST" action="enviar_reset.php" class="w-full max-w-md mt-8">
                  <input name="email" class="reset-input" placeholder="E-mail" type="email" required>
                  <?php if (isset($_GET['erro'])): ?>
                    <p class="text-red-600 text-sm mb-4 text-center">E-mail não cadastrado. Tente novamente com um e-mail válido.</p>
                  <?php endif; ?>
                  <button type="submit" class="reset-btn">Enviar Código</button>
                </form>
              </div>
              
              <!-- Parte 2: Redefinir senha -->
              <div id="redefinirParte" class="hidden">
                <h2 class="text-2xl font-bold text-blue-900 text-center mb-4">Redefinir Senha</h2>
                <p class="text-blue-900 text-sm text-center mb-8">Digite o código enviado ao seu e-mail e a nova senha.</p>

                <!-- Cronômetro e botão -->
                <div class="relative w-full max-w-md mt-2 mb-[-8px]">
                  <p id="tempoRestante" class="absolute left-0 -top-6 text-xs text-blue-900">expira em: 15:00</p>
                  <button id="reenviarCodigo" onclick="reenviarCodigo()" class="absolute right-0 -top-6 text-xs text-blue-900 underline hover:text-blue-700" disabled>Reenviar código</button>
                </div>

                <form method="POST" action="processar_reset.php" class="w-full max-w-md mt-8">
                  <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">
                  <input name="token" class="reset-input" placeholder="Código do E-mail" type="text" required>
                  <input name="nova_senha" class="reset-input" placeholder="Nova Senha" type="password" required>
                  <button type="submit" class="reset-btn">Atualizar Senha</button>
                </form>
              </div>
            </div>
          </div>
          
          <div class="settings-card">
            <h2 class="settings-title">Sessões Ativas</h2>
            
            <div class="form-group">
              <p class="text-sm text-gray-600 mb-2">Estes são os dispositivos que estão atualmente logados na sua conta.</p>
              
              <div class="border rounded-lg divide-y">
                <div class="p-3 flex justify-between items-center">
                  <div>
                    <p class="font-medium">Chrome - Windows 10</p>
                    <p class="text-sm text-gray-500">São Paulo, BR • Agora mesmo</p>
                  </div>
                  <button class="text-red-600 hover:underline text-sm">Sair</button>
                </div>
                
                <div class="p-3 flex justify-between items-center">
                  <div>
                    <p class="font-medium">Safari - iPhone</p>
                    <p class="text-sm text-gray-500">Rio de Janeiro, BR • 2 horas atrás</p>
                  </div>
                  <button class="text-red-600 hover:underline text-sm">Sair</button>
                </div>
              </div>
            </div>
          </div>
          
          <div class="settings-card bg-red-50 border-red-200">
            <h2 class="settings-title text-red-800">Zona Perigosa</h2>
            
            <div class="form-group">
              <p class="text-sm text-gray-700 mb-4">Ao excluir sua conta, todos os seus dados serão permanentemente removidos. Esta ação não pode ser desfeita.</p>
              <button type="button" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                Excluir minha conta permanentemente
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>

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

  <script>
    // Controle das abas
    function changeTab(tabName) {
      // Atualiza a URL sem recarregar a página
      history.pushState(null, null, `?tab=${tabName}`);
      
      // Remove a classe active de todas as abas e conteúdos
      document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.classList.remove('active');
      });
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
      });
      
      // Adiciona a classe active na aba e conteúdo selecionados
      document.querySelector(`.settings-tab[onclick="changeTab('${tabName}')"]`).classList.add('active');
      document.getElementById(`${tabName}-tab`).classList.add('active');
    }
    
    // Modo escuro
    const darkModeToggle = document.getElementById('darkModeToggle');
    
    // Verifica preferência salva ou do sistema
    if (localStorage.getItem('darkMode') === 'enabled' || 
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.body.classList.add('dark-mode');
      darkModeToggle.checked = true;
    }
    
    darkModeToggle.addEventListener('change', function() {
      if (this.checked) {
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled');
      } else {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'disabled');
      }
    });
    
    // Controle do modo para daltônicos
    document.querySelectorAll('input[name="daltonismo"]').forEach(radio => {
      radio.addEventListener('change', function() {
        document.body.className = '';
        document.body.classList.remove('dark-mode');
        if (this.value !== 'none') {
          document.body.classList.add(`daltonismo-${this.value}`);
        }
        localStorage.setItem('daltonismoMode', this.value);
      });
      
      // Aplica o modo salvo
      if (radio.value === localStorage.getItem('daltonismoMode')) {
        radio.checked = true;
        if (radio.value !== 'none') {
          document.body.classList.add(`daltonismo-${radio.value}`);
        }
      }
    });
    
    // Código para redefinição de senha
    let tempoExpiracao = 900; // 15 minutos
    let tempoReenvio = 60;
    let intervaloExpiracao, intervaloReenvio;

    function atualizarContador() {
      const minutos = Math.floor(tempoExpiracao / 60);
      const segundos = tempoExpiracao % 60;
      document.getElementById("tempoRestante").textContent = `Código expira em: ${minutos}:${segundos < 10 ? '0' : ''}${segundos}`;
      tempoExpiracao--;

      if (tempoExpiracao < 0) {
        clearInterval(intervaloExpiracao);
        document.getElementById("tempoRestante").textContent = "O código expirou.";
        document.getElementById("reenviarCodigo").disabled = false;
      }
    }

    function iniciarExpiracao() {
      tempoExpiracao = 900;
      atualizarContador();
      clearInterval(intervaloExpiracao);
      intervaloExpiracao = setInterval(atualizarContador, 1000);
    }

    function iniciarCooldownReenvio() {
      let btn = document.getElementById("reenviarCodigo");
      tempoReenvio = 60;
      btn.disabled = true;

      clearInterval(intervaloReenvio);
      intervaloReenvio = setInterval(() => {
        tempoReenvio--;
        if (tempoReenvio <= 0) {
          btn.disabled = false;
          clearInterval(intervaloReenvio);
        }
      }, 1000);
    }

    function mostrarMensagemFlutuante() {
      const msg = document.getElementById("mensagemSucesso");
      msg.classList.remove("opacity-0");
      msg.classList.add("opacity-100");

      setTimeout(() => {
        msg.classList.remove("opacity-100");
        msg.classList.add("opacity-0");
      }, 3000);
    }

    function reenviarCodigo() {
      alert("Código reenviado para seu e-mail.");
      mostrarMensagemFlutuante();
      iniciarExpiracao();
      iniciarCooldownReenvio();
    }

    // Carrega a aba correta ao abrir a página
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const tabParam = urlParams.get('tab');
      
      if (tabParam) {
        changeTab(tabParam);
      }
      
      // Verifica se deve mostrar a parte de redefinição
      if (urlParams.get('sucesso') === '1') {
        document.getElementById("enviarParte").classList.add("hidden");
        document.getElementById("redefinirParte").classList.remove("hidden");
        mostrarMensagemFlutuante();
        iniciarExpiracao();
        iniciarCooldownReenvio();
      }
    });
  </script>
</body>

</html>