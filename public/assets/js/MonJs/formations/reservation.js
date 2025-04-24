
(function() {
    // Variable globale pour suivre l'état des réservations
    window.hasExistingReservation = false;
    
    // Initialisation immédiate
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initReservationSystem);
    } else {
        initReservationSystem();
    }
    function initReservationSystem() {
        // Vérification immédiate du panier avant tout
        const cartCount = parseInt(localStorage.getItem('cartCount') || '0');
        
        // Vérifier d'abord s'il existe une réservation avant toute autre action
        checkExistingReservations().then(hasReservation => {
            window.hasExistingReservation = hasReservation;
            
            // Si aucune réservation n'existe ET le panier n'est pas vide, alors initialiser le bouton "Réserver"
            if (!hasReservation && cartCount > 0) {
                // CORRECTION: Créer immédiatement le bouton réserver si nécessaire
                createReserverButton();
                
                // Vérifier si un bouton de réservation existe déjà et ajouter l'écouteur d'événement
                const existingButton = document.querySelector('.reserver-button');
                if (existingButton) {
                    existingButton.removeEventListener('click', handleReservationClick);
                    existingButton.addEventListener('click', handleReservationClick);
                    // Assurer que le bouton est immédiatement visible
                    existingButton.style.opacity = '1';
                    existingButton.style.display = 'block';
                }
            }
        });
    }
})();

/**
 * Vérifie si l'utilisateur a déjà une réservation active
 * Si oui, transforme le bouton "Réserver" en "Voir mes réservations" et ajoute le bouton "Annuler"
 * @return {Promise<boolean>} Promesse qui se résout à true si l'utilisateur a une réservation, false sinon
 */
function checkExistingReservations() {
    return new Promise((resolve) => {
        // Signaler que la vérification est en cours
        window.checkingReservations = true;
        
        checkUserAuthentication()
            .then(authenticated => {
                if (authenticated) {
                    fetch('/api/reservations/check', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(handleResponse)
                    .then(data => {
                        window.checkingReservations = false;
                        if (data.hasReservation) {
                            transformReserverButton(data.reservation_id);
                            window.hasExistingReservation = true;
                            resolve(true);
                        } else {
                            window.hasExistingReservation = false;
                            resolve(false);
                        }
                    })
                    .catch(error => {
                        window.checkingReservations = false;
                        console.error('Erreur lors de la vérification des réservations:', error);
                        window.hasExistingReservation = false;
                        resolve(false);
                    });
                } else {
                    window.checkingReservations = false;
                    window.hasExistingReservation = false;
                    resolve(false);
                }
            })
            .catch(() => {
                window.checkingReservations = false;
                window.hasExistingReservation = false;
                resolve(false);
            });
    });
}

/**
 * Transforme le bouton de réservation en bouton "Voir mes réservations"
 * et ajoute un bouton pour annuler la réservation
 * @param {number} reservationId - L'ID de la réservation
 */
function transformReserverButton(reservationId) {
    // Supprimer d'abord les deux boutons s'ils existent
    const existingReserverButton = document.querySelector('.reserver-button');
    if (existingReserverButton) {
        existingReserverButton.remove();
    }
    
    const existingCancelButton = document.querySelector('.annuler-button');
    if (existingCancelButton) {
        existingCancelButton.remove();
    }
    
    // Trouver le conteneur où les boutons doivent être placés
    const totalPriceElement = document.querySelector('.total-price');
    if (!totalPriceElement) return;
    
    const totalContainer = totalPriceElement.closest('.total-container') || totalPriceElement.parentElement;
    
    // Créer et ajouter le bouton "Voir mes réservations" en PREMIER
    const reserverButton = document.createElement('button');
    reserverButton.className = 'reserver-button';
    reserverButton.innerHTML = 'Voir mes réservations <svg class="arrow-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    
    reserverButton.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '/mes-reservations';
    });
    
    totalContainer.appendChild(reserverButton);
    
    // Créer et ajouter le bouton d'annulation en SECOND
    const cancelButton = document.createElement('button');
    cancelButton.className = 'annuler-button';
    cancelButton.innerHTML = 'Annuler la réservation';
    
    cancelButton.addEventListener('click', function(e) {
        e.preventDefault();
        cancelReservation(reservationId);
    });
    
    totalContainer.appendChild(cancelButton);
}

/**
 * Annule une réservation existante
 * @param {number} reservationId - L'ID de la réservation à annuler
 */
// function cancelReservation(reservationId) {
//     fetch('/api/reservations/cancel', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//             'X-Requested-With': 'XMLHttpRequest',
//             'Accept': 'application/json'
//         },
//         body: JSON.stringify({
//             reservation_id: reservationId
//         })
//     })
//     .then(handleResponse)
//     .then(data => {
//         if (data.success) {
//             console.log(data.message || 'Réservation annulée avec succès');
            
//             // Mise à jour de la variable globale
//             window.hasExistingReservation = false;
            
//             // Restaurer le bouton "Réserver" original
//             restoreReserverButton();
            
//             // Supprimer le bouton "Annuler"
//             const cancelButton = document.querySelector('.annuler-button');
//             if (cancelButton) {
//                 cancelButton.remove();
//             }
//         } else {
//             console.error(data.message || 'Erreur lors de l\'annulation de la réservation');
//         }
//     })
//     .catch(error => {
//         console.error('Erreur lors de l\'annulation de la réservation:', error);
//     });
// }
function cancelReservation(reservationId) {
    fetch('/api/reservations/cancel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            reservation_id: reservationId
        })
    })
    .then(handleResponse)
    .then(data => {
        if (data.success) {
            console.log(data.message || 'Réservation annulée avec succès');
            
            // Mise à jour de la variable globale
            window.hasExistingReservation = false;
            
            // Supprimer le bouton "Annuler" immédiatement
            const cancelButton = document.querySelector('.annuler-button');
            if (cancelButton) {
                cancelButton.remove();
            }
            
            // Restaurer le bouton "Réserver" immédiatement sans attendre
            // CORRECTION: Créer le bouton immédiatement
            const cartCount = parseInt(localStorage.getItem('cartCount') || '0');
            if (cartCount > 0) {
                restoreReserverButton();
                // Assurer que le bouton est visible sans délai
                const reserverButton = document.querySelector('.reserver-button');
                if (reserverButton) {
                    reserverButton.style.opacity = '1';
                    reserverButton.style.display = 'block';
                }
            }
        } else {
            console.error(data.message || 'Erreur lors de l\'annulation de la réservation');
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'annulation de la réservation:', error);
    });
}
/**
 * Restaure le bouton "Réserver" d'origine
 */
function restoreReserverButton() {
    const reserverButton = document.querySelector('.reserver-button');
    if (reserverButton) {
        // Restaurer le texte et l'icône d'origine
        reserverButton.innerHTML = 'Réserver <svg class="arrow-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        
        // Supprimer tous les écouteurs d'événements existants
        const newButton = reserverButton.cloneNode(true);
        reserverButton.parentNode.replaceChild(newButton, reserverButton);
        
        // Ajouter le bon écouteur d'événement pour la création de réservation
        newButton.addEventListener('click', handleReservationClick);
    } else {
        // Si le bouton n'existe pas, on le crée
        createReserverButton();
        const newButton = document.querySelector('.reserver-button');
        if (newButton) {
            newButton.addEventListener('click', handleReservationClick);
        }
    }
}

/**
 * Gère le clic sur le bouton de réservation
 * @param {Event} e - L'événement de clic
 */
function handleReservationClick(e) {
    e.preventDefault();
    e.stopPropagation(); // Empêcher la propagation de l'événement
    processReservation();
}

/**
 * Traite la demande de réservation
 * Vérifie l'authentification avant de procéder
 */
function processReservation() {
    checkUserAuthentication()
        .then(authenticated => {
            if (authenticated) {
                // Vérifier si le panier n'est pas vide
                checkCartNotEmpty()
                    .then(notEmpty => {
                        if (notEmpty) {
                            // Appeler la fonction de création de réservation
                            createReservation()
                                .then(response => {
                                    handleReservationResponse(response);
                                })
                                .catch(handleReservationError);
                        } else {
                            console.log('Votre panier est vide');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la vérification du panier:', error);
                    });
            } else {
                console.log('Veuillez vous connecter pour réserver');
                redirectToLogin();
            }
        });
}

/**
 * Vérifie si l'utilisateur est authentifié
 * @return {Promise<boolean>} - Promesse résolue avec true si l'utilisateur est authentifié
 */
function checkUserAuthentication() {
    return fetch('/api/user/check-auth', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        return data.authenticated;
    })
    .catch(error => {
        console.error('Erreur lors de la vérification de l\'authentification:', error);
        return false;
    });
}

/**
 * Vérifie si le panier n'est pas vide
 * @return {Promise<boolean>} - Promesse résolue avec true si le panier n'est pas vide
 */
function checkCartNotEmpty() {
    return fetch('/panier/items-count', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(handleResponse)
    .then(data => {
        return data.count > 0;});
    }
    
/**
 * Crée une réservation via une requête AJAX
 * @return {Promise<Object>} - Promesse résolue avec la réponse du serveur
 */
function createReservation() {
    return fetch('/api/reservations/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            reservation_date: new Date().toISOString().split('T')[0], // Date actuelle
            reservation_time: new Date().toTimeString().split(' ')[0] // Heure actuelle
        })
    })
    .then(response => {
        if (!response.ok) {
            // Si le statut n'est pas OK (2xx), on lance une erreur
            return response.json().then(errorData => {
                throw new Error(errorData.message || 'Erreur lors de la réservation');
            });
        }
        return response.json();
    });
}

/**
 * Traite la réponse de la création de réservation
 * @param {Object} response - La réponse du serveur
 */
function handleReservationResponse(response) {
    if (response.success) {
        console.log(response.message || 'Réservation effectuée avec succès');
        
        // Mettre à jour la variable globale
        window.hasExistingReservation = true;
        
        // CORRECTION MAJEURE: Avant de vider le panier, on transforme d'abord le bouton
        if (response.reservation_id) {
            transformReserverButton(response.reservation_id);
        }
        
        // Si la réservation est réussie et qu'il faut vider le panier
        // mais on ne supprime pas le bouton ou le badge
        if (response.clearCart) {
            // Mise à jour du compteur sans supprimer visuellement le bouton
            // On garde la référence à 0 dans localStorage mais on ne modifie pas l'UI
            localStorage.setItem('cartCount', '0');
            // Ne pas appeler updateCartCount(0) ici car cela supprimerait le bouton
        }

        // Redirection éventuelle vers la page de confirmation
        if (response.redirectUrl) {
            window.location.href = response.redirectUrl;
        }
    } else {
        console.error(response.message || 'Erreur lors de la réservation');
    }
}

/**
 * Gère les erreurs lors de la création de réservation
 * @param {Error} error - L'erreur survenue
 */
function handleReservationError(error) {
    console.error('Erreur lors de la réservation:', error);
}

/**
 * Redirige vers la page de connexion
 * Sauvegarde l'URL actuelle pour y revenir après connexion
 */
function redirectToLogin() {
    // Sauvegarder l'URL actuelle pour rediriger après la connexion
    localStorage.setItem('redirectAfterLogin', window.location.href);
    window.location.href = '/login';
}

/**
 * Gère la réponse HTTP et vérifie si elle est OK
 * @param {Response} response - La réponse HTTP
 * @return {Promise<Object>} - Promesse résolue avec le contenu JSON de la réponse
 */
function handleResponse(response) {
    if (!response.ok) {
        throw new Error('Erreur réseau');
    }
    return response.json();
}

/**
 * Crée le bouton réserver si nécessaire
 * Cette fonction est aussi définie dans panier.js mais nous avons besoin d'une version ici pour les cas où 
 * le bouton n'existe pas après une réservation.
 */
function createReserverButton() {
    // Ne pas créer le bouton si l'utilisateur a déjà une réservation
    if (window.hasExistingReservation === true) return false;
    
    // Ne pas créer le bouton si une vérification de réservation est en cours
    if (window.checkingReservations === true) return false;
    
    // Ne pas vérifier le localStorage - on veut créer le bouton même si le panier est vide
    const totalPriceElement = document.querySelector('.total-price');
    if (!totalPriceElement) return false;
    
    // Vérifier si le bouton existe déjà ou s'il y a un bouton d'annulation
    if (document.querySelector('.reserver-button') || document.querySelector('.annuler-button')) return true;
    
    const reservButton = document.createElement('button');
    reservButton.className = 'reserver-button';
    reservButton.innerHTML = 'Réserver <svg class="arrow-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    
    // Afficher immédiatement sans transition
    reservButton.style.opacity = '1';
    
    // On ajoute l'événement ici
    reservButton.addEventListener('click', handleReservationClick);
    
    const totalContainer = totalPriceElement.closest('.total-container') || totalPriceElement.parentElement;
    totalContainer.appendChild(reservButton);
    
    return true;
}

// Exposer les fonctions nécessaires globalement
window.processReservation = processReservation;
window.createReservation = createReservation;
window.checkUserAuthentication = checkUserAuthentication;
window.transformReserverButton = transformReserverButton;
window.cancelReservation = cancelReservation;
window.checkExistingReservations = checkExistingReservations;
window.createReserverButton = createReserverButton;  // Important d'exposer notre version