// Ajouter au chargement du document
document.addEventListener('DOMContentLoaded', function() {
    // Masquer les boutons "Voir plus" pour les descriptions courtes
    document.querySelectorAll('.description-text').forEach(desc => {
        const formationId = desc.id.split('-').pop();
        const button = document.getElementById(`show-more-${formationId}`);
        
        // Si la hauteur de contenu est inférieure à la hauteur max visible
        if (desc.scrollHeight <= 120) { // 120px est la valeur définie dans le CSS
            if (button) button.style.display = 'none';
        } else {
            console.log(`Description ${formationId} nécessite un bouton Voir plus (${desc.scrollHeight}px)`);
        }
    });
});
// Ajoutez ce code au début de votre fichier JS
(function() {
    // Créer et ajouter le style immédiatement
    const style = document.createElement('style');
    style.id = 'description-truncate-styles';
    style.innerHTML = `
        .description-container {
            position: relative;
            max-width: 100%;
        }
        .description-text {
            display: -webkit-box;
            -webkit-line-clamp: 5; /* Limite à 5 lignes */
            -webkit-box-orient: vertical;
            overflow: hidden !important; /* Force l'overflow hidden */
            max-height: 120px !important; /* Force la hauteur max */
            transition: all 0.3s ease;
            margin-bottom: 5px;
            line-height: 1.5;
        }
        .description-text.expanded {
            -webkit-line-clamp: unset !important;
            max-height: 2000px !important; /* Une valeur très grande */
        }
        .show-more-btn {
            display: inline-block;
            margin-top: 5px;
            font-size: 0.85rem;
            padding: 0;
            text-decoration: underline;
            color: #007bff;
            cursor: pointer;
        }
    `;
    
    // Ajouter le style au début du document
    if (document.head) {
        document.head.appendChild(style);
    } else {
        // Si head n'est pas encore disponible, attendre un peu
        window.addEventListener('DOMContentLoaded', () => {
            document.head.appendChild(style);
        });
    }
})();
    // Fonction pour afficher les détails d'une formation dans un modal
    function showFormationDetails(formationId) {
        // Rechercher le modal correspondant à la formation
        const modal = $(`#formation-modal-${formationId}`);
        
        // Afficher le modal
        if (modal.length) {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } else {
            console.error(`Modal pour la formation #${formationId} non trouvé`);
        }
    }

    // Handler pour le clic sur l'icône "œil"
    $(document).on('click', '.product-hover a[data-bs-toggle="modal"]', function(e) {
        e.preventDefault();
        const modalId = $(this).attr('data-bs-target');
        const formationId = modalId.split('-').pop();
        showFormationDetails(formationId);
    });

  // À ajouter juste après la définition des styles
function initializeDescriptions() {
    document.querySelectorAll('.description-text').forEach(desc => {
        // Forcer la réinitialisation (s'assurer que la description est tronquée au début)
        desc.classList.remove('expanded');
        
        // Vérifier si le bouton "Voir plus" est nécessaire
        const formationId = desc.id.split('-').pop();
        const button = document.getElementById(`show-more-${formationId}`);
        
        if (button) {
            // Pour les descriptions longues qui nécessitent le bouton
            if (desc.scrollHeight > 120 || desc.textContent.length > 300) {
                button.style.display = 'inline-block';
                button.textContent = 'Voir plus';
            } else {
                // Pour les descriptions courtes, cacher le bouton
                button.style.display = 'none';
            }
        }
    });
}

// Exécuter cette fonction une fois que le DOM est chargé
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDescriptions);
} else {
    // Si le DOM est déjà chargé, exécuter immédiatement
    initializeDescriptions();
}

// Exécuter à nouveau après un court délai pour gérer les descriptions chargées dynamiquement
setTimeout(initializeDescriptions, 500);
    function createFormationCard(formation) {
        // Format des prix
        let priceHtml = '';
        if (formation.type === 'payante') {
            if (formation.discount > 0) {
                priceHtml = `
                    ${parseFloat(formation.final_price).toFixed(2)} Dt
                    <del>${parseFloat(formation.price).toFixed(2)} Dt</del>
                `;
            } else {
                priceHtml = `${parseFloat(formation.price).toFixed(2)} Dt`;
            }
        } else {
            priceHtml = `Gratuit`;  // Changé de &nbsp; à "Gratuit" pour meilleure visibilité
        }
        
        // Formatage des dates
        const startDate = new Date(formation.start_date);
        const endDate = new Date(formation.end_date);
        const formattedStartDate = `${startDate.getDate().toString().padStart(2, '0')}/${(startDate.getMonth()+1).toString().padStart(2, '0')}/${startDate.getFullYear()}`;
        const formattedEndDate = `${endDate.getDate().toString().padStart(2, '0')}/${(endDate.getMonth()+1).toString().padStart(2, '0')}/${endDate.getFullYear()}`;
        
        // S'assurer que les propriétés existent
        const coursCount = formation.cours_count || (formation.courses ? formation.courses.length : 0);
        const userName = formation.user ? formation.user.name : '';
        const userLastname = formation.user ? formation.user.lastname : '';
        
        // Informations sur les feedbacks
        const totalFeedbacks = formation.total_feedbacks || 0;
        const averageRating = formation.average_rating ? parseFloat(formation.average_rating).toFixed(1) : '0.0';
        const sumRatings = formation.sum_ratings || 0;
        
        // Préparation de l'affichage des étoiles de notation - CORRIGÉ SELON LES NOUVELLES RÈGLES
        let ratingStarsHtml = '';

        if (totalFeedbacks > 0) {
            // Afficher d'abord la note moyenne
            ratingStarsHtml = `<span class="rating-value">${averageRating}</span> `;
            
            // Calculer le nombre d'étoiles pleines, demi-étoiles et vides
            const ratingValue = parseFloat(averageRating);
            const fullStars = Math.floor(ratingValue);
            const decimal = parseFloat((ratingValue - fullStars).toFixed(1)); // partie décimale arrondie à 1 chiffre après la virgule
            
            // Nouvelle logique selon les règles spécifiées:
            let hasHalfStar = false;
            let additionalFullStar = false;
            
            if (decimal > 0.2 && decimal < 0.8) {
                hasHalfStar = true;
            } else if (decimal >= 0.8) {
                additionalFullStar = true;
            }
            
            const totalFilledStars = fullStars + (additionalFullStar ? 1 : 0);
            const emptyStars = 5 - totalFilledStars - (hasHalfStar ? 1 : 0);
            
            // Générer les étoiles pleines
            for (let i = 0; i < fullStars; i++) {
                ratingStarsHtml += '<i class="fa fa-star text-warning"></i>';
            }
            
            // Ajouter une étoile supplémentaire si nécessaire (pour 0.8+)
            if (additionalFullStar) {
                ratingStarsHtml += '<i class="fa fa-star text-warning"></i>';
            }
            
            // Ajouter une demi-étoile si nécessaire (pour 0.3-0.7)
            if (hasHalfStar) {
                ratingStarsHtml += '<i class="fa fa-star-half-alt text-warning"></i>';
            }
            
            // Ajouter les étoiles vides
            for (let i = 0; i < emptyStars; i++) {
                ratingStarsHtml += '<i class="far fa-star text-muted"></i>';
            }
            
            // Ajouter le nombre d'avis entre parenthèses
            ratingStarsHtml += ` <span class="ms-1 text-muted">(${totalFeedbacks})</span>`;
            
            // Pour le débogage - afficher dans la console uniquement à l'intérieur de la fonction
            console.log(`Note: ${averageRating}, Étoiles pleines: ${totalFilledStars}, Demi-étoile: ${hasHalfStar ? 'Oui' : 'Non'}, Étoiles vides: ${emptyStars}`);
        } else {
            // Toujours afficher 5 étoiles vides même s'il n'y a pas d'avis
            ratingStarsHtml = '<span class="rating-value">0.0</span> ';
            for (let i = 0; i < 5; i++) {
                ratingStarsHtml += '<i class="far fa-star text-muted"></i>';
            }
            ratingStarsHtml += ' <span class="ms-1 text-muted">(0)</span>';
        }
        
        // Badge pour les feedbacks
        const feedbackBadgeHtml = `
            <span class="badge ${totalFeedbacks > 0 ? 'bg-success' : 'bg-secondary'} ms-2">
                ${totalFeedbacks} avis
            </span>
        `;
        
        // Attributs data pour filtrage côté client
        const dataAttributes = `
            data-category-id="${formation.category_id}"
            data-status="${formation.status}"
            data-id="${formation.id}"
            data-description="${(formation.description || '').replace(/"/g, '&quot;')}"
        `;
    
        
        return `
            <div class="col-xl-3 col-sm-6 xl-4 formation-item" ${dataAttributes}>
                <div class="card h-100">
                    <div class="product-box d-flex flex-column h-100">
                        <div class="product-img" style="height: 200px; overflow: hidden; position: relative;">
                            ${formation.type === 'gratuite' ? '<div class="ribbon ribbon-danger">Gratuite</div>' : ''}
                            ${formation.discount > 0 ? `<div class="ribbon ribbon-success ribbon-right">${formation.discount}%</div>` : ''}
                            <img class="img-fluid" src="${window.location.origin}/storage/${formation.image}" alt="${formation.title}" style="width: 100%; height: 100%; object-fit: cover;" />
                            <div class="product-hover">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#formation-modal-${formation.id}">
                                            <i class="icon-eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/panier"><i class="icon-shopping-cart"></i></a>
                                    </li>
                                    <li>
                                        <a href="/formation/${formation.id}/edit"><i class="icon-pencil"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="delete-formation" data-id="${formation.id}">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal fade" id="formation-modal-${formation.id}">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">${formation.title}</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="product-box row">
                                            <div class="product-img col-lg-6" style="height: 300px; overflow: hidden;">
                                                <img class="img-fluid" src="${window.location.origin}/storage/${formation.image}" alt="${formation.title}" style="width: 100%; height: 100%; object-fit: cover;" />
                                            </div>
                                            <div class="product-details col-lg-6 text-start">
                                                <a href="/admin/formation/${formation.id}"> 
                                                    <h4>${formation.title}</h4>
                                                </a>
                                                <div class="rating-wrapper mb-2">
                                                    ${ratingStarsHtml}
                                                </div>
                                                <div class="product-price">
                                                    ${formation.type === 'payante' ? priceHtml : 'Gratuit'}
                                                </div>
                                                <div class="product-view">
                                                <div class="description-container">
                                                    <p class="mb-0 description-text" id="description-${formation.id}">${formation.description || ''}</p>
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-link text-primary show-more-btn" id="show-more-${formation.id}" onclick="toggleDescription(${formation.id})">Voir plus</a>
                                                </div>                                                    
                                                       <div class="mt-3">
                                                        <p><strong>Places:</strong> ${formation.total_seats}</p>
                                                        <p><strong>Durée:</strong> ${formation.duration || '0 heures'}</p>
                                                        <p><strong>Date début:</strong> ${formattedStartDate}</p>
                                                        <p><strong>Date fin:</strong> ${formattedEndDate}</p>
                                                        <p><strong>Nombre de cours:</strong> ${coursCount}</p>
                                                    </div>
                                                </div>
                                                <div class="addcart-btn">
                                                    <a class="btn btn-primary" href="/panier">Ajouter au panier</a>
                                                    <a class="btn btn-primary" href="/admin/formation/${formation.id}">Voir détails</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-details flex-grow-1 d-flex flex-column">
                            <div class="card-content">
                                <a href="/admin/formation/${formation.id}"> 
                                    <h4 class="formation-title" title="${formation.title}">${formation.title}</h4>                     
                                </a>
                                <p>Par ${userName} ${userLastname}</p>
                                <div class="rating-wrapper mb-2">
                                    ${ratingStarsHtml}
                                </div>
                                <div class="mb-2">
                                    <span class="badge badge-light-info">${coursCount} cours</span>
                                    <span class="badge badge-light-secondary">${formation.total_seats} places</span>
                                    <span class="badge badge-light-warning">${totalFeedbacks} avis</span>
                                </div>
                            </div>
                            <div class="mt-auto product-price-container">
                                <div class="product-price">
                                    ${priceHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Ajoutez ce style pour améliorer l'affichage des étoiles
    $(document).ready(function() {
        // Ajouter du CSS pour les étoiles et les cartes
        const ratingStyle = `
            <style>
.formation-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;  /* Ajoute des points de suspension */
    line-height: 1.2;         /* Réduit légèrement la hauteur de ligne */
    max-height: 2.4em;        /* Limite stricte à 2 lignes */
    height: auto;             /* Remplace la hauteur fixe */
    word-break: break-word;
    margin-bottom: 8px;
}
                .rating-wrapper {
                    display: flex;
                    align-items: center;
                }
                .fa-star, .fa-star-half-alt, .far.fa-star {
                    font-size: 14px;
                    margin-right: 1px;
                }
                .text-warning {
                    color: #ffc107 !important;
                }
                .text-muted {
                    color: #6c757d !important;
                }
                .product-details {
                    padding: 12px !important;
                    margin-bottom: 0 !important;
                    display: flex;
                    flex-direction: column;
                }
                .card .product-box {
                    padding-bottom: 0 !important;
                }
                .product-price-container {
                    margin-top: auto;
                    padding-top: 10px;
                }
                .product-price {
                    font-weight: bold;
                    height: 24px; /* Hauteur fixe pour le conteneur de prix */
                    display: flex;
                    align-items: center;
                }
                .card {
                    width: 100%;
                    margin-right: 0;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                }
                .formation-item {
                    padding-right: 10px;
                    padding-left: 10px;
                }
                .card-content {
                    flex: 1;
                }
            </style>
        `;
        $('head').append(ratingStyle);
        
        // Assurez-vous que Font Awesome est chargé
        if (typeof feather !== 'undefined' && $('.fa-star').length === 0) {
            // Si vous utilisez Feather icons au lieu de Font Awesome, ajoutez cette fonction
            console.log('Utilisation de Feather icons pour les étoiles');
            $('.rating-wrapper').each(function(){
                const ratingText = $(this).text();
                $(this).html(ratingText.replace(/fa-star/g, 'feather-star'));
            });
        }
        
        // S'assurer que Font Awesome est chargé pour les étoiles vides
        if ($('link[href*="font-awesome"]').length === 0) {
            $('head').append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">');
        }
    });
    
    // Exporter les fonctions pour pouvoir les utiliser dans d'autres fichiers
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = {
            createFormationCard
        };
    }
    // Ajoutez cette fonction à votre code JavaScript existant
function updateButtonLayout() {
    // Sélectionner tous les conteneurs de boutons
    document.querySelectorAll('.addcart-btn').forEach(function(buttonContainer) {
        // Ajouter une classe pour le style flexbox
        buttonContainer.classList.add('flex-container');
        
        // Si le conteneur n'a pas déjà le style appliqué
        if (!buttonContainer.hasAttribute('data-styled')) {
            buttonContainer.setAttribute('data-styled', 'true');
            
            // Appliquer le style directement
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '10px';
            buttonContainer.style.width = '100%';
            
            // Trouver tous les boutons à l'intérieur
            const buttons = buttonContainer.querySelectorAll('.btn');
            buttons.forEach(function(btn) {
                btn.style.flex = '1';
                btn.style.whiteSpace = 'nowrap';
            });
        }
    });
}

// Modifier la fonction updateAddToCartButton pour appliquer le layout horizontal
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
    
    // Appliquer le layout horizontal aux boutons
    updateButtonLayout();
}


function toggleDescription(formationId) {
    console.log("Toggling description for formation", formationId);
    
    const descriptionElement = document.getElementById(`description-${formationId}`);
    const buttonElement = document.getElementById(`show-more-${formationId}`);
    
    if (descriptionElement && buttonElement) {
        // Force le recalcul des styles (important pour certains navigateurs)
        void descriptionElement.offsetWidth;
        
        if (descriptionElement.classList.contains('expanded')) {
            // Réduire la description
            descriptionElement.classList.remove('expanded');
            buttonElement.textContent = 'Voir plus';
            console.log("Description réduite");
        } else {
            // Étendre la description
            descriptionElement.classList.add('expanded');
            buttonElement.textContent = 'Voir moins';
            console.log("Description étendue");
        }
    } else {
        console.error("Éléments non trouvés:", 
                     descriptionElement ? "Button missing" : "Description missing", 
                     "pour formation", formationId);
    }
}

// Ajouter les styles pour la description tronquée et complète
document.addEventListener('DOMContentLoaded', function() {
    const descriptionStyle = document.createElement('style');
descriptionStyle.textContent = `
    .description-container {
        position: relative;
        max-width: 100%;
    }
    .description-text {
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 120px; /* Hauteur explicite pour 5 lignes environ */
        transition: all 0.3s ease;
        margin-bottom: 5px;
        line-height: 1.5;
    }
    .description-text.expanded {
        -webkit-line-clamp: unset;
        max-height: 2000px; /* Une valeur très grande */
    }
    .show-more-btn {
        display: inline-block;
        margin-top: 5px;
        font-size: 0.85rem;
        padding: 0;
        text-decoration: underline;
        color: #007bff;
        cursor: pointer;
    }
`;
document.head.appendChild(descriptionStyle);
});

// Modifier les modals existants au chargement de la page
$(document).ready(function() {
    // Rechercher tous les modals existants
    document.querySelectorAll('.modal .product-view').forEach(function(productView) {
        const modal = productView.closest('.modal');
        if (!modal) return;
        
        const formationId = modal.id.split('-').pop();
        const descriptionP = productView.querySelector('p.mb-0');
        
        if (descriptionP && !productView.querySelector('.description-container')) {
            // Créer la structure HTML pour la description
            const descriptionText = descriptionP.textContent || '';
            const descriptionContainer = document.createElement('div');
            descriptionContainer.className = 'description-container';
            
            const newDescription = document.createElement('p');
            newDescription.className = 'mb-0 description-text';
            newDescription.id = `description-${formationId}`;
            newDescription.textContent = descriptionText;
            
            const showMoreBtn = document.createElement('a');
            showMoreBtn.href = 'javascript:void(0)';
            showMoreBtn.className = 'btn btn-sm btn-link text-primary show-more-btn';
            showMoreBtn.id = `show-more-${formationId}`;
            showMoreBtn.textContent = 'Voir plus';
            showMoreBtn.onclick = function() { toggleDescription(formationId); };
            
            descriptionContainer.appendChild(newDescription);
            descriptionContainer.appendChild(showMoreBtn);
            
            // Remplacer l'ancien paragraphe par le nouveau conteneur
            descriptionP.parentNode.replaceChild(descriptionContainer, descriptionP);
        }
    });
});
// Ajouter ce style au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter un style global pour les conteneurs de boutons
    const style = document.createElement('style');
    style.textContent = `
        .addcart-btn {
            display: flex !important;
            gap: 10px !important;
            width: 100% !important;
        }
        .addcart-btn .btn {
            flex: 1 !important;
            white-space: nowrap !important;
        }
    `;
    document.head.appendChild(style);
    
    // Appliquer le layout horizontal aux boutons existants
    setTimeout(updateButtonLayout, 500);
});


