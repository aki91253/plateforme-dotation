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
                        'canope-dark': '#0B162C',
                        'canope-slate': '#1C2942',
                        'canope-light': '#FFFFFF',
                        'canope-gray' : '#3B556D',
                        'canope-teal' : '#5FC2BA',
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
  <!-- From Uiverse.io by Javierrocadev --> 
<div class="flex flex-row gap-2">
  <div class="w-4 h-4 rounded-full bg-blue-700 animate-bounce"></div>
  <div class="w-4 h-4 rounded-full bg-blue-700 animate-bounce [animation-delay:-.3s]"></div>
  <div class="w-4 h-4 rounded-full bg-blue-700 animate-bounce [animation-delay:-.5s]"></div>
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
            <a href="index.php" class="font-bold text-canope-dark text-xl">
                <img src="assets/img/logo.png" alt="Réseau Canopé Logo" class="h-9">
            </a>
            
            <!-- Mobile: Cart + Hamburger Menu -->
            <div class="flex items-center gap-4 lg:hidden">
                <!-- Mobile Cart Icon -->
                <a href="selection.php" class="relative" title="Ma sélection">
                    <svg class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span id="cart-count-mobile" class="absolute -top-2 -right-2 bg-canope-slate text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold hidden">0</span>
                </a>
                
                <!-- Hamburger Button -->
                <button id="mobile-menu-btn" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Menu">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Desktop Navigation -->
            <ul class="hidden lg:flex items-center gap-8 list-none m-0 p-0">
                <li><a href="index.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-dark transition-colors">Accueil</a></li>
                <li><a href="donations.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-dark transition-colors">Catalogue</a></li>
                <li><a href="contact.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-dark transition-colors">Contact</a></li>
                <li><a href="demande.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-dark transition-colors">Suivre ma demande</a></li>
                <!-- Selection List Icon -->
                <li>
                    <a href="selection.php" class="relative group" title="Ma sélection">
                        <svg class="h-7 w-7 text-gray-700 hover:text-canope-dark transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.75">
							<path fill="none" d="M17.671,13.945l0.003,0.002l1.708-7.687l-0.008-0.002c0.008-0.033,0.021-0.065,0.021-0.102c0-0.236-0.191-0.428-0.427-0.428H5.276L4.67,3.472L4.665,3.473c-0.053-0.175-0.21-0.306-0.403-0.306H1.032c-0.236,0-0.427,0.191-0.427,0.427c0,0.236,0.191,0.428,0.427,0.428h2.902l2.667,9.945l0,0c0.037,0.119,0.125,0.217,0.239,0.268c-0.16,0.26-0.257,0.562-0.257,0.891c0,0.943,0.765,1.707,1.708,1.707S10,16.068,10,15.125c0-0.312-0.09-0.602-0.237-0.855h4.744c-0.146,0.254-0.237,0.543-0.237,0.855c0,0.943,0.766,1.707,1.708,1.707c0.944,0,1.709-0.764,1.709-1.707c0-0.328-0.097-0.631-0.257-0.891C17.55,14.182,17.639,14.074,17.671,13.945 M15.934,6.583h2.502l-0.38,1.709h-2.312L15.934,6.583zM5.505,6.583h2.832l0.189,1.709H5.963L5.505,6.583z M6.65,10.854L6.192,9.146h2.429l0.19,1.708H6.65z M6.879,11.707h2.027l0.189,1.709H7.338L6.879,11.707z M8.292,15.979c-0.472,0-0.854-0.383-0.854-0.854c0-0.473,0.382-0.855,0.854-0.855s0.854,0.383,0.854,0.855C9.146,15.596,8.763,15.979,8.292,15.979 M11.708,13.416H9.955l-0.189-1.709h1.943V13.416z M11.708,10.854H9.67L9.48,9.146h2.228V10.854z M11.708,8.292H9.386l-0.19-1.709h2.512V8.292z M14.315,13.416h-1.753v-1.709h1.942L14.315,13.416zM14.6,10.854h-2.037V9.146h2.227L14.6,10.854z M14.884,8.292h-2.321V6.583h2.512L14.884,8.292z M15.978,15.979c-0.471,0-0.854-0.383-0.854-0.854c0-0.473,0.383-0.855,0.854-0.855c0.473,0,0.854,0.383,0.854,0.855C16.832,15.596,16.45,15.979,15.978,15.979 M16.917,13.416h-1.743l0.189-1.709h1.934L16.917,13.416z M15.458,10.854l0.19-1.708h2.218l-0.38,1.708H15.458z"></path>
						</svg>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-canope-slate text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold hidden">0</span>
                    </a>
                </li>
                
                <?php if (isLoggedIn()): ?>
                <!-- Logged in: Montre le menu utilisateurs -->
                <li class="relative group">
                    <button class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-canope-dark transition-colors">
                        <div class="w-8 h-8 bg-canope-dark/10 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
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
                    <a href="login.php" class="flex justify-center gap-2 items-center shadow-lg text-sm bg-canope-slate backdrop-blur-md font-semibold relative z-10 px-4 py-2 overflow-hidden border-2 border-canope-gray rounded-full group no-underline text-white hover:bg-gradient-to-r hover:from-canope-dark hover:to-canope-gray hover:border-canope-slate transition-all duration-300">
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
    
    <!-- Mobile Navigation Drawer (placed outside header for proper fixed positioning) -->
    <div id="mobile-menu" class="lg:hidden fixed inset-0 z-[100] hidden">
        <!-- Overlay -->
        <div id="mobile-menu-overlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        
        <!-- Drawer -->
        <div id="mobile-menu-drawer" class="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-out flex flex-col">
            <!-- Drawer Header -->
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-white flex-shrink-0">
                <span class="font-semibold text-gray-800">Menu</span>
                <button id="mobile-menu-close" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Navigation Links -->
            <nav class="p-5 bg-white flex-1 overflow-y-auto">
                <ul class="space-y-1">
                    <li>
                        <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <a href="donations.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Catalogue
                        </a>
                    </li>
                    <li>
                        <a href="contact.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="demande.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Suivre ma demande
                        </a>
                    </li>
                    <li>
                        <a href="selection.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Ma sélection
                            <span id="cart-count-drawer" class="ml-auto bg-canope-slate text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold hidden">0</span>
                        </a>
                    </li>
                </ul>
                
                <div class="border-t border-gray-100 my-4"></div>
                
                <?php if (isLoggedIn()): ?>
                <!-- Logged in: Mobile user menu -->
                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($_SESSION['user_email']) ?></p>
                    <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($_SESSION['user_etablissement']) ?></p>
                </div>
                <ul class="space-y-1">
                    <li>
                        <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Mon profil
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Déconnexion
                        </a>
                    </li>
                </ul>
                <?php else: ?>
                <!-- Not logged in: Mobile login button -->
                <a href="login.php" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-canope-slate text-white rounded-xl font-semibold hover:bg-canope-dark transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Espace Admin
                </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
    
    <!-- Mobile Menu Script -->
    <script>
        (function() {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const overlay = document.getElementById('mobile-menu-overlay');
            const drawer = document.getElementById('mobile-menu-drawer');
            const closeBtn = document.getElementById('mobile-menu-close');
            
            function openMenu() {
                menu.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    drawer.classList.remove('translate-x-full');
                }, 10);
            }
            
            function closeMenu() {
                drawer.classList.add('translate-x-full');
                setTimeout(() => {
                    menu.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }
            
            menuBtn.addEventListener('click', openMenu);
            closeBtn.addEventListener('click', closeMenu);
            overlay.addEventListener('click', closeMenu);
            
            // Sync cart counts between mobile icons
            function syncMobileCartCount() {
                const mainCount = document.getElementById('cart-count');
                const mobileCount = document.getElementById('cart-count-mobile');
                const drawerCount = document.getElementById('cart-count-drawer');
                
                if (mainCount && mobileCount) {
                    mobileCount.textContent = mainCount.textContent;
                    mobileCount.className = mainCount.className;
                }
                if (mainCount && drawerCount) {
                    drawerCount.textContent = mainCount.textContent;
                    if (mainCount.classList.contains('hidden')) {
                        drawerCount.classList.add('hidden');
                    } else {
                        drawerCount.classList.remove('hidden');
                    }
                }
            }
            
            // Observe changes to main cart count
            const mainCount = document.getElementById('cart-count');
            if (mainCount) {
                const observer = new MutationObserver(syncMobileCartCount);
                observer.observe(mainCount, { attributes: true, childList: true, characterData: true, subtree: true });
                syncMobileCartCount();
            }
        })();
    </script>
    
    <main class="flex-1">