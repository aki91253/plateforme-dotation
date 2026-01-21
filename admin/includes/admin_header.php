<?php
/**
 * Admin Panel Header with Sidebar Navigation
 * Light theme matching Canopé brand
 */
require_once 'includes/admin_auth.php';
$currentAdmin = getCurrentAdmin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Plateforme Solidaire</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover {
            background: rgba(91, 91, 91, 0.15);
            color: #cccbcb;
        }
        .sidebar-link.active {
            background: rgba(91, 91, 91, 0.2);
            color: #adadad;
            border-left-color: #adadad;
        }
        .sidebar-link.active svg {
            color: #adadad;
        }
        
        /* Sidebar responsive styles */
        .admin-sidebar {
            transition: transform 0.3s ease-in-out;
            z-index: 40;
        }
        
        /* Sidebar collapsed state */
        .admin-sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
            z-index: 30;
        }
        
        /* Main content transition for sidebar toggle */
        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }
        
        .main-content.sidebar-collapsed {
            margin-left: 0 !important;
        }
        
        /* Mobile: sidebar hidden by default, show overlay */
        @media (max-width: 1023px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
            }
        }
        
        /* Desktop: hide overlay */
        @media (min-width: 1024px) {
            .sidebar-overlay {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-blue-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar Overlay (mobile) -->
        <div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 bg-black/50 opacity-0 pointer-events-none lg:hidden"></div>
        
        <!-- Sidebar - Light Theme -->
        <aside id="admin-sidebar" class="admin-sidebar w-64 bg-canope-slate border-r border-white flex flex-col fixed h-full shadow-sm">
            <!-- Logo -->
            <div class="p-5 border-b border-gray-100">
                <a href="index.php" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-canope-dark rounded-xl flex items-center justify-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="font-semibold text-lg text-white">Canopé Admin</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 py-6">
                <p class="px-6 text-xs font-semibold text-white uppercase tracking-wider mb-3">Menu</p>
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="index.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-white <?= $currentPage === 'index' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="stock.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-white <?= $currentPage === 'stock' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>Stock</span>
                        </a>
                    </li>
                    <li>
                        <a href="requests.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-white <?= $currentPage === 'requests' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Demandes</span>
                        </a>
                    </li>
                </ul>

                <!-- Divider -->
                <div class="my-6 mx-6 border-t border-white"></div>

                <p class="px-6 text-xs font-semibold text-white uppercase tracking-wider mb-3">Liens</p>
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="../index.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span>Voir le site</span>
                        </a>
                    </li>
                </ul>

                <?php if (isSuperAdmin()): ?>
                <!-- Superadmin Section -->
                <div class="my-6 mx-6 border-t border-white"></div>

                <p class="px-6 text-xs font-semibold text-white uppercase tracking-wider mb-3">Super Admin</p>
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="admins.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-white <?= $currentPage === 'admins' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 5.197v-1a6 6 0 00-7-5.916" />
                            </svg>
                            <span>Gestion Admins</span>
                        </a>
                    </li>
                </ul>
                <?php endif; ?>
            </nav>

              <!-- Other -->
                <div class="my-6 mx-6 border-t border-white"></div>

                <p class="px-6 text-xs font-semibold text-white uppercase tracking-wider mb-3">Options</p>
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="setting.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5">
							<path d="M17.498,11.697c-0.453-0.453-0.704-1.055-0.704-1.697c0-0.642,0.251-1.244,0.704-1.697c0.069-0.071,0.15-0.141,0.257-0.22c0.127-0.097,0.181-0.262,0.137-0.417c-0.164-0.558-0.388-1.093-0.662-1.597c-0.075-0.141-0.231-0.22-0.391-0.199c-0.13,0.02-0.238,0.027-0.336,0.027c-1.325,0-2.401-1.076-2.401-2.4c0-0.099,0.008-0.207,0.027-0.336c0.021-0.158-0.059-0.316-0.199-0.391c-0.503-0.274-1.039-0.498-1.597-0.662c-0.154-0.044-0.32,0.01-0.416,0.137c-0.079,0.106-0.148,0.188-0.22,0.257C11.244,2.956,10.643,3.207,10,3.207c-0.642,0-1.244-0.25-1.697-0.704c-0.071-0.069-0.141-0.15-0.22-0.257C7.987,2.119,7.821,2.065,7.667,2.109C7.109,2.275,6.571,2.497,6.07,2.771C5.929,2.846,5.85,3.004,5.871,3.162c0.02,0.129,0.027,0.237,0.027,0.336c0,1.325-1.076,2.4-2.401,2.4c-0.098,0-0.206-0.007-0.335-0.027C3.001,5.851,2.845,5.929,2.77,6.07C2.496,6.572,2.274,7.109,2.108,7.667c-0.044,0.154,0.01,0.32,0.137,0.417c0.106,0.079,0.187,0.148,0.256,0.22c0.938,0.936,0.938,2.458,0,3.394c-0.069,0.072-0.15,0.141-0.256,0.221c-0.127,0.096-0.181,0.262-0.137,0.416c0.166,0.557,0.388,1.096,0.662,1.596c0.075,0.143,0.231,0.221,0.392,0.199c0.129-0.02,0.237-0.027,0.335-0.027c1.325,0,2.401,1.076,2.401,2.402c0,0.098-0.007,0.205-0.027,0.334C5.85,16.996,5.929,17.154,6.07,17.23c0.501,0.273,1.04,0.496,1.597,0.66c0.154,0.047,0.32-0.008,0.417-0.137c0.079-0.105,0.148-0.186,0.22-0.256c0.454-0.453,1.055-0.703,1.697-0.703c0.643,0,1.244,0.25,1.697,0.703c0.071,0.07,0.141,0.15,0.22,0.256c0.073,0.098,0.188,0.152,0.307,0.152c0.036,0,0.073-0.004,0.109-0.016c0.558-0.164,1.096-0.387,1.597-0.66c0.141-0.076,0.22-0.234,0.199-0.393c-0.02-0.129-0.027-0.236-0.027-0.334c0-1.326,1.076-2.402,2.401-2.402c0.098,0,0.206,0.008,0.336,0.027c0.159,0.021,0.315-0.057,0.391-0.199c0.274-0.5,0.496-1.039,0.662-1.596c0.044-0.154-0.01-0.32-0.137-0.416C17.648,11.838,17.567,11.77,17.498,11.697 M16.671,13.334c-0.059-0.002-0.114-0.002-0.168-0.002c-1.749,0-3.173,1.422-3.173,3.172c0,0.053,0.002,0.109,0.004,0.166c-0.312,0.158-0.64,0.295-0.976,0.406c-0.039-0.045-0.077-0.086-0.115-0.123c-0.601-0.6-1.396-0.93-2.243-0.93s-1.643,0.33-2.243,0.93c-0.039,0.037-0.077,0.078-0.116,0.123c-0.336-0.111-0.664-0.248-0.976-0.406c0.002-0.057,0.004-0.113,0.004-0.166c0-1.75-1.423-3.172-3.172-3.172c-0.054,0-0.11,0-0.168,0.002c-0.158-0.312-0.293-0.639-0.405-0.975c0.044-0.039,0.085-0.078,0.124-0.115c1.236-1.236,1.236-3.25,0-4.486C3.009,7.719,2.969,7.68,2.924,7.642c0.112-0.336,0.247-0.664,0.405-0.976C3.387,6.668,3.443,6.67,3.497,6.67c1.75,0,3.172-1.423,3.172-3.172c0-0.054-0.002-0.11-0.004-0.168c0.312-0.158,0.64-0.293,0.976-0.405C7.68,2.969,7.719,3.01,7.757,3.048c0.6,0.6,1.396,0.93,2.243,0.93s1.643-0.33,2.243-0.93c0.038-0.039,0.076-0.079,0.115-0.123c0.336,0.112,0.663,0.247,0.976,0.405c-0.002,0.058-0.004,0.114-0.004,0.168c0,1.749,1.424,3.172,3.173,3.172c0.054,0,0.109-0.002,0.168-0.004c0.158,0.312,0.293,0.64,0.405,0.976c-0.045,0.038-0.086,0.077-0.124,0.116c-0.6,0.6-0.93,1.396-0.93,2.242c0,0.847,0.33,1.645,0.93,2.244c0.038,0.037,0.079,0.076,0.124,0.115C16.964,12.695,16.829,13.021,16.671,13.334 M10,5.417c-2.528,0-4.584,2.056-4.584,4.583c0,2.529,2.056,4.584,4.584,4.584s4.584-2.055,4.584-4.584C14.584,7.472,12.528,5.417,10,5.417 M10,13.812c-2.102,0-3.812-1.709-3.812-3.812c0-2.102,1.71-3.812,3.812-3.812c2.102,0,3.812,1.71,3.812,3.812C13.812,12.104,12.102,13.812,10,13.812"></path>
						</svg>
                            <span>Paramètres</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Admin Info -->
            <div class="p-4 border-t border-gray-100 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-canope-dark/10 rounded-full flex items-center justify-center">
                        <span class="text-canope-dark font-semibold text-sm">
                            <?= strtoupper(substr($currentAdmin['first_name'], 0, 1) . substr($currentAdmin['last_name'], 0, 1)) ?>
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($currentAdmin['first_name'] . ' ' . $currentAdmin['last_name']) ?></p>
                        <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($currentAdmin['job_title']) ?></p>
                    </div>
                    <a href="logout.php" class="text-gray-400 hover:text-red-500 transition-colors p-2 hover:bg-red-50 rounded-lg" title="Déconnexion">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content flex-1 lg:ml-64 transition-all duration-300">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-4 lg:px-8 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <!-- Hamburger Menu Button -->
                        <button id="hamburger-btn" class="hamburger-btn p-2 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Toggle menu">
                            <svg id="hamburger-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h1 class="text-xl lg:text-2xl font-semibold text-gray-800" id="page-title">Tableau de bord</h1>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 lg:p-8">
