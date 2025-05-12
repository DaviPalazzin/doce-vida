<?php
$pageTitle = "Doce Vida - Ranking";
require './partials/header.php';
require './partials/menu.php';
?>
<link rel="stylesheet" href="css/ranking.css">
<main class="ranking-container">
    <section class="ranking-header">
        <h2><i class="fas fa-trophy"></i> Ranking dos Protetores da Saúde</h2>
        <p>Veja sua posição e acompanhe seu progresso na jornada de aprendizado sobre diabetes!</p>

        <div class="user-highlight">
            <div class="user-position">
                <span class="rank-badge">#3</span>
                <img src="img/avatar.jpg" alt="Seu avatar" class="user-avatar">
                <div class="user-info">
                    <h3>Você</h3>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: 75%"></div>
                        <span>75% para o próximo nível</span>
                    </div>
                </div>
                <div class="user-stats">
                    <div class="stat">
                        <span class="stat-value">1,850</span>
                        <span class="stat-label">Pontos</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">12</span>
                        <span class="stat-label">Conquistas</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ranking-filters">
        <div class="filter-group">
            <button class="filter-btn active">Geral</button>
            <button class="filter-btn">Semanal</button>
            <button class="filter-btn">Mensal</button>
        </div>
        <div class="filter-group">
            <select class="age-filter">
                <option value="all">Todas as idades</option>
                <option value="kids">Crianças (6-12)</option>
                <option value="teens">Adolescentes (13-19)</option>
                <option value="adults">Adultos (20+)</option>
            </select>
        </div>
    </section>

    <section class="ranking-list">
        <div class="ranking-list-header">
            <span>Posição</span>
            <span>Usuário</span>
            <span>Pontos</span>
            <span>Nível</span>
            <span>Conquistas</span>
        </div>

        <div class="ranking-item first-place">
            <div class="rank">1º</div>
            <div class="user">
                <img src="img/avatar.jpg" alt="Avatar" class="avatar">
                <span class="username">SuperSaúde</span>
            </div>
            <div class="points">2,450</div>
            <div class="level">
                <span class="level-badge">Ouro</span>
            </div>
            <div class="badges">
                <i class="fas fa-medal gold"></i>
                <i class="fas fa-medal silver"></i>
                <i class="fas fa-medal bronze"></i>
            </div>
        </div>

        <div class="ranking-item second-place">
            <div class="rank">2º</div>
            <div class="user">
                <img src="img/avatar.jpg" alt="Avatar" class="avatar">
                <span class="username">AçúcarControlado</span>
            </div>
            <div class="points">2,100</div>
            <div class="level">
                <span class="level-badge">Ouro</span>
            </div>
            <div class="badges">
                <i class="fas fa-medal gold"></i>
                <i class="fas fa-medal silver"></i>
            </div>
        </div>

        <div class="ranking-item third-place highlight">
            <div class="rank">3º</div>
            <div class="user">
                <img src="img/avatar.jpg" alt="Avatar" class="avatar">
                <span class="username">Você</span>
            </div>
            <div class="points">1,850</div>
            <div class="level">
                <span class="level-badge">Prata</span>
            </div>
            <div class="badges">
                <i class="fas fa-medal gold"></i>
                <i class="fas fa-star"></i>
            </div>
        </div>

        <div class="ranking-item">
            <div class="rank">4º</div>
            <div class="user">
                <img src="img/avatar.jpg" alt="Avatar" class="avatar">
                <span class="username">NutriExpert</span>
            </div>
            <div class="points">1,600</div>
            <div class="level">
                <span class="level-badge">Prata</span>
            </div>
            <div class="badges">
                <i class="fas fa-medal silver"></i>
                <i class="fas fa-star"></i>
            </div>
        </div>

        <div class="ranking-item">
            <div class="rank">5º</div>
            <div class="user">
                <img src="img/avatar.jpg" alt="Avatar" class="avatar">
                <span class="username">InsulinaPower</span>
            </div>
            <div class="points">1,450</div>
            <div class="level">
                <span class="level-badge">Prata</span>
            </div>
            <div class="badges">
                <i class="fas fa-medal silver"></i>
            </div>
        </div>
    </section>

    <section class="health-tip">
        <div class="tip-container">
            <i class="fas fa-lightbulb"></i>
            <div class="tip-content">
                <h3>Dica de Saúde</h3>
                <p>Sabia que praticar 30 minutos de exercícios físicos por dia pode ajudar a controlar os níveis de glicose no sangue? Tente incluir uma caminhada na sua rotina!</p>
            </div>
        </div>
    </section>
    <script src="./ranking.js"></script>
</main>

<?php
require './partials/footer.php';
?>
