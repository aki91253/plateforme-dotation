console.log('Dotation Platform loaded');

//-------------------
//Méthode pour cart 


function getCart() { // Méthode pour obtenir la sélection 
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
        showNotification(`"${productName}" est déjà dans votre sélection`);
        return;
    }

    cart.push({ id: productId, name: productName });
    saveCart(cart);
    showNotification(`"${productName}" ajouté à votre sélection`);
}

function removeFromCart(productId) {
    let cart = getCart();
    cart = cart.filter(item => item.id !== productId);
    saveCart(cart);

    if (typeof displayCart === 'function') displayCart();
}

function updateQuantity(productId, newQuantity) {
    const cart = getCart();
    const item = cart.find(item => item.id === productId);

    if (item) {
        if (newQuantity <= 0) removeFromCart(productId);
        else { item.quantity = newQuantity; saveCart(cart); }
    }

    if (typeof displayCart === 'function') displayCart();
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

//--------------------
// Méthdode pour les favoris 
//_------------------



function getFav() {
    const fav = localStorage.getItem('canope_fav');
    return fav ? JSON.parse(fav) : [];
}

function saveFav(fav) {
    localStorage.setItem('canope_fav', JSON.stringify(fav));
    updateFavCount();
}

function addToFav(productId, productName) {
    const fav = getFav();
    const existingItem = fav.find(item => item.id === productId);

    if (existingItem) {
        showNotification(`"${productName}" est déjà dans vos favoris`);
        return;
    }

    fav.push({ id: productId, name: productName });
    saveFav(fav);
    showNotification(`"${productName}" ajouté à vos favoris`);
}

function removeFromFav(productId) {
    let fav = getFav();
    fav = fav.filter(item => item.id !== productId);
    saveFav(fav);

    if (typeof displayFav === 'function') displayFav();
}

function clearFav() {
    localStorage.removeItem('canope_fav');
    updateFavCount();
}

function updateFavCount() {
    const fav = getFav();
    const totalItems = fav.length;
    const favCountElement = document.getElementById('fav-count');

    if (favCountElement) {
        if (totalItems > 0) {
            favCountElement.textContent = totalItems;
            favCountElement.classList.remove('hidden');
        } else {
            favCountElement.classList.add('hidden');
        }
    }
}

// ====================
// Notifications (Toast)
// ====================
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed bottom-5 right-5 bg-canope-green text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-y-full opacity-0 transition-all duration-300';
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => { notification.classList.remove('translate-y-full', 'opacity-0'); }, 10);
    setTimeout(() => {
        notification.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

// ====================
// Init counts on load
// ====================
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    updateFavCount();
});
