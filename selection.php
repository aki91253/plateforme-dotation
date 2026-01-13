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
<div class="bg-gradient-to-r from-canope-gray to-canope-teal py-10 px-5">
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

<!--Contenu principal -->
<div class="max-w-6xl mx-auto px-5 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Colonne de gauche - Formulaire -->
        <div class="flex-1 space-y-6">
            
            <!-- Vos informations -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-gray" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Vos informations</h2>
                </div>
                
                <form id="request-form" onsubmit="submitRequest(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" required placeholder="Dupont"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-slate focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" required placeholder="Marie"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-slate focus:border-transparent transition-all">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Établissement <span class="text-red-500">*</span></label>
                        <input type="text" name="establishment_name" required placeholder="École Primaire de..."
                               value="<?= $userEtablissement ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-slate focus:border-transparent transition-all">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required placeholder="email@ac-corse.fr"
                                   value="<?= $userEmail ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-slate focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone portable <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" required placeholder="0612345678"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-slate focus:border-transparent transition-all">
                        </div>
                    </div>
                    
                    <!-- champs cachés -->
                    <input type="hidden" name="cart_data" id="cart-data-input">
                    <input type="hidden" name="request_type" id="request-type-input" value="RECEVOIR">
                </form>
            </div>
            
            
            <!-- Dotations demandées -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Dotations demandées</h2>
                </div>
                
                <div id="cart-items" class="space-y-3">
                <!-- Les items du panier seront insérés ici -->
                </div>
                
                <div id="cart-empty" class="text-center py-8 hidden">
                    <span class="text-4xl mb-3 block">📋</span>
                    <p class="text-gray-500">Aucune dotation sélectionnée</p>
                    <a href="donations.php" class="text-canope-slate hover:underline text-sm mt-2 inline-block">← Parcourir les dotations</a>
                </div>
            </div>
            
            <!-- Commentaire -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-canope-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Commentaire</h2>
                </div>
                
                <textarea name="comment" form="request-form" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-canope-gray focus:border-transparent transition-all resize-none"
                          placeholder="Précisez votre projet pédagogique, contraintes de dates, etc."></textarea>
            </div>
            
        </div>
        
        <!-- Colonne de droite - Récapitulatif -->
        <div class="lg:w-80">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm sticky top-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-6">Récapitulatif</h2>
                
                <div id="recap-items" class="space-y-3 mb-6">
                    <p id="recap-empty" class="text-gray-400 text-sm text-center py-4">Aucune dotation sélectionnée</p>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mb-6">
                    <label for="privacy-checkbox" class="flex items-start gap-3 cursor-pointer group">
                        <input id="privacy-checkbox" type="checkbox" class="peer hidden" />
                        <div class="h-5 w-5 flex-shrink-0 flex rounded-md border border-gray-300 bg-gray-50 peer-checked:bg-canope-gray peer-checked:border-canope-gray transition mt-0.5">
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
                            J'accepte la <a href="policy.php" class="text-canope-gray hover:underline">politique de confidentialité</a> et le traitement de mes <a href="policy.php#article3" class="text-canope-slate hover:underline">données personnelles</a>. <span class="text-red-500">*</span>
                        </span>
                    </label>
                </div>
                <!-- Bouton d'envoi -->
                <div id="submit-btn-container" class="relative inline-flex items-center justify-center gap-4 group w-full">
                    <div
                        class="absolute inset-0 duration-1000 opacity-60 transitiona-all rounded-xl blur-lg filter"
                    ></div>
                    <button
                        type="button"
                        onclick="submitFormFromSidebar()"
                        id="submit-btn"
                        disabled
                        class="group relative inline-flex items-center justify-center text-base rounded-xl bg-canope-slate px-8 py-3 font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 w-full disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                        title="Envoyer ma demande"
                    >Envoyer ma demande<svg
                        aria-hidden="true"
                        viewBox="0 0 10 10"
                        height="10"
                        width="10"
                        fill="none"
                        class="mt-0.5 ml-2 -mr-1 stroke-white stroke-2"
                    >
                        <path
                            d="M0 5h7"
                            class="transition opacity-0"
                        ></path>
                        <path
                            d="M1 1l4 4-4 4"
                            class="transition"
                        ></path>
                    </svg>
                    </button>
                </div>
                
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

<!-- Modal de confirmation -->
<div id="success-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8 text-center animate-slide-up">
        <div class="w-16 h-16 bg-canope-gray/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-canope-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Demande envoyée !</h2>
        <p class="text-gray-600 mb-4">Votre demande de dotation a été enregistrée. Voici votre numéro de suivi :</p>
        
        <!-- Token Display -->
        <div class="bg-gray-50 border-2 border-dashed border-canope-gray/30 rounded-xl p-4 mb-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Token de suivi</p>
            <p id="request-token" class="text-lg font-mono font-bold text-canope-slate break-all"></p>
        </div>
        
        <p class="text-sm text-gray-500 mb-6">Conservez ce token pour suivre l'état de votre demande.</p>
        
        <a href="donations.php" class="inline-block bg-canope-gray text-white px-6 py-3 rounded-xl font-semibold hover:bg-canope-slate transition-colors">
            Découvrir d'autres dotations
        </a>
    </div>
</div>

<style>
    .request-type-btn.selected {
        border-color: #3A6B56;
        background-color: rgba(58, 107, 86, 0.05);
    }
    
    /* Style du checkbox custom */
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

// Affichage des éléments du panier au chargement de la page
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
    
    // Affichage des éléments du panier (colonne de gauche)
    let cartHtml = '';
    cart.forEach(item => {
        const qty = item.quantity || 1;
        cartHtml += `
            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100 group hover:border-canope-slate/30 transition-all">
                <div class="flex-1">
                    <h3 class="font-medium text-gray-800">${item.name}</h3>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-lg">
                        <button onclick="updateQuantity(${item.id}, ${qty - 1})"
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-canope-slate hover:bg-gray-50 rounded-l-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                            </svg>
                        </button>
                        <span class="w-8 text-center font-medium text-gray-800">${qty}</span>
                        <button onclick="updateQuantity(${item.id}, ${qty + 1})"
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-canope-slate hover:bg-gray-50 rounded-r-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <button onclick="removeFromCart(${item.id})"
                            class="text-gray-400 hover:text-red-500 transition-colors p-2" title="Retirer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
    });
    cartItemsContainer.innerHTML = cartHtml;
    
    // Recap des items (colonne de droite)
    let recapHtml = '';
    cart.forEach(item => {
        recapHtml += `
            <div class="flex items-center gap-3 p-2 rounded-lg bg-gray-50">
                <div class="w-2 h-2 bg-canope-gray rounded-full"></div>
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
    
    // Vérification de la validité du formulaire
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Set données du panier
    document.getElementById('cart-data-input').value = JSON.stringify(cart);
    
    // Submit le formulaire
    submitRequest(new Event('submit'));
}

function submitRequest(event) {
    event.preventDefault();
    
    const form = document.getElementById('request-form');
    const formData = new FormData(form);
    
    // Ajout des données du panier
    formData.set('cart_data', JSON.stringify(getCart()));
    
    // Submit au backend
    fetch('submit_request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Display the token in the modal
            document.getElementById('request-token').textContent = data.token;
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

// Initialisation de l'affichage du panier
document.addEventListener('DOMContentLoaded', function() {
    displayCart();
    
    // Gestion de l'état du bouton en fonction du checkbox
    const privacyCheckbox = document.getElementById('privacy-checkbox');
    const submitBtn = document.getElementById('submit-btn');
    const gradientGlow = document.querySelector('#submit-btn-container > div:first-child');
    
    function updateButtonState() {
        if (privacyCheckbox.checked) {
            submitBtn.disabled = false;
            gradientGlow.classList.remove('opacity-0');
            gradientGlow.classList.add('opacity-60');
        } else {
            submitBtn.disabled = true;
            gradientGlow.classList.remove('opacity-60');
            gradientGlow.classList.add('opacity-0');
        }
    }
    
    privacyCheckbox.addEventListener('change', updateButtonState);
    updateButtonState(); // État initial
});
</script>

<?php include 'includes/footer.php'; ?>
