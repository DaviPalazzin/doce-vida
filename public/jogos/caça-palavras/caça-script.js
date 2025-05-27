const wordsList = [
  ["insulina", "glicose", "exercicio", "dieta", "caminhada", "medicacao", "frutas", "verduras", "controle"],
  ["salada", "atividade", "agua", "saudavel", "cuidado", "peso", "controle", "vitamina", "insulina"],
];

let currentLevel = 0;
let currentWords = wordsList[currentLevel];
let gridSize = 10;
let grid = [];
let foundWords = [];
let currentSelection = [];
let isDragging = false;
let seconds = 0;
let timerInterval;
const gridElement = document.getElementById('grid');
const wordListElement = document.getElementById('word-list');
const messageElement = document.getElementById('message');
const restartButton = document.getElementById('restart-button');
const homeButton = document.getElementById('home-button');
const nextGameButton = document.getElementById('next-game-button');

let placar = 0;

function resetPlacar() {
  placar = 0;
  document.querySelector("#placarVal").innerHTML = placar;
}

function plusPlacar() {
  placar += 10;
  document.querySelector("#placarVal").innerHTML = placar;
  console.log(placar)
}

let wordColors = [
  "#f94144", "#f3722c", "#f8961e", "#f9c74f", "#90be6d", "#43aa8b", "#577590",
  "#277da1", "#9b5de5", "#f15bb5", "#f1c40f"
];

// Event Listeners
restartButton.addEventListener('click', () => initGame(currentWords));
nextGameButton.addEventListener('click', nextLevel);
homeButton.addEventListener('click', () => {
  clearInterval(timerInterval);
  window.location.href = "/public/home.php"; // Redireciona para a página inicial
});

// Função para iniciar o cronômetro
function startTimer() {
  clearInterval(timerInterval);
  seconds = 0;
  timerInterval = setInterval(() => {
      seconds++;
      const min = String(Math.floor(seconds / 60)).padStart(2, '0');
      const sec = String(seconds % 60).padStart(2, '0');
      document.getElementById('time').textContent = `${min}:${sec}`;
      if (seconds >= 600) {
          clearInterval(timerInterval);
          messageElement.textContent = "⏰ Tempo esgotado! Tente novamente.";
      }
  }, 1000);
}

// Função para gerar uma letra aleatória
function getRandomLetter() {
  const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  return letters[Math.floor(Math.random() * letters.length)];
}

// Função para gerar uma grade vazia
function generateEmptyGrid() {
  grid = Array.from({ length: gridSize }, () => Array(gridSize).fill(''));
}

// Função para verificar se uma palavra pode ser colocada
function canPlaceWord(word, row, col, dx, dy) {
  const endRow = row + (word.length - 1) * dy;
  const endCol = col + (word.length - 1) * dx;
  
  if (endRow < 0 || endRow >= gridSize || endCol < 0 || endCol >= gridSize) {
      return false;
  }

  for (let i = 0; i < word.length; i++) {
      const r = row + i * dy;
      const c = col + i * dx;
      if (grid[r][c] !== '' && grid[r][c] !== word[i].toUpperCase()) {
          return false;
      }
  }
  return true;
}

// Função para preencher as células vazias com letras aleatórias
function fillEmptyCells() {
  for (let r = 0; r < gridSize; r++) {
      for (let c = 0; c < gridSize; c++) {
          if (grid[r][c] === '') grid[r][c] = getRandomLetter();
      }
  }
}

// Função para desenhar a grade
function drawGrid() {
  gridElement.innerHTML = '';
  for (let r = 0; r < gridSize; r++) {
      for (let c = 0; c < gridSize; c++) {
          const cell = document.createElement('div');
          cell.className = 'cell';
          cell.textContent = grid[r][c];
          cell.dataset.row = r;
          cell.dataset.col = c;

          cell.addEventListener('mousedown', startSelection);
          cell.addEventListener('mouseenter', continueSelection);
          cell.addEventListener('mouseup', endSelection);

          gridElement.appendChild(cell);
      }
  }
}

// Função para desenhar a lista de palavras
function drawWordList() {
  wordListElement.innerHTML = 'Palavras: ' + currentWords.map((w, i) =>
      `<span id="word-${w}" style="color:${wordColors[i % wordColors.length]}">${w}</span>`).join(' ');
}

// Contador de palavras por direção (global ou no escopo do gerador)
const directionCounts = {
  horizontal: 40,   // dx: 1, dy: 0
  vertical: 30,     // dx: 0, dy: 1
  diagonalDown: 0, // dx: 1, dy: 1
  diagonalUp: 0    // dx: 1, dy: -1
};

function placeWord(word) {
  word = word.toUpperCase();

  // Direções com pesos baseados no uso (quanto mais usada, menor o peso)
  const directions = [
    { dx: 1, dy: 0, weight: 1 / (1 + directionCounts.horizontal), type: 'horizontal' },
    { dx: 0, dy: 1, weight: 1 / (1 + directionCounts.vertical), type: 'vertical' },
    { dx: 1, dy: 1, weight: 1 / (1 + directionCounts.diagonalDown), type: 'diagonalDown' },
    { dx: 1, dy: -1, weight: 1 / (1 + directionCounts.diagonalUp), type: 'diagonalUp' }
  ];

  // Ordena direções pelo peso (maior peso primeiro)
  const sortedDirections = [...directions].sort((a, b) => b.weight - a.weight);

  for (const dir of sortedDirections) {
    for (let attempts = 0; attempts < 25; attempts++) {
      const row = Math.floor(Math.random() * gridSize);
      const col = Math.floor(Math.random() * gridSize);

      if (canPlaceWord(word, row, col, dir.dx, dir.dy)) {
        for (let i = 0; i < word.length; i++) {
          const r = row + i * dir.dy;
          const c = col + i * dir.dx;
          grid[r][c] = word[i];
        }
        // Atualiza o contador da direção utilizada
        directionCounts[dir.type]++;
        return true;
      }
    }
  }
  return false;
}

// Função para iniciar o jogo
function initGame(words) {
  let success = false;
  resetPlacar();
  
  for (let attempts = 0; attempts < 50 && !success; attempts++) {
      generateEmptyGrid();
      let placedWords = 0;

      const shuffledWords = shuffleArray([...words]);

      for (let word of shuffledWords) {
          if (placeWord(word)) {
              placedWords++;
          } else {
              break;
          }
      }

      if (placedWords === words.length) {
          success = true;
      }
  }

  if (!success) {
      messageElement.textContent = "Erro ao gerar o jogo. Tente recarregar a página.";
      console.error("Falha ao gerar o jogo após várias tentativas.");
      return;
  }

  foundWords = [];
  currentSelection = [];
  isDragging = false;
  fillEmptyCells();
  drawGrid();
  drawWordList();
  startTimer();
  messageElement.textContent = "Encontre todas as palavras!";
}

// Função para embaralhar o array
function shuffleArray(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
  }
  return arr;
}

// Funções de seleção
function startSelection(e) {
  isDragging = true;
  const cell = e.target;
  const row = parseInt(cell.dataset.row);
  const col = parseInt(cell.dataset.col);
  currentSelection = [{ cell, row, col }];
  cell.classList.add('selected');
}

function continueSelection(e) {
  if (!isDragging) return;
  const cell = e.target;
  if (!cell.classList.contains('cell')) return;

  const row = parseInt(cell.dataset.row);
  const col = parseInt(cell.dataset.col);

  const lastSelected = currentSelection[currentSelection.length - 1];
  if (!lastSelected) return;

  // Verifica se é adjacente
  const rowDiff = Math.abs(lastSelected.row - row);
  const colDiff = Math.abs(lastSelected.col - col);
  if (rowDiff > 1 || colDiff > 1 || (rowDiff === 0 && colDiff === 0)) return;

  // Verifica se está na mesma linha/coluna/diagonal
  const firstSelected = currentSelection[0];
  const direction = {
      row: row - firstSelected.row,
      col: col - firstSelected.col
  };

  // Normaliza a direção para manter consistência
  const normalizeDirection = (num) => num !== 0 ? num / Math.abs(num) : 0;
  const normDirRow = normalizeDirection(direction.row);
  const normDirCol = normalizeDirection(direction.col);

  // Verifica se a nova célula segue a mesma direção
  const isValid = currentSelection.every((item, index) => {
      const expectedRow = firstSelected.row + normDirRow * index;
      const expectedCol = firstSelected.col + normDirCol * index;
      return item.row === expectedRow && item.col === expectedCol;
  });

  if (!isValid) {
      const newDirection = {
          row: row - lastSelected.row,
          col: col - lastSelected.col
      };

      // Permite apenas mudanças de direção se for a segunda célula
      if (currentSelection.length === 1) {
          currentSelection.push({ cell, row, col });
          cell.classList.add('selected');
      }
      return;
  }

  const alreadySelected = currentSelection.some(item => item.row === row && item.col === col);
  if (!alreadySelected) {
      currentSelection.push({ cell, row, col });
      cell.classList.add('selected');
  }
}

function endSelection() {
  isDragging = false;
  if (currentSelection.length > 1) {
      const selectedWord = currentSelection.map(item => item.cell.textContent).join('');
      checkWord(selectedWord);
  }
  currentSelection.forEach(item => item.cell.classList.remove('selected'));
  currentSelection = [];
}

// Função para verificar a palavra selecionada
function checkWord(selectedWord) {
  const normalizedSelectedWord = selectedWord.toLowerCase();
  const reversedWord = normalizedSelectedWord.split('').reverse().join('');
  
  let foundWord = null;
  if (currentWords.includes(normalizedSelectedWord) && !foundWords.includes(normalizedSelectedWord)) {
      foundWord = normalizedSelectedWord;
  } else if (currentWords.includes(reversedWord) && !foundWords.includes(reversedWord)) {
      foundWord = reversedWord;
  }

  if (foundWord) {
      plusPlacar()
      foundWords.push(foundWord);
      messageElement.textContent = `"${foundWord.toUpperCase()}" encontrado!`;
      
      const colorIndex = currentWords.indexOf(foundWord);
      const color = wordColors[colorIndex % wordColors.length];
      
      currentSelection.forEach(item => {
          item.cell.classList.add('found');
          item.cell.style.backgroundColor = color;
      });
      
      const wordElement = document.getElementById(`word-${foundWord}`);
      if (wordElement) {
          wordElement.classList.add('found');
      }

      if (foundWords.length === currentWords.length) {
          messageElement.textContent = "Parabéns! Você encontrou todas as palavras!";
          clearInterval(timerInterval);
          nextGameButton.disabled = false;
      }
  } else if (currentSelection.length > 1) {
      messageElement.textContent = "Tente novamente!";
      setTimeout(() => messageElement.textContent = "", 1500);
  }
}

// Função para avançar para o próximo nível
function nextLevel() {
  currentLevel = (currentLevel + 1) % wordsList.length;
  currentWords = wordsList[currentLevel];
  nextGameButton.disabled = true;
  initGame(currentWords);
}

// Iniciar o jogo
initGame(currentWords);
nextGameButton.disabled = true;