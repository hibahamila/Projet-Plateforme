
// Ce fichier gère l'affichage d'un panneau de détail qui apparaît lorsqu'un utilisateur survole une carte de formation


// formationCards.js - Gestion du panneau de détail des formations

$(document).ready(function() {
    // S'assurer que les utilitaires sont disponibles
    if (!window.FormationUtils) {
        console.error("Module d'utilitaires non chargé. Veuillez inclure utils.js avant formationCards.js");
        return;
    }
    
    // Initialisation du panneau de détail
    if ($('#formation-detail-panel').length === 0) {
        $('body').append('<div id="formation-detail-panel"></div>');
    }
    
    // Variables de contrôle pour le panneau
    let timeoutId;
    let isOverCard = false;
    let isOverPanel = false;
    
    /**
     * Affiche le panneau de détail pour une carte de formation
     * @param {jQuery} $card - La carte de formation survolée
     */


function showDetailPanel($card) {
    const formationData = FormationUtils.extractFormationDataFromCard($card[0]);
    const cardPosition = $card.offset();
    const cardWidth = $card.width();
    const windowWidth = $(window).width();
    const windowHeight = $(window).height();
    
    // Détermine si le panneau doit s'afficher à gauche ou à droite
    let panelPosition = 'right';
    if (cardPosition.left + cardWidth + 400 > windowWidth) {
        panelPosition = 'left';
    }
    
    // Prépare les métadonnées (durée, nombre de cours)
    let metaInfoHTML = '';
    const isDurationValid = formationData.duration !== "00:00:00" && 
                          formationData.duration !== "00:00" && 
                          formationData.duration !== "0:00" && 
                          formationData.duration !== "00:0"&&
                          formationData.duration !== "0:00"  ;


    
    if (isDurationValid && formationData.coursesCount > 0) {
        metaInfoHTML = `<div class="formation-meta-info">
            ${FormationUtils.formatDuration(formationData.duration)} au total • ${formationData.coursesCount} cours
        </div>`;
    } else if (isDurationValid) {
        metaInfoHTML = `<div class="formation-meta-info">
            ${FormationUtils.formatDuration(formationData.duration)} 
        </div>`;
    } else if (formationData.coursesCount > 0) {
        metaInfoHTML = `<div class="formation-meta-info">
            ${formationData.coursesCount} cours
        </div>`;
    }
    
    // Prépare les caractéristiques si disponibles
    let featuresHTML = '';
    if (formationData.features.length) {
        featuresHTML = '<ul class="features">';
        formationData.features.forEach(feature => {
            featuresHTML += `<li><b style="color: blue;">✓</b> ${feature}</li>`;
        });
        featuresHTML += '</ul>';
    }
    
    // Création du contenu du panneau avec un bouton de chargement
    let buttonHTML = '<button class="btn-loading"><i class="fas fa-spinner fa-spin"></i></button>';
    let panelHTML = `
        <div class="panel-content">
            <h3>${formationData.title}</h3>
            ${metaInfoHTML}
            <div class="description rich-content" style="margin-bottom: 15px;">${formationData.description}</div>
            ${featuresHTML}
            <div style="margin-top: 15px;"></div>
            ${buttonHTML}
        </div>
    `;
    
    // Configuration du panneau
    const $panel = $('#formation-detail-panel');
    $panel.html(panelHTML);
    $panel.css('opacity', 0).addClass('active');
    
    // Calcul de la position du panneau
    const panelHeight = $panel.outerHeight();
    const panelBottom = cardPosition.top - 55 + panelHeight;
    let fixedTopOffset = -55;
    
    // Ajustement si le panneau dépasse la fenêtre
    if (panelBottom > windowHeight + $(window).scrollTop()) {
        fixedTopOffset = windowHeight + $(window).scrollTop() - panelHeight - cardPosition.top - 20;
    }
    
    // Positionnement final du panneau
    if (panelPosition === 'right') {
        $panel.css({
            'left': cardPosition.left + cardWidth + 10,
            'top': cardPosition.top + fixedTopOffset, 
            'opacity': 1
        });
    } else {
        $panel.css({
            'left': cardPosition.left - 410,
            'top': cardPosition.top + fixedTopOffset, 
            'opacity': 1
        });
    }
    
    // Marquer comme survolé et effacer tout timeout existant
    isOverCard = true;
    clearTimeout(timeoutId);
    
    // Vérifier si la formation est dans le panier pour mettre à jour le bouton
    // D'abord vérifier l'état local des boutons sur la carte correspondante
    const cardButton = $(`.formation-card[data-id="${formationData.id}"] .btn-add-to-cart, 
                         .formation-card-container[data-id="${formationData.id}"] .btn-add-to-cart`);
    const isInCartLocally = cardButton.length > 0 && cardButton.hasClass('in-cart');
    
    if (isInCartLocally) {
        // Si l'état local indique déjà dans le panier, mettre à jour immédiatement
        $panel.find('.btn-loading').replaceWith(
            '<a href="/panier" class="btn-view-cart"><i class="fas fa-shopping-cart"></i> Accéder au panier</a>'
        );
    } else {
        // Vérifier si la formation est dans le tableau des formations du panier en mémoire
        const currentCartItems = window.cartFormations || [];
        const isInLocalArray = currentCartItems.some(item => item.id === formationData.id);
        
        if (isInLocalArray) {
            // Si trouvé dans notre tableau local, afficher le bouton "Accéder au panier"
            $panel.find('.btn-loading').replaceWith(
                '<a href="/panier" class="btn-view-cart"><i class="fas fa-shopping-cart"></i> Accéder au panier</a>'
            );
        } else {
            // Vérifier avec le serveur si la formation est dans le panier
            FormationUtils.checkFormationInCart(formationData.id, function(inCart) {
                if (inCart) {
                    $panel.find('.btn-loading').replaceWith(
                        '<a href="/panier" class="btn-view-cart"><i class="fas fa-shopping-cart"></i> Accéder au panier</a>'
                    );
                } else {
                    const addButton = $(`<button class="btn-add-to-cart purple-btn" data-formation-id="${formationData.id}"><i class="fas fa-cart-plus"></i> Ajouter au panier</button>`);
                    
                    // Gestion du clic avec mise à jour immédiate
                    addButton.on('click', function(e) {
                        e.preventDefault();
                        const $this = $(this);
                        
                        // Afficher un indicateur de chargement
                        $this.html('<i class="fas fa-spinner fa-spin"></i>');
                        $this.prop('disabled', true);
                        
                        // Appel à la fonction addToCart avec callback
                        FormationUtils.addToCart(formationData.id, function(success) {
                            if (success) {
                                // Mettre à jour immédiatement le bouton
                                $this.replaceWith(
                                    '<a href="/panier" class="btn-view-cart"><i class="fas fa-shopping-cart"></i> Accéder au panier</a>'
                                );
                                
                                // Mettre à jour également le bouton sur la carte
                                FormationUtils.updateButtonState(formationData.id, true);
                            } else {
                                // En cas d'erreur, rétablir le bouton
                                $this.html('<i class="fas fa-cart-plus"></i> Ajouter au panier');
                                $this.prop('disabled', false);
                            }
                        });
                    });
                    
                    $panel.find('.btn-loading').replaceWith(addButton);
                }
            });
        }
    }
}
   
    
    // Gestion du survol des cartes de formation
    $(document).on('mouseenter', '.formation-card', function(e) {
        // Ne pas afficher le panneau si on survole un élément devant être ignoré
        if ($(e.target).closest('.prevent-detail-panel, .action-menu, .action-dots, .action-dropdown').length) {
            return;
        }
        
        showDetailPanel($(this));
    });
    
    // Gestion de la sortie du survol des cartes
    $(document).on('mouseleave', '.formation-card', function(e) {
        if ($(e.toElement || e.relatedTarget).closest('.prevent-detail-panel, .action-menu, .action-dots, .action-dropdown').length) {
            return;
        }
        
        isOverCard = false;
        timeoutId = setTimeout(function() {
            if (!isOverPanel) {
                $('#formation-detail-panel').removeClass('active').css('opacity', 0);
            }
        }, 300);
    });
    
    // Gestion du survol du panneau de détail
    $(document).on('mouseenter', '#formation-detail-panel', function() {
        isOverPanel = true;
        clearTimeout(timeoutId);
    });
    
    // Gestion de la sortie du panneau de détail
    $(document).on('mouseleave', '#formation-detail-panel', function() {
        isOverPanel = false;
        timeoutId = setTimeout(function() {
            if (!isOverCard) {
                $('#formation-detail-panel').removeClass('active').css('opacity', 0);
            }
        }, 300);
    });
    
    // Empêcher l'affichage du panneau pour certains éléments
    $(document).on('mouseenter click', '.prevent-detail-panel, .action-menu, .action-dots, .action-dropdown, .action-item', function(e) {
        e.stopPropagation();
        $('#formation-detail-panel').removeClass('active').css('opacity', 0);
        isOverCard = false;
        isOverPanel = false;
        clearTimeout(timeoutId);
    });
    
    // Gestionnaire pour le bouton "Ajouter au panier" dans le panneau de détail
    $(document).on('click', '.btn-add-to-cart', function() {
        const formationId = $(this).data('formation-id');
        FormationUtils.addFormationToCart(formationId, $(this));
    });
});


// Ce fichier gère l'affichage d'un panneau de détail qui apparaît lorsqu'un utilisateur survole une carte de formation
