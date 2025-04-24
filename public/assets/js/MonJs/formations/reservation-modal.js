
// // reservation-modal.js - Modal de détails de réservation
// function loadCSS() {
//     const link = document.createElement('link');
//     link.rel = 'stylesheet';
//     link.href = '/assets/css/MonCss/reservation-modal.css'; // Ajustez le chemin selon votre structure
//     document.head.appendChild(link);
// }

// document.addEventListener('DOMContentLoaded', function() {
//     loadCSS();

//     // Créer le modal une seule fois au chargement de la page
//     createReservationModal();
    
//     // Observer les changements du DOM pour attacher l'écouteur au bouton s'il est créé dynamiquement
//     const observer = new MutationObserver(function(mutations) {
//         mutations.forEach(function(mutation) {
//             if (mutation.addedNodes.length) {
//                 const reserverButton = document.querySelector('.reserver-button:not([data-listener])');
//                 if (reserverButton) {
//                     attachReserverButtonListener(reserverButton);
//                 }
//             }
//         });
//     });
    
//     observer.observe(document.body, { childList: true, subtree: true });

//     // Attacher un écouteur d'événement au bouton réserver s'il existe déjà
//     attachReserverButtonListener();
// });

// // Fonction pour créer le modal de réservation
// function createReservationModal() {
//     const modalHTML = `
//     <div id="reservation-modal" class="modal-overlay" style="display: none;">
//         <div class="modal-content">
//             <div class="modal-header">
//                 <h3>Détails de votre réservation</h3>
//                 <span class="close-modal">&times;</span>
//             </div>
//             <div class="modal-body">
//                 <div id="reservation-loading">
//                     <div class="spinner modal-spinner"></div>
//                     <p>Chargement des détails...</p>
//                 </div>
//                 <div id="reservation-details" style="display: none;">
//                     <div id="reservation-id-container">
//                         <p>ID de réservation: <span id="reservation-id" class="highlight">--</span></p>
//                     </div>
//                     <div class="formations-list">
//                         <!-- Les formations seront injectées ici -->
//                     </div>
//                     <div class="reservation-total">
//                         <h4>Total: <span id="reservation-total-price">--</span></h4>
//                     </div>
//                 </div>
//                 <div id="reservation-error" style="display: none;">
//                     <p class="error-message">Une erreur s'est produite lors du chargement des détails.</p>
//                 </div>
//             </div>
//             <div class="modal-footer">
//                 <button id="confirm-reservation" class="btn btn-primary">Confirmer la réservation</button>
//                 <button id="cancel-modal" class="btn btn-secondary">Annuler</button>
//             </div>
//         </div>
//     </div>
//     `;
    
//     // Ajouter le modal au body
//     const modalContainer = document.createElement('div');
//     modalContainer.innerHTML = modalHTML;
//     document.body.appendChild(modalContainer);
    
//     // Ajouter les styles du modal
//     const modalStyle = document.createElement('style');
    
    
//     // Ajouter les gestionnaires d'événements pour le modal
//     document.querySelector('.close-modal').addEventListener('click', closeModal);
//     document.getElementById('cancel-modal').addEventListener('click', closeModal);
    
//     // Gestionnaire pour le bouton de confirmation
//     document.getElementById('confirm-reservation').addEventListener('click', function() {
//         // Afficher un indicateur de chargement sur le bouton
//         const confirmButton = this;
//         const originalText = confirmButton.innerHTML;
//         confirmButton.innerHTML = '<span class="spinner"></span> Traitement...';
//         confirmButton.disabled = true;
        
//         // Appeler la fonction de création de réservation
//         createReservation()
//             .then(response => {
//                 if (response.success) {
//                     displayToastMessage(response.message || 'Réservation effectuée avec succès', 'success');
                    
//                     // Si la réservation est réussie, on peut vider le panier
//                     if (response.clearCart) {
//                         updateCartCount(0);
//                     }
                    
//                     // Fermer le modal
//                     closeModal();
//                 } else {
//                     displayToastMessage(response.message || 'Erreur lors de la réservation', 'error');
//                     // Restaurer le bouton
//                     confirmButton.innerHTML = originalText;
//                     confirmButton.disabled = false;
//                 }
//             })
//             .catch(error => {
//                 console.error('Erreur:', error);
//                 displayToastMessage('Erreur lors de la réservation', 'error');
//                 // Restaurer le bouton
//                 confirmButton.innerHTML = originalText;
//                 confirmButton.disabled = false;
//             });
//     });
// }

// // Fonction pour fermer le modal
// function closeModal() {
//     const modal = document.getElementById('reservation-modal');
//     modal.style.display = 'none';
// }

// // Fonction pour afficher le modal
// function showModal() {
//     const modal = document.getElementById('reservation-modal');
//     modal.style.display = 'flex';
// }

// // Fonction pour attacher l'écouteur d'événement au bouton réserver
// function attachReserverButtonListener(button = null) {
//     const reserverButton = button || document.querySelector('.reserver-button:not([data-listener])');
    
//     if (reserverButton && !reserverButton.hasAttribute('data-listener')) {
//         reserverButton.setAttribute('data-listener', 'true');
        
//         // Remplacer l'événement de clic existant par notre nouvelle fonction
//         reserverButton.addEventListener('click', function(e) {
//             e.preventDefault();
//             e.stopPropagation();
            
//             // Vérifier si l'utilisateur est connecté
//             checkUserAuthentication()
//                 .then(authenticated => {
//                     if (authenticated) {
//                         // Vérifier si le panier n'est pas vide
//                         fetch('/panier/items-count', {
//                             method: 'GET',
//                             headers: {
//                                 'X-Requested-With': 'XMLHttpRequest',
//                                 'Accept': 'application/json'
//                             }
//                         })
//                         .then(response => response.json())
//                         .then(data => {
//                             if (data.count > 0) {
//                                 // Charger les détails du panier et afficher le modal
//                                 loadCartDetails();
//                             } else {
//                                 displayToastMessage('Votre panier est vide', 'warning');
//                             }
//                         })
//                         .catch(error => {
//                             console.error('Erreur:', error);
//                             displayToastMessage('Erreur lors de la vérification du panier', 'error');
//                         });
//                     } else {
//                         displayToastMessage('Veuillez vous connecter pour réserver', 'info');
//                         redirectToLogin();
//                     }
//                 });
//         });
//     }
// }

// // Fonction pour charger les détails du panier
// function loadCartDetails() {
//     // Afficher le modal avec l'indicateur de chargement
//     showModal();
//     document.getElementById('reservation-loading').style.display = 'block';
//     document.getElementById('reservation-details').style.display = 'none';
//     document.getElementById('reservation-error').style.display = 'none';
    
//     // Charger les détails du panier via AJAX
//     fetch('/api/cart/details', {
//         method: 'GET',
//         headers: {
//             'X-Requested-With': 'XMLHttpRequest',
//             'Accept': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         }
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error('Erreur lors du chargement des détails du panier');
//         }
//         return response.json();
//     })
//     .then(data => {
//         if (data.success) {
//             renderCartDetails(data);
//         } else {
//             throw new Error(data.message || 'Erreur lors du chargement des détails du panier');
//         }
//     })
//     .catch(error => {
//         console.error('Erreur:', error);
//         document.getElementById('reservation-loading').style.display = 'none';
//         document.getElementById('reservation-error').style.display = 'block';
//     });
// }

// // Fonction pour afficher les détails du panier dans le modal
// function renderCartDetails(data) {
//     const detailsContainer = document.getElementById('reservation-details');
//     const formationsList = detailsContainer.querySelector('.formations-list');
//     const reservationId = document.getElementById('reservation-id');
//     const totalPrice = document.getElementById('reservation-total-price');
    
//     // Vider la liste des formations
//     formationsList.innerHTML = '';
    
//     // Afficher l'ID de pré-réservation (généré par le backend ou temporaire)
//     reservationId.textContent = data.reservation_id || 'Sera généré à la confirmation';
    
//     // Calculer le prix total
//     let total = 0;
    
//     // Ajouter chaque formation à la liste
//     data.trainings.forEach(training => {
//         const formationItem = document.createElement('div');
//         formationItem.className = 'formation-item';
        
//         // Ajouter le titre de la formation
//         const titleElement = document.createElement('div');
//         titleElement.className = 'formation-title';
//         titleElement.textContent = training.title;
//         formationItem.appendChild(titleElement);
        
//         // Ajouter les informations de prix
//         const priceElement = document.createElement('div');
//         priceElement.className = 'formation-price';
        
//         // Si il y a une remise, afficher le prix original et la remise
//         if (training.discount > 0) {
//             const priceInfo = document.createElement('div');
            
//             const originalPrice = document.createElement('span');
//             originalPrice.className = 'original-price';
//             originalPrice.textContent = `${training.price} €`;
//             priceInfo.appendChild(originalPrice);
            
//             const discount = document.createElement('span');
//             discount.className = 'discount';
//             discount.textContent = ` -${training.discount}% `;
//             priceInfo.appendChild(discount);
            
//             priceElement.appendChild(priceInfo);
            
//             const finalPrice = document.createElement('div');
//             finalPrice.className = 'final-price';
//             finalPrice.textContent = `${training.final_price} €`;
//             priceElement.appendChild(finalPrice);
            
//             // Ajouter au total
//             total += parseFloat(training.final_price);
//         } else {
//             // Pas de remise, afficher seulement le prix
//             const finalPrice = document.createElement('div');
//             finalPrice.className = 'final-price';
//             finalPrice.textContent = `${training.price} €`;
//             priceElement.appendChild(finalPrice);
            
//             // Ajouter au total
//             total += parseFloat(training.price);
//         }
        
//         formationItem.appendChild(priceElement);
        
//         // Ajouter les dates de la formation
//         const datesElement = document.createElement('div');
//         datesElement.className = 'formation-dates';
        
//         const startDate = document.createElement('div');
//         startDate.textContent = `Début: ${training.start_date}`;
//         datesElement.appendChild(startDate);
        
//         const endDate = document.createElement('div');
//         endDate.textContent = `Fin: ${training.end_date}`;
//         datesElement.appendChild(endDate);
        
//         formationItem.appendChild(datesElement);
        
//         // Ajouter la formation à la liste
//         formationsList.appendChild(formationItem);
//     });
    
//     // Afficher le prix total
//     totalPrice.textContent = `${total.toFixed(2)} €`;
    
//     // Masquer le chargement et afficher les détails
//     document.getElementById('reservation-loading').style.display = 'none';
//     detailsContainer.style.display = 'block';
// }

// // Fonction pour créer une réservation via une requête AJAX
// function createReservation() {
//     return fetch('/api/reservations/create', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//             'X-Requested-With': 'XMLHttpRequest',
//             'Accept': 'application/json'
//         },
//         body: JSON.stringify({
//             // Pas besoin d'envoyer cart_id et user_id car le backend les récupérera 
//             // depuis la session de l'utilisateur connecté
//             reservation_date: new Date().toISOString().split('T')[0], // Date actuelle
//             reservation_time: new Date().toTimeString().split(' ')[0] // Heure actuelle
//         })
//     })
//     .then(response => {
//         if (!response.ok) {
//             // Si le statut n'est pas OK (2xx), on lance une erreur
//             return response.json().then(errorData => {
//                 throw new Error(errorData.message || 'Erreur lors de la réservation');
//             });
//         }
//         return response.json();
//     });
// }

// // Fonction pour vérifier si l'utilisateur est connecté
// function checkUserAuthentication() {
//     return fetch('/api/user/check-auth', {
//         method: 'GET',
//         headers: {
//             'X-Requested-With': 'XMLHttpRequest',
//             'Accept': 'application/json'
//         }
//     })
//     .then(response => response.json())
//     .then(data => {
//         return data.authenticated;
//     })
//     .catch(error => {
//         console.error('Erreur lors de la vérification de l\'authentification:', error);
//         return false;
//     });
// }

// // Fonction pour rediriger vers la page de connexion si l'utilisateur n'est pas connecté
// function redirectToLogin() {
//     // Sauvegarder l'URL actuelle pour rediriger après la connexion
//     localStorage.setItem('redirectAfterLogin', window.location.href);
//     window.location.href = '/login';
// }

// // Fonction simple pour afficher un toast - CORRIGÉE pour éviter la récursion
// function displayToastMessage(message, type = 'success') {
//     // Créer un élément toast
//     const toast = document.createElement('div');
//     toast.className = `toast toast-${type}`;
//     toast.textContent = message;
    
//     // Ajouter le toast au body
//     document.body.appendChild(toast);
    
//     // Programmer la suppression du toast
//     setTimeout(() => {
//         toast.style.opacity = '0';
//         setTimeout(() => {
//             if (toast.parentNode) {
//                 document.body.removeChild(toast);
//             }
//         }, 500);
//     }, 3000);
    
//     return toast;
// }

// // Fonction pour mettre à jour le compteur du panier
// function updateCartCount(count) {
//     // Trouver l'élément du compteur de panier et le mettre à jour
//     const cartCountElement = document.querySelector('.cart-count');
//     if (cartCountElement) {
//         cartCountElement.textContent = count;
//     }
// }

// // Exporter les fonctions pour les rendre disponibles globalement
// window.showModal = showModal;
// window.closeModal = closeModal;
// window.loadCartDetails = loadCartDetails;
// window.attachReserverButtonListener = attachReserverButtonListener;
// window.displayToastMessage = displayToastMessage;