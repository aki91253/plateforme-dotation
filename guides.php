<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="bg-canope-dark py-16 px-5 bg-gradient-to-br from-canope-dark to-canope-gray">
    <div class="max-w-4xl mx-auto text-center">
        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <h1 class="text-4xl md:text-5xl font-normal text-white mb-4">Guides & Tutoriels</h1>
        <p class="text-white/80 text-lg">Apprenez à utiliser la plateforme de dotation étape par étape.</p>
    </div>
</div>

<!-- Guide Cards -->
<div class="max-w-6xl mx-auto px-5 py-12">
    
    <!-- Guide 1: Faire une demande -->
    <div class="bg-white rounded-2xl border border-gray-200 p-8 mb-8 shadow-sm">
        <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center shrink-0">
                <span class="text-2xl font-bold text-teal-700">1</span>
            </div>
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Comment faire une demande de dotation</h2>
                <p class="text-gray-500">Guide complet pour effectuer votre première demande.</p>
            </div>
        </div>
        
        <div class="space-y-6 ml-16">
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-teal-700 rounded-full flex items-center justify-center shrink-0 text-white font-semibold text-sm">1</div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-1">Parcourez le catalogue</h3>
                    <p class="text-gray-600">Rendez-vous sur la page <a href="donations.php" class="text-canope-green hover:underline">Liste de dotations</a> pour découvrir toutes les ressources disponibles. Utilisez les filtres par catégorie pour affiner votre recherche.</p>
                </div>
            </div>
            
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-teal-700 rounded-full flex items-center justify-center shrink-0 text-white font-semibold text-sm">2</div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-1">Ajoutez à votre sélection</h3>
                    <p class="text-gray-600">Cliquez sur le bouton "Ajouter à la sélection" sur chaque ressource souhaitée. Vous pouvez voir le nombre d'éléments dans votre sélection via l'icône en haut de page.</p>
                </div>
            </div>
            
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-teal-700 rounded-full flex items-center justify-center shrink-0 text-white font-semibold text-sm">3</div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-1">Remplissez vos informations</h3>
                    <p class="text-gray-600">Accédez à votre sélection et remplissez le formulaire avec vos coordonnées : nom, prénom, établissement, email et téléphone. Choisissez le type de demande (première demande ou réassort).</p>
                </div>
            </div>
            
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-teal-700 rounded-full flex items-center justify-center shrink-0 text-white font-semibold text-sm">4</div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-1">Validez et conservez votre token</h3>
                    <p class="text-gray-600">Après validation, vous recevrez un <strong>token de suivi</strong> unique. Conservez-le précieusement pour suivre l'état de votre demande.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Guide 2: Suivre sa demande -->
    <div class="bg-white rounded-2xl border border-gray-200 p-8 mb-8 shadow-sm">
        <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <span class="text-2xl font-bold text-amber-600">2</span>
            </div>
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Comment suivre votre demande</h2>
                <p class="text-gray-500">Consultez l'état de votre demande à tout moment.</p>
            </div>
        </div>
        
        <div class="space-y-6 ml-16">
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shrink-0 text-white font-semibold text-sm">1</div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-1">Accédez à la page de suivi</h3>
                    <p class="text-gray-600">Cliquez sur <a href="demande.php" class="text-canope-green hover:underline">Suivre ma demande</a> dans le menu de navigation.</p>
                </div>
            </div>
            
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shrink-0 text-white font-semibold text-sm">2</div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-1">Entrez votre token</h3>
                    <p class="text-gray-600">Saisissez le token de suivi que vous avez reçu lors de votre demande dans le champ prévu à cet effet.</p>
                </div>
            </div>
            
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shrink-0 text-white font-semibold text-sm">3</div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-1">Consultez les détails</h3>
                    <p class="text-gray-600">Vous verrez le statut actuel de votre demande, les informations du demandeur, les dotations demandées et l'historique complet.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statuts expliqués -->
    <div class="bg-white rounded-2xl border border-gray-200 p-8 mb-8 shadow-sm">
        <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Comprendre les statuts</h2>
                <p class="text-gray-500">Les différentes étapes du traitement de votre demande.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-16">
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium mb-2">En attente</span>
                <p class="text-gray-600 text-sm">Votre demande a été reçue et est en cours de vérification par notre équipe.</p>
            </div>
            
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-2">Vérifiée</span>
                <p class="text-gray-600 text-sm">Les informations ont été validées, la demande passe en traitement.</p>
            </div>
            
            <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium mb-2">Approuvée</span>
                <p class="text-gray-600 text-sm">Demande acceptée !</p>
            </div>
            
            <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium mb-2">Envoyée</span>
                <p class="text-gray-600 text-sm">Colis expédié</p>
            </div>
            
            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium mb-2">Livrée</span>
                <p class="text-gray-600 text-sm">La dotation a été réceptionnée. Bonne utilisation !</p>
            </div>
            
            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium mb-2">Rejetée</span>
                <p class="text-gray-600 text-sm">La demande n'a pas pu être acceptée. Contactez-nous pour plus d'informations.</p>
            </div>
        </div>
    </div>
    
    <!-- Tips -->
    <div class="bg-gradient-to-br from-canope-green/5 to-canope-olive/5 rounded-2xl border border-canope-dark/20 p-8">
        <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 bg-canope-green/10 rounded-xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Conseils pratiques</h2>
                <p class="text-gray-500">Quelques astuces pour optimiser vos demandes.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-16">
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-gray-600">Vérifiez la disponibilité des ressources avant de faire une demande groupée.</p>
            </div>
            
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-gray-600">Utilisez le champ commentaire pour préciser votre projet pédagogique.</p>
            </div>
            
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-gray-600">Conservez votre token de suivi dans un endroit sûr.</p>
            </div>
            
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-gray-600">Soyez patient, votre demande sera traitée dans les plus brefs délais.</p>
            </div>
        </div>
    </div>
    
</div>

<!-- CTA -->
<div class="max-w-4xl mx-auto px-5 pb-12">
    <div class="bg-gradient-to-r from-canope-dark to-canope-gray rounded-2xl p-8 md:p-12 text-center text-white">
        <h2 class="text-2xl md:text-3xl font-semibold mb-4">Prêt à faire votre demande ?</h2>
        <p class="text-white/80 mb-6">Parcourez notre catalogue et sélectionnez vos ressources pédagogiques.</p>
        <a href="donations.php" class="inline-flex items-center gap-2 bg-canope-teal text-white px-6 py-3 rounded-full font-semibold transition-colors shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Voir les dotations
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
