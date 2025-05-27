const questions = [
  {
    type: "text",
    question: "Qual é o hormônio responsável por controlar a glicose no sangue?",
    answer: "insulina"
  },
  {
    type: "multiple",
    question: "Qual destes alimentos é mais saudável para diabéticos?",
    options: ["Refrigerante", "Arroz branco", "Maçã", "Bolo de chocolate"],
    answer: "Maçã"
  },
  {
    type: "text",
    question: "Qual órgão produz a insulina?",
    answer: "pâncreas"
  },
  {
    type: "multiple",
    question: "Qual atividade física é recomendada para controle do diabetes?",
    options: ["Ver TV", "Caminhada", "Dormir", "Jogar videogame"],
    answer: "Caminhada"
  },
  {
    type: "multiple",
    question: "Qual é o tempo ideal para medir a glicemia após uma refeição?",
    options: ["1 hora", "2 horas", "4 horas", "10 minutos"],
    answer: "2 horas"
  },
  {
    type: "text",
    question: "Como se chama a taxa de açúcar no sangue?",
    answer: "glicemia"
  },
  {
    type: "multiple",
    question: "O que deve ser evitado por diabéticos?",
    options: ["Água", "Legumes", "Açúcar refinado", "Frutas"],
    answer: "Açúcar refinado"
  },
  {
    type: "text",
    question: "Qual profissional ajuda no controle alimentar do diabetes?",
    answer: "nutricionista"
  },
  {
    type: "multiple",
    question: "Qual destes sintomas pode indicar diabetes?",
    options: ["Visão turva", "Coceira no ouvido", "Dores musculares", "Falta de ar"],
    answer: "Visão turva"
  },
  {
    type: "text",
    question: "Qual é a faixa ideal da glicemia em jejum (em mg/dL)?",
    answer: "70 a 99"
  }
];

let currentQuestion = 0;
let score = 0;
let timer;
let timeLeft = 20;

const questionEl = document.getElementById("question");
const optionsEl = document.getElementById("options");
const textInput = document.getElementById("text-answer");
const nextBtn = document.getElementById("next-btn");
const timerEl = document.getElementById("timer");
const resultEl = document.getElementById("result");

function normalize(str) {
  return str.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function startTimer() {
  timeLeft = 20;
  timerEl.textContent = `Tempo restante: ${timeLeft}s`;
  timer = setInterval(() => {
    timeLeft--;
    timerEl.textContent = `Tempo restante: ${timeLeft}s`;
    if (timeLeft <= 0) {
      clearInterval(timer);
      handleAnswer(null); // tempo esgotado
    }
  }, 1000);
}

function showQuestion() {
  const q = questions[currentQuestion];
  questionEl.textContent = q.question;
  optionsEl.innerHTML = "";
  textInput.style.display = "none";
  nextBtn.style.display = "none";

  if (q.type === "multiple") {
    q.options.forEach(opt => {
      const btn = document.createElement("div");
      btn.className = "option";
      btn.textContent = opt;
      btn.onclick = () => handleAnswer(opt);
      optionsEl.appendChild(btn);
    });
  } else {
    textInput.value = "";
    textInput.style.display = "block";
    nextBtn.style.display = "inline-block";
  }

  startTimer();
}

nextBtn.onclick = () => {
  const answer = textInput.value.trim();
  handleAnswer(answer);
};

function handleAnswer(answer) {
  clearInterval(timer);
  const correct = questions[currentQuestion].answer;
  const normalizedAnswer = normalize(answer || "");
  const normalizedCorrect = normalize(correct);

  if (normalizedAnswer === normalizedCorrect) {
    score += 50;
  } else {
    score += 9;
  }

  currentQuestion++;
  if (currentQuestion < questions.length) {
    showQuestion();
  } else {
    showResult();
  }
}

function showResult() {
  questionEl.textContent = "Quiz finalizado!";
  optionsEl.innerHTML = "";
  textInput.style.display = "none";
  nextBtn.style.display = "none";
  timerEl.style.display = "none";
  resultEl.innerHTML = `Você marcou <strong>${score}</strong> pontos de 500.`;
}

// Redirecionamento para home.php
document.getElementById('homeButton').addEventListener('click', () => {
  clearInterval(timer); // Para o cronômetro se estiver ativo
  window.location.href = "/public/home.php"; 
});

showQuestion(); // Mantenha esta linha por último