<?php
// Include authentification (session initialisé)
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme Solidaire</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'canope-green': '#3A6B56',
                        'canope-olive': '#4A5D3B',
                        'canope-light': '#E0E8E3',
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'serif'],
                        'body': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'slide-up': 'slideUp 1s ease-out forwards',
                    },
                    keyframes: {
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(80px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
    </style>
    <!-- CSS Custom (Chargé après Tailwind pour permettre des remplacements) -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<div id="loader" class="fixed inset-0 bg-white/80 flex items-center justify-center z-50">
  <div class="relative">
      <div class="w-20 h-20 border-lime-200 border-2 rounded-full"></div>
      <div class="w-20 h-20 border-lime-700 border-t-2 animate-spin rounded-full absolute left-0 top-0"></div>
  </div>
</div>
 <script>
    // Cacher le loader après le chargement complet de la page
    window.addEventListener('load', () => {
        document.getElementById('loader').style.display = 'none';
    });

    // Afficher le loader (ex: bouton ou action)
    function showLoader() {
        document.getElementById('loader').style.display = 'flex';
    }
  </script>
<body class="min-h-screen flex flex-col bg-white text-gray-800">
    <header class="bg-white py-4 sticky top-0 z-40 shadow-sm">
        <nav class="max-w-6xl mx-auto px-5 flex justify-between items-center">
            <!-- Logo -->
            <a href="index.php" class="font-bold text-canope-green text-xl">
                <img src="assets/img/logo.jpg" alt="Réseau Canopé Logo" class="h-12">
            </a>
            
            <ul class="flex items-center gap-8 list-none m-0 p-0">
                <li><a href="index.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Accueil</a></li>
                <li><a href="donations.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Liste de dotations</a></li>
                <li><a href="contact.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Contact</a></li>
                <li><a href="demande.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Suivre ma demande</a></li>
                <!-- Selection List Icon -->
                <li>
                    <a href="selection.php" class="relative group" title="Ma sélection">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-700 hover:text-canope-green transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-canope-green text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold hidden">0</span>
                    </a>
                </li>
                <li> <a href="favori.php" class="relative group" title="Mes Favoris">
                    <!-- Checkbox cachée -->

                    <input type="button" class="peer absolute opacity-0 w-0 h-0" />

                    <!-- Icône étoile -->
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    class="w-8 h-8 fill-gray-500 transition-all duration-300 
                    hover:scale-110 
                    peer-checked:fill-yellow-400"
                    >
    <path
      d="M9.362,9.158c0,0-3.16,0.35-5.268,0.584c-0.19,0.023-0.358,0.15-0.421,0.343s0,0.394,0.14,0.521
      c1.566,1.429,3.919,3.569,3.919,3.569c-0.002,0-0.646,3.113-1.074,5.19c-0.036,0.188,0.032,0.387,0.196,0.506
      c0.163,0.119,0.373,0.121,0.538,0.028c1.844-1.048,4.606-2.624,4.606-2.624s2.763,1.576,4.604,2.625
      c0.168,0.092,0.378,0.09,0.541-0.029c0.164-0.119,0.232-0.318,0.195-0.505c-0.428-2.078-1.071-5.191-1.071-5.191
      s2.353-2.14,3.919-3.566c0.14-0.131,0.202-0.332,0.14-0.524s-0.23-0.319-0.42-0.341
      c-2.108-0.236-5.269-0.586-5.269-0.586s-1.31-2.898-2.183-4.83
      c-0.082-0.173-0.254-0.294-0.456-0.294s-0.375,0.122-0.453,0.294
      C10.671,6.26,9.362,9.158,9.362,9.158z"
    />
            </svg>
</li>
                <?php if (isLoggedIn()): ?>
                <!-- Logged in: Montre le menu utilisateurs -->
                <li class="relative group">
                    <button class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-canope-green transition-colors">
                        <div class="w-8 h-8 bg-canope-green/10 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <!-- Menu utilisateur (dropdown) -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="profile.php" class="block p-3 border-b border-gray-100 hover:bg-gray-50 rounded-t-xl transition-colors">
                            <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($_SESSION['user_email']) ?></p>
                            <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($_SESSION['user_etablissement']) ?></p>
                        </a>
                        <a href="profile.php" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Mon profil
                        </a>
                        <a href="logout.php" class="flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Déconnexion
                        </a>
                    </div>
                </li>
                <?php else: ?>
                <!-- Not logged in: Montre le bouton de connexion -->
                <li>
                    <a href="login.php" class="flex justify-center gap-2 items-center shadow-lg text-sm bg-canope-green backdrop-blur-md font-semibold relative z-10 px-4 py-2 overflow-hidden border-2 border-canope-green rounded-full group no-underline text-white hover:bg-gradient-to-r hover:from-canope-green hover:to-[#4a8a70] hover:border-[#4a8a70] transition-all duration-300">
                        Espace Admin
                        <svg class="w-6 h-6 justify-end group-hover:rotate-90 ease-linear duration-300 rounded-full border border-white/50 p-1 rotate-45" viewBox="0 0 16 19" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 18C7 18.5523 7.44772 19 8 19C8.55228 19 9 18.5523 9 18H7ZM8.70711 0.292893C8.31658 -0.0976311 7.68342 -0.0976311 7.29289 0.292893L0.928932 6.65685C0.538408 7.04738 0.538408 7.68054 0.928932 8.07107C1.31946 8.46159 1.95262 8.46159 2.34315 8.07107L8 2.41421L13.6569 8.07107C14.0474 8.46159 14.6805 8.46159 15.0711 8.07107C15.4616 7.68054 15.4616 7.04738 15.0711 6.65685L8.70711 0.292893ZM9 18L9 1H7L7 18H9Z" class="fill-white"></path>
                        </svg>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main class="flex-1">