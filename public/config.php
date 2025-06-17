<?php
$mostrarVoltar = true;
require './partials/header.php';
require './partials/menu.php';

// Determina a aba ativa
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'conta';

// Conecta ao banco de dados e busca o nome do usuário usando o email da sessão
include('conexao.php');
$email = $_SESSION['email']; // Usando o email da sessão
$query = "SELECT username FROM usuarios WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $user_data = mysqli_fetch_assoc($result);
  $username = $user_data['username'];
} else {
  $username = "Usuário"; // Valor padrão caso não encontre
}

// Adicione isso na parte de processamento POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
  $token = $_POST['delete_token'];

  // Verificar token no banco de dados
  $query = "SELECT delete_token, delete_token_expira FROM usuarios WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);

    if ($data['delete_token'] === $token && strtotime($data['delete_token_expira']) > time()) {
      // Token válido - excluir conta
      $deleteQuery = "DELETE FROM usuarios WHERE email = '$email'";
      if (mysqli_query($conn, $deleteQuery)) {
        session_destroy();
        header("Location: /index.php?account_deleted=1");
        exit();
      }
    } else {
      $deleteError = "Código inválido ou expirado.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<!-- ... (cabeçalho permanece o mesmo) ... -->

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Configurações - Doce Vida</title>

  <!-- CSS e Tailwind -->
  <link rel="stylesheet" href="/public/css/style.css" />
  <link rel="stylesheet" href="/public/css/settings.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="/public/script.js" defer></script>
</head>

<body>

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
                <div class="form-control-static">
                  <?= htmlspecialchars($username) ?>
                  <span class="text-sm text-gray-500 ml-2">
                    <i class="fas fa-lock"></i>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Data de nascimento</label>
                <input type="date" class="form-control" value="1990-01-01">
              </div>
            </div>

            <!-- ... (restante do código permanece igual) ... -->

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

        <!-- Restante do código permanece igual -->

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

              <!-- Parte 1: Enviar código -->
              <div id="enviarParte">
                <h2 class="reset-title">Redefinir Senha</h2>
                <p class="reset-subtitle">Clique no botão abaixo para ser direcionado à pagina de redefinição de senha.</p>

                <div style="text-align: center;">
  <a href="resetar_senha.php" class="reset-btn">Redefinir</a>
</div>

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

              <button type="button" id="deleteAccountBtn" class="danger-btn">
                <i class="fas fa-exclamation-triangle mr-2"></i>Excluir minha conta permanentemente
              </button>

              <?php if (isset($_GET['delete_sent'])): ?>
                <div class="mt-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
                  <div class="flex items-center">
                    <i class="fas fa-envelope text-blue-500 mr-2"></i>
                    <p>Código de confirmação enviado para seu e-mail. Verifique sua caixa de entrada.</p>
                  </div>
                </div>
              <?php endif; ?>

              <?php if (isset($_GET['delete_error'])): ?>
                <div class="mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                  <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <p>
                      <?php
                      echo $_GET['delete_error'] == 1 ? "E-mail não encontrado." : ($_GET['delete_error'] == 2 ? "Erro ao enviar e-mail. Tente novamente." :
                          "Erro desconhecido.");
                      ?>
                    </p>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>

<!-- Modal de Exclusão de Conta -->
<div id="deleteAccountModal" class="modal hidden fixed inset-0 z-50 overflow-y-auto">
  <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
  
  <div class="modal-container relative w-full max-w-md mx-auto my-8 p-4">
    <div class="modal-content bg-white rounded-lg shadow-xl overflow-hidden dark:bg-gray-800">
      <!-- Cabeçalho -->
      <div class="modal-header bg-red-600 p-4">
        <h3 class="text-xl font-bold text-white">Confirmar Exclusão de Conta</h3>
      </div>
      
      <!-- Corpo -->
      <div class="modal-body p-6">
        <div class="flex items-center mb-4">
          <div class="flex-shrink-0 text-red-500">
            <i class="fas fa-exclamation-triangle text-2xl"></i>
          </div>
          <div class="ml-3">
            <p class="text-gray-700 dark:text-gray-300">Você está prestes a excluir permanentemente sua conta e todos os dados associados. Esta ação não pode ser desfeita.</p>
          </div>
        </div>
        
        <?php if (isset($deleteError)): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $deleteError; ?>
          </div>
        <?php endif; ?>
        
        <div id="deleteStep1">
          <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Para iniciar o processo, clique no botão abaixo para receber um código de confirmação por e-mail.</p>
          
<form method="POST" action="enviar_delete.php" class="mt-4" id="deleteTokenRequestForm">

            <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">
            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700 flex items-center justify-center">
              <i class="fas fa-paper-plane mr-2"></i> Enviar Código de Confirmação
            </button>
          </form>
        </div>
        
        <div id="deleteStep2" class="hidden">
          <form method="POST" action="processar_delete.php" class="mt-4">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Insira o código de 6 dígitos que enviamos para <?= $_SESSION['email'] ?>:</p>
            
            <input type="text" name="delete_token" id="deleteToken" class="w-full px-3 py-2 border rounded-md text-center text-xl tracking-widest dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="______" maxlength="6" required>
            <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">
            
            <p id="tokenError" class="text-red-500 text-sm mt-2 hidden">Código inválido. Tente novamente.</p>
            
            <div class="flex justify-between items-center mt-4">
              <button type="button" id="resendCode" class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-redo mr-1"></i> Reenviar código
              </button>
              
              <div class="flex space-x-2">
                <button type="button" id="cancelDelete" class="px-4 py-2 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                  Cancelar
                </button>
                <button type="submit" id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50" disabled>
                  Confirmar Exclusão
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
  </main>

  <?php require './partials/footer.php'; ?>
  <script>

// Modal de exclusão de conta
const deleteAccountBtn = document.getElementById('deleteAccountBtn');
const deleteModal = document.getElementById('deleteAccountModal');
const cancelDeleteBtn = document.getElementById('cancelDelete');
const confirmDeleteBtn = document.getElementById('confirmDelete');
const deleteTokenInput = document.getElementById('deleteToken');
const tokenError = document.getElementById('tokenError');
const deleteStep1 = document.getElementById('deleteStep1');
const deleteStep2 = document.getElementById('deleteStep2');
const resendCodeBtn = document.getElementById('resendCode');

// Verificar se deve mostrar a etapa 2
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('delete_sent')) {
  deleteStep1.classList.add('hidden');
  deleteStep2.classList.remove('hidden');
}

// Abrir modal
deleteAccountBtn.addEventListener('click', () => {
  deleteModal.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  
  // Resetar estados
  if (urlParams.get('delete_sent')) {
    deleteStep1.classList.add('hidden');
    deleteStep2.classList.remove('hidden');
  } else {
    deleteStep1.classList.remove('hidden');
    deleteStep2.classList.add('hidden');
  }
  deleteTokenInput.value = '';
  tokenError.classList.add('hidden');
});

// Reenviar código
resendCodeBtn?.addEventListener('click', () => {
  fetch('enviar_delete.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `email=${encodeURIComponent('<?= $_SESSION['email'] ?>')}`
  })
  .then(response => {
    if (response.ok) {
      alert('Código reenviado com sucesso! Verifique seu e-mail.');
    } else {
      alert('Erro ao reenviar código. Tente novamente.');
    }
  });
});

// Validação do token
deleteTokenInput?.addEventListener('input', (e) => {
  const value = e.target.value.replace(/\D/g, '');
  e.target.value = value;
  
  if (value.length === 6) {
    confirmDeleteBtn.disabled = false;
  } else {
    confirmDeleteBtn.disabled = true;
  }
});

// Fechar modal
cancelDeleteBtn.addEventListener('click', () => {
  deleteModal.classList.add('hidden');
  document.body.style.overflow = '';
});

// Fechar ao clicar fora
deleteModal.addEventListener('click', (e) => {
  if (e.target.classList.contains('modal-overlay')) {
    deleteModal.classList.add('hidden');
    document.body.style.overflow = '';
  }
});

// Fechar com ESC
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
    deleteModal.classList.add('hidden');
    document.body.style.overflow = '';
  }
});

    // Atualize a função changeTab no seu arquivo config.php
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
      const activeTab = document.querySelector(`.settings-tab[onclick="changeTab('${tabName}')"]`);
      activeTab.classList.add('active');
      document.getElementById(`${tabName}-tab`).classList.add('active');

      // Centraliza a aba ativa
      if (activeTab) {
        activeTab.scrollIntoView({
          behavior: 'smooth',
          block: 'nearest',
          inline: 'center'
        });
      }
    }
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

    // Modo escuro - Versão melhorada
const darkModeToggle = document.getElementById('darkModeToggle');

// Função para aplicar/remover o tema escuro
function applyDarkMode(isDark) {
    if (isDark) {
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.body.classList.remove('dark-mode');
        document.documentElement.setAttribute('data-theme', 'light');
    }
}

// Verifica preferência salva ou do sistema
const savedTheme = localStorage.getItem('theme');
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
    applyDarkMode(true);
    darkModeToggle.checked = true;
} else {
    applyDarkMode(false);
    darkModeToggle.checked = false;
}

// Event listener para o toggle
darkModeToggle.addEventListener('change', function() {
    if (this.checked) {
        localStorage.setItem('theme', 'dark');
        applyDarkMode(true);
    } else {
        localStorage.setItem('theme', 'light');
        applyDarkMode(false);
    }
    
    // Dispara evento personalizado para outras páginas
    const themeEvent = new CustomEvent('themeChanged', {
        detail: { theme: this.checked ? 'dark' : 'light' }
    });
    window.dispatchEvent(themeEvent);
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

    // Interceptar envio do formulário de solicitação do código
const deleteTokenRequestForm = document.getElementById('deleteTokenRequestForm');
deleteTokenRequestForm?.addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = new FormData(deleteTokenRequestForm);

  try {
    const response = await fetch('enviar_delete.php', {
      method: 'POST',
      body: formData
    });

    if (response.ok) {
      // Mostrar a etapa 2 do modal
      deleteStep1.classList.add('hidden');
      deleteStep2.classList.remove('hidden');
      deleteTokenInput.focus();
    } else {
      const errorText = await response.text();
      if (errorText === 'email_not_found') {
        alert('E-mail não encontrado.');
      } else {
        alert('Erro ao enviar o e-mail. Tente novamente.');
      }
    }
  } catch (error) {
    alert('Erro de conexão. Tente novamente.');
  }
});

  </script>
</body>

</html>
