// Main JavaScript file
console.log('Dotation Platform loaded');

// Cart functionality using localStorage
function getCart() {
    const cart = localStorage.getItem('canope_cart');
    return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) {
    localStorage.setItem('canope_cart', JSON.stringify(cart));
    updateCartCount();
}

function addToCart(productId, productName) {
    const cart = getCart();
    const existingItem = cart.find(item => item.id === productId);

    if (existingItem) {
        // Already in selection - just notify
        showNotification(`"${productName}" est déjà dans votre sélection`);
        return;
    }

    cart.push({
        id: productId,
        name: productName
    });

    saveCart(cart);

    // Visual feedback - show toast notification
    showNotification(`"${productName}" ajouté à votre sélection`);
}

function removeFromCart(productId) {
    let cart = getCart();
    cart = cart.filter(item => item.id !== productId);
    saveCart(cart);

    // Refresh cart display if on cart page
    if (typeof displayCart === 'function') {
        displayCart();
    }
}

function updateQuantity(productId, newQuantity) {
    const cart = getCart();
    const item = cart.find(item => item.id === productId);

    if (item) {
        if (newQuantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = newQuantity;
            saveCart(cart);
        }
    }

    // Refresh cart display if on cart page
    if (typeof displayCart === 'function') {
        displayCart();
    }
}

function clearCart() {
    localStorage.removeItem('canope_cart');
    updateCartCount();
}

function updateCartCount() {
    const cart = getCart();
    const totalItems = cart.length;
    const cartCountElement = document.getElementById('cart-count');

    if (cartCountElement) {
        if (totalItems > 0) {
            cartCountElement.textContent = totalItems;
            cartCountElement.classList.remove('hidden');
        } else {
            cartCountElement.classList.add('hidden');
        }
    }
}

function showNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'fixed bottom-5 right-5 bg-canope-green text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-y-full opacity-0 transition-all duration-300';
    notification.textContent = message;
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-y-full', 'opacity-0');
    }, 10);

    // Remove after 2 seconds
    setTimeout(() => {
        notification.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

// Update cart count on page load
document.addEventListener('DOMContentLoaded', updateCartCount);
