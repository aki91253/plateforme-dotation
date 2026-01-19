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
            background: rgba(58, 107, 86, 0.1);
            color: #3A6B56;
        }
        .sidebar-link.active {
            background: rgba(58, 107, 86, 0.15);
            color: #3A6B56;
            border-left-color: #3A6B56;
        }
        .sidebar-link.active svg {
            color: #3A6B56;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar - Light Theme -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full shadow-sm">
            <!-- Logo -->
            <div class="p-5 border-b border-gray-100">
                <a href="index.php" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-canope-dark rounded-xl flex items-center justify-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="font-semibold text-lg text-gray-800">Canopé Admin</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 py-6">
                <p class="px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Menu</p>
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="index.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-gray-600 <?= $currentPage === 'index' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="products.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-gray-600 <?= $currentPage === 'products' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span>Dotations</span>
                        </a>
                    </li>
                    <li>
                        <a href="stock.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-gray-600 <?= $currentPage === 'stock' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>Stock</span>
                        </a>
                    </li>
                    <li>
                        <a href="requests.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-gray-600 <?= $currentPage === 'requests' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Demandes</span>
                        </a>
                    </li>
                    <li>
                        <a href="visibility.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-gray-600 <?= $currentPage === 'visibility' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Visibilité</span>
                        </a>
                    </li>
                </ul>

                <!-- Divider -->
                <div class="my-6 mx-6 border-t border-gray-100"></div>

                <p class="px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Liens</p>
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="../index.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border-l-4 border-transparent text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span>Voir le site</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Admin Info -->
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
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
        <div class="flex-1 ml-64">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800" id="page-title">Tableau de bord</h1>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8">
