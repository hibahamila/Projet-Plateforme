
// document.addEventListener('DOMContentLoaded', function() {
//     // Récupération du token CSRF pour les requêtes AJAX
//     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
//     // Variable pour suivre les requêtes en cours
//     let pendingRequests = {};
    
//     // Fonction pour stocker les IDs des formations dans le panier
//     function storeCartFormationsInLocalStorage(formationIds) {
//         localStorage.setItem('cartFormations', JSON.stringify(formationIds));
//     }
    
//     // Initialisation du compteur de panier
//     initializeCartCounter();
    
//     // Ajout des écouteurs d'événements pour les boutons d'action
//     setupEventListeners();
    
//     /**
//      * Initialise le compteur de panier depuis localStorage ou via API
//      */
//     function initializeCartCounter() {
//         // Vérifier s'il y a une valeur en localStorage
//         let cartCount = localStorage.getItem('cartCount');
        
//         if (cartCount !== null) {
//             updateCartBadge(parseInt(cartCount, 10));
//         } else {
//             // Si pas de valeur en localStorage, récupérer depuis l'API
//             fetchCartCount();
//         }
//     }
    
//     /**
//      * Récupère le nombre d'articles dans le panier via API
//      */
//     function fetchCartCount() {
//         fetch('/panier/count', {
//             method: 'GET',
//             headers: {
//                 'Accept': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//             }
//         })
//         .then(response => response.json())
//         .then(data => {
//             const count = data.count || 0;
//             localStorage.setItem('cartCount', count.toString());
//             updateCartBadge(count);
//         })
//         .catch(error => console.error('Erreur lors de la récupération du compteur:', error));
//     }
    
//     /**
//      * Met à jour le badge du compteur de panier dans l'interface
//      * @param {number} count - Le nombre d'articles dans le panier
//      */
//     function updateCartBadge(count) {
//         const cartBadge = document.querySelector('.cart-badge');
//         if (cartBadge) {
//             cartBadge.textContent = count;
//             cartBadge.style.display = count > 0 ? 'block' : 'none';
//         }
//     }
    
//     /**
//      * Configure les écouteurs d'événements pour les interactions avec le panier
//      */
//     function setupEventListeners() {
//         document.addEventListener('click', function(event) {
//             const addToCartBtn = event.target.closest('.addcart-btn .btn[href="/panier"]');
//             if (addToCartBtn) {
//                 event.preventDefault();
                
//                 // Empêcher les clics multiples rapides
//                 if (addToCartBtn.classList.contains('processing')) {
//                     return;
//                 }
                
//                 // Vérifier si le bouton a déjà l'attribut data-in-cart
//                 if (addToCartBtn.getAttribute('data-in-cart') === 'true') {
//                     // Si le produit est déjà dans le panier, rediriger vers la page panier
//                     window.location.href = '/panier';
//                     return;
//                 }
                
//                 // Marquer le bouton comme étant en cours de traitement
//                 addToCartBtn.classList.add('processing');
                
//                 // Rechercher l'ID de formation
//                 let formationId;
//                 const modalContent = addToCartBtn.closest('.modal-content');
//                 if (modalContent) {
//                     // Si le bouton est dans un modal
//                     formationId = modalContent.closest('.modal').id.split('-').pop();
//                 } else {
//                     // Si le bouton est sur une carte de formation
//                     const formationCard = addToCartBtn.closest('.formation-item, .product-box');
//                     if (formationCard) {
//                         formationId = formationCard.closest('[data-category-id]')?.dataset.categoryId;
                        
//                         // Alternative si data-category-id n'est pas trouvé
//                         if (!formationId && formationCard.hasAttribute('data-formation-id')) {
//                             formationId = formationCard.getAttribute('data-formation-id');
//                         }
//                     }
//                 }
                
//                 if (formationId) {
//                     // Éviter les appels multiples pour le même ID
//                     if (pendingRequests[formationId]) {
//                         return;
//                     }
                    
//                     pendingRequests[formationId] = true;
//                     addToCart(formationId, false, function() {
//                         // Callback pour réinitialiser l'état après le traitement
//                         addToCartBtn.classList.remove('processing');
//                         delete pendingRequests[formationId];
//                     });
//                 } else {
//                     addToCartBtn.classList.remove('processing');
//                 }
//             }
//         });
        
//         // Pour les boutons "Supprimer" dans le panier
//         document.querySelectorAll('.remove-link').forEach(button => {
//             button.addEventListener('click', function(e) {
//                 e.preventDefault();
                
//                 // Empêcher les clics multiples rapides
//                 if (this.classList.contains('processing')) {
//                     return;
//                 }
                
//                 this.classList.add('processing');
//                 const formationId = this.getAttribute('data-formation-id');
                
//                 if (pendingRequests[formationId]) {
//                     this.classList.remove('processing');
//                     return;
//                 }
                
//                 pendingRequests[formationId] = true;
//                 removeFromCart(formationId, () => {
//                     this.classList.remove('processing');
//                     delete pendingRequests[formationId];
//                 });
//             });
//         });
        
//         // Vérifier si les formations sont dans le panier au chargement de la page, mais une seule fois
//         if (!window.formationsChecked) {
//             window.formationsChecked = true;
//             checkFormationsInCart();
//         }
//     }
    
//     /**
//      * Ajoute une formation au panier
//      * @param {string|number} formationId - L'ID de la formation à ajouter
//      * @param {boolean} redirectToCart - Si true, redirige vers la page panier après l'ajout
//      * @param {function} callback - Fonction appelée une fois l'opération terminée
//      */
//     function addToCart(formationId, redirectToCart = false, callback = null) {
//         // Vérifier d'abord si cette formation est déjà dans le localStorage
//         const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
//         if (cartFormations.includes(formationId.toString())) {
//             showNotification('Cette formation est déjà dans votre panier', 'info');
//             if (callback) callback();
//             return;
//         }
        
//         localStorage.setItem('lastAddedFormation', formationId);
//         updateAddToCartButton(formationId, true); // Mettre à jour le bouton immédiatement pour le feedback visuel
        
//         fetch('/panier/ajouter', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'Accept': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//             },
//             body: JSON.stringify({
//                 training_id: formationId
//             })
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 // Mise à jour du compteur
//                 localStorage.setItem('cartCount', data.cartCount.toString());
//                 updateCartBadge(data.cartCount);
                
//                 // Ajouter la formation au localStorage seulement si elle n'y est pas déjà
//                 if (!cartFormations.includes(formationId.toString())) {
//                     cartFormations.push(formationId.toString());
//                     storeCartFormationsInLocalStorage(cartFormations);
//                     showNotification(data.message, 'success');
//                 }
                
//                 updateAddToCartButton(formationId, true);
                
//                 if (redirectToCart) {
//                     window.location.href = '/panier';
//                 }
//             } else {
//                 localStorage.removeItem('lastAddedFormation');
//                 updateAddToCartButton(formationId, false); // Réinitialiser le bouton
//                 showNotification(data.message, 'error');
//             }
            
//             if (callback) callback();
//         })
//         .catch(error => {
//             console.error('Erreur lors de l\'ajout au panier:', error);
//             localStorage.removeItem('lastAddedFormation');
//             updateAddToCartButton(formationId, false); // Réinitialiser le bouton
//             showNotification('Une erreur est survenue lors de l\'ajout au panier.', 'error');
            
//             if (callback) callback();
//         });
//     }
    
//     /**
//      * Supprime une formation du panier
//      * @param {string|number} formationId - L'ID de la formation à supprimer
//      * @param {function} callback - Fonction appelée une fois l'opération terminée
//      */
//     function removeFromCart(formationId, callback = null) {
//         fetch('/panier/supprimer', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'Accept': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//             },
//             body: JSON.stringify({
//                 formation_id: formationId
//             })
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 // Mise à jour du compteur
//                 localStorage.setItem('cartCount', data.cartCount.toString());
//                 updateCartBadge(data.cartCount);
                
//                 // Retirer la formation du localStorage
//                 const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
//                 const updatedFormations = cartFormations.filter(id => id !== formationId.toString());
//                 storeCartFormationsInLocalStorage(updatedFormations);
                
//                 // Supprimer l'élément de la liste si nous sommes sur la page panier
//                 const formationItem = document.querySelector(`.formation-item[data-formation-id="${formationId}"]`);
//                 if (formationItem) {
//                     formationItem.remove();
                    
//                     // Mettre à jour les totaux
//                     updateCartTotals(data);
                    
//                     // Si le panier est vide après suppression
//                     if (data.cartCount === 0) {
//                         showEmptyCartMessage();
//                     }
//                 }
                
//                 // Mettre à jour l'état du bouton
//                 updateAddToCartButton(formationId, false);
                
//                 showNotification(data.message, 'success');
//             } else {
//                 showNotification(data.message, 'error');
//             }
            
//             if (callback) callback();
//         })
//         .catch(error => {
//             console.error('Erreur lors de la suppression du panier:', error);
//             showNotification('Une erreur est survenue lors de la suppression.', 'error');
            
//             if (callback) callback();
//         });
//     }
    
//     // function checkFormationsInCart() {
//     //     // Récupérer les formations déjà connues du localStorage
//     //     const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
        
//     //     // Vérifier d'abord la dernière formation ajoutée (pour une mise à jour rapide lors du retour arrière)
//     //     const lastAddedFormation = localStorage.getItem('lastAddedFormation');
//     //     if (lastAddedFormation) {
//     //         console.log("Formation précédemment ajoutée:", lastAddedFormation);
//     //         // Désactiver immédiatement le bouton pour la dernière formation ajoutée
//     //         updateAddToCartButton(lastAddedFormation, true);
            
//     //         // Ajouter cette formation aux formations du panier si elle n'y est pas déjà
//     //         if (!cartFormations.includes(lastAddedFormation)) {
//     //             cartFormations.push(lastAddedFormation);
//     //             storeCartFormationsInLocalStorage(cartFormations);
//     //         }
            
//     //         // Supprimer cette information maintenant qu'elle a été traitée
//     //         localStorage.removeItem('lastAddedFormation');
//     //     }
        
//     //     // Mettre à jour tous les boutons pour les formations connues du localStorage
//     //     cartFormations.forEach(formationId => {
//     //         updateAddToCartButton(formationId, true);
//     //     });
        
//     //     // Ensuite, synchroniser avec le serveur
//     //     fetch('/panier/items', {
//     //         method: 'GET',
//     //         headers: {
//     //             'Accept': 'application/json',
//     //             'X-CSRF-TOKEN': csrfToken
//     //         }
//     //     })
//     //     .then(response => response.json())
//     //     .then(data => {
//     //         if (data.items && Array.isArray(data.items)) {
//     //             // Créer un nouvel array des IDs de formations
//     //             const serverFormationIds = data.items.map(item => item.formation_id.toString());
                
//     //             // Mettre à jour le localStorage avec les données du serveur (plus fiables)
//     //             storeCartFormationsInLocalStorage(serverFormationIds);
                
//     //             // Mettre à jour tous les boutons
//     //             serverFormationIds.forEach(formationId => {
//     //                 updateAddToCartButton(formationId, true);
//     //             });
                
//     //             // Mettre à jour le compteur du panier
//     //             if (serverFormationIds.length !== parseInt(localStorage.getItem('cartCount') || '0', 10)) {
//     //                 localStorage.setItem('cartCount', serverFormationIds.length.toString());
//     //                 updateCartBadge(serverFormationIds.length);
//     //             }
//     //         }
//     //     })
//     //     .catch(error => console.error('Erreur lors de la vérification du panier:', error));
//     // }
    
//     function checkFormationsInCart() {
//         // Récupérer les formations déjà connues du localStorage
//         const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
        
//         // Vérifier d'abord la dernière formation ajoutée (pour une mise à jour rapide lors du retour arrière)
//         const lastAddedFormation = localStorage.getItem('lastAddedFormation');
//         if (lastAddedFormation) {
//             console.log("Formation précédemment ajoutée:", lastAddedFormation);
//             // Désactiver immédiatement le bouton pour la dernière formation ajoutée
//             updateAddToCartButton(lastAddedFormation, true);
            
//             // Ajouter cette formation aux formations du panier si elle n'y est pas déjà
//             if (!cartFormations.includes(lastAddedFormation)) {
//                 cartFormations.push(lastAddedFormation);
//                 storeCartFormationsInLocalStorage(cartFormations);
//             }
            
//             // Supprimer cette information maintenant qu'elle a été traitée
//             localStorage.removeItem('lastAddedFormation');
//         }
        
//         // Mettre à jour tous les boutons pour les formations connues du localStorage
//         cartFormations.forEach(formationId => {
//             updateAddToCartButton(formationId, true);
//         });
        
//         // Mettre à jour tous les boutons sur les cartes de formation visibles
//         $('.formation-item, .product-box').each(function() {
//             const formationId = $(this).closest('[data-category-id]')?.dataset.categoryId 
//                 || $(this).attr('data-formation-id');
            
//             if (formationId && cartFormations.includes(formationId.toString())) {
//                 const cartButton = $(this).find('.addcart-btn .btn[href="/panier"]');
//                 if (cartButton.length) {
//                     cartButton.text('Accéder au panier');
//                     cartButton.attr('data-in-cart', 'true');
//                 }
//             }
//         });
        
//         // Ensuite, synchroniser avec le serveur
//         fetch('/panier/items', {
//             method: 'GET',
//             headers: {
//                 'Accept': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//             }
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.items && Array.isArray(data.items)) {
//                 // Créer un nouvel array des IDs de formations
//                 const serverFormationIds = data.items.map(item => item.toString());
                
//                 // Mettre à jour le localStorage avec les données du serveur (plus fiables)
//                 storeCartFormationsInLocalStorage(serverFormationIds);
                
//                 // Mettre à jour tous les boutons
//                 serverFormationIds.forEach(formationId => {
//                     updateAddToCartButton(formationId, true);
//                 });
                
//                 // Mettre à jour le compteur du panier
//                 if (serverFormationIds.length !== parseInt(localStorage.getItem('cartCount') || '0', 10)) {
//                     localStorage.setItem('cartCount', serverFormationIds.length.toString());
//                     updateCartBadge(serverFormationIds.length);
//                 }
//             }
//         })
//         .catch(error => console.error('Erreur lors de la vérification du panier:', error));
//     }
//     // Voici la fonction modifiée qui gère l'ouverture du modal
//     $(document).on('shown.bs.modal', '.modal', function() {
//         const formationId = this.id.split('-').pop();
//         if (formationId) {
//             // Vérifier d'abord dans le localStorage pour une réponse immédiate
//             const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
//             const inCart = cartFormations.includes(formationId.toString());
            
//             // Mettre à jour immédiatement le bouton dans le modal
//             const modalButton = this.querySelector('.addcart-btn .btn[href="/panier"]');
//             if (modalButton) {
//                 if (inCart) {
//                     modalButton.textContent = 'Accéder au panier';
//                     modalButton.setAttribute('data-in-cart', 'true');
//                 } else {
//                     modalButton.textContent = 'Ajouter au panier';
//                     modalButton.removeAttribute('data-in-cart');
//                 }
//             }
            
//             // Appel à la fonction générale pour mettre à jour tous les boutons liés à cette formation
//             updateAddToCartButton(formationId, inCart);
//         }
//     });
    
//     /**
//      * Met à jour l'apparence du bouton "Ajouter au panier"
//      * @param {string|number} formationId - L'ID de la formation
//      * @param {boolean} inCart - Indique si la formation est dans le panier
//      */
//     // function updateAddToCartButton(formationId, inCart) {
//     //     // Trouver tous les boutons pour cette formation (dans la liste et dans le modal)
//     //     const buttons = document.querySelectorAll(`.addcart-btn .btn[href="/panier"]`);
//     //     const modalButtons = document.querySelectorAll(`#formation-modal-${formationId} .addcart-btn .btn[href="/panier"]`);
        
//     //     // Fonction pour mettre à jour un bouton spécifique
//     //     function updateButton(button) {
//     //         if (inCart) {
//     //             // Garder la classe btn-primary pour avoir la même couleur que "Ajouter au panier"
//     //             button.classList.add('btn-primary');
//     //             button.classList.remove('btn-secondary');
//     //             button.disabled = false; // Garder le bouton actif
//     //             button.setAttribute('data-in-cart', 'true');
//     //             button.textContent = 'Accéder au panier'; // Changer le texte du bouton
//     //         } else {
//     //             button.classList.add('btn-primary');
//     //             button.classList.remove('btn-secondary');
//     //             button.disabled = false;
//     //             button.removeAttribute('data-in-cart');
//     //             button.textContent = 'Ajouter au panier'; // Réinitialiser le texte du bouton
//     //         }
//     //     }
        
//     //     // Mettre à jour les boutons dans les modals
//     //     modalButtons.forEach(button => {
//     //         updateButton(button);
//     //     });
        
//     //     // Mettre à jour les boutons dans les cartes de formation
//     //     buttons.forEach(button => {
//     //         const card = button.closest('.formation-item, .product-box');
//     //         if (card) {
//     //             const cardId = card.closest('[data-category-id]')?.dataset.categoryId;
//     //             const cardFormationId = card.getAttribute('data-formation-id');
                
//     //             // Vérifier si ce bouton correspond à notre formation
//     //             if (cardId === formationId || cardFormationId === formationId) {
//     //                 updateButton(button);
//     //             }
//     //         }
//     //     });
//     // }
//     function updateAddToCartButton(formationId, inCart) {
//     console.log('Updating button for formation:', formationId, 'inCart:', inCart);
    
//     // Trouver tous les boutons pour cette formation (dans la liste et dans le modal)
//     const buttons = document.querySelectorAll(`.addcart-btn .btn[href="/panier"]`);
//     const modalButtons = document.querySelectorAll(`#formation-modal-${formationId} .addcart-btn .btn[href="/panier"]`);
    
//     // Fonction pour mettre à jour un bouton spécifique
//     function updateButton(button) {
//         if (inCart) {
//             // Garder la classe btn-primary pour avoir la même couleur que "Ajouter au panier"
//             button.classList.add('btn-primary');
//             button.classList.remove('btn-secondary');
//             button.disabled = false; // Garder le bouton actif
//             button.setAttribute('data-in-cart', 'true');
//             button.textContent = 'Accéder au panier'; // Changer le texte du bouton
//         } else {
//             button.classList.add('btn-primary');
//             button.classList.remove('btn-secondary');
//             button.disabled = false;
//             button.removeAttribute('data-in-cart');
//             button.textContent = 'Ajouter au panier'; // Réinitialiser le texte du bouton
//         }
//     }
    
//     // Mettre à jour les boutons dans les modals
//     modalButtons.forEach(button => {
//         updateButton(button);
//     });
    
//     // Mettre à jour les boutons dans les cartes de formation
//     buttons.forEach(button => {
//         const card = button.closest('.formation-item, .product-box');
//         if (card) {
//             const cardId = card.getAttribute('data-category-id') || card.getAttribute('data-formation-id');
            
//             // Vérifier si ce bouton correspond à notre formation
//             if (cardId === formationId.toString()) {
//                 updateButton(button);
//             }
//         }
//     });
    
//     // Chercher également dans toutes les cartes de formation visibles
//     document.querySelectorAll('.formation-item, .product-box').forEach(card => {
//         const cardId = card.getAttribute('data-category-id') || card.getAttribute('data-formation-id');
        
//         if (cardId === formationId.toString()) {
//             const button = card.querySelector('.addcart-btn .btn[href="/panier"]');
//             if (button) {
//                 updateButton(button);
//             }
//         }
//     });
// }
    
//     /**
//      * Met à jour les totaux affichés dans le résumé du panier
//      * @param {Object} data - Les données de totaux
//      */
//     function updateCartTotals(data) {
//         const totalPriceElement = document.querySelector('.total-price');
//         const originalPriceElement = document.querySelector('.original-price');
//         const discountPercentageElement = document.querySelector('.discount-percentage');
        
//         if (totalPriceElement) {
//             totalPriceElement.textContent = `${data.totalPrice} Dt`;
//         }
        
//         if (originalPriceElement && data.hasDiscount) {
//             originalPriceElement.textContent = `${data.totalWithoutDiscount} Dt`;
//         }
        
//         if (discountPercentageElement && data.hasDiscount) {
//             discountPercentageElement.textContent = `-${data.discountPercentage}%`;
//         }
//     }
    
//     /**
//      * Affiche un message de panier vide
//      */
//     function showEmptyCartMessage() {
//         const panierContent = document.querySelector('.panier-content');
//         if (panierContent) {
//             panierContent.innerHTML = `
//                 <div class="empty-cart">
//                     <i class="fas fa-shopping-cart"></i>
//                     <p>Votre panier est vide</p>
//                     <a href="/formation/formations">Découvrir des formations</a>
//                 </div>
//             `;
//         }
//     }
    
//     /**
//      * Affiche une notification à l'utilisateur
//      * @param {string} message - Le message à afficher
//      * @param {string} type - Le type de notification (success, error, info)
//      */
//     function showNotification(message, type = 'info') {
//         // Vérifier si la fonction Toast existe (comme dans Bootstrap ou autre framework)
//         if (typeof Toast !== 'undefined') {
//             new Toast({
//                 text: message,
//                 type: type,
//                 autoHide: true,
//                 duration: 3000
//             }).show();
//             return;
//         }
        
//         // Fallback: alert simple (à remplacer par une meilleure implémentation)
//         if (type === 'error') {
//             alert('Erreur: ' + message);
//         } else {
//             alert(message);
//         }
//     }
    
//     // Fonction pour afficher les détails d'une formation dans un modal
//     window.showFormationDetails = function(formationId) {
//         // Vérifier d'abord si cette formation est dans le panier avant d'ouvrir le modal
//         const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
//         const inCart = cartFormations.includes(formationId.toString());
//         console.log('showFormationDetails - formationId:', formationId, 'inCart:', inCart);

        
//         // Rechercher le modal correspondant à la formation
//         const modal = $(`#formation-modal-${formationId}`);
        
//         // Préparer le bouton avec le bon texte avant d'afficher le modal
//         if (modal.length) {
//             const modalButton = modal[0].querySelector('.addcart-btn .btn[href="/panier"]');
//             if (modalButton) {
//                 if (inCart) {
//                     modalButton.textContent = 'Accéder au panier';
//                     modalButton.setAttribute('data-in-cart', 'true');
//                 } else {
//                     modalButton.textContent = 'Ajouter au panier';
//                     modalButton.removeAttribute('data-in-cart');
//                 }
//             }
            
//             // Afficher le modal
//             const bsModal = new bootstrap.Modal(modal);
//             bsModal.show();
//         } else {
//             console.error(`Modal pour la formation #${formationId} non trouvé`);
//         }
//     };

//     // Handler pour le clic sur l'icône "œil" - modifié pour mettre à jour le bouton avant l'ouverture du modal
//     $(document).on('click', '.product-hover a[data-bs-toggle="modal"]', function(e) {
//         e.preventDefault();
//         const modalId = $(this).attr('data-bs-target');
//         const formationId = modalId.split('-').pop();
//         console.log('Clic sur œil - formationId:', formationId);

        
//         // Vérifier si cette formation est dans le panier avant d'ouvrir le modal
//         const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
//         const inCart = cartFormations.includes(formationId.toString());
        
//         // Mise à jour préalable des boutons avant l'ouverture du modal
//         updateAddToCartButton(formationId, inCart);
        
//         // Puis afficher le modal avec l'état déjà correct
//         window.showFormationDetails(formationId);
//     });
// }); 







document.addEventListener('DOMContentLoaded', function() {
    // Récupération du token CSRF pour les requêtes AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Variable pour suivre les requêtes en cours
    let pendingRequests = {};
    
    // Fonction pour stocker les IDs des formations dans le panier
    function storeCartFormationsInLocalStorage(formationIds) {
        localStorage.setItem('cartFormations', JSON.stringify(formationIds));
    }
    
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
                
                // Empêcher les clics multiples rapides
                if (addToCartBtn.classList.contains('processing')) {
                    return;
                }
                
                // Vérifier si le bouton a déjà l'attribut data-in-cart
                if (addToCartBtn.getAttribute('data-in-cart') === 'true') {
                    // Si le produit est déjà dans le panier, rediriger vers la page panier
                    window.location.href = '/panier';
                    return;
                }
                
                // Marquer le bouton comme étant en cours de traitement
                addToCartBtn.classList.add('processing');
                
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
                        formationId = formationCard.getAttribute('data-category-id');
                        
                        // Alternative si data-category-id n'est pas trouvé
                        if (!formationId && formationCard.hasAttribute('data-formation-id')) {
                            formationId = formationCard.getAttribute('data-formation-id');
                        }
                    }
                }
                
                if (formationId) {
                    // Éviter les appels multiples pour le même ID
                    if (pendingRequests[formationId]) {
                        return;
                    }
                    
                    pendingRequests[formationId] = true;
                    addToCart(formationId, false, function() {
                        // Callback pour réinitialiser l'état après le traitement
                        addToCartBtn.classList.remove('processing');
                        delete pendingRequests[formationId];
                    });
                } else {
                    addToCartBtn.classList.remove('processing');
                }
            }
        });
        
        // Pour les boutons "Supprimer" dans le panier
        document.querySelectorAll('.remove-link').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Empêcher les clics multiples rapides
                if (this.classList.contains('processing')) {
                    return;
                }
                
                this.classList.add('processing');
                const formationId = this.getAttribute('data-formation-id');
                
                if (pendingRequests[formationId]) {
                    this.classList.remove('processing');
                    return;
                }
                
                pendingRequests[formationId] = true;
                removeFromCart(formationId, () => {
                    this.classList.remove('processing');
                    delete pendingRequests[formationId];
                });
            });
        });
        
        // Vérifier si les formations sont dans le panier au chargement de la page, mais une seule fois
        if (!window.formationsChecked) {
            window.formationsChecked = true;
            checkFormationsInCart();
        }
    }
    
    /**
     * Ajoute une formation au panier
     * @param {string|number} formationId - L'ID de la formation à ajouter
     * @param {boolean} redirectToCart - Si true, redirige vers la page panier après l'ajout
     * @param {function} callback - Fonction appelée une fois l'opération terminée
     */
    function addToCart(formationId, redirectToCart = false, callback = null) {
        // Vérifier d'abord si cette formation est déjà dans le localStorage
        const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
        if (cartFormations.includes(formationId.toString())) {
            showNotification('Cette formation est déjà dans votre panier', 'info');
            if (callback) callback();
            return;
        }
        
        localStorage.setItem('lastAddedFormation', formationId);
        updateAddToCartButton(formationId, true); // Mettre à jour le bouton immédiatement pour le feedback visuel
        
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
                
                // Ajouter la formation au localStorage seulement si elle n'y est pas déjà
                if (!cartFormations.includes(formationId.toString())) {
                    cartFormations.push(formationId.toString());
                    storeCartFormationsInLocalStorage(cartFormations);
                    showNotification(data.message, 'success');
                }
                
                updateAddToCartButton(formationId, true);
                
                if (redirectToCart) {
                    window.location.href = '/panier';
                }
            } else {
                localStorage.removeItem('lastAddedFormation');
                updateAddToCartButton(formationId, false); // Réinitialiser le bouton
                showNotification(data.message, 'error');
            }
            
            if (callback) callback();
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout au panier:', error);
            localStorage.removeItem('lastAddedFormation');
            updateAddToCartButton(formationId, false); // Réinitialiser le bouton
            showNotification('Une erreur est survenue lors de l\'ajout au panier.', 'error');
            
            if (callback) callback();
        });
    }
    
    /**
     * Supprime une formation du panier
     * @param {string|number} formationId - L'ID de la formation à supprimer
     * @param {function} callback - Fonction appelée une fois l'opération terminée
     */
    function removeFromCart(formationId, callback = null) {
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
                
                // Retirer la formation du localStorage
                const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
                const updatedFormations = cartFormations.filter(id => id !== formationId.toString());
                storeCartFormationsInLocalStorage(updatedFormations);
                
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
            
            if (callback) callback();
        })
        .catch(error => {
            console.error('Erreur lors de la suppression du panier:', error);
            showNotification('Une erreur est survenue lors de la suppression.', 'error');
            
            if (callback) callback();
        });
    }
    
    function checkFormationsInCart() {
        // Récupérer les formations déjà connues du localStorage
        const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
        
        // Vérifier d'abord la dernière formation ajoutée (pour une mise à jour rapide lors du retour arrière)
        const lastAddedFormation = localStorage.getItem('lastAddedFormation');
        if (lastAddedFormation) {
            console.log("Formation précédemment ajoutée:", lastAddedFormation);
            // Désactiver immédiatement le bouton pour la dernière formation ajoutée
            updateAddToCartButton(lastAddedFormation, true);
            
            // Ajouter cette formation aux formations du panier si elle n'y est pas déjà
            if (!cartFormations.includes(lastAddedFormation)) {
                cartFormations.push(lastAddedFormation);
                storeCartFormationsInLocalStorage(cartFormations);
            }
            
            // Supprimer cette information maintenant qu'elle a été traitée
            localStorage.removeItem('lastAddedFormation');
        }
        
        // Mettre à jour tous les boutons pour les formations connues du localStorage
        cartFormations.forEach(formationId => {
            updateAddToCartButton(formationId, true);
        });
        
        // Mettre à jour tous les boutons sur les cartes de formation visibles
        $('.formation-item, .product-box').each(function() {
            const formationId = $(this).attr('data-category-id') || $(this).attr('data-formation-id');
            
            if (formationId && cartFormations.includes(formationId.toString())) {
                const cartButton = $(this).find('.addcart-btn .btn[href="/panier"]');
                if (cartButton.length) {
                    cartButton.text('Accéder au panier');
                    cartButton.attr('data-in-cart', 'true');
                }
            }
        });
        
        // Ensuite, synchroniser avec le serveur
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
                // Créer un nouvel array des IDs de formations
                const serverFormationIds = data.items.map(item => item.toString());
                
                // Mettre à jour le localStorage avec les données du serveur (plus fiables)
                storeCartFormationsInLocalStorage(serverFormationIds);
                
                // Mettre à jour tous les boutons
                serverFormationIds.forEach(formationId => {
                    updateAddToCartButton(formationId, true);
                });
                
                // Mettre à jour le compteur du panier
                if (serverFormationIds.length !== parseInt(localStorage.getItem('cartCount') || '0', 10)) {
                    localStorage.setItem('cartCount', serverFormationIds.length.toString());
                    updateCartBadge(serverFormationIds.length);
                }
            }
        })
        .catch(error => console.error('Erreur lors de la vérification du panier:', error));
    }
    
    // Voici la fonction modifiée qui gère l'ouverture du modal
    $(document).on('shown.bs.modal', '.modal', function() {
        const formationId = this.id.split('-').pop();
        if (formationId) {
            // Vérifier d'abord dans le localStorage pour une réponse immédiate
            const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
            const inCart = cartFormations.includes(formationId.toString());
            
            // Mettre à jour immédiatement le bouton dans le modal
            const modalButton = $(this).find('.addcart-btn .btn[href="/panier"]');
            if (modalButton.length) {
                if (inCart) {
                    modalButton.text('Accéder au panier');
                    modalButton.attr('data-in-cart', 'true');
                } else {
                    modalButton.text('Ajouter au panier');
                    modalButton.removeAttr('data-in-cart');
                }
            }
            
            // Appel à la fonction générale pour mettre à jour tous les boutons liés à cette formation
            updateAddToCartButton(formationId, inCart);
        }
    });
    
    /**
     * Met à jour l'apparence du bouton "Ajouter au panier"
     * @param {string|number} formationId - L'ID de la formation
     * @param {boolean} inCart - Indique si la formation est dans le panier
     */
    function updateAddToCartButton(formationId, inCart) {
        console.log('Updating button for formation:', formationId, 'inCart:', inCart);
        
        // Trouver tous les boutons pour cette formation (dans la liste et dans le modal)
        const buttons = document.querySelectorAll(`.addcart-btn .btn[href="/panier"]`);
        const modalButtons = document.querySelectorAll(`#formation-modal-${formationId} .addcart-btn .btn[href="/panier"]`);
        
        // Fonction pour mettre à jour un bouton spécifique
        function updateButton(button) {
            if (inCart) {
                // Garder la classe btn-primary pour avoir la même couleur que "Ajouter au panier"
                button.classList.add('btn-primary');
                button.classList.remove('btn-secondary');
                button.disabled = false; // Garder le bouton actif
                button.setAttribute('data-in-cart', 'true');
                button.textContent = 'Accéder au panier'; // Changer le texte du bouton
            } else {
                button.classList.add('btn-primary');
                button.classList.remove('btn-secondary');
                button.disabled = false;
                button.removeAttribute('data-in-cart');
                button.textContent = 'Ajouter au panier'; // Réinitialiser le texte du bouton
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
                const cardId = card.getAttribute('data-category-id') || card.getAttribute('data-formation-id');
                
                // Vérifier si ce bouton correspond à notre formation
                if (cardId === formationId.toString()) {
                    updateButton(button);
                }
            }
        });
        
        // Chercher également dans toutes les cartes de formation visibles
        document.querySelectorAll('.formation-item, .product-box').forEach(card => {
            const cardId = card.getAttribute('data-category-id') || card.getAttribute('data-formation-id');
            
            if (cardId === formationId.toString()) {
                const button = card.querySelector('.addcart-btn .btn[href="/panier"]');
                if (button) {
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

    // Fonction pour afficher les détails d'une formation dans un modal
    window.showFormationDetails = function(formationId) {
        // Vérifier d'abord si cette formation est dans le panier avant d'ouvrir le modal
        const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
        const inCart = cartFormations.includes(formationId.toString());
        console.log('showFormationDetails - formationId:', formationId, 'inCart:', inCart);

        // Rechercher le modal correspondant à la formation
        const modal = $(`#formation-modal-${formationId}`);
        
        // Préparer le bouton avec le bon texte avant d'afficher le modal
        if (modal.length) {
            const modalButton = modal.find('.addcart-btn .btn[href="/panier"]');
            if (modalButton.length) {
                if (inCart) {
                    modalButton.text('Accéder au panier');
                    modalButton.attr('data-in-cart', 'true');
                } else {
                    modalButton.text('Ajouter au panier');
                    modalButton.removeAttr('data-in-cart');
                }
            }
            
            // Afficher le modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } else {
            console.error(`Modal pour la formation #${formationId} non trouvé`);
        }
    };

    // Handler pour le clic sur l'icône "œil" - modifié pour mettre à jour le bouton avant l'ouverture du modal
    $(document).on('click', '.product-hover a[data-bs-toggle="modal"]', function(e) {
        e.preventDefault();
        const modalId = $(this).attr('data-bs-target');
        const formationId = modalId.split('-').pop();
        console.log('Clic sur œil - formationId:', formationId);
        
        // Vérifier si cette formation est dans le panier avant d'ouvrir le modal
        const cartFormations = JSON.parse(localStorage.getItem('cartFormations') || '[]');
        const inCart = cartFormations.includes(formationId.toString());
        
        // Préparation du modal avant son ouverture
        const modal = $(modalId);
        if (modal.length) {
            const modalButton = modal.find('.addcart-btn .btn[href="/panier"]');
            if (modalButton.length) {
                if (inCart) {
                    modalButton.text('Accéder au panier');
                    modalButton.attr('data-in-cart', 'true');
                } else {
                    modalButton.text('Ajouter au panier');
                    modalButton.removeAttr('data-in-cart');
                }
            }
        }
        
        // Puis appeler la fonction pour afficher le modal
        window.showFormationDetails(formationId);
    });
});
// Ajoutez ce code à votre fichier JavaScript existant ou créez un nouveau fichier

document.addEventListener('DOMContentLoaded', function() {
    // Empêcher le décalage lors de l'ouverture des modals
    $(document).on('show.bs.modal', '.modal', function() {
        // Mémoriser la largeur initiale
        const initialWidth = $('body').width();
        
        // Attacher un style fixe au body
        $('body').css({
            'overflow': 'hidden',
            'padding-right': '0 !important',
            'width': initialWidth + 'px'
        });
        
        // Empêcher le modal-backdrop de créer un décalage
        $('.modal-backdrop').css({
            'width': '100%',
            'padding-right': '0'
        });
    });
    
    // Nettoyer les styles après fermeture du modal
    $(document).on('hidden.bs.modal', '.modal', function() {
        // Réinitialiser les styles du body
        $('body').css({
            'overflow': '',
            'padding-right': '',
            'width': ''
        });
        
        // S'assurer que la classe modal-open est bien supprimée
        if (!$('.modal.show').length) {
            $('body').removeClass('modal-open');
        }
    });
    
    // Ajouter des styles CSS pour forcer l'annulation du décalage
    const style = document.createElement('style');
    style.textContent = `
        body.modal-open {
            overflow: hidden !important;
            padding-right: 0 !important;
        }
        .modal-open .modal {
            overflow-x: hidden;
            overflow-y: auto;
            padding-right: 0 !important;
        }
        .modal-backdrop {
            width: 100% !important;
            padding-right: 0 !important;
        }
        html {
            overflow: visible !important;
        }
    `;
    document.head.appendChild(style);
});