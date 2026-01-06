<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-5 py-8">
    <h1 class="text-4xl font-normal mb-8 text-gray-900">Ma sélection</h1>
    
    <!-- Cart Items Container -->
    <div id="cart-container">
        <div id="cart-empty" class="text-center py-16 hidden">
            <span class="text-6xl mb-4 block">📋</span>
            <p class="text-gray-500 text-lg">Votre sélection est vide.</p>
            <a href="donations.php" class="text-canope-green hover:underline mt-2 inline-block">← Parcourir les dotations</a>
        </div>
        
        <div id="cart-items" class="space-y-4">
            <!-- Cart items will be dynamically inserted here -->
        </div>
        
        <!-- Cart Summary & Submit Form -->
        <div id="cart-summary" class="mt-8 hidden">
            <div class="border-t-2 border-gray-200 pt-6">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-xl font-semibold text-gray-800">Dotations sélectionnées :</span>
                    <span id="total-items" class="text-2xl font-bold text-canope-green">0</span>
                </div>
                
                <button onclick="showRequestForm()" 
                        class="w-full bg-canope-green text-white py-4 rounded-xl font-semibold text-lg hover:bg-canope-olive transition-colors shadow-lg">
                    Valider ma demande
                </button>
            </div>
        </div>
    </div>
    
    <!-- Request Form Modal -->
    <div id="request-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Formulaire de demande</h2>
                    <button onclick="hideRequestForm()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
            </div>
            
            <form id="request-form" class="p-6 space-y-4" onsubmit="submitRequest(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Contact Info -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-canope-green focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="tel" name="phone"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-canope-green focus:border-transparent">
                    </div>
                </div>
                
                <!-- Establishment Info -->
                <div class="pt-4 border-t border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-3">Établissement</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'établissement *</label>
                            <input type="text" name="establishment_name" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-canope-green focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la classe *</label>
                            <input type="text" name="establishment_name" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-canope-green focus:border-transparent">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                                <input type="text" name="establishment_postal"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-canope-green focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                <input type="text" name="establishment_city"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-canope-green focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Request Type -->
                <div class="pt-4 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de demande *</label>
                    <select name="request_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-canope-green focus:border-transparent">
                        <option value="RECEVOIR">Première dotation</option>
                        <option value="REASSORT">Réassort</option>
                    </select>
                </div>
                
                <!-- Comment -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                    <textarea name="comment" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-canope-green focus:border-transparent"
                              placeholder="Informations supplémentaires..."></textarea>
                </div>
                
                <!-- Hidden cart data -->
                <input type="hidden" name="cart_data" id="cart-data-input">
                
                <button type="submit"
                        class="w-full bg-canope-green text-white py-3 rounded-xl font-semibold text-lg hover:bg-canope-olive transition-colors">
                    Envoyer ma demande
                </button>
            </form>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8 text-center">
            <span class="text-6xl block mb-4">✅</span>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Demande envoyée !</h2>
            <p class="text-gray-600 mb-6">Votre demande de dotation a été enregistrée. Vous recevrez une confirmation par email.</p>
            <a href="donations.php" class="inline-block bg-canope-green text-white px-6 py-3 rounded-xl font-semibold hover:bg-canope-olive transition-colors">
                Découvrir d'autres dotations
            </a>
        </div>
    </div>
</div>

<script>
// Display cart items on page load
function displayCart() {
    const cart = getCart();
    const cartItemsContainer = document.getElementById('cart-items');
    const cartEmpty = document.getElementById('cart-empty');
    const cartSummary = document.getElementById('cart-summary');
    const totalItemsEl = document.getElementById('total-items');
    
    if (cart.length === 0) {
        cartEmpty.classList.remove('hidden');
        cartSummary.classList.add('hidden');
        cartItemsContainer.innerHTML = '';
        return;
    }
    
    cartEmpty.classList.add('hidden');
    cartSummary.classList.remove('hidden');
    
    let html = '';
    
    cart.forEach(item => {
        html += `
            <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">${item.name}</h3>
                </div>
                <button onclick="removeFromCart(${item.id})"
                        class="text-red-500 hover:text-red-700 transition-colors p-2" title="Retirer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `;
    });
    
    cartItemsContainer.innerHTML = html;
    totalItemsEl.textContent = cart.length + ' dotation' + (cart.length > 1 ? 's' : '');
}

function showRequestForm() {
    document.getElementById('cart-data-input').value = JSON.stringify(getCart());
    document.getElementById('request-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideRequestForm() {
    document.getElementById('request-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

function submitRequest(event) {
    event.preventDefault();
    
    // In a real application, you would send this data to a PHP endpoint
    // For now, we'll simulate a successful submission
    
    hideRequestForm();
    document.getElementById('success-modal').classList.remove('hidden');
    
    // Clear the cart
    clearCart();
    displayCart();
}

// Initialize cart display
document.addEventListener('DOMContentLoaded', displayCart);
</script>

<?php include 'includes/footer.php'; ?>
