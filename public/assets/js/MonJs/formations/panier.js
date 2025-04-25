(function() {
    // Première action: récupérer le compte du localStorage pour affichage immédiat
    var storedCount = parseInt(localStorage.getItem('cartCount') || '0');
    window.globalCartCount = storedCount;
    
    // Injecter style pour gérer l'affichage des badges
    var style = document.createElement('style');
    style.innerHTML = `
        .cart-badge, .custom-violet-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #2563EB;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            z-index: 10;
            opacity: 1 !important;
            transition: none !important;
        }
        /* Style pour le bouton réserver sans transition */
        .reserver-button {
            display: block;
            opacity: 1 !important;
            transition: none !important;
        }
        /* Assurer que les compteurs sont visibles immédiatement */
        .panier-count {
            opacity: 1 !important;
            transition: none !important;
        }
    `;
    document.head.appendChild(style);
    // Exécuter immédiatement les mises à jour d'affichage
    updateCartCountDisplay(storedCount);
    createAllBadges(storedCount);
    
    // CORRECTION IMPORTANTE: Créer immédiatement le bouton de réservation si nécessaire
    if (storedCount > 0 && !window.hasExistingReservation && 
        !document.querySelector('.reserver-button') && 
        !document.querySelector('.annuler-button')) {
        // Créer d'abord le bouton sans attendre la vérification
        createReserverButton();
        
        // Ensuite vérifier si l'utilisateur a une réservation existante
        if (typeof checkExistingReservations === 'function') {
            checkExistingReservations().then(hasReservation => {
                if (hasReservation) {
                    // Si une réservation existe, supprimer le bouton "Réserver" qui vient d'être créé
                    const reserveButton = document.querySelector('.reserver-button');
                    if (reserveButton) {
                        reserveButton.remove();
                    }
                }
            });
        }
    }
    
    // Ensuite vérifier avec le serveur pour une synchronisation
    setTimeout(fetchCartItemsCount, 10);
    
    
    // Établir les écouteurs d'événements DOM dès que possible
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeListeners);
    } else {
        initializeListeners();
    }
    
    function initializeListeners() {
        setupRemoveFromCartListeners();
        
        // Rafraîchissement périodique et lors de retour sur la page
        setInterval(refreshCartBadge, 5000);
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                refreshCartBadge();
            }
        });
    }
})();

// Fonction pour créer tous les badges dès le début
function createAllBadges(count) {
    if (count <= 0) return;
    
    // Créer le badge même avant que le DOM ne soit complètement chargé
    var intervalId = setInterval(function() {
        var cartIcons = document.querySelectorAll('.shopping-cart-icon, svg[data-icon="shopping-cart"], .cart-icon, a[href*="panier"] svg, .cart-container svg');
        
        if (cartIcons.length > 0) {
            cartIcons.forEach(function(icon) {
                const cartContainer = icon.closest('a, div, button, .cart-container');
                if (cartContainer && !cartContainer.querySelector('.cart-badge, .custom-violet-badge')) {
                    createBadgeForContainer(cartContainer, count);
                }
            });
            clearInterval(intervalId);
        }
    }, 5);
}

// Créer un badge pour un conteneur spécifique
function createBadgeForContainer(container, count) {
    if (!container || count <= 0) return;
    
    let badge = document.createElement('span');
    badge.className = 'cart-badge custom-violet-badge';
    badge.textContent = count.toString();
    
    if (getComputedStyle(container).position === 'static') {
        container.style.position = 'relative';
    }
    
    container.appendChild(badge);
}

// Fonction pour mettre à jour visuellement le compteur (sans faire de requête)
function updateCartCountDisplay(count) {
    // Mettre à jour tous les compteurs textuels
    var panierCountElements = document.querySelectorAll('.panier-count');
    panierCountElements.forEach(function(el) {
        el.textContent = count + ' formation(s)';
        el.style.opacity = '1'; // Visible immédiatement
    });
    
    // Mettre à jour le badge si présent
    updateBadge(count);
}

// Mise à jour du badge visuel
function updateBadge(count) {
    var badges = document.querySelectorAll('.cart-badge, .custom-violet-badge');
    
    if (count <= 0) {
        badges.forEach(function(badge) {
            badge.style.display = 'none';
        });
        return;
    }
    
    if (badges.length > 0) {
        badges.forEach(function(badge) {
            badge.textContent = count.toString();
            badge.style.display = 'flex';
        });
    } else {
        createNewBadge(count);
    }
}

function createNewBadge(count) {
    if (count <= 0) return;
    
    const cartIcons = document.querySelectorAll('.shopping-cart-icon, svg[data-icon="shopping-cart"], .cart-icon, a[href*="panier"] svg, .cart-container svg');
    
    cartIcons.forEach(function(cartIcon) {
        const cartContainer = cartIcon.closest('a, div, button, .cart-container');
        if (!cartContainer) return;
        
        let badge = cartContainer.querySelector('.cart-badge, .custom-violet-badge');
        
        if (badge) {
            badge.textContent = count.toString();
            badge.style.display = 'flex';
        } else {
            badge = document.createElement('span');
            badge.className = 'cart-badge custom-violet-badge';
            badge.textContent = count.toString();
            
            if (getComputedStyle(cartContainer).position === 'static') {
                cartContainer.style.position = 'relative';
            }
            
            cartContainer.appendChild(badge);
        }
    });
}

// Fonction centralisée pour mettre à jour le compteur du panier partout
function updateCartCount(count) {
    count = parseInt(count) || 0;
    
    // Mettre à jour la variable globale
    window.globalCartCount = count;
    
    // Mettre à jour le localStorage
    localStorage.setItem('cartCount', count.toString());
    
    // Mettre à jour visuellement
    updateCartCountDisplay(count);
    
    // Gérer l'affichage du bouton réserver - MODIFIÉ POUR RESPECTER LES RÉSERVATIONS EXISTANTES
    if (count > 0) {
        // Ne créer le bouton que si l'utilisateur n'a pas de réservation existante
        if (window.hasExistingReservation !== true && !document.querySelector('.reserver-button') && !document.querySelector('.annuler-button')) {
            // Si la vérification des réservations est disponible, l'utiliser d'abord
            if (typeof checkExistingReservations === 'function' && !window.checkingReservations) {
                checkExistingReservations().then(hasReservation => {
                    // Ne créer le bouton que s'il n'y a pas de réservation existante
                    if (!hasReservation && !document.querySelector('.reserver-button') && !document.querySelector('.annuler-button')) {
                        createReserverButton();
                    }
                });
            }
        }
    } else if (count === 0 && !window.hasExistingReservation) {
        const reservButton = document.querySelector('.reserver-button');
        if (reservButton && reservButton.textContent.includes('Réserver')) {
            reservButton.remove();
        }
    }
}

// Remplacer forceUpdateCartBadge par updateCartCount
function forceUpdateCartBadge(count) {
    updateCartCount(count);
}

// Mise à jour de la fonction fetchCartItemsCount
function fetchCartItemsCount() {
    fetch('/panier/items-count', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(handleResponse)
    .then(data => {
        const count = parseInt(data.count) || 0;
        updateCartCount(count);
    })
    .catch(error => {
        console.error('Erreur:', error);
        // On laisse la valeur actuelle en cas d'erreur
    });
}

// Mettre à jour la fonction updateUIAfterRemoval
function updateUIAfterRemoval(response) {
    updateCartCount(response.cartCount);
    updateCartSummary(response);
    
    if (response.cartCount === 0) {
        const panierContent = document.querySelector('.panier-content');
        if (panierContent) {
            panierContent.outerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Votre panier est vide</p>
                    <a href="formation/formations">Découvrir des formations</a>
                </div>
            `;
        }
    }
}

// Remplacer la fonction refreshCartBadge existante
function refreshCartBadge() {
    fetchCartItemsCount();
    
    // CORRECTION: Vérifier également si un bouton de réservation doit être ajouté immédiatement
    // sans attendre la réponse de fetchCartItemsCount
    const cartCount = parseInt(localStorage.getItem('cartCount') || '0');
    if (cartCount > 0 && !window.hasExistingReservation && 
        !document.querySelector('.reserver-button') && 
        !document.querySelector('.annuler-button')) {
        createReserverButton();
    }
}

function setupRemoveFromCartListeners() {
    document.addEventListener('click', function(e) {
        const removeLink = e.target.closest('.remove-link');
        if (removeLink) {
            e.preventDefault();
            const formationId = removeLink.getAttribute('data-formation-id');
            if (formationId) {
                removeFromCart(formationId);
            }
        }
    });
}

function removeFromCart(formationId) {
    fetch('/panier/supprimer', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            formation_id: formationId
        })
    })
    .then(handleResponse)
    .then(response => {
        if (response.success) {
            const formationItem = document.querySelector(`.formation-item[data-formation-id="${formationId}"]`);
            if (formationItem) {
                formationItem.remove(); // Suppression immédiate sans transition
                updateUIAfterRemoval(response);
            }
            console.log(response.message || 'Formation supprimée du panier');
        } else {
            console.error(response.message || 'Erreur lors de la suppression de la formation');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

// Version modifiée - Retourne true si le bouton a été créé
// Ajouter une vérification pour ne pas créer le bouton s'il existe déjà un bouton de réservation
function createReserverButton() {
    if (parseInt(localStorage.getItem('cartCount') || '0') <= 0) return false;
    
    // Ne pas créer le bouton si l'un des boutons liés aux réservations existe déjà
    if (document.querySelector('.reserver-button') || document.querySelector('.annuler-button')) {
        return true;
    }
    
    const totalPriceElement = document.querySelector('.total-price');
    if (!totalPriceElement) return false;
    
    const reservButton = document.createElement('button');
    reservButton.className = 'reserver-button';
    reservButton.innerHTML = 'Réserver <svg class="arrow-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    
    // Afficher immédiatement sans transition
    reservButton.style.opacity = '1';
    
    // IMPORTANT: Ne pas ajouter d'écouteur d'événement ici, car c'est fait dans reservation.js
    
    const totalContainer = totalPriceElement.closest('.total-container') || totalPriceElement.parentElement;
    totalContainer.appendChild(reservButton);
    
    return true;
}

function handleResponse(response) {
    if (!response.ok) {
        throw new Error('Erreur réseau');
    }
    return response.json();
}

function updateCartSummary(response) {
    if (response.cartCount === 0) {
        const existingButton = document.querySelector('.reserver-button');
        if (existingButton) {
            existingButton.remove();
        }
        return;
    }
    
    const totalPriceElement = document.querySelector('.total-price');
    if (totalPriceElement) {
        totalPriceElement.textContent = response.totalPrice + ' DT';
        
        // Ne pas créer le bouton "Réserver" s'il existe déjà un bouton "Annuler"
        if (!document.querySelector('.reserver-button') && !document.querySelector('.annuler-button')) {
            // Vérifier d'abord s'il existe une réservation
            if (typeof checkExistingReservations === 'function') {
                checkExistingReservations();
                // Si aucune réservation n'est trouvée, créer le bouton "Réserver" après un délai
                setTimeout(function() {
                    if (!document.querySelector('.reserver-button') && !document.querySelector('.annuler-button')) {
                        createReserverButton();
                    }
                }, 200);
            } else {
                createReserverButton();
            }
        }
    }
    
    if (response.hasDiscount && parseFloat(response.discountedItemsOriginalPrice) > 0) {
        let originalPriceElement = document.querySelector('.original-price');
        let discountElement = document.querySelector('.discount-percentage');
        
        if (originalPriceElement) {
            originalPriceElement.textContent = response.discountedItemsOriginalPrice + ' DT';
        } else if (totalPriceElement) {
            originalPriceElement = document.createElement('div');
            originalPriceElement.className = 'original-price';
            originalPriceElement.textContent = response.discountedItemsOriginalPrice + ' DT';
            totalPriceElement.insertAdjacentElement('afterend', originalPriceElement);
        }
        
        if (discountElement) {
            discountElement.textContent = response.discountPercentage + '% ';
        } else if (originalPriceElement) {
            discountElement = document.createElement('div');
            discountElement.className = 'discount-percentage';
            discountElement.textContent = response.discountPercentage +  '% ';
            originalPriceElement.insertAdjacentElement('afterend', discountElement);
        }
    } else {
        const originalPrice = document.querySelector('.original-price');
        const discountPercentage = document.querySelector('.discount-percentage');
        
        if (originalPrice) originalPrice.remove();
        if (discountPercentage) discountPercentage.remove();
    }
}

// Exposer les fonctions nécessaires globalement
window.removeFromCart = removeFromCart;
window.updateCartSummary = updateCartSummary;
window.forceUpdateCartBadge = forceUpdateCartBadge;
window.refreshCartBadge = refreshCartBadge;
window.fetchCartItemsCount = fetchCartItemsCount;
window.createReserverButton = createReserverButton;
window.checkIfInCart = checkIfInCart;
window.updateCartCount = updateCartCount; // Exposer updateCartCount