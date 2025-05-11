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
  <link rel="stylesheet" href="public/css/settings.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="public/script.js" defer></script>
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
                <label class="form-label">Data de nascimento</label>
                <input type="date" class="form-control" value="1990-01-01">
              </div>
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
                <h2 class="reset-title">Redefinir Senha</h2>
                <p class="reset-subtitle">Digite o seu e-mail para receber o código de redefinição.</p>

                <form method="POST" action="enviar_reset.php" class="w-full max-w-md mt-8">
                  <input name="email" class="reset-input" placeholder="E-mail" type="email" required>
                  <?php if (isset($_GET['erro'])): ?>
                    <p class="reset-error">E-mail não cadastrado. Tente novamente com um e-mail válido.</p>
                  <?php endif; ?>
                  <button type="submit" class="reset-btn">Enviar Código</button>
                </form>
              </div>
              
              <!-- Parte 2: Redefinir senha -->
              <div id="redefinirParte" class="hidden">
                <h2 class="reset-title">Redefinir Senha</h2>
                <p class="reset-subtitle">Digite o código enviado ao seu e-mail e a nova senha.</p>

                <!-- Cronômetro e botão -->
                <div class="reset-timer-container">
                  <p id="tempoRestante" class="reset-timer">expira em: 15:00</p>
                  <button id="reenviarCodigo" onclick="reenviarCodigo()" class="reset-resend" disabled>Reenviar código</button>
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
              <p class="session-description">Estes são os dispositivos que estão atualmente logados na sua conta.</p>
              
              <div class="session-list">
                <div class="session-item">
                  <div>
                    <p class="session-device">Chrome - Windows 10</p>
                    <p class="session-info">São Paulo, BR • Agora mesmo</p>
                  </div>
                  <button class="session-logout">Sair</button>
                </div>
                
                <div class="session-item">
                  <div>
                    <p class="session-device">Safari - iPhone</p>
                    <p class="session-info">Rio de Janeiro, BR • 2 horas atrás</p>
                  </div>
                  <button class="session-logout">Sair</button>
                </div>
              </div>
            </div>
          </div>
          
          <div class="settings-card danger-zone">
            <h2 class="danger-title">Zona Perigosa</h2>
            
            <div class="form-group">
              <p class="danger-description">Ao excluir sua conta, todos os seus dados serão permanentemente removidos. Esta ação não pode ser desfeita.</p>
              <button type="button" class="danger-btn">
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