console.log('Dotation Platform loaded');

//-------------------
//Méthode pour cart 
//-------------------
function getCart() { // 1.1 -- Méthode pour obtenir le panier
    const cart = localStorage.getItem('canope_cart');
    return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) { //2.1 -- Méthode pour sauvegarder le panier quand on ajoute 
    localStorage.setItem('canope_cart', JSON.stringify(cart));
    updateCartCount();
}

function addToCart(productId, productName, quantity = 1) {//3.1 -- Méthode pour ajouter un produit dans le paniern (Id, nom du produit, quantité)
    const cart = getCart();
    const existingItem = cart.find(item => item.id === productId);

    if (existingItem) { // 3.2 -- Si le produit existe déjà, on met à jour la quantité
        existingItem.quantity = quantity;
        saveCart(cart);
        showNotification(`Quantité de "${productName}" mise à jour (${quantity})`);
        return;
    }

    cart.push({ id: productId, name: productName, quantity: quantity }); // 3.3 --  Sinon on ajoute le produit dans le panier 
    saveCart(cart);
    showNotification(`"${productName}" ajouté à votre sélection`);
}

function removeFromCart(productId) {//4.1 -- Méthode pour supprimer un produit du panier ( La croix )
    let cart = getCart();
    cart = cart.filter(item => item.id !== productId);
    saveCart(cart);

    if (typeof displayCart === 'function') displayCart();
}

function updateQuantity(productId, newQuantity) { // 5.1 -- Méthode pour mettre à jour la quantité du panier lors d'un "+" ou "-"
    const cart = getCart();
    const item = cart.find(item => item.id === productId);

    if (item) { // 5.2 -- Vérifie si l'item existe 
        if (newQuantity <= 0) removeFromCart(productId); // 5.3 --  Vérifie si la quantité ne descend pas en dessous de 0 ou égale 0 --> alors suppression du produit
        else { item.quantity = newQuantity; saveCart(cart); } // 5.4 --  Sinon on change la valeur de la quantité du produit avec la nouvelle 
    }

    if (typeof displayCart === 'function') displayCart();
}

function clearCart() { // 6.1 -- Méthode pour vider entièrement le panier
    localStorage.removeItem('canope_cart');
    updateCartCount();
}

function updateCartCount() { // 7.1 -- Méthode pour mettre à jour le nombre d'éléments du panier ( pour la petite bulle sur l'icone du panier )
    const cart = getCart();
    const totalItems = cart.length;
    const cartCountElement = document.getElementById('cart-count');

    if (cartCountElement) { // 7.2 -- Vérifie si l'élément existe 
        if (totalItems > 0) { // 7.3 -- Vérifie si il y a au moins 1 élément
            cartCountElement.textContent = totalItems;
            cartCountElement.classList.remove('hidden');
        } else { // 7.4 -- Sinon si le panier est vide cache la bulle 
            cartCountElement.classList.add('hidden');
        }
    }
}



// ====================
// Notifications (Toast)
// ====================
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed bottom-5 right-5 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-y-full transition-all duration-300';
    notification.style.backgroundColor = '#0B162C';
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => { notification.classList.remove('translate-y-full'); }, 10);
    setTimeout(() => {
        notification.classList.add('translate-y-full');
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
