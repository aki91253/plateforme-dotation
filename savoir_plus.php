<?php
require_once 'includes/db.php';
include 'includes/header.php';
require_once 'admin/maintenance_check.php';
?>

<!-- Hero Section -->
<div class="bg-gradient-to-br from-canope-dark via-canope-slate to-canope-gray-600 py-20 px-5">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-normal text-white mb-4">En savoir plus</h1>
        <p class="text-white/80 text-lg">Découvrez Réseau Canopé et la plateforme de dotation.</p>
    </div>
</div>

<!-- Section sur le réseau Canopé -->
<div class="max-w-6xl mx-auto px-5 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="inline-block px-4 py-1 bg-teal-100 text-teal-700 rounded-full text-sm font-medium mb-4">Qui sommes-nous ?</span>
            <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-6">Réseau Canopé</h2>
            <p class="text-gray-600 mb-4 leading-relaxed">
                Réseau Canopé est le réseau de création et d'accompagnement pédagogiques placé sous la tutelle du ministère de l'Éducation nationale. Notre mission est d'accompagner les enseignants dans leur pratique quotidienne.
            </p>
            <p class="text-gray-600 mb-6 leading-relaxed">
                Nous proposons des ressources pédagogiques innovantes, des formations continues et un accompagnement personnalisé pour tous les acteurs de l'éducation en France.
            </p>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-purple-700 font-medium">Ressources</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-yellow-700 font-medium">Formations</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <span class="text-blue-700 font-medium">Innovation</span>
                </div>
            </div>
        </div>
        <div class="relative">
            <div class="bg-gradient-to-br from-canope-green/20 to-canope-olive/20 rounded-3xl p-8 lg:p-12">
                <img src="assets/img/logov1.jpg" alt="Réseau Canopé" class="w-full max-w-xs mx-auto rounded-2xl shadow-lg">
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-canope-green/10 rounded-full blur-2xl"></div>
        </div>
    </div>
</div>

<!-- Section Portail de Ressources Numériques -->
<div class="bg-gradient-to-r from-teal-50 to-blue-50 py-12">
    <div class="max-w-4xl mx-auto px-5">
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 flex flex-col md:flex-row items-center gap-6">
            <div class="w-16 h-16 bg-gradient-to-br from-canope-teal to-canope-green rounded-xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Portail de Ressources Numériques</h3>
                <p class="text-gray-600 mb-4">Accédez à notre portail de ressources numériques pour découvrir encore plus de contenus pédagogiques en ligne.</p>
                <a href="https://educorsica.spread.name" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-canope-teal text-white px-5 py-2.5 rounded-full font-medium hover:bg-canope-green transition-colors">
                    <span>Accéder au portail</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Section sur la plateforme -->
<div class="bg-gray-50 py-16">
    <div class="max-w-6xl mx-auto px-5">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium mb-4">La plateforme</span>
            <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-4">Plateforme de Dotation</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Une solution moderne pour faciliter l'accès aux ressources pédagogiques pour les établissements de l'académie de Corse.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Fonctionnalité 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Catalogue riche</h3>
                <p class="text-gray-600">Accédez à un large choix de ressources pédagogiques : albums, livres, guides, affiches et bien plus encore.</p>
            </div>
            
            <!-- Fonctionnalité 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Demandes simplifiées</h3>
                <p class="text-gray-600">Faites vos demandes en quelques clics et suivez leur avancement grâce à un système de suivi transparent.</p>
            </div>
            
            <!-- Fonctionnalité 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Suivi en temps réel</h3>
                <p class="text-gray-600">Consultez le statut de vos demandes à tout moment grâce à votre token de suivi personnalisé.</p>
            </div>
        </div>
    </div>
</div>

<!-- Section sur le fonctionnement -->
<div class="max-w-6xl mx-auto px-5 py-16">
    <div class="text-center mb-12">
        <span class="inline-block px-4 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">Comment ça marche ?</span>
        <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-4">Un processus simple</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">De la découverte des ressources à la réception de votre dotation, nous simplifions chaque étape.</p>
    </div>
    
    <div class="flex flex-col md:flex-row items-start justify-center gap-4 md:gap-0">
        <!-- Étape 1 -->
        <div class="text-center flex-1 max-w-[200px]">
            <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto">1</div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2 mt-4">Parcourez</h3>
            <p class="text-gray-600 text-sm">Explorez notre catalogue de ressources pédagogiques.</p>
        </div>
        
        <!-- Flèche 1 -->
        <div class="hidden md:flex items-center justify-center shrink-0 h-16">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </div>
        
        <!-- Étape 2 -->
        <div class="text-center flex-1 max-w-[200px]">
            <div class="w-16 h-16 bg-amber-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto">2</div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2 mt-4">Sélectionnez</h3>
            <p class="text-gray-600 text-sm">Ajoutez les ressources à votre sélection.</p>
        </div>
        
        <!-- Flèche 2 -->
        <div class="hidden md:flex items-center justify-center shrink-0 h-16">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </div>
        
        <!-- Étape 3 -->
        <div class="text-center flex-1 max-w-[200px]">
            <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto">3</div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2 mt-4">Demandez</h3>
            <p class="text-gray-600 text-sm">Remplissez le formulaire et validez.</p>
        </div>
        
        <!-- Flèche 3 -->
        <div class="hidden md:flex items-center justify-center shrink-0 h-16">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </div>
        
        <!-- Étape 4 -->
        <div class="text-center flex-1 max-w-[200px]">
            <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto">4</div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2 mt-4">Recevez</h3>
            <p class="text-gray-600 text-sm">Recevez vos ressources directement à votre établissement.</p>
        </div>
    </div>
</div>

<!-- Section sur les statistiques -->
<div class="bg-gradient-to-r from-canope-dark to-canope-gray py-16">
    <div class="max-w-6xl mx-auto px-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2">100+</div>
                <div class="text-white/80">Établissements</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2">500+</div>
                <div class="text-white/80">Ressources disponibles</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2">1000+</div>
                <div class="text-white/80">Demandes traitées</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2">98%</div>
                <div class="text-white/80">Satisfaction</div>
            </div>
        </div>
    </div>
</div>

<!-- Section CTA -->
<div class="max-w-4xl mx-auto px-5 py-16">
    <div class="rounded-2xl border-2 border-gray-100 p-8 md:p-12 text-center shadow-lg bg-gradient-to-br from-canope-dark to-canope-gray">
        <h2 class="text-2xl md:text-3xl font-semibold text-white mb-4">Prêt à découvrir nos ressources ?</h2>
        <p class="text-white mb-8">Parcourez notre catalogue et faites votre première demande de dotation.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="donations.php" class="inline-flex items-center justify-center gap-2 bg-canope-teal text-white px-6 py-3 rounded-full font-semibold transition-colors shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Voir les dotations
            </a>
            <a href="support.php" class="inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-canope-gray hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Questions fréquentes
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
