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
<body class="min-h-screen flex flex-col bg-white text-gray-800">
    <header class="bg-white py-4">
        <nav class="max-w-6xl mx-auto px-5 flex justify-between items-center">
            <!-- Logo -->
            <a href="index.php" class="font-bold text-canope-green text-xl">
                <img src="assets/img/logo.jpg" alt="Réseau Canopé Logo" class="h-12">
            </a>
            
            <ul class="flex items-center gap-8 list-none m-0 p-0">
                <li><a href="index.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Accueil</a></li>
                <li><a href="donations.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Liste de donations</a></li>
                <li><a href="contact.php" class="text-gray-800 no-underline text-sm uppercase tracking-wide font-medium hover:text-canope-green transition-colors">Contact</a></li>
                <li><a href="#" class="bg-canope-olive text-white px-6 py-2 rounded-full text-sm no-underline hover:opacity-90 transition-opacity">Connexion</a></li>
            </ul>
        </nav>
    </header>
    <main class="flex-1">
