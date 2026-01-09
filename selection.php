<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// GET les informations de l'utilisateur connecté
$currentUser = getCurrentUser();
$userEmail = $currentUser ? htmlspecialchars($currentUser['email']) : '';
$userEtablissement = $currentUser ? htmlspecialchars($currentUser['etablissement']) : '';

include 'includes/header.php';
?>

<!-- section header -->
<div class="bg-gradient-to-r from-canope-green to-canope-olive py-10 px-5">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h1 class="text-3xl font-semibold text-white">Faire une demande</h1>
        </div>
        <p class="text-white/80 text-sm ml-13">Remplissez le formulaire ci-dessous pour demander une ou plusieurs dotations</p>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-6xl mx-auto px-5 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Left Column - Form -->
        <div class="flex-1 space-y-6">
            
            <!-- Vos informations -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Vos informations</h2>
                </div>
                
                <form id="request-form" onsubmit="submitRequest(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" required placeholder="Dupont"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-green focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" required placeholder="Marie"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-green focus:border-transparent transition-all">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Établissement <span class="text-red-500">*</span></label>
                        <input type="text" name="establishment_name" required placeholder="École Primaire de..."
                               value="<?= $userEtablissement ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-green focus:border-transparent transition-all">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required placeholder="email@ac-corse.fr"
                                   value="<?= $userEmail ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-green focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone portable <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" required placeholder="0612345678"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-green focus:border-transparent transition-all">
                        </div>
                    </div>
                    
                    <!-- Hidden fields -->
                    <input type="hidden" name="cart_data" id="cart-data-input">
                    <input type="hidden" name="request_type" id="request-type-input" value="RECEVOIR">
                </form>
            </div>
            
            <!-- Type de demande -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Type de demande</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="button" id="type-livraison" onclick="selectRequestType('RECEVOIR')"
                            class="request-type-btn selected p-4 rounded-xl border-2 border-canope-green bg-canope-green/5 text-center transition-all hover:border-canope-green">
                        <span class="block font-semibold text-gray-800">Livraison</span>
                        <span class="text-sm text-gray-500">Première demande de dotation</span>
                    </button>
                    <button type="button" id="type-reassort" onclick="selectRequestType('REASSORT')"
                            class="request-type-btn p-4 rounded-xl border-2 border-gray-200 bg-white text-center transition-all hover:border-canope-green/50">
                        <span class="block font-semibold text-gray-800">Réassort</span>
                        <span class="text-sm text-gray-500">Renouvellement ou complément</span>
                    </button>
                </div>
            </div>
            
            <!-- Dotations demandées -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Dotations demandées</h2>
                </div>
                
                <div id="cart-items" class="space-y-3">
                    <!-- Cart items will be dynamically inserted here -->
                </div>
                
                <div id="cart-empty" class="text-center py-8 hidden">
                    <span class="text-4xl mb-3 block">📋</span>
                    <p class="text-gray-500">Aucune dotation sélectionnée</p>
                    <a href="donations.php" class="text-canope-green hover:underline text-sm mt-2 inline-block">← Parcourir les dotations</a>
                </div>
            </div>
            
            <!-- Commentaire -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Commentaire (optionnel)</h2>
                </div>
                
                <textarea name="comment" form="request-form" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-green focus:border-transparent transition-all resize-none"
                          placeholder="Précisez votre projet pédagogique, contraintes de dates, etc."></textarea>
            </div>
            
        </div>
        
        <!-- Right Column - Récapitulatif -->
        <div class="lg:w-80">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm sticky top-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-6">Récapitulatif</h2>
                
                <div id="recap-items" class="space-y-3 mb-6">
                    <p id="recap-empty" class="text-gray-400 text-sm text-center py-4">Aucune dotation sélectionnée</p>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mb-6">
                    <label for="privacy-checkbox" class="flex items-start gap-3 cursor-pointer group">
                        <input id="privacy-checkbox" type="checkbox" class="peer hidden" />
                        <div class="h-5 w-5 flex-shrink-0 flex rounded-md border border-gray-300 bg-gray-50 peer-checked:bg-canope-green peer-checked:border-canope-green transition mt-0.5">
                            <svg
                                fill="none"
                                viewBox="0 0 24 24"
                                class="w-5 h-5 stroke-gray-50 peer-checked:stroke-white"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M4 12.6111L8.92308 17.5L20 6.5"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-600 group-hover:text-gray-800 transition-colors">
                            J'accepte la <a href="#" class="text-canope-green hover:underline">politique de confidentialité</a> et le traitement de mes données personnelles. <span class="text-red-500">*</span>
                        </span>
                    </label>
                </div>
                
                <button type="button" onclick="submitFormFromSidebar()" id="submit-btn"
                        class="w-full bg-canope-green text-white py-4 rounded-xl font-semibold text-base hover:bg-canope-olive transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-canope-green/20">
                    Envoyer ma demande
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
                
                <p id="selection-warning" class="text-sm text-gray-500 text-center mt-4 flex items-center justify-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Sélectionnez au moins une dotation
                </p>
            </div>
        </div>
        
    </div>
</div>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8 text-center animate-slide-up">
        <div class="w-16 h-16 bg-canope-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-canope-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Demande envoyée !</h2>
        <p class="text-gray-600 mb-6">Votre demande de dotation a été enregistrée. Vous recevrez une confirmation par email.</p>
        <a href="donations.php" class="inline-block bg-canope-green text-white px-6 py-3 rounded-xl font-semibold hover:bg-canope-olive transition-colors">
            Découvrir d'autres dotations
        </a>
    </div>
</div>

<style>
    .request-type-btn.selected {
        border-color: #3A6B56;
        background-color: rgba(58, 107, 86, 0.05);
    }
    
    /* Custom checkbox styling */
    #privacy-checkbox:checked + div {
        background-color: #3A6B56;
        border-color: #3A6B56;
    }
    
    #privacy-checkbox:checked + div svg {
        stroke: white;
    }
    
    #privacy-checkbox + div svg {
        stroke: transparent;
    }
</style>

<script>
// Request type selection
function selectRequestType(type) {
    document.getElementById('request-type-input').value = type;
    
    const livraisonBtn = document.getElementById('type-livraison');
    const reassortBtn = document.getElementById('type-reassort');
    
    if (type === 'RECEVOIR') {
        livraisonBtn.classList.add('selected', 'border-canope-green', 'bg-canope-green/5');
        livraisonBtn.classList.remove('border-gray-200', 'bg-white');
        reassortBtn.classList.remove('selected', 'border-canope-green', 'bg-canope-green/5');
        reassortBtn.classList.add('border-gray-200', 'bg-white');
    } else {
        reassortBtn.classList.add('selected', 'border-canope-green', 'bg-canope-green/5');
        reassortBtn.classList.remove('border-gray-200', 'bg-white');
        livraisonBtn.classList.remove('selected', 'border-canope-green', 'bg-canope-green/5');
        livraisonBtn.classList.add('border-gray-200', 'bg-white');
    }
}

// Display cart items on page load
function displayCart() {
    const cart = getCart();
    const cartItemsContainer = document.getElementById('cart-items');
    const cartEmpty = document.getElementById('cart-empty');
    const recapItems = document.getElementById('recap-items');
    const recapEmpty = document.getElementById('recap-empty');
    const selectionWarning = document.getElementById('selection-warning');
    
    if (cart.length === 0) {
        cartEmpty.classList.remove('hidden');
        cartItemsContainer.innerHTML = '';
        recapItems.innerHTML = '<p id="recap-empty" class="text-gray-400 text-sm text-center py-4">Aucune dotation sélectionnée</p>';
        selectionWarning.classList.remove('hidden');
        return;
    }
    
    cartEmpty.classList.add('hidden');
    selectionWarning.classList.add('hidden');
    
    // Cart items (left column)
    let cartHtml = '';
    cart.forEach(item => {
        cartHtml += `
            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100 group hover:border-canope-green/30 transition-all">
                <div class="flex-1">
                    <h3 class="font-medium text-gray-800">${item.name}</h3>
                </div>
                <button onclick="removeFromCart(${item.id})"
                        class="text-gray-400 hover:text-red-500 transition-colors p-2 opacity-0 group-hover:opacity-100" title="Retirer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `;
    });
    cartItemsContainer.innerHTML = cartHtml;
    
    // Recap items (right column)
    let recapHtml = '';
    cart.forEach(item => {
        recapHtml += `
            <div class="flex items-center gap-3 p-2 rounded-lg bg-gray-50">
                <div class="w-2 h-2 bg-canope-green rounded-full"></div>
                <span class="text-sm text-gray-700 truncate flex-1">${item.name}</span>
            </div>
        `;
    });
    recapItems.innerHTML = recapHtml;
}

function submitFormFromSidebar() {
    const cart = getCart();
    const privacyCheckbox = document.getElementById('privacy-checkbox');
    const form = document.getElementById('request-form');
    
    if (cart.length === 0) {
        alert('Veuillez sélectionner au moins une dotation.');
        return;
    }
    
    if (!privacyCheckbox.checked) {
        alert('Veuillez accepter la politique de confidentialité.');
        return;
    }
    
    // Check form validity
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Set cart data
    document.getElementById('cart-data-input').value = JSON.stringify(cart);
    
    // Submit the form
    submitRequest(new Event('submit'));
}

function submitRequest(event) {
    event.preventDefault();
    
    const form = document.getElementById('request-form');
    const formData = new FormData(form);
    
    // Add cart data
    formData.set('cart_data', JSON.stringify(getCart()));
    
    // Submit to backend
    fetch('submit_request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('success-modal').classList.remove('hidden');
            clearCart();
            displayCart();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur de connexion. Veuillez réessayer.');
        console.error(error);
    });
}

// Initialize cart display
document.addEventListener('DOMContentLoaded', displayCart);
</script>

<?php include 'includes/footer.php'; ?>
