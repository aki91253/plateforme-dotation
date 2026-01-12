<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<!-- Hero Section with colorful gradient -->
<div class="bg-gradient-to-br from-canope-green via-canope-olive to-emerald-600 py-16 px-5">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-normal text-white mb-4">Comment pouvons-nous vous aider ?</h1>
        <p class="text-white/80 text-lg">Trouvez des réponses à vos questions sur les dotations et la plateforme.</p>
    </div>
</div>

<!-- Category Cards -->
<div class="max-w-6xl mx-auto px-5 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Card 1: Dotations -->
        <a href="#dotations" class="group bg-white rounded-2xl border-2 border-gray-100 p-6 hover:border-canope-green hover:shadow-lg transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-canope-green/10 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-canope-green/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-canope-green transition-colors">Dotations</h3>
                    <p class="text-sm text-gray-500">Découvrez les ressources disponibles et comment les demander.</p>
                </div>
            </div>
        </a>

        <!-- Card 2: Ma demande -->
        <a href="#demande" class="group bg-white rounded-2xl border-2 border-gray-100 p-6 hover:border-amber-400 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-amber-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-amber-600 transition-colors">Ma demande</h3>
                    <p class="text-sm text-gray-500">Suivez l'état de votre demande et consultez son historique.</p>
                </div>
            </div>
        </a>

        <!-- Card 3: Premiers pas -->
        <a href="#premiers-pas" class="group bg-white rounded-2xl border-2 border-gray-100 p-6 hover:border-blue-400 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">Premiers pas</h3>
                    <p class="text-sm text-gray-500">Démarrez rapidement avec la plateforme de dotation.</p>
                </div>
            </div>
        </a>

        <!-- Card 4: Ressources -->
        <a href="#ressources" class="group bg-white rounded-2xl border-2 border-gray-100 p-6 hover:border-purple-400 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-purple-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-purple-600 transition-colors">Ressources</h3>
                    <p class="text-sm text-gray-500">Types de ressources pédagogiques proposées.</p>
                </div>
            </div>
        </a>

        <!-- Card 5: Guides -->
        <a href="guides.php" class="group bg-white rounded-2xl border-2 border-gray-100 p-6 hover:border-rose-400 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-rose-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-rose-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-rose-600 transition-colors">Guides</h3>
                    <p class="text-sm text-gray-500">Tutoriels et guides d'utilisation de la plateforme.</p>
                </div>
            </div>
        </a>

        <!-- Card 6: Contact -->
        <a href="contact.php" class="group bg-white rounded-2xl border-2 border-gray-100 p-6 hover:border-teal-400 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-teal-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-teal-600 transition-colors">Contact</h3>
                    <p class="text-sm text-gray-500">Besoin d'aide ? Contactez notre équipe.</p>
                </div>
            </div>
        </a>

    </div>
</div>

<!-- FAQ Sections -->
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-5">
        
        <!-- Section Dotations -->
        <div id="dotations" class="mb-12 scroll-mt-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-3">
                <span class="w-10 h-10 bg-canope-green/10 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </span>
                Dotations
            </h2>
            <div class="space-y-3">
                <details class="bg-white rounded-xl border border-gray-200 group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-canope-green transition-colors flex justify-between items-center list-none">
                        Qu'est-ce qu'une dotation ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Une dotation est un ensemble de ressources pédagogiques (livres, albums, guides) mis à disposition gratuitement des établissements scolaires par Réseau Canopé. Ces ressources sont destinées à enrichir les projets éducatifs.
                    </div>
                </details>
                
                <details class="bg-white rounded-xl border border-gray-200 group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-canope-green transition-colors flex justify-between items-center list-none">
                        Comment faire une demande de dotation ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        <ol class="list-decimal list-inside space-y-2">
                            <li>Parcourez la <a href="donations.php" class="text-canope-green hover:underline">liste des dotations</a> disponibles</li>
                            <li>Ajoutez les ressources souhaitées à votre sélection</li>
                            <li>Remplissez le formulaire avec vos informations</li>
                            <li>Validez votre demande et conservez votre token de suivi</li>
                        </ol>
                    </div>
                </details>

                <details class="bg-white rounded-xl border border-gray-200 group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-canope-green transition-colors flex justify-between items-center list-none">
                        Combien de ressources puis-je demander ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Le nombre de ressources dépend de la disponibilité et du type de dotation. Certaines ressources peuvent avoir des quantités limitées. Vous pouvez ajuster les quantités lors de votre demande.
                    </div>
                </details>
            </div>
        </div>

        <!-- Section Demande -->
        <div id="demande" class="mb-12 scroll-mt-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-3">
                <span class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </span>
                Ma demande
            </h2>
            <div class="space-y-3">
                <details class="bg-white rounded-xl border border-gray-200 group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-amber-600 transition-colors flex justify-between items-center list-none">
                        Comment suivre ma demande ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Après validation de votre demande, vous recevez un token de suivi. Rendez-vous sur la page <a href="demande.php" class="text-canope-green hover:underline">Suivre ma demande</a> et entrez ce token pour consulter l'état de votre demande.
                    </div>
                </details>
                
                <details class="bg-white rounded-xl border border-gray-200 group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-amber-600 transition-colors flex justify-between items-center list-none">
                        Quels sont les différents statuts d'une demande ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        <ul class="space-y-2">
                            <li><span class="inline-block px-2 py-1 bg-amber-100 text-amber-700 rounded text-sm font-medium">En attente</span> - Demande reçue, en cours de vérification</li>
                            <li><span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm font-medium">Vérifiée</span> - Demande validée par notre équipe</li>
                            <li><span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-sm font-medium">Approuvée</span> - Demande acceptée, préparation en cours</li>
                            <li><span class="inline-block px-2 py-1 bg-purple-100 text-purple-700 rounded text-sm font-medium">Envoyée</span> - Colis expédié</li>
                            <li><span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-sm font-medium">Livrée</span> - Dotation reçue</li>
                        </ul>
                    </div>
                </details>

                <details class="bg-white rounded-xl border border-gray-200 group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-amber-600 transition-colors flex justify-between items-center list-none">
                        J'ai perdu mon token de suivi, que faire ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Si vous avez perdu votre token, <a href="contact.php" class="text-canope-green hover:underline">contactez-nous</a> en précisant votre nom, établissement et la date approximative de votre demande. Notre équipe vous retrouvera votre token.
                    </div>
                </details>
            </div>
        </div>

        <!-- Section Premiers pas -->
        <div id="premiers-pas" class="mb-12 scroll-mt-8 faq-section">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-3">
                <span class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
                Premiers pas
            </h2>
            <div class="space-y-3">
                <details class="bg-white rounded-xl border border-gray-200 group faq-item">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-blue-600 transition-colors flex justify-between items-center list-none">
                        Comment utiliser la plateforme ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        La plateforme vous permet de demander des ressources pédagogiques gratuitement. Parcourez le catalogue, ajoutez des ressources à votre sélection, puis remplissez le formulaire de demande. C'est simple et rapide !
                    </div>
                </details>
                
                <details class="bg-white rounded-xl border border-gray-200 group faq-item">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-blue-600 transition-colors flex justify-between items-center list-none">
                        Qui peut faire une demande de dotation ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Les dotations sont destinées aux établissements scolaires de l'académie de Corse : écoles, collèges, lycées et universités. Les enseignants et responsables pédagogiques peuvent effectuer une demande au nom de leur établissement.
                    </div>
                </details>

                <details class="bg-white rounded-xl border border-gray-200 group faq-item">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-blue-600 transition-colors flex justify-between items-center list-none">
                        Les dotations sont-elles payantes ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Non, toutes les dotations proposées sur cette plateforme sont entièrement gratuites. Elles sont mises à disposition par Réseau Canopé dans le cadre de sa mission de soutien à l'éducation.
                    </div>
                </details>
            </div>
        </div>

        <!-- Section Ressources -->
        <div id="ressources" class="mb-12 scroll-mt-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-3">
                <span class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </span>
                Ressources
            </h2>
            <div class="space-y-3">
                <details class="bg-white rounded-xl border border-gray-200 group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-purple-600 transition-colors flex justify-between items-center list-none">
                        Quels types de ressources sont disponibles ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Nous proposons une variété de ressources pédagogiques : albums, livres documentaires, guides pour enseignants, affiches, jeux éducatifs et supports numériques. Chaque ressource est sélectionnée pour accompagner vos projets éducatifs.
                    </div>
                </details>
                
                <details class="bg-white rounded-xl border border-gray-200 group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-purple-600 transition-colors flex justify-between items-center list-none">
                        Pour quels niveaux scolaires sont ces ressources ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Les ressources couvrent tous les niveaux : maternelle, élémentaire, collège, lycée et universités. Chaque dotation indique les niveaux ciblés pour vous aider à choisir les ressources adaptées à vos élèves.
                    </div>
                </details>
                
                <details class="bg-white rounded-xl border border-gray-200 group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-purple-600 transition-colors flex justify-between items-center list-none">
                        Comment sont mises à jour les ressources ?
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Le catalogue est régulièrement enrichi avec de nouvelles ressources. Nous ajoutons des dotations tout au long de l'année en fonction des programmes scolaires et des besoins des enseignants.
                    </div>
                </details>
            </div>
        </div>

    </div>
</div>

<!-- Contact CTA -->
<div class="max-w-4xl mx-auto px-5 py-12">
    <div class="bg-gradient-to-r from-canope-green to-canope-olive rounded-2xl p-8 md:p-12 text-center text-white">
        <h2 class="text-2xl md:text-3xl font-semibold mb-4">Vous n'avez pas trouvé de réponse ?</h2>
        <p class="text-white/80 mb-6">Notre équipe est disponible pour répondre à toutes vos questions.</p>
        <a href="contact.php" class="inline-flex items-center gap-2 bg-white text-canope-green px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Contactez-nous
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
