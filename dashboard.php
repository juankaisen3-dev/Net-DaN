<?php
include_once 'config/config.php';
include_once 'config/database.php';
include_once 'controllers/AuthController.php';

$database = new Database();
$db = $database->getConnection();
$auth = new AuthController($db);

if(!$auth->isLoggedIn()) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetVerse - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --netflix-red: #e50914;
            --netflix-dark: #141414;
            --netflix-gray: #2c2c2c;
        }
        
        body {
            background: var(--netflix-dark);
            color: white;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, transparent 100%);
            padding: 20px 50px;
        }
        
        .video-card {
            transition: transform 0.3s ease;
            border: none;
            background: var(--netflix-gray);
        }
        
        .video-card:hover {
            transform: scale(1.05);
            z-index: 1;
        }
        
        .hero-section {
            height: 70vh;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://via.placeholder.com/1920x1080');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding: 0 50px;
        }
        
        @media (max-width: 768px) {
            .navbar { padding: 15px 20px; }
            .hero-section { height: 50vh; padding: 0 20px; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-danger fw-bold fs-3" href="#">
                <i class="fas fa-play-circle"></i> NetVerse
            </a>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section mt-5">
        <div class="hero-content">
            <h1 class="display-4 fw-bold">Bienvenue sur NetVerse</h1>
            <p class="lead mb-4">Streaming illimité de films et séries</p>
            <button class="btn btn-danger btn-lg me-3"><i class="fas fa-play"></i> Regarder maintenant</button>
            <button class="btn btn-outline-light btn-lg"><i class="fas fa-info-circle"></i> Plus d'infos</button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="container-fluid mt-4">
        <!-- Section Films populaires -->
        <section class="mb-5">
            <h3 class="mb-4">Films populaires</h3>
            <div class="row" id="featured-videos">
                <!-- Les vidéos seront chargées ici via JavaScript -->
            </div>
        </section>

        <!-- Section Séries -->
        <section class="mb-5">
            <h3 class="mb-4">Séries tendances</h3>
            <div class="row" id="series-videos">
                <!-- Les séries seront chargées ici -->
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Charger les vidéos
        async function loadVideos() {
            try {
                const response = await fetch('api/videos.php?featured=true');
                const data = await response.json();
                
                if(data.success) {
                    const container = document.getElementById('featured-videos');
                    container.innerHTML = data.data.map(video => `
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card video-card h-100">
                                <img src="${video.thumbnail}" class="card-img-top" alt="${video.title}">
                                <div class="card-body">
                                    <h6 class="card-title">${video.title}</h6>
                                    <p class="card-text small text-muted">${video.genre} • ${video.release_year}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            } catch(error) {
                console.error('Erreur:', error);
            }
        }

        // Déconnexion
        async function logout() {
            try {
                const response = await fetch('api/auth.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'logout'})
                });
                const data = await response.json();
                if(data.success) {
                    window.location.href = 'index.html';
                }
            } catch(error) {
                console.error('Erreur:', error);
            }
        }

        // Charger les vidéos au démarrage
        document.addEventListener('DOMContentLoaded', loadVideos);
    </script>
</body>
</html>