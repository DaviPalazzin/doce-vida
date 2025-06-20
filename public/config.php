<?php
session_start();

// Redireciona se não estiver logado
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$mostrarVoltar = true;

// Determina a aba ativa
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'conta';

// Conecta ao banco de dados e busca os dados do usuário
include('conexao.php');
$email = $_SESSION['email'];
$query = "SELECT username, data_nascimento, sexo FROM usuarios WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $user_data = mysqli_fetch_assoc($result);
  $username = $user_data['username'];
  $data_nascimento = $user_data['data_nascimento'];
  $sexo = $user_data['sexo'];
} else {
  $username = "Usuário";
  $data_nascimento = '';
  $sexo = '';
}

// Processar POST para salvar dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $active_tab === 'conta' && isset($_POST['salvar_dados'])) {
  $nova_data_nascimento = $_POST['data_nascimento'] ?? null;
  $novo_sexo = $_POST['sexo'] ?? null;
  
  // Atualizar no banco de dados
  $update_query = "UPDATE usuarios SET data_nascimento = ?, sexo = ? WHERE email = ?";
  $stmt = mysqli_prepare($conn, $update_query);
  mysqli_stmt_bind_param($stmt, "sss", $nova_data_nascimento, $novo_sexo, $email);
  
  if (mysqli_stmt_execute($stmt)) {
    $success_message = "Dados atualizados com sucesso!";
    // Atualizar variáveis locais para exibir os novos valores
    $data_nascimento = $nova_data_nascimento;
    $sexo = $novo_sexo;
  } else {
    $error_message = "Erro ao atualizar dados: " . mysqli_error($conn);
  }
  mysqli_stmt_close($stmt);
}

// Processar exclusão de conta
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

// Buscar sessões ativas
$current_session_token = $_COOKIE['session_token'] ?? '';
$query = "SELECT s.* FROM sessoes s 
          JOIN usuarios u ON s.usuario_id = u.id 
          WHERE u.email = '$email' 
          ORDER BY s.data_ultimo_acesso DESC";
$sessoes_result = mysqli_query($conn, $query);
$sessoes_ativas = [];
if ($sessoes_result) {
    $sessoes_ativas = mysqli_fetch_all($sessoes_result, MYSQLI_ASSOC);
}

// Processar logout de sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['encerrar_sessao'])) {
    $sessao_id = $_POST['sessao_id'];
    $query = "DELETE FROM sessoes WHERE id = ? AND usuario_id = (SELECT id FROM usuarios WHERE email = ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $sessao_id, $email);
    mysqli_stmt_execute($stmt);
    
    // Se estiver encerrando a sessão atual, fazer logout
    if (mysqli_affected_rows($conn)) {
        foreach ($sessoes_ativas as $sessao) {
            if ($sessao['id'] == $sessao_id && $sessao['token_sessao'] == $current_session_token) {
                session_destroy();
                setcookie('session_token', '', time() - 3600, '/');
                header("Location: /login.php");
                exit();
            }
        }
        // Recarregar a página para atualizar a lista
        header("Location: ".$_SERVER['PHP_SELF']."?tab=seguranca");
        exit();
    }
}

require './partials/header.php';
require './partials/menu.php';

?>

<!DOCTYPE html>
<html lang="pt-br">

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
  
  <style>
    .fade-out {
      transition: opacity 0.5s ease-out;
      opacity: 0;
    }
    .session-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px;
      border-bottom: 1px solid #e2e8f0;
    }
    .session-item.current-session {
      background-color: #f0fdf4;
      border-left: 3px solid #10b981;
    }
    .current-session-badge {
      background-color: #10b981;
      color: white;
      padding: 2px 6px;
      border-radius: 4px;
      font-size: 12px;
      margin-left: 8px;
    }
    .session-device {
      font-weight: 600;
      margin-bottom: 4px;
    }
    .session-info {
      color: #64748b;
      font-size: 14px;
    }
    .session-logout {
      background-color: #ef4444;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.2s;
    }
    .session-logout:hover {
      background-color: #dc2626;
    }
  </style>
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
        <div class="settings-tab <?= $active_tab === 'seguranca' ? 'active' : '' ?>" onclick="changeTab('seguranca')">
          <i class="fas fa-shield-alt"></i>
          Segurança
        </div>
      </div>

      <!-- Conteúdo das abas -->
      <form method="POST" action="config.php?tab=<?= $active_tab ?>">
        <!-- ABA CONTA -->
        <div id="conta-tab" class="tab-content <?= $active_tab === 'conta' ? 'active' : '' ?>">
          <?php if (isset($success_message)): ?>
            <div id="successMessage" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
              <p><?= $success_message ?></p>
            </div>
          <?php endif; ?>
          
          <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
              <p><?= $error_message ?></p>
            </div>
          <?php endif; ?>

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
                <input type="date" name="data_nascimento" class="form-control" value="<?= htmlspecialchars($data_nascimento) ?>">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Sexo</label>
              <select name="sexo" class="form-control">
                <option value="">Selecione</option>
                <option value="masculino" <?= $sexo === 'masculino' ? 'selected' : '' ?>>Masculino</option>
                <option value="feminino" <?= $sexo === 'feminino' ? 'selected' : '' ?>>Feminino</option>
                <option value="outro" <?= $sexo === 'outro' ? 'selected' : '' ?>>Outro</option>
                <option value="nao_informar" <?= $sexo === 'nao_informar' ? 'selected' : '' ?>>Prefiro não informar</option>
              </select>
            </div>
          </div>

          <div class="flex justify-end">
            <button type="submit" name="salvar_dados" class="btn-save">
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
                <?php if (empty($sessoes_ativas)): ?>
                  <p class="text-gray-500">Nenhuma sessão ativa no momento.</p>
                <?php else: ?>
                  <?php foreach ($sessoes_ativas as $sessao): 
                    $data_acesso = new DateTime($sessao['data_ultimo_acesso']);
                    $agora = new DateTime();
                    $intervalo = $agora->diff($data_acesso);
                    
                    $tempo_atras = '';
                    if ($intervalo->y > 0) {
                        $tempo_atras = $intervalo->y . ' ano' . ($intervalo->y > 1 ? 's' : '') . ' atrás';
                    } elseif ($intervalo->m > 0) {
                        $tempo_atras = $intervalo->m . ' mês' . ($intervalo->m > 1 ? 'es' : '') . ' atrás';
                    } elseif ($intervalo->d > 0) {
                        $tempo_atras = $intervalo->d . ' dia' . ($intervalo->d > 1 ? 's' : '') . ' atrás';
                    } elseif ($intervalo->h > 0) {
                        $tempo_atras = $intervalo->h . ' hora' . ($intervalo->h > 1 ? 's' : '') . ' atrás';
                    } elseif ($intervalo->i > 0) {
                        $tempo_atras = $intervalo->i . ' minuto' . ($intervalo->i > 1 ? 's' : '') . ' atrás';
                    } else {
                        $tempo_atras = 'Agora mesmo';
                    }
                    
                    $localizacao = [];
                    if ($sessao['cidade']) $localizacao[] = $sessao['cidade'];
                    if ($sessao['estado']) $localizacao[] = $sessao['estado'];
                    if ($sessao['pais']) $localizacao[] = $sessao['pais'];
                  ?>
                  <div class="session-item <?= $sessao['token_sessao'] === $current_session_token ? 'current-session' : '' ?>">
                    <div>
                      <p class="session-device">
                        <?= htmlspecialchars($sessao['navegador'] ?? 'Navegador desconhecido') ?> - 
                        <?= htmlspecialchars($sessao['sistema_operacional'] ?? 'Sistema desconhecido') ?>
                        <?php if ($sessao['token_sessao'] === $current_session_token): ?>
                          <span class="current-session-badge">(esta sessão)</span>
                        <?php endif; ?>
                      </p>
                      <p class="session-info">
                        <?= !empty($localizacao) ? implode(', ', $localizacao) . ' • ' : '' ?>
                        <?= $tempo_atras ?>
                      </p>
                    </div>
                    <form method="POST" action="">
                      <input type="hidden" name="sessao_id" value="<?= $sessao['id'] ?>">
                      <button type="submit" name="encerrar_sessao" class="session-logout">
                        Sair
                      </button>
                    </form>
                  </div>
                  <?php endforeach; ?>
                <?php endif; ?>
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
        </div>
      </form>
    </div>
  </main>

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
</script>

</body>
</html>