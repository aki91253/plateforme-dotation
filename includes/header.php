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
    <!-- Custom CSS (loaded after Tailwind to allow overrides) -->
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
    <header class="bg-white py-4">
        <nav class="max-w-6xl mx-auto px-5 flex justify-between items-center">
            <!-- Logo -->
            <a href="index.php" class="font-bold text-canope-green text-xl">
                <img src="assets/img/logo.jpg" alt="Réseau Canopé Logo" class="h-12">
            </a>
            
            <ul class="flex items-center gap-8 list-none m-0 p-0">
                <li><a href="index.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Accueil</a></li>
                <li><a href="donations.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Liste de dotations</a></li>
                <li><a href="contact.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Contact</a></li>
                <!-- Selection List Icon -->
                <li>
                    <a href="selection.php" class="relative group" title="Ma sélection">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-700 hover:text-canope-green transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-canope-green text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold hidden">0</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex justify-center gap-2 items-center shadow-xl text-sm bg-gray-50 backdrop-blur-md font-semibold isolation-auto border-gray-50 before:absolute before:w-full before:transition-all before:duration-700 before:hover:w-full before:-left-full before:hover:left-0 before:rounded-full before:bg-canope-green hover:text-gray-50 before:-z-10 before:aspect-square before:hover:scale-150 before:hover:duration-700 relative z-10 px-4 py-2 overflow-hidden border-2 rounded-full group no-underline text-gray-800">
                        Connexion
                        <svg class="w-6 h-6 justify-end group-hover:rotate-90 group-hover:bg-gray-50 text-gray-50 ease-linear duration-300 rounded-full border border-gray-700 group-hover:border-none p-1 rotate-45" viewBox="0 0 16 19" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 18C7 18.5523 7.44772 19 8 19C8.55228 19 9 18.5523 9 18H7ZM8.70711 0.292893C8.31658 -0.0976311 7.68342 -0.0976311 7.29289 0.292893L0.928932 6.65685C0.538408 7.04738 0.538408 7.68054 0.928932 8.07107C1.31946 8.46159 1.95262 8.46159 2.34315 8.07107L8 2.41421L13.6569 8.07107C14.0474 8.46159 14.6805 8.46159 15.0711 8.07107C15.4616 7.68054 15.4616 7.04738 15.0711 6.65685L8.70711 0.292893ZM9 18L9 1H7L7 18H9Z" class="fill-gray-800 group-hover:fill-gray-800"></path>
                        </svg>
                    </a>
                    <!-- Bouton Scroll To Top -->
<button 
  id="scrollToTopBtn"
  class="fixed right-4 bottom-4 z-50 w-16 h-16 rounded-full bg-green-600 text-white flex items-center justify-center shadow-lg hover:bg-green-700 active:scale-90 transition-all duration-300"
  title="Remonter en haut">
  <!-- Flèche vers le haut -->
  <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
  </svg>
</button>

<script>
  // Sélection du bouton
  const scrollBtn = document.getElementById('scrollToTopBtn');

  // Quand on clique dessus, remonter en haut
  scrollBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
</script>

                </li>
            </ul>
        </nav>
    </header>
    <main class="flex-1">