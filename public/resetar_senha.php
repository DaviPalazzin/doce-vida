<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Doce Vida - Redefinir Senha</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    let tempoRestante = 300;
    let timer;

    function iniciarContagemRegressiva() {
      timer = setInterval(function () {
        const minutos = Math.floor(tempoRestante / 60);
        const segundos = tempoRestante % 60;
        document.getElementById("tempoRestante").textContent = `Tempo restante: ${minutos}:${segundos < 10 ? '0' : ''}${segundos}`;
        tempoRestante--;

        if (tempoRestante < 0) {
          clearInterval(timer);
          document.getElementById("tempoRestante").textContent = "O código expirou. Clique para reenviar.";
          document.getElementById("reenviarCodigo").disabled = false;
        }
      }, 1000);
    }

    function reenviarCodigo() {
      alert("Código reenviado para seu e-mail.");
      tempoRestante = 300;
      iniciarContagemRegressiva();
      document.getElementById("reenviarCodigo").disabled = true;
    }
  </script>
</head>
<body class="bg-blue-200 h-screen flex items-center justify-center">
  <div class="flex w-full max-w-4xl">

    <!-- Parte 1: Enviar código -->
    <div class="w-1/2 flex flex-col items-center justify-center bg-white p-8 rounded-lg shadow-lg" id="enviarParte">
      <h1 class="text-3xl font-bold text-blue-900 text-center">Redefinir Senha</h1>
      <p class="text-blue-900 text-sm mt-2 text-center">Digite o seu e-mail para receber o código de redefinição.</p>

      <form method="POST" action="enviar_reset.php" class="mt-8 w-3/4">
        <input name="email" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="E-mail" type="email" required>
        <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">Enviar Código</button>
      </form>
    </div>

    <!-- Parte 2: Redefinir senha -->
    <div class="w-1/2 flex flex-col items-center justify-center bg-white p-8 rounded-lg shadow-lg hidden" id="redefinirParte">
      <h1 class="text-3xl font-bold text-blue-900 text-center">Redefinir Senha</h1>
      <p class="text-blue-900 text-sm mt-2 text-center">Digite o código enviado ao seu e-mail e a nova senha.</p>

      <!-- Mensagem de sucesso (só aparece se sucesso=1) -->
      <p id="mensagemSucesso" class="text-green-600 text-center mt-2 hidden">Código de redefinição enviado com sucesso! Verifique seu e-mail.</p>

      <!-- Contador e botão de reenviar -->
      <div class="text-center mt-4">
        <p id="tempoRestante" class="text-blue-900 text-lg">Tempo restante: 5:00</p>
        <button id="reenviarCodigo" onclick="reenviarCodigo()" class="mt-4 bg-blue-400 text-white py-2 px-4 rounded-full hover:bg-blue-500" disabled>Reenviar Código</button>
      </div>

      <form method="POST" action="processar_reset.php" class="mt-8 w-3/4">
    <!-- Campo oculto para o email -->
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
    <input name="token" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Código do E-mail" type="text" required>
    <input name="nova_senha" class="w-full px-4 py-2 mb-4 border border-blue-900 rounded-full text-blue-900 focus:outline-none" placeholder="Nova Senha" type="password" required>
    <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded-full hover:bg-blue-500">Atualizar Senha</button>
</form>

    </div>

  </div>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('sucesso') === '1') {
      document.getElementById("enviarParte").classList.add("hidden");
      document.getElementById("redefinirParte").classList.remove("hidden");
      document.getElementById("mensagemSucesso").classList.remove("hidden");
      iniciarContagemRegressiva();
    }
  </script>
</body>
</html>
