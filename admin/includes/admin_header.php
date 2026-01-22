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
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
							<path d="M15.573,11.624c0.568-0.478,0.947-1.219,0.947-2.019c0-1.37-1.108-2.569-2.371-2.569s-2.371,1.2-2.371,2.569c0,0.8,0.379,1.542,0.946,2.019c-0.253,0.089-0.496,0.2-0.728,0.332c-0.743-0.898-1.745-1.573-2.891-1.911c0.877-0.61,1.486-1.666,1.486-2.812c0-1.79-1.479-3.359-3.162-3.359S4.269,5.443,4.269,7.233c0,1.146,0.608,2.202,1.486,2.812c-2.454,0.725-4.252,2.998-4.252,5.685c0,0.218,0.178,0.396,0.395,0.396h16.203c0.218,0,0.396-0.178,0.396-0.396C18.497,13.831,17.273,12.216,15.573,11.624 M12.568,9.605c0-0.822,0.689-1.779,1.581-1.779s1.58,0.957,1.58,1.779s-0.688,1.779-1.58,1.779S12.568,10.427,12.568,9.605 M5.06,7.233c0-1.213,1.014-2.569,2.371-2.569c1.358,0,2.371,1.355,2.371,2.569S8.789,9.802,7.431,9.802C6.073,9.802,5.06,8.447,5.06,7.233 M2.309,15.335c0.202-2.649,2.423-4.742,5.122-4.742s4.921,2.093,5.122,4.742H2.309z M13.346,15.335c-0.067-0.997-0.382-1.928-0.882-2.732c0.502-0.271,1.075-0.429,1.686-0.429c1.828,0,3.338,1.385,3.535,3.161H13.346z"></path>
						</svg>
                            <span>Gestion Admins</span>
                        </a>
                    </li>
                    <li>
                        <a href="database_backup.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-white <?= $currentPage === 'database_backup' ? 'active' : '' ?>">
                            <svg  class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path fill="none" d="M7.228,11.464H1.996c-0.723,0-1.308,0.587-1.308,1.309v5.232c0,0.722,0.585,1.308,1.308,1.308h5.232
                                    c0.723,0,1.308-0.586,1.308-1.308v-5.232C8.536,12.051,7.95,11.464,7.228,11.464z M7.228,17.351c0,0.361-0.293,0.654-0.654,0.654
                                    H2.649c-0.361,0-0.654-0.293-0.654-0.654v-3.924c0-0.361,0.292-0.654,0.654-0.654h3.924c0.361,0,0.654,0.293,0.654,0.654V17.351z
                                    M17.692,11.464H12.46c-0.723,0-1.308,0.587-1.308,1.309v5.232c0,0.722,0.585,1.308,1.308,1.308h5.232
                                    c0.722,0,1.308-0.586,1.308-1.308v-5.232C19,12.051,18.414,11.464,17.692,11.464z M17.692,17.351c0,0.361-0.293,0.654-0.654,0.654
                                    h-3.924c-0.361,0-0.654-0.293-0.654-0.654v-3.924c0-0.361,0.293-0.654,0.654-0.654h3.924c0.361,0,0.654,0.293,0.654,0.654V17.351z
                                    M7.228,1H1.996C1.273,1,0.688,1.585,0.688,2.308V7.54c0,0.723,0.585,1.308,1.308,1.308h5.232c0.723,0,1.308-0.585,1.308-1.308
                                    V2.308C8.536,1.585,7.95,1,7.228,1z M7.228,6.886c0,0.361-0.293,0.654-0.654,0.654H2.649c-0.361,0-0.654-0.292-0.654-0.654V2.962
                                    c0-0.361,0.292-0.654,0.654-0.654h3.924c0.361,0,0.654,0.292,0.654,0.654V6.886z M17.692,1H12.46c-0.723,0-1.308,0.585-1.308,1.308
                                    V7.54c0,0.723,0.585,1.308,1.308,1.308h5.232C18.414,8.848,19,8.263,19,7.54V2.308C19,1.585,18.414,1,17.692,1z M17.692,6.886
                                    c0,0.361-0.293,0.654-0.654,0.654h-3.924c-0.361,0-0.654-0.292-0.654-0.654V2.962c0-0.361,0.293-0.654,0.654-0.654h3.924
                                    c0.361,0,0.654,0.292,0.654,0.654V6.886z"></path>
                            </svg>
                            <span>Base de données</span>
                        </a>
                    </li>
                </ul>
                <?php endif; ?>
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
