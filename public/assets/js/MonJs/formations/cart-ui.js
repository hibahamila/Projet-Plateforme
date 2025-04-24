
// Ce fichier se concentre sur l'interface utilisateur du panier et la gestion des interactions après l'ajout d'une formation.
// cart-ui.js - Fonctions d'interface utilisateur du panier

document.addEventListener('DOMContentLoaded', function() {
    // S'assurer que les utilitaires sont disponibles
    if (!window.FormationUtils) {
        console.error("Module d'utilitaires non chargé. Veuillez inclure utils.js avant cart-ui.js");
        return;
    }

    // Initialiser les formations du panier si non défini
    window.cartFormations = window.cartFormations || [];

    // Initialisation UI
    initCartUI();
});

/**
 * Initialise l'interface utilisateur du panier
 */
function initCartUI() {
    createModalIfNeeded();
    setupModalEventListeners();
}

/**
 * Crée la modale et le toast si nécessaire
 */
function createModalIfNeeded() {
    // Création de la modale principale
    if (!document.getElementById('add-to-cart-modal')) {
        const modalHTML = `
            <div id="add-to-cart-modal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Ajouté au panier</h2>
                        <span class="close-modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="formation-details">
                            <div class="formation-image-container">
                                <img class="formation-image" src="" alt="Image de formation">
                            </div>
                            <div class="formation-info">
                                <h3 class="formation-title"></h3>
                                <p class="formation-instructor"></p>
                                <div class="formation-rating"></div>
                                <div class="formation-price">
                                    <span class="final-price"></span>
                                    <div class="discount-info">
                                        <span class="original-price"></span>
                                        <span class="discount-percentage"></span>
                                    </div>
                                </div>
                                <div class="badge-container"></div>
                            </div>
                        </div>
                        <div id="cart-added-formations">
                            <!-- Les formations ajoutées s'afficheront ici -->
                        </div>
                        <div class="modal-actions">
                            <button class="btn-pay">Réserver <i class="fas fa-arrow-right"></i></button>
                            <button class="btn-view-cart">Accéder au panier <i class="fas fa-shopping-cart"></i></button>
                        </div>
                        <div class="related-formations">
                            <h3>Vous pouvez achetez </h3>
                            <div class="related-formations-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        <link rel="stylesheet" href="/assets/css/MonCss/cart-modal.css">
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    // Toast de confirmation compact
    if (!document.getElementById('confirmation-toast')) {
        const toastHTML = `
            <div id="confirmation-toast">
                <div class="toast-content">
                    <i class="fas fa-check-circle"></i>
                    <span class="toast-message"></span>
                    <span class="close-toast">&times;</span>
                </div>
                <!-- La timeline sera ajoutée dynamiquement -->
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', toastHTML);

        // Ajouter un listener pour fermer le toast
        document.querySelector('.close-toast').addEventListener('click', function() {
            document.getElementById('confirmation-toast').style.display = 'none';
        });
    }

    // Styles CSS pour le toast compact
    if (!document.getElementById('toast-styles')) {
        const styleTag = document.createElement('style');
        styleTag.id = 'toast-styles';
        styleTag.textContent = `
            #confirmation-toast {
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #ffffff;
                color: #333;
                padding: 10px 15px;
                border-radius: 3px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
                z-index: 9999;
                max-width: 300px;
                width: auto;
                display: none;
                overflow: hidden;
                font-size: 14px;
            }

            .toast-content {
                display: flex;
                align-items: center;
                position: relative;
                padding-right: 20px;
            }

            .toast-content i {
                margin-right: 8px;
                font-size: 16px;
            }

            .close-toast {
                position: absolute;
                top: -5px;
                right: -10px;
                cursor: pointer;
                font-size: 16px;
            }

            .toast-timeline {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                width: 0%;
                background-color:rgb(252, 251, 255);
                transition: width 3s linear;
            }
        `;
        document.head.appendChild(styleTag);
    }
}

/**
 * Affiche un toast de confirmation
 * @param {string} message - Message à afficher
 * @param {number} duration - Durée d'affichage en millisecondes
 */
function showToast(message, duration = 5000) {
    const toast = document.getElementById('confirmation-toast');
    const toastMessage = toast.querySelector('.toast-message');

    // Définir le message
    toastMessage.textContent = message;

    // Supprimer l'ancienne timeline si elle existe
    const oldTimeline = toast.querySelector('.toast-timeline');
    if (oldTimeline) {
        oldTimeline.remove();
    }

    // Créer et ajouter une nouvelle timeline
    const timeline = document.createElement('div');
    timeline.className = 'toast-timeline';
    timeline.style.backgroundColor = '#ffffff'; // Force la couleur blanche directement
    toast.appendChild(timeline);

    // Afficher le toast
    toast.style.display = 'block';
    toast.style.backgroundColor = '#2B6ED4'; // Couleur bleue
    toast.style.color = '#ffffff';

    // Animer la timeline
    setTimeout(() => {
        timeline.style.width = '100%';
    }, 10);

    // Masquer le toast après la durée spécifiée
    setTimeout(() => {
        toast.style.display = 'none';
    }, duration);
}

/**
 * Configure les écouteurs d'événements pour la modale
 */
function setupModalEventListeners() {
    const modal = document.getElementById('add-to-cart-modal');
    const closeModalBtn = document.querySelector('.close-modal');

    // Masquer le panneau de détail lorsque la modal est ouverte
    if (modal) {
        modal.addEventListener('show', function() {
            $('#formation-detail-panel').removeClass('active').css('opacity', 0);
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    // Fermer la modale en cliquant en dehors
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Configurer les boutons d'action dans la modale
    setupActionButtons();

    // Gestionnaire pour l'ajout des formations liées
    $(document).on('click', '.add-related-btn', function() {
        const formationId = $(this).data('id');
        const relatedCard = $(this).closest('.related-formation-card');

        // Extraire les données de la formation
        const cardData = {
            id: formationId,
            title: relatedCard.find('.related-formation-title').text(),
            instructor: relatedCard.find('.related-formation-instructor').text(),
            image: relatedCard.find('.related-formation-image').attr('src'),
            price: relatedCard.find('.final-price').text(),
            rating: relatedCard.find('.rating-value').text(),
            ratingStars: relatedCard.find('.rating-stars').html(),
            ratingCount: relatedCard.find('.rating-count').text().replace(/[()]/g, ''),
            isBestseller: relatedCard.find('.badge-bestseller-small').length > 0,
            hasDiscount: relatedCard.find('.original-price').length > 0,
            originalPrice: relatedCard.find('.original-price').text(),
            discountPercentage: relatedCard.find('.discount-percentage').text(),
            category: relatedCard.attr('data-category') || ''
        };

        // Ajouter la formation au panier via AJAX
        $.ajax({
            url: '/panier/ajouter',
            type: 'POST',
            data: {
                formation_id: formationId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Mettre à jour le cache
                    window.cartStatusCache[formationId] = true;

                    // Mettre à jour le badge du panier
                    FormationUtils.updateCartBadge(response.cartCount);

                    // Ajouter à l'affichage du panier
                    addFormationToCartDisplay(cardData);

                    // Afficher un toast de confirmation
                    showToast("Formation ajoutée au panier avec succès");
                }
            }
        });
    });
}

/**
 * Configure les boutons d'action dans la modale
 */
function setupActionButtons() {
    const payBtn = document.querySelector('#add-to-cart-modal .btn-pay');
    const cartBtn = document.querySelector('#add-to-cart-modal .btn-view-cart');

    // Supprimer les écouteurs d'événements existants
    payBtn.replaceWith(payBtn.cloneNode(true));
    cartBtn.replaceWith(cartBtn.cloneNode(true));

    // Ajouter de nouveaux écouteurs
    document.querySelector('#add-to-cart-modal .btn-pay').addEventListener('click', function() {
        window.location.href = '/checkout';
    });

    document.querySelector('#add-to-cart-modal .btn-view-cart').addEventListener('click', function() {
        window.location.href = '/panier';
    });
}

/**
 * Remplit la modale avec les données de la formation
 * @param {Object} formationData - Données de la formation
 */
function populateModal(formationData) {
    document.querySelector('#add-to-cart-modal .formation-title').textContent = formationData.title;
    document.querySelector('#add-to-cart-modal .formation-instructor').textContent = formationData.instructor;

    // Afficher le prix final
    const finalPriceElement = document.querySelector('#add-to-cart-modal .final-price');
    finalPriceElement.textContent = formationData.price;

    // Gérer l'affichage de la remise
    const discountInfoElement = document.querySelector('#add-to-cart-modal .discount-info');
    const originalPriceElement = document.querySelector('#add-to-cart-modal .original-price');
    const discountPercentageElement = document.querySelector('#add-to-cart-modal .discount-percentage');

    if (formationData.hasDiscount) {
        // Afficher le prix original barré
        originalPriceElement.textContent = formationData.originalPrice;
        originalPriceElement.style.textDecoration = 'line-through';
        originalPriceElement.style.color = '#6a6f73';
        originalPriceElement.style.marginRight = '8px';

        // Afficher le pourcentage de remise en rouge
        discountPercentageElement.textContent = formationData.discountPercentage;
        discountPercentageElement.style.color = '#a10000';
        discountPercentageElement.style.fontWeight = 'bold';

        // S'assurer que le conteneur de remise est visible
        discountInfoElement.style.display = 'inline-block';
    } else {
        // Cacher les éléments de remise s'il n'y en a pas
        discountInfoElement.style.display = 'none';
    }

    const imageElement = document.querySelector('#add-to-cart-modal .formation-image');
    if (formationData.image && formationData.image !== '') {
        imageElement.src = formationData.image;
        imageElement.style.display = 'block';
    } else {
        imageElement.src = '/api/placeholder/200/120'; // Image par défaut
        imageElement.style.display = 'block';
    }

    // Récupérer les dimensions de l'image principale pour les utiliser plus tard
    const mainImageWidth = imageElement.clientWidth || 200;
    const mainImageHeight = imageElement.clientHeight || 120;

    // Stocker ces dimensions dans des attributs data pour les utiliser plus tard
    document.querySelector('#add-to-cart-modal').setAttribute('data-main-image-width', mainImageWidth);
    document.querySelector('#add-to-cart-modal').setAttribute('data-main-image-height', mainImageHeight);

    // Afficher ou masquer le badge bestseller
    const badgeContainer = document.querySelector('#add-to-cart-modal .badge-container');
    badgeContainer.innerHTML = formationData.isBestseller ? '<span class="badge-bestseller">Meilleure vente</span>' : '';

    // Afficher la note et les étoiles
    const ratingContainer = document.querySelector('#add-to-cart-modal .formation-rating');
    if (parseFloat(formationData.rating) > 0) {
        ratingContainer.innerHTML = `
            <span class="rating-value">${formationData.rating}</span>
            <span class="rating-stars">${formationData.ratingStars || FormationUtils.generateStars(parseFloat(formationData.rating))}</span>
            <span class="rating-count">${formationData.ratingCount}</span>
        `;
        ratingContainer.style.display = 'flex';
    } else {
        ratingContainer.style.display = 'none';
    }
}

/**
 * Ajoute une formation à l'affichage du panier
 * @param {Object} formationData - Données de la formation
 */
function addFormationToCartDisplay(formationData) {
    // Vérifier si cette formation est déjà dans la liste en utilisant une comparaison stricte
    const existingFormationIndex = window.cartFormations.findIndex(f => f.id === formationData.id);

    if (existingFormationIndex === -1) {
        // Ajouter à notre liste de suivi seulement si elle n'existe pas déjà
        window.cartFormations.push(formationData);
    } else {
        // Si la formation existe déjà, pas besoin de l'ajouter à nouveau
        console.log("Formation déjà dans le panier:", formationData.id);
        return;
    }

    // Créer l'élément HTML pour la formation
    const cartFormationElement = document.createElement('div');
    cartFormationElement.className = 'formation-details';
    cartFormationElement.setAttribute('data-id', formationData.id);

    // Préparer l'affichage du prix avec remise si applicable
    let priceHTML = '';
    if (formationData.hasDiscount) {
        priceHTML = `
            <div class="formation-price">
                <span class="final-price">${formationData.price}</span>
                <div class="discount-info">
                    <span class="original-price">${formationData.originalPrice}</span>
                    <span class="discount-percentage">${formationData.discountPercentage}</span>
                </div>
            </div>
        `;
    } else {
        priceHTML = `<div class="formation-price"><span class="final-price">${formationData.price}</span></div>`;
    }

    // Gérer correctement l'affichage de la notation
    let ratingHTML = '';
    if (parseFloat(formationData.rating) > 0) {
        ratingHTML = `
            <div class="formation-rating">
                <span class="rating-value">${formationData.rating}</span>
                <span class="rating-stars">${formationData.ratingStars || FormationUtils.generateStars(parseFloat(formationData.rating))}</span>
                <span class="rating-count">${formationData.ratingCount}</span>
            </div>
        `;
    }

    // HTML de la formation ajoutée
    cartFormationElement.innerHTML = `
        <div class="formation-image-container">
            <img class="formation-image" src="${formationData.image}" alt="${formationData.title}">
        </div>
        <div class="formation-info">
            <h3 class="formation-title">${formationData.title}</h3>
            <p class="formation-instructor">${formationData.instructor}</p>
            ${ratingHTML}
            ${priceHTML}
            <div class="badge-container">
                ${formationData.isBestseller ? '<span class="badge-bestseller">Meilleure vente</span>' : ''}
            </div>
        </div>
    `;

    // Ajouter un séparateur avant la nouvelle formation (sauf pour la première)
    const container = document.getElementById('cart-added-formations');
    if (container.children.length > 0) {
        const divider = document.createElement('div');
        divider.className = 'formation-divider';
        divider.style.height = '1px';
        divider.style.backgroundColor = '#e0e0e0';
        divider.style.margin = '20px 0';
        container.appendChild(divider);
    }

    // Ajouter au DOM
    container.appendChild(cartFormationElement);

    // Supprimer cette formation de la section "Vous pouvez acheter" si elle y est présente
    const relatedFormationCard = document.querySelector(`.related-formation-card[data-id="${formationData.id}"]`);
    if (relatedFormationCard) {
        relatedFormationCard.remove();

        // Vérifier s'il reste des formations dans la section
        const remainingCards = document.querySelectorAll('.related-formation-card');
        if (remainingCards.length === 0) {
            // Masquer la section "Vous pouvez acheter" s'il n'y a plus de formations
            hideRelatedFormationsSection();
        }
    }

    // Debug pour confirmer l'ajout
    console.log("Formation ajoutée à l'affichage:", formationData.id, formationData.title);
}

/**
 * Masque la section des formations liées
 */
function hideRelatedFormationsSection() {
    const relatedSection = document.querySelector('.related-formations');
    if (relatedSection) {
        relatedSection.style.display = 'none';
    }
}

/**
 * Charge les formations liées à une formation
 * @param {string} category - Catégorie de la formation
 * @param {string|number} currentFormationId - ID de la formation actuelle
 */
function loadRelatedFormations(category, currentFormationId) {
    console.log("Chargement des formations de la catégorie:", category);

    // Si pas de catégorie, masquer la section et sortir
    if (!category || category.trim() === '') {
        const relatedSection = document.querySelector('.related-formations');
        if (relatedSection) {
            relatedSection.style.display = 'none';
        }
        return;
    }

    console.log("Formations actuellement dans le panier:", window.cartFormations.map(f => f.id));

    // Simuler un appel AJAX avec fetch pour obtenir les formations associées
    fetch(`/api/formations?category=${encodeURIComponent(category)}&exclude=${currentFormationId}`)
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            // En cas d'erreur ou si l'API n'existe pas, on utilise des cartes de la page
            throw new Error('API non disponible');
        })
        .then(data => {
            if (data && Array.isArray(data) && data.length > 0) {
                // Filtrer pour exclure les formations déjà dans le panier ET la formation actuelle
                const filteredData = data.filter(formation => {
                    const formationId = formation.id.toString();
                    // Exclure la formation actuelle
                    if (formationId === currentFormationId.toString()) {
                        return false;
                    }
                    // Exclure toutes les formations déjà dans le panier
                    for (let i = 0; i < window.cartFormations.length; i++) {
                        if (window.cartFormations[i].id.toString() === formationId) {
                            return false;
                        }
                    }
                    // S'assurer que la formation appartient à la même catégorie
                    return formation.category === category;
                });

                console.log("Formations filtrées:", filteredData.length);

                if (filteredData.length > 0) {
                    // Sélectionner aléatoirement 2 formations
                    const shuffled = filteredData.sort(() => 0.5 - Math.random());
                    const selected = shuffled.slice(0, 2);
                    displayRelatedFormations(selected);
                } else {
                    // Pas de formations à afficher, masquer la section
                    hideRelatedFormationsSection();
                }
            } else {
                // Pas de formations trouvées via l'API, chercher sur la page
                const foundFormations = getFormationsFromPage(currentFormationId, 2, category);
                if (!foundFormations) {
                    hideRelatedFormationsSection();
                }
            }
        })
        .catch(error => {
            console.warn("Erreur lors du chargement des formations associées:", error);
            // En cas d'erreur, chercher sur la page
            const foundFormations = getFormationsFromPage(currentFormationId, 2, category);
            if (!foundFormations) {
                hideRelatedFormationsSection();
            }
        });
}

/**
 * Recherche des formations sur la page actuelle
 * @param {string|number} currentFormationId - ID de la formation actuelle
 * @param {number} count - Nombre de formations à récupérer
 * @param {string} category - Catégorie des formations
 * @returns {boolean} - True si des formations ont été trouvées, false sinon
 */
function getFormationsFromPage(currentFormationId, count, category) {
    const allCards = document.querySelectorAll('.formation-card');
    console.log("Cartes trouvées sur la page:", allCards.length);
    console.log("Formation actuelle ID:", currentFormationId);
    console.log("Catégorie recherchée:", category);

    // Filtrer pour exclure la formation actuelle, celles déjà dans le panier, et inclure seulement celles de la même catégorie
    const availableCards = Array.from(allCards).filter(card => {
        const cardId = (card.getAttribute('data-id') || '0').toString();

        // Exclure la formation actuelle
        if (cardId === currentFormationId.toString()) {
            return false;
        }

        // Exclure les formations déjà dans le panier
        for (let i = 0; i < window.cartFormations.length; i++) {
            if (window.cartFormations[i].id.toString() === cardId) {
                return false;
            }
        }

        // Vérifier la catégorie de la carte (si elle est spécifiée)
        if (category && category.trim() !== '') {
            const cardCategory = card.getAttribute('data-category') || '';
            // Ne garder que les cartes de la même catégorie
            if (cardCategory.trim() !== category.trim()) {
                return false;
            }
        }

        return true;
    });

    console.log("Cartes disponibles après filtrage:", availableCards.length);

    if (availableCards.length > 0) {
        // Mélanger et prendre les 'count' premières cartes (ou moins si pas assez)
        const shuffled = availableCards.sort(() => 0.5 - Math.random());
        const numToSelect = Math.min(count, shuffled.length);
        const selected = shuffled.slice(0, numToSelect);

        // Extraire les données des cartes sélectionnées
        const relatedFormations = selected.map(card => FormationUtils.extractFormationDataFromCard(card));
        console.log("Formations à afficher:", relatedFormations.map(f => f.id));
        displayRelatedFormations(relatedFormations);
        return true;
    } else {
        // Si aucune formation n'est trouvée, masquer la section "Vous pouvez acheter"
        hideRelatedFormationsSection();
        return false;
    }
}

/**
 * Affiche les formations associées dans la modale
 * @param {Array} relatedFormations - Liste des formations associées
 */
function displayRelatedFormations(relatedFormations) {
    const relatedContainer = document.querySelector('.related-formations-container');
    relatedContainer.innerHTML = '';

    // Récupérer les dimensions de l'image principale
    const mainImageWidth = document.querySelector('#add-to-cart-modal').getAttribute('data-main-image-width') || 200;
    const mainImageHeight = document.querySelector('#add-to-cart-modal').getAttribute('data-main-image-height') || 120;

    relatedFormations.forEach(formation => {
        // Préparer l'affichage du prix avec remise si applicable
        let priceHTML = '';
        if (formation.hasDiscount) {
            priceHTML = `
                <div class="related-formation-price">
                    <span class="final-price">${formation.price}</span>
                    <span class="original-price" style="text-decoration: line-through; color: #6a6f73; font-size: 0.9em;">${formation.originalPrice}</span>
                    <span class="discount-percentage" style="color: #a10000; font-weight: bold;">${formation.discountPercentage}</span>
                </div>
            `;
        } else {
            priceHTML = `<div class="related-formation-price">${formation.price}</div>`;
        }

        const cardHTML = `
            <div class="related-formation-card" data-id="${formation.id}" data-category="${formation.category}">
                ${formation.isBestseller ? '<span class="badge-bestseller-small">Meilleure vente</span>' : ''}
                <img class="related-formation-image" src="${formation.image}" alt="${formation.title}"
                     style="width: ${mainImageWidth}px; height: ${mainImageHeight}px; object-fit: cover;">
                <div class="related-formation-info">
                    <h4 class="related-formation-title">${formation.title}</h4>
                    <p class="related-formation-instructor">${formation.instructor}</p>
                    <div class="related-formation-rating">
                        <span class="rating-value">${formation.rating}</span>
                        <span class="rating-stars">${FormationUtils.generateStars(formation.rating)}</span>
                        <span class="rating-count">(${formation.ratingCount})</span>
                    </div>
                    ${priceHTML}
                    <button class="add-related-btn" data-id="${formation.id}">+</button>
                </div>
            </div>
        `;

        relatedContainer.insertAdjacentHTML('beforeend', cardHTML);
    });
}

/**
 * Affiche un toast de confirmation
 * @param {string} message - Message à afficher
 */
function showConfirmationToast(message) {
    showToast(message, 3000);
}

// Exporter les fonctions pour une utilisation globale
window.CartUI = {
    populateModal,
    addFormationToCartDisplay,
    loadRelatedFormations,
    showToast,
    showConfirmationToast
};


// Initialisation de l'interface utilisateur

// Création de la modale d'ajout au panier
// Création du toast de confirmation
// Configuration des écouteurs d'événements


// Gestion des modales

// Affichage d'une modale quand un utilisateur ajoute une formation au panier
// Possibilité de fermer la modale
// Affichage des détails de la formation ajoutée


// Notifications toast

// Affichage de notifications temporaires lors de l'ajout d'une formation
// Animation avec une timeline qui diminue progressivement


// Gestion des formations liées

// Affichage de formations recommandées dans la modale
// Possibilité d'ajouter directement ces formations au panier


// Mise à jour de l'affichage du panier

// Ajout visuel des formations dans l'interface du panier
// Gestion des formations déjà présentes dans le panier