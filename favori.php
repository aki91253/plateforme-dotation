<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-5 py-8">
    <h1 class="text-4xl font-normal mb-8 text-gray-900">Mes favoris</h1>
    
    <!-- Favoris Container -->
    <div id="favorites-container">
        <div id="favorites-empty" class="text-center py-16 hidden">
            <span class="text-6xl mb-4 block">💛</span>
            <p class="text-gray-500 text-lg">Vous n'avez pas encore ajouté de favoris.</p>
            <a href="donations.php" class="text-canope-green hover:underline mt-2 inline-block">← Parcourir les dotations</a>
        </div>
        
        <div id="favorites-items" class="space-y-4">
            <!-- Favoris items seront ajoutés dynamiquement ici -->
        </div>
    </div>
</div>

<script>
// Récupérer les favoris depuis localStorage
function getFavorites() {
    return JSON.parse(localStorage.getItem('favorites') || '[]');
}

// Retirer un favori
function removeFavorite(id) {
    let favorites = getFavorites();
    favorites = favorites.filter(item => item.id !== id);
    localStorage.setItem('favorites', JSON.stringify(favorites));
    displayFavorites();
}

// Afficher les favoris
function displayFavorites() {
    const favorites = getFavorites();
    const container = document.getElementById('favorites-items');
    const empty = document.getElementById('favorites-empty');

    if (favorites.length === 0) {
        empty.classList.remove('hidden');
        container.innerHTML = '';
        return;
    }

    empty.classList.add('hidden');

    let html = '';
    favorites.forEach(item => {
        html += `
            <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">${item.name}</h3>
                </div>
                <button onclick="removeFavorite(${item.id})"
                        class="text-red-500 hover:text-red-700 transition-colors p-2" title="Retirer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `;
    });

    container.innerHTML = html;
}

// Initialisation à la charge de la page
document.addEventListener('DOMContentLoaded', displayFavorites);
</script>

<?php include 'includes/footer.php'; ?>
