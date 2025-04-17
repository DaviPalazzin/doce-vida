<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Doce Vida - Redefinir Senha</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-200 h-screen flex items-center justify-center">

  <!-- Mensagem flutuante -->
  <div id="mensagemSucesso" class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white text-sm px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-500 pointer-events-none z-50">
    Código de redefinição enviado com sucesso! Verifique seu e-mail.
  </div>

  <div class="flex w-full max-w-4xl bg-white rounded-lg shadow-lg">

    <!-- LADO ESQUERDO - Formulário -->
    <div class="w-full md:w-1/2 flex flex-col items-center justify-center p-8">

      <!-- Logo -->
      <div class="text-center mb-4">
        <img alt="Logo Doce Vida" class="h-16 w-30 mx-auto" src="img/fita doce vida.png">
      </div>

      <!-- Parte 1: Enviar código -->
      <div id="enviarParte">
        <h2 class="text-2xl font-bold text-blue-900 text-center mb-4">Redefinir Senha</h2>
        <p class="text-blue-900 text-sm text-center">Digite o seu e-mail para receber o código de redefinição.</p>

        <form method="POST" action="enviar_reset.php" class="w-full max-w-md mt-8">
          <input name="email" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="E-mail" type="email" required>
          <?php if (isset($_GET['erro'])): ?>
            <p class="text-red-600 text-sm mb-4 text-center">E-mail não cadastrado. Tente novamente com um e-mail válido.</p>
          <?php endif; ?>
          <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">Enviar Código</button>
        </form>
      </div>
<!-- Parte 2: Redefinir senha -->
<div id="redefinirParte" class="hidden">
  <h2 class="text-2xl font-bold text-blue-900 text-center mb-4">Redefinir Senha</h2>
  <p class="text-blue-900 text-sm text-center mb-8">Digite o código enviado ao seu e-mail e a nova senha.</p> <!-- Adicionando a margem aqui -->

<!-- Cronômetro e botão -->
<div class="relative w-full max-w-md mt-2 mb-[-8px]">
  <p id="tempoRestante" class="absolute left-0 -top-6 text-xs text-blue-900">expira em: 15:00</p> <!-- Alterado aqui para "expira em:" -->
  <button id="reenviarCodigo" onclick="reenviarCodigo()" class="absolute right-0 -top-6 text-xs text-blue-900 underline hover:text-blue-700" disabled>Reenviar código</button>
</div>



  <form method="POST" action="processar_reset.php" class="w-full max-w-md mt-8">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
    <input name="token" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Código do E-mail" type="text" required>
    <input name="nova_senha" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Nova Senha" type="password" required>
    <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">Atualizar Senha</button>
  </form>
</div>


      <a href="index.php" class="text-blue-900 text-sm underline block text-center mt-4">Voltar para o login</a>
    </div>

    <!-- LADO DIREITO - Ilustração -->
    <div class="hidden md:flex w-1/2 items-center justify-center">
      <div class="relative">
        <div class="bg-blue-300 rounded-full h-64 w-64 flex items-center justify-center">
          <div class="bg-blue-400 rounded-full h-48 w-48 flex items-center justify-center">
            <img alt="Imagem ilustrativa" class="h-128 w-64" src="img/login doce vida.png">
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
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

    // Verifica se veio com ?sucesso=1 na URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('sucesso') === '1') {
      document.getElementById("enviarParte").classList.add("hidden");
      document.getElementById("redefinirParte").classList.remove("hidden");
      mostrarMensagemFlutuante();
      iniciarExpiracao();
      iniciarCooldownReenvio();
    }
  </script>

</body>
</html>
