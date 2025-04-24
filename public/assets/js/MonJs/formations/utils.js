// utils.js - Fonctions utilitaires partagées

// Cache global pour les statuts des formations dans le panier
window.cartStatusCache = window.cartStatusCache || {};
// Cache global pour les formations dans le panier
window.cartFormations = window.cartFormations || [];

/**
 * Vérifie si une formation est dans le panier
 * @param {string|number} formationId - ID de la formation
 * @param {Function} callback - Fonction de rappel avec le résultat
 */
function checkFormationInCart(formationId, callback) {
    // Si nous avons déjà récupéré cette information, utiliser le cache
    if (window.cartStatusCache.hasOwnProperty(formationId)) {
        callback(window.cartStatusCache[formationId]);
        return;
    }
    
    // Sinon, faire une requête AJAX
    $.ajax({
        url: `/panier/check/${formationId}`,
        type: 'GET',
        success: function(response) {
            window.cartStatusCache[formationId] = response.in_cart;
            callback(response.in_cart);
        },
        error: function() {
            callback(false);
        }
    });
}

/**
 * Ajoute une formation au panier
 * @param {string|number} formationId - ID de la formation
 * @param {jQuery} $button - Élément bouton d'ajout
 */
function addFormationToCart(formationId, $button) {
    $.ajax({
        url: '/panier/ajouter',
        type: 'POST',
        data: {
            formation_id: formationId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Mettre à jour le cache immédiatement
                window.cartStatusCache[formationId] = true;
                
                // Mettre à jour le badge du panier dans l'en-tête
                updateCartBadge(response.cartCount);
                
                // Remplacer le bouton par "Accéder au panier"
                $button.replaceWith('<a href="/panier" class="btn-view-cart"> Accéder au panier</a>');
            }
        }
    });
}

/**
 * Formate une durée de formation
 * @param {string} duration - Durée au format HH:MM:SS ou HH:MM
 * @returns {string} - Durée formatée
 */
function formatDuration(duration) {
    if (!duration || duration === '00:00:00' || duration === '00:00') {
        return '0 heures';
    }
    
    // Gestion du format HH:MM:SS
    const parts = duration.split(':');
    const hours = parseInt(parts[0]);
    const minutes = parseInt(parts.length > 1 ? parts[1] : 0);
    
    if (hours === 0 && minutes === 0) {
        return '0 heures';
    } else if (hours === 0) {
        return `${minutes} min`;
    } else if (minutes === 0) {
        return `${hours} h`;
    } else {
        return `${hours}h ${minutes}min`;
    }
}

/**
 * Génère des étoiles en fonction de la note
 * @param {number} rating - Note (de 0 à 5)
 * @returns {string} - HTML des étoiles
 */
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const decimalPart = rating - fullStars;
    const hasHalfStar = decimalPart >= 0.25;
    
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= fullStars) {
            starsHtml += '<i class="fas fa-star"></i>';
        } else if (i === fullStars + 1 && hasHalfStar) {
            starsHtml += '<i class="fas fa-star-half-alt"></i>';
        } else {
            starsHtml += '<i class="far fa-star"></i>';
        }
    }
    return starsHtml;
}

/**
 * Met à jour le badge du panier dans l'en-tête
 * @param {number} count - Nombre d'éléments dans le panier
 */
function updateCartBadge(count) {
    // Si le badge existe déjà
    if ($('.cart-badge').length) {
        if (count > 0) {
            // Mettre à jour le texte du badge
            $('.cart-badge').text(count);
        } else {
            // Si le panier est vide, supprimer le badge
            $('.cart-badge').remove();
        }
    } else if (count > 0) {
        // Si le badge n'existe pas et qu'il y a des articles dans le panier, créer le badge
        $('.cart-container').append(`<span class="cart-badge">${count}</span>`);
    }
}

/**
 * Extrait les données d'une formation à partir d'une carte
 * @param {HTMLElement} card - Élément DOM de la carte
 * @returns {Object} - Objet contenant les données de la formation
 */
function extractFormationDataFromCard(card) {
    const $card = $(card);
    
    // Extraire le titre
    const titleElement = $card.find('.formation-title');
    const title = titleElement.length ? titleElement.text().trim() : 'Formation';
    
    // Extraire l'instructeur
    const instructorElement = $card.find('.formation-instructor');
    const instructor = instructorElement.length ? instructorElement.text().trim() : 'Instructeur';
    
    // Extraire l'image
    let image = '';
    const imgElement = $card.find('img');
    if (imgElement.length && imgElement.attr('src')) {
        image = imgElement.attr('src');
    }
    
    // Extraire les informations de prix et remises
    let finalPrice = '0 DT';
    let hasDiscount = false;
    let originalPrice = '';
    let discountPercentage = '';
    
    // Vérifier si nous avons un prix final
    const finalPriceElement = $card.find('.final-price');
    if (finalPriceElement.length) {
        finalPrice = finalPriceElement.text().trim();
        
        // Vérifier s'il y a une remise
        const originalPriceElement = $card.find('.original-price');
        if (originalPriceElement.length) {
            hasDiscount = true;
            originalPrice = originalPriceElement.text().trim();
            
            // Chercher le pourcentage de remise s'il existe
            const discountElement = $card.find('.discount-percentage');
            if (discountElement.length) {
                discountPercentage = discountElement.text().trim();
            } else {
                // Calculer le pourcentage si non disponible
                try {
                    const finalPriceValue = parseFloat(finalPrice.replace(/[^\d.,]/g, '').replace(',', '.'));
                    const originalPriceValue = parseFloat(originalPrice.replace(/[^\d.,]/g, '').replace(',', '.'));
                    
                    if (originalPriceValue > 0) {
                        const discount = ((originalPriceValue - finalPriceValue) / originalPriceValue) * 100;
                        discountPercentage = `-${Math.round(discount)}%`;
                    }
                } catch (e) {
                    console.error("Erreur lors du calcul de la remise:", e);
                }
            }
        }
    }
    
    // Informations de notation
    let rating = '0';
    let ratingStars = '';
    let ratingCount = '(0)';
    
    const ratingValueElement = $card.find('.rating-value');
    if (ratingValueElement.length) {
        rating = ratingValueElement.text().trim();
        
        const starsElement = $card.find('.rating-stars');
        if (starsElement.length) {
            ratingStars = starsElement.html();
        } else {
            ratingStars = generateStars(parseFloat(rating));
        }
        
        const countElement = $card.find('.rating-count');
        if (countElement.length) {
            ratingCount = countElement.text().trim();
        }
    }
    
    const isBestseller = $card.find('.badge-bestseller').length > 0;
    
    // Essayer d'extraire l'ID de la formation
    let formationId = '0';
    
    // Méthode 1: Depuis un attribut data-id
    if ($card.attr('data-id')) {
        formationId = $card.attr('data-id');
    } 
    // Méthode 2: Depuis les boutons d'action
    else {
        const actionElements = $card.closest('.formation-card-container').find('.action-item');
        if (actionElements.length) {
            actionElements.each(function() {
                const $action = $(this);
                if ($action.attr('data-delete-url')) {
                    const url = $action.attr('data-delete-url');
                    const matches = url.match(/\/formation\/(\d+)$/);
                    if (matches && matches[1]) {
                        formationId = matches[1];
                        return false; // Sortir de la boucle each
                    }
                } else if ($action.attr('data-edit-url')) {
                    const url = $action.attr('data-edit-url');
                    const matches = url.match(/\/formation\/(\d+)\/edit$/);
                    if (matches && matches[1]) {
                        formationId = matches[1];
                        return false; // Sortir de la boucle each
                    }
                }
            });
        }
    }

    // Extraire la catégorie de la formation
    let category = '';
    // Rechercher d'abord dans les attributs data-
    if ($card.attr('data-category')) {
        category = $card.attr('data-category');
    } else {
        // Essayer de trouver un élément contenant la catégorie
        const categoryElement = $card.find('.formation-category');
        if (categoryElement.length) {
            category = categoryElement.text().trim();
        } else {
            // On peut aussi essayer de récupérer la catégorie depuis l'URL
            const currentPath = window.location.pathname;
            const categoryMatch = currentPath.match(/\/category\/([^\/]+)/);
            if (categoryMatch && categoryMatch[1]) {
                category = decodeURIComponent(categoryMatch[1]);
            }
        }
    }
    
    // Extraction de la description
    const getDescriptionContent = function() {
        const $description = $card.find('.formation-description, .description, .formation-desc');
        if ($description.length) {
            if ($description.is(':not(:has(*))')) {
                return $description.text();
            }
            return $description.html();
        }
        const textElements = $card.find('*').filter(function() {
            return $(this).text().trim().length > 0 && 
                !$(this).hasClass('formation-title') && 
                !$(this).hasClass('formation-instructor');
        });
        
        return textElements.first().html() || 'Description non disponible';
    };
    
    // Extraction des caractéristiques
    const features = [];
    $card.find('.formation-features li, .features li').each(function() {
        features.push($(this).html());
    });
    
    return {
        id: formationId,
        title: title,
        instructor: instructor,
        image: image,
        price: finalPrice,
        rating: rating,
        ratingStars: ratingStars,
        ratingCount: ratingCount,
        isBestseller: isBestseller,
        hasDiscount: hasDiscount,
        originalPrice: originalPrice,
        discountPercentage: discountPercentage,
        category: category,
        description: getDescriptionContent(),
        features: features,
        duration: $card.attr('data-duration') || "00:00",
        coursesCount: parseInt($card.attr('data-courses-count') || 0)
    };
}

// Exporter les fonctions pour une utilisation globale
window.FormationUtils = {
    checkFormationInCart,
    addFormationToCart,
    formatDuration,
    generateStars,
    updateCartBadge,
    extractFormationDataFromCard

};