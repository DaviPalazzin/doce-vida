document.addEventListener('DOMContentLoaded', function() {
    // Simulação de dados do usuário
    const userData = {
        name: "João",
        avatar: "avatar-default.png",
        rank: 3,
        points: 1850,
        level: "Prata",
        progress: 75,
        achievements: 12,
        nextLevel: "Ouro",
        pointsNeeded: 250
    };

    // Atualizar informações do usuário
    document.querySelector('.user-profile span').textContent = `Olá, ${userData.name}!`;
    
    // Simulação de dados do ranking
    const rankingData = [
        { id: 1, name: "SuperSaúde", avatar: "avatar1.png", points: 2450, level: "Ouro", achievements: 15 },
        { id: 2, name: "AçúcarControlado", avatar: "avatar2.png", points: 2100, level: "Ouro", achievements: 12 },
        { id: 3, name: "Você", avatar: "avatar-default.png", points: 1850, level: "Prata", achievements: 12 },
        { id: 4, name: "NutriExpert", avatar: "avatar3.png", points: 1600, level: "Prata", achievements: 10 },
        { id: 5, name: "InsulinaPower", avatar: "avatar4.png", points: 1450, level: "Prata", achievements: 8 },
        { id: 6, name: "GlicoseBoa", avatar: "avatar5.png", points: 1300, level: "Prata", achievements: 7 },
        { id: 7, name: "SaúdeTotal", avatar: "avatar6.png", points: 1150, level: "Bronze", achievements: 6 },
        { id: 8, name: "DoceEquilíbrio", avatar: "avatar7.png", points: 950, level: "Bronze", achievements: 5 },
        { id: 9, name: "VidaAtiva", avatar: "avatar8.png", points: 800, level: "Bronze", achievements: 4 },
        { id: 10, name: "BemEstar", avatar: "avatar9.png", points: 650, level: "Bronze", achievements: 3 }
    ];

    // Função para renderizar o ranking
    function renderRanking(data) {
        const rankingList = document.querySelector('.ranking-list');
        
        // Limpar itens existentes (exceto o cabeçalho)
        while (rankingList.children.length > 1) {
            rankingList.removeChild(rankingList.lastChild);
        }
        
        // Adicionar novos itens
        data.forEach((user, index) => {
            const rankingItem = document.createElement('div');
            rankingItem.className = `ranking-item ${index === 0 ? 'first-place' : index === 1 ? 'second-place' : index === 2 ? 'third-place' : ''} ${user.name === 'Você' ? 'highlight' : ''}`;
            
            rankingItem.innerHTML = `
                <div class="rank">${index + 1}º</div>
                <div class="user">
                    <img src="${user.avatar}" alt="Avatar" class="avatar">
                    <span class="username">${user.name}</span>
                </div>
                <div class="points">${user.points.toLocaleString()}</div>
                <div class="level">
                    <span class="level-badge ${user.level.toLowerCase()}">${user.level}</span>
                </div>
                <div class="badges">
                    ${user.achievements >= 10 ? '<i class="fas fa-medal gold"></i>' : user.achievements >= 5 ? '<i class="fas fa-medal silver"></i>' : '<i class="fas fa-medal bronze"></i>'}
                    ${user.achievements >= 15 ? '<i class="fas fa-star"></i>' : ''}
                </div>
            `;
            
            rankingList.appendChild(rankingItem);
        });
    }

    // Renderizar ranking inicial
    renderRanking(rankingData);

    // Filtros
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Simular filtragem
            let filteredData = [...rankingData];
            
            if (this.textContent === "Semanal") {
                filteredData = filteredData.map(user => ({
                    ...user,
                    points: Math.floor(user.points * 0.3)
                })).sort((a, b) => b.points - a.points);
            } else if (this.textContent === "Mensal") {
                filteredData = filteredData.map(user => ({
                    ...user,
                    points: Math.floor(user.points * 0.7)
                })).sort((a, b) => b.points - a.points);
            }
            
            renderRanking(filteredData);
        });
    });

    // Filtro por idade
    document.querySelector('.age-filter').addEventListener('change', function() {
        // Simular filtragem por idade (não implementado completamente pois precisaria de mais dados)
        console.log(`Filtrar por: ${this.value}`);
    });

    // Dicas de saúde rotativas
    const healthTips = [
        "Sabia que praticar 30 minutos de exercícios físicos por dia pode ajudar a controlar os níveis de glicose no sangue? Tente incluir uma caminhada na sua rotina!",
        "Alimentos ricos em fibras, como frutas, verduras e grãos integrais, ajudam a controlar o açúcar no sangue. Inclua-os em suas refeições!",
        "Beber água regularmente ajuda os rins a eliminarem o excesso de açúcar através da urina. Mantenha-se hidratado!",
        "Monitorar regularmente seus níveis de glicose ajuda a entender como seu corpo reage a diferentes alimentos e atividades.",
        "Dormir bem é essencial para o controle da glicose. Procure dormir 7-8 horas por noite."
    ];

    function rotateHealthTip() {
        const randomTip = healthTips[Math.floor(Math.random() * healthTips.length)];
        document.querySelector('.tip-content p').textContent = randomTip;
    }

    // Rotacionar dica a cada 10 segundos (para demonstração)
    rotateHealthTip();
    setInterval(rotateHealthTip, 10000);

    // Simular atualização de pontos em tempo real (para demonstração)
    setInterval(() => {
        const pointsElement = document.querySelector('.user-highlight .stat-value');
        let currentPoints = parseInt(pointsElement.textContent.replace(/,/g, ''));
        
        // Adicionar pontos aleatoriamente (simulação)
        const pointsToAdd = Math.floor(Math.random() * 5);
        currentPoints += pointsToAdd;
        
        pointsElement.textContent = currentPoints.toLocaleString();
        
        // Atualizar progresso
        const progress = Math.min(100, userData.progress + (pointsToAdd / userData.pointsNeeded * 25));
        document.querySelector('.progress-bar').style.width = `${progress}%`;
        document.querySelector('.progress-container span').textContent = `${Math.floor(progress)}% para o próximo nível`;
    }, 5000);
});