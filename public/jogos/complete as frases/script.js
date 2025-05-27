const phrases = [
  { full: "A insulina ajuda a controlar o nível de glicose no sangue.", missing: "insulina" },
  { full: "Manter uma alimentação equilibrada é essencial para o controle do diabetes.", missing: "alimentação" },
  { full: "A prática de exercício físico regula o açúcar no sangue.", missing: "exercício" },
  { full: "Beber bastante água é importante para manter a hidratação.", missing: "água" },
  { full: "Verduras e legumes devem estar presentes na dieta.", missing: "Verduras" },
  { full: "Evitar o excesso de carboidratos simples ajuda a manter o equilíbrio.", missing: "carboidratos" },
  { full: "A automonitorização da glicemia é essencial para o controle diário.", missing: "glicemia" },
  { full: "Dormir bem também influencia no controle do diabetes.", missing: "Dormir" },
  { full: "Frutas com baixo índice glicêmico são recomendadas.", missing: "Frutas" },
  { full: "O acompanhamento médico deve ser feito regularmente.", missing: "médico" }
];

let currentIndex = 0;
let score = 0;
let timer;
let timeLeft = 30;

const phraseEl = document.getElementById("phrase");
const inputEl = document.getElementById("userInput");
const timerEl = document.getElementById("timer");
const scoreEl = document.getElementById("score");
const resultEl = document.getElementById("result");

function startGame() {
  showPhrase();
  startTimer();
}

function normalize(text) {
  return text
    .toLowerCase()
    .normalize("NFD") // separa acentos das letras
    .replace(/[\u0300-\u036f]/g, ""); // remove acentos
}

function showPhrase() {
  const phrase = phrases[currentIndex];
  const display = phrase.full.replace(phrase.missing, "_____");
  phraseEl.textContent = display;
  inputEl.value = "";
  resultEl.textContent = "";
}

function startTimer() {
  timeLeft = 30;
  timerEl.textContent = `⏳ Tempo: ${timeLeft}`;
  clearInterval(timer);
  timer = setInterval(() => {
    timeLeft--;
    timerEl.textContent = `⏳ Tempo: ${timeLeft}`;
    if (timeLeft <= 0) {
      clearInterval(timer);
      handleTimeout();
    }
  }, 1000);
}

function checkAnswer() {
  clearInterval(timer);
  const userAnswer = normalize(inputEl.value.trim());
  const correctAnswer = normalize(phrases[currentIndex].missing);

  if (userAnswer === correctAnswer) {
    score += 25;
    resultEl.textContent = "✅ Correto! +25 pontos";
  } else {
    score += 9;
    resultEl.textContent = `❌ Errado. A resposta correta era: "${phrases[currentIndex].missing}". +9 pontos`;
  }

  scoreEl.textContent = `Pontuação: ${score}`;

  setTimeout(() => {
    nextPhrase();
  }, 2000);
}

function handleTimeout() {
  score += 9;
  resultEl.textContent = `⏰ Tempo esgotado! A resposta correta era: "${phrases[currentIndex].missing}". +9 pontos`;
  scoreEl.textContent = `Pontuação: ${score}`;
  setTimeout(() => {
    nextPhrase();
  }, 2000);
}

function nextPhrase() {
  currentIndex++;
  if (currentIndex < phrases.length) {
    showPhrase();
    startTimer();
  } else {
    phraseEl.textContent = "🎉 Fim do jogo!";
    inputEl.style.display = "none";
    document.querySelector("button").style.display = "none";
    timerEl.style.display = "none";
    resultEl.innerHTML = `✅ Pontuação final: <strong>${score}</strong> pontos`;
  }
}

// Novo código para o botão Voltar
document.getElementById('homeButton').addEventListener('click', () => {
  clearInterval(timer); // Para o cronômetro se estiver rodando
  window.location.href = "/public/home.php"; // Redireciona para a página inicial
});

startGame(); // Mantenha esta linha por último