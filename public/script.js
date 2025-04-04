function toggleMenu() {
    const menu = document.getElementById('menu');
    menu.classList.toggle('show');
}

// Fechar o menu ao clicar fora dele
document.addEventListener('click', function(event) {
    const menu = document.getElementById('menu');
    const profileBtn = document.querySelector('.profile-btn');

    if (!menu.contains(event.target) && !profileBtn.contains(event.target)) {
        menu.classList.remove('show');
    }
});

// Lista de funções do site (adicione mais itens conforme necessário)
const siteFunctions = [
    { name: "Caça Palavras", link: "#" },
    { name: "Aprenda a Comer", link: "#" },
    { name: "Configurações", link: "config.html" },
    { name: "Perfil", link: "perfil.php" },
    { name: "Sobre Nós", link: "sobre.html" },
    { name: "Sair", link: "#" }
];

function searchFunction() {
    let input = document.getElementById("searchBar").value.toLowerCase();
    let resultsDiv = document.getElementById("searchResults");

    // Limpa os resultados anteriores
    resultsDiv.innerHTML = "";
    resultsDiv.style.display = "none"; // Oculta os resultados caso a pesquisa esteja vazia

    if (input.length > 0) {
        let filteredResults = siteFunctions.filter(item => item.name.toLowerCase().includes(input));

        if (filteredResults.length > 0) {
            resultsDiv.style.display = "block"; // Exibe os resultados quando há correspondência

            filteredResults.forEach(function(result) {
                let div = document.createElement("div");
                div.classList.add("search-item");
                div.innerHTML = `<a href="${result.link}">${result.name}</a>`;
                resultsDiv.appendChild(div);
            });
        } else {
            resultsDiv.style.display = "block";
            resultsDiv.innerHTML = "<div class='search-item'>Nenhum resultado encontrado</div>";
        }
    }
}