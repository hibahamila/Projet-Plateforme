
/**
 * Fichier de gestion du panier d'achat pour les formations
 * Permet d'ajouter, supprimer et vérifier les formations dans le panier
 */
// Ajouter cet écouteur d'événement pour détecter quand l'utilisateur revient à la page
window.addEventListener('pageshow', function(event) {
    // Le event.persisted est true si la page est chargée depuis le cache (retour arrière)
    if (event.persisted) {
        console.log("Utilisateur revenu sur la page via navigation arrière");
        // Vérifier immédiatement l'état des formations dans le panier
        checkFormationsInCart();
    }
});
document.addEventListener('DOMContentLoaded', function() {
    // Récupération du token CSRF pour les requêtes AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Initialisation du compteur de panier
    initializeCartCounter();
    
    // Ajout des écouteurs d'événements pour les boutons d'action
    setupEventListeners();
    
    /**
     * Initialise le compteur de panier depuis localStorage ou via API
     */
    function initializeCartCounter() {
        // Vérifier s'il y a une valeur en localStorage
        let cartCount = localStorage.getItem('cartCount');
        
        if (cartCount !== null) {
            updateCartBadge(parseInt(cartCount, 10));
        } else {
            // Si pas de valeur en localStorage, récupérer depuis l'API
            fetchCartCount();
        }
    }
    
    /**
     * Récupère le nombre d'articles dans le panier via API
     */
    function fetchCartCount() {
        fetch('/panier/count', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            const count = data.count || 0;
            localStorage.setItem('cartCount', count.toString());
            updateCartBadge(count);
        })
        .catch(error => console.error('Erreur lors de la récupération du compteur:', error));
    }
    
    /**
     * Met à jour le badge du compteur de panier dans l'interface
     * @param {number} count - Le nombre d'articles dans le panier
     */
    function updateCartBadge(count) {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'block' : 'none';
        }
    }
    
    /**
     * Configure les écouteurs d'événements pour les interactions avec le panier
     */
    function setupEventListeners() {
        document.addEventListener('click', function(event) {
            const addToCartBtn = event.target.closest('.addcart-btn .btn[href="/panier"]');
            if (addToCartBtn) {
                event.preventDefault();
                
                // Vérifier si le bouton a déjà l'attribut data-in-cart
                if (addToCartBtn.getAttribute('data-in-cart') === 'true' || addToCartBtn.disabled) {
                    console.log("Produit déjà dans le panier");
                    return;
                }
                
                // Rechercher l'ID de formation
                let formationId;
                const modalContent = addToCartBtn.closest('.modal-content');
                if (modalContent) {
                    // Si le bouton est dans un modal
                    formationId = modalContent.closest('.modal').id.split('-').pop();
                } else {
                    // Si le bouton est sur une carte de formation
                    const formationCard = addToCartBtn.closest('.formation-item, .product-box');
                    if (formationCard) {
                        formationId = formationCard.closest('[data-category-id]')?.dataset.categoryId;
                        
                        // Alternative si data-category-id n'est pas trouvé
                        if (!formationId && formationCard.hasAttribute('data-formation-id')) {
                            formationId = formationCard.getAttribute('data-formation-id');
                        }
                    }
                }
                
                if (formationId) {
                    addToCart(formationId, true); // Rediriger vers le panier
                }
            }
        });
    // Pour les boutons "Ajouter au panier"
// document.addEventListener('click', function(event) {
//     const addToCartBtn = event.target.closest('.addcart-btn .btn[href="/panier"]');
//     if (addToCartBtn) {
//         // Si le bouton est désactivé, ne rien faire
//         if (addToCartBtn.disabled) {
//             event.preventDefault();
//             return;
//         }
        
//         event.preventDefault();
        
//         // Rechercher l'ID de formation
//         let formationId;
//         const modalContent = addToCartBtn.closest('.modal-content');
//         if (modalContent) {
//             // Si le bouton est dans un modal
//             formationId = modalContent.closest('.modal').id.split('-').pop();
//         } else {
//             // Si le bouton est sur une carte de formation
//             const formationCard = addToCartBtn.closest('.formation-item, .product-box');
//             if (formationCard) {
//                 formationId = formationCard.closest('[data-category-id]')?.dataset.categoryId;
                
//                 // Alternative si data-category-id n'est pas trouvé
//                 if (!formationId && formationCard.hasAttribute('data-formation-id')) {
//                     formationId = formationCard.getAttribute('data-formation-id');
//                 }
//             }
//         }
        
//         if (formationId) {
//             addToCart(formationId, true); // Rediriger vers le panier
//         }
//     }

// });
        // Pour les boutons "Supprimer" dans le panier
        document.querySelectorAll('.remove-link').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const formationId = this.getAttribute('data-formation-id');
                removeFromCart(formationId);
            });
        });
        
        // Vérifier si les formations sont dans le panier au chargement de la page
        checkFormationsInCart();
    }
    
    /**
     * Ajoute une formation au panier
     * @param {string|number} formationId - L'ID de la formation à ajouter
     * @param {boolean} redirectToCart - Si true, redirige vers la page panier après l'ajout
     */
  

    function addToCart(formationId, redirectToCart = false) {
        // Ne pas désactiver le bouton immédiatement - seulement stocker l'ID pour le retour arrière
        localStorage.setItem('lastAddedFormation', formationId);
        
        fetch('/panier/ajouter', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                training_id: formationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mise à jour du compteur
                localStorage.setItem('cartCount', data.cartCount.toString());
                updateCartBadge(data.cartCount);
                
                // Si on doit rediriger, ne pas mettre à jour l'état du bouton
                if (redirectToCart) {
                    window.location.href = '/panier';
                } else {
                    // Seulement désactiver le bouton si on ne redirige pas
                    updateAddToCartButton(formationId, true);
                    showNotification(data.message, 'success');
                }
            } else {
                localStorage.removeItem('lastAddedFormation');
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout au panier:', error);
            localStorage.removeItem('lastAddedFormation');
            showNotification('Une erreur est survenue lors de l\'ajout au panier.', 'error');
        });
    }
    /**
     * Supprime une formation du panier
     * @param {string|number} formationId - L'ID de la formation à supprimer
     */
    function removeFromCart(formationId) {
        fetch('/panier/supprimer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                formation_id: formationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mise à jour du compteur
                localStorage.setItem('cartCount', data.cartCount.toString());
                updateCartBadge(data.cartCount);
                
                // Supprimer l'élément de la liste si nous sommes sur la page panier
                const formationItem = document.querySelector(`.formation-item[data-formation-id="${formationId}"]`);
                if (formationItem) {
                    formationItem.remove();
                    
                    // Mettre à jour les totaux
                    updateCartTotals(data);
                    
                    // Si le panier est vide après suppression
                    if (data.cartCount === 0) {
                        showEmptyCartMessage();
                    }
                }
                
                // Mettre à jour l'état du bouton
                updateAddToCartButton(formationId, false);
                
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la suppression du panier:', error);
            showNotification('Une erreur est survenue lors de la suppression.', 'error');
        });
    }
    

    function checkFormationsInCart() {
        // Vérifier d'abord la dernière formation ajoutée (pour une mise à jour rapide lors du retour arrière)
        const lastAddedFormation = localStorage.getItem('lastAddedFormation');
        if (lastAddedFormation) {
            console.log("Formation précédemment ajoutée:", lastAddedFormation);
            // Désactiver immédiatement le bouton pour la dernière formation ajoutée
            updateAddToCartButton(lastAddedFormation, true);
            
            // Ne pas effacer cette information pour qu'elle persiste même après les retours arrière
            localStorage.removeItem('lastAddedFormation');
        }
        
        // Ensuite, vérifier toutes les formations via l'API pour être sûr
        fetch('/panier/items', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.items && Array.isArray(data.items)) {
                // Mettre à jour tous les boutons pour les formations dans le panier
                data.items.forEach(item => {
                    if (item.formation_id) {
                        updateAddToCartButton(item.formation_id, true);
                    }
                });
            }
        })
        .catch(error => console.error('Erreur lors de la vérification du panier:', error));
        
        // Vérification individuelle des formations (garde cette partie comme fallback)
        const formationCards = document.querySelectorAll('.formation-item, .product-box');
        const modals = document.querySelectorAll('.modal[id^="formation-modal-"]');
        
        // Fonction pour vérifier une formation spécifique
        function checkSingleFormation(formationId) {
            fetch(`/panier/check/${formationId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                updateAddToCartButton(formationId, data.in_cart);
            })
            .catch(error => console.error('Erreur lors de la vérification du panier:', error));
        }
        
        // Vérifier chaque formation
        formationCards.forEach(card => {
            let formationId;
            
            if (card.hasAttribute('data-formation-id')) {
                formationId = card.getAttribute('data-formation-id');
            } else if (card.closest('[data-category-id]')) {
                formationId = card.closest('[data-category-id]').dataset.categoryId;
            }
            
            if (formationId) {
                checkSingleFormation(formationId);
            }
        });
        
        modals.forEach(modal => {
            const formationId = modal.id.split('-').pop();
            if (formationId) {
                checkSingleFormation(formationId);
            }
        });
    }
    $(document).on('shown.bs.modal', '.modal', function() {
        const formationId = this.id.split('-').pop();
        if (formationId) {
            // Vérifier si cette formation est dans le panier quand le modal s'ouvre
            fetch(`/panier/check/${formationId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                updateAddToCartButton(formationId, data.in_cart);
            })
            .catch(error => console.error('Erreur lors de la vérification du panier:', error));
        }
    });
    // Ajouter cette fonction au gestionnaire d'événements pour l'ouverture des modals
$(document).on('click', '.product-hover a[data-bs-toggle="modal"]', function(e) {
    e.preventDefault();
    const modalId = $(this).attr('data-bs-target');
    const formationId = modalId.split('-').pop();
    
    // Vérifier si cette formation est dans le panier avant d'ouvrir le modal
    fetch(`/panier/check/${formationId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        updateAddToCartButton(formationId, data.in_cart);
        
        // Puis afficher le modal
        showFormationDetails(formationId);
    })
    .catch(error => {
        console.error('Erreur lors de la vérification du panier:', error);
        // Afficher quand même le modal en cas d'erreur
        showFormationDetails(formationId);
    });
});
    /**
     * Met à jour l'apparence du bouton "Ajouter au panier"
     * @param {string|number} formationId - L'ID de la formation
     * @param {boolean} inCart - Indique si la formation est dans le panier
     */
   
    function updateAddToCartButton(formationId, inCart) {
        // Trouver tous les boutons pour cette formation (dans la liste et dans le modal)
        const buttons = document.querySelectorAll(`.addcart-btn .btn[href="/panier"]`);
        const modalButtons = document.querySelectorAll(`#formation-modal-${formationId} .addcart-btn .btn[href="/panier"]`);
        
        // Fonction pour mettre à jour un bouton spécifique
        function updateButton(button) {
            if (inCart) {
                button.classList.add('btn-secondary');
                button.classList.remove('btn-primary');
                button.disabled = true;
                
                // Assurons-nous que le bouton ne déclenche plus d'événements
                button.setAttribute('data-in-cart', 'true');
            } else {
                button.classList.add('btn-primary');
                button.classList.remove('btn-secondary');
                button.disabled = false;
                button.removeAttribute('data-in-cart');
            }
        }
        
        // Mettre à jour les boutons dans les modals
        modalButtons.forEach(button => {
            updateButton(button);
        });
        
        // Mettre à jour les boutons dans les cartes de formation
        buttons.forEach(button => {
            const card = button.closest('.formation-item, .product-box');
            if (card) {
                const cardId = card.closest('[data-category-id]')?.dataset.categoryId;
                const cardFormationId = card.getAttribute('data-formation-id');
                
                // Vérifier si ce bouton correspond à notre formation
                if (cardId === formationId || cardFormationId === formationId) {
                    updateButton(button);
                }
            }
        });
    }
    
    /**
     * Met à jour les totaux affichés dans le résumé du panier
     * @param {Object} data - Les données de totaux
     */
    function updateCartTotals(data) {
        const totalPriceElement = document.querySelector('.total-price');
        const originalPriceElement = document.querySelector('.original-price');
        const discountPercentageElement = document.querySelector('.discount-percentage');
        
        if (totalPriceElement) {
            totalPriceElement.textContent = `${data.totalPrice} Dt`;
        }
        
        if (originalPriceElement && data.hasDiscount) {
            originalPriceElement.textContent = `${data.totalWithoutDiscount} Dt`;
        }
        
        if (discountPercentageElement && data.hasDiscount) {
            discountPercentageElement.textContent = `-${data.discountPercentage}%`;
        }
    }
    
    /**
     * Affiche un message de panier vide
     */
    function showEmptyCartMessage() {
        const panierContent = document.querySelector('.panier-content');
        if (panierContent) {
            panierContent.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Votre panier est vide</p>
                    <a href="/formation/formations">Découvrir des formations</a>
                </div>
            `;
        }
    }
    
    /**
     * Affiche une notification à l'utilisateur
     * @param {string} message - Le message à afficher
     * @param {string} type - Le type de notification (success, error, info)
     */
    function showNotification(message, type = 'info') {
        // Vérifier si la fonction Toast existe (comme dans Bootstrap ou autre framework)
        if (typeof Toast !== 'undefined') {
            new Toast({
                text: message,
                type: type,
                autoHide: true,
                duration: 3000
            }).show();
            return;
        }
        
        // Fallback: alert simple (à remplacer par une meilleure implémentation)
        if (type === 'error') {
            alert('Erreur: ' + message);
        } else {
            alert(message);
        }
    }
});