
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

    // Fonction pour créer la carte de formation avec ses détails
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
            priceHtml = `&nbsp;`;
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
        
        // Attributs data pour filtrage côté client
        const dataAttributes = `
            data-category-id="${formation.category_id}"
            data-status="${formation.status}"
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
                                                <div class="product-price">
                                                    ${formation.type === 'payante' ? priceHtml : '&nbsp;'}
                                                </div>
                                                <div class="product-view">
                                                    <p class="mb-0">${formation.description || ''}</p>
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
                            <div>
                                <a href="/admin/formation/${formation.id}"> 
                                    <h4 class="text-truncate" title="${formation.title}">${formation.title}</h4>
                                </a>
                                <p>Par ${userName} ${userLastname}</p>
                                <div class="mb-2">
                                    <span class="badge badge-light-info">${coursCount} cours</span>
                                    <span class="badge badge-light-secondary">${formation.total_seats} places</span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <div class="product-price mb-2">
                                    ${priceHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Exporter les fonctions pour pouvoir les utiliser dans d'autres fichiers
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = {
            showFormationDetails,
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


