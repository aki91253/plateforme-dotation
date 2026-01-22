<?php
// Lire le message de maintenance
$maintenanceFile = __DIR__ . '/maintenance.lock';
$maintenanceInfo = null;

if (file_exists($maintenanceFile)) {
    $maintenanceInfo = json_decode(file_get_contents($maintenanceFile), true);
}

$message = $maintenanceInfo['message'] ?? 'Une maintenance est en cours.';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance en cours - Canopé Dotations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'canope-dark': '#0B162C',
                        'canope-teal': '#5FC2BA',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes spin-slow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }
        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-canope-dark via-gray-800 to-canope-teal min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Logo/Icône -->
        <div class="text-center mb-8">
            <div class="relative inline-block">
                <!-- Cercle extérieur qui pulse -->
                <div class="absolute inset-0 bg-canope-teal rounded-full opacity-20 animate-pulse-glow"></div>
                
                <!-- Cercle tournant -->
                <div class="relative w-24 h-24 mx-auto">
                    <svg class="animate-spin-slow" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="url(#gradient)" stroke-width="8" stroke-dasharray="70 200" stroke-linecap="round"/>
                        <defs>
                            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#5FC2BA;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#0B162C;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                    </svg>
                    
                    <!-- Icône centrale -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-canope-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl border border-white/20">
            <h1 class="text-3xl font-bold text-white text-center mb-4">
                Maintenance en cours
            </h1>
            
            <p class="text-white/80 text-center mb-6 leading-relaxed">
                <?= htmlspecialchars($message) ?>
            </p>

            <div class="bg-white/5 rounded-lg p-4 mb-6">
                <p class="text-white/60 text-sm text-center">
                    Nous travaillons pour améliorer votre expérience.<br>
                    Merci de votre patience.
                </p>
            </div>

            <!-- Points animés -->
            <div class="flex justify-center gap-2">
                <div class="w-3 h-3 bg-canope-teal rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                <div class="w-3 h-3 bg-canope-teal rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                <div class="w-3 h-3 bg-canope-teal rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
             <img src="assets/img/logo.png" alt="Réseau Canopé Logo" class="mx-auto h-12">
        </div>
    </div>
</body>
</html>