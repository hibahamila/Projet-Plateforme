

//Ce fichier se concentre sur l'affichage et la manipulation des formations, la gestion des carrousels, 
// la création des cartes de formation, et les actions sur 
// les formations (modification, suppression). Il réagit aux événements déclenchés par le fichier categories.js.



// formations.js - Gestion des formations et carrousels
document.addEventListener('DOMContentLoaded', function() {
    const carouselInitialized = {
        '.formations-carousel': false,
        '.formations-carousel-published': false,
        '.formations-carousel-unpublished': false
    };

    //Initialise un carousel Slick.

    function initCarousel(carouselSelector) {
        const $carousel = $(carouselSelector);
        // Vérifier si le carousel est déjà initialisé
        if ($carousel.hasClass('slick-initialized')) {
            $carousel.slick('unslick');
        }
        // Initialiser le carousel avec Slick
        $carousel.slick({
            dots: false,
            infinite: false,
            speed: 600,
            slidesToShow: 3,
            slidesToScroll: 3,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            centerPadding: '0px',
            centerMode: false,
            rtl: false,
            initialSlide: 0,
            cssEase: 'ease-in-out',
            responsive: [
                {
                    breakpoint: 2200,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4
                    }
                },
                {
                    breakpoint: 1800,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 1400,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        
        // Marquer comme initialisé
        carouselInitialized[carouselSelector] = true;
        
        // Mettre à jour les flèches initialement
        updateCarouselArrows($carousel);
        
        // Gérer les événements de changement
        $carousel.on('afterChange', function(event, slick) {
            updateCarouselArrows($carousel);
        });
    }

    //Rôle: Met à jour l'état des flèches de navigation du carousel.
    //Fonctionnement: Active/désactive les flèches en fonction de la position actuelle dans le carousel.



    
    function updateCarouselArrows($carousel) {
        if (!$carousel.hasClass('slick-initialized')) return;
        
        const currentSlide = $carousel.slick('slickCurrentSlide');
        const slideCount = $carousel.find('.slick-slide:not(.slick-cloned)').length;
        const slidesToShow = $carousel.slick('slickGetOption', 'slidesToShow');
        // Flèche précédente
        const $prevArrow = $carousel.find('.slick-prev');
        if (currentSlide === 0) {
            $prevArrow.addClass('slick-disabled').attr('aria-disabled', 'true');
        } else {
            $prevArrow.removeClass('slick-disabled').attr('aria-disabled', 'false');
        }
        // Flèche suivante
        const $nextArrow = $carousel.find('.slick-next');
        if (currentSlide >= slideCount - slidesToShow) {
            $nextArrow.addClass('slick-disabled').attr('aria-disabled', 'true');
        } else {
            $nextArrow.removeClass('slick-disabled').attr('aria-disabled', 'false');
        }
    }
    

    //Rôle: Rafraîchit tous les carrousels initialisés.

    function updateAllCarousels() {
        $('.slick-initialized').each(function() {
            $(this).slick('resize');
            updateCarouselArrows($(this));
        });
    }

    //Met à jour le contenu d'un carousel spécifique.

// Détruit le carousel existant s'il existe
// Vide son contenu
// Ajoute les nouveaux éléments
// Réinitialise le carousel ou affiche un message si aucun élément
    
function updateSingleCarousel(selector, items) {
    const carousel = document.querySelector(selector);
    if (!carousel) return;
    
    if ($(carousel).hasClass('slick-initialized')) {
        $(carousel).slick('unslick');
    }
    
    // Vérifier d'abord s'il y a des formations avant de vider le carousel
    if (items.length > 0) {
        carousel.innerHTML = '';
        // Ajouter les nouveaux éléments
        items.forEach(item => {
            carousel.appendChild(item);
        });
        initCarousel(selector);
    } else {
        carousel.innerHTML = '<div class="no-formations">Aucune formation trouvée</div>';
    }
}

    //Rôle: Crée une carte HTML pour une formation.

    function createFormationCard(formation) {
        const duration = formation.duration || '00:00:00';
        const coursesCount = formation.cours_count || 0;
        
        // Formater la durée pour affichage
        let formattedDuration = '';
        if (duration) {
            const durationParts = duration.split(':');
            const hours = parseInt(durationParts[0]);
            const minutes = parseInt(durationParts[1]);
            
            if (hours > 0) {
                formattedDuration += `${hours}h `;
            }
            if (minutes > 0 || hours === 0) {
                formattedDuration += `${minutes}min`;
            }
        }
        
        const card = document.createElement('div');
        card.className = 'formation-card-container';
        // Utiliser le titre complet
        const fullTitle = formation.title;
        // Déterminer les classes spéciales pour la carte
        const hasRating = formation.average_rating !== null && formation.total_feedbacks > 0;
        const isFree = formation.price == 0;
        const hasPaidPrice = formation.price > 0;
        // Classes pour la carte en fonction des conditions
        const cardClasses = [
            'formation-card',
            !hasRating ? 'no-rating' : '',
            isFree ? 'has-free-badge' : '',
            !hasRating && isFree ? 'compact-card' : '',
            !hasRating && !isFree && hasPaidPrice ? 'price-bottom' : ''
        ].filter(Boolean).join(' ');
        
        let html = `
            <div class="${cardClasses}" data-duration="${duration}" 
                 data-courses-count="${coursesCount}">
                ${formation.status && formation.is_bestseller ? '<span class="badge-bestseller">Meilleure vente</span>' : ''}
                
                ${formation.image 
                    ? `<img src="${window.location.origin}/storage/${formation.image}" alt="${fullTitle}">`
                    : '<div class="placeholder-image">Image de formation</div>'
                }
                
                <div class="title-container">
                    <h4 class="formation-title ${fullTitle.length < 50 ? 'title-short' : ''}" title="${fullTitle}">${fullTitle}</h4>
                </div>
                <div class="formation-instructor ${!hasRating ? 'no-rating' : ''}">
                    ${formation.user 
                        ? `${formation.user.name} ${formation.user.lastname || ''}`
                        : 'Professeur non défini'
                    }
                </div>
                
                <div class="formation-description" style="display:none;">
                    ${formation.description || 'Aucune description disponible'}
                </div>           
                <div class="formation-rating-price ${!hasRating ? 'no-rating' : ''}">
                    <div class="formation-rating">
        `;
        if (hasRating) {
            const rating = formation.average_rating;
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
            html += `
                      <span class="rating-value">${parseFloat(rating).toFixed(1)}</span>
                        <span class="rating-stars">${starsHtml}</span>
                         <span class="rating-count">(${formation.total_feedbacks})</span>
                     `;
        }
        
        html += `
                    </div>
                    <div class="price-container ${!hasRating ? 'no-rating' : ''}">
                        ${formation.price == 0 
                            ? ``
                            : formation.discount > 0 
                                ? `<div style="display: flex; align-items: center;">
                                    <span class="original-price">${parseFloat(formation.price).toFixed(3)} DT</span>
                                    <span class="discount-badge">-${formation.discount}%</span>
                                   </div>
                                   <span class="final-price">${parseFloat(formation.final_price).toFixed(3)} DT</span>`
                                : `<span class="final-price">${parseFloat(formation.price).toFixed(3)} DT</span>`
                        }
                    </div>
                </div>
                
                <!-- Déplacer le badge gratuit ici pour qu'il soit toujours à la même position -->
                <div class="card-badges">
                    ${formation.price == 0 ? '<span class="badge-free">Gratuite</span>' : ''}
                </div>
                
                <div class="action-menu">
                    <div class="action-dots">
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                    <div class="action-dropdown">
                        <div class="action-item edit-action" data-edit-url="${window.location.origin}/formation/${formation.id}/edit">
                            Modifier        
                        </div>
                        <div class="action-item delete-action" data-delete-url="${window.location.origin}/formation/${formation.id}">
                            Supprimer
                        </div>
                    </div>
                </div>
        `;
        
        card.innerHTML = html;
    
        return card;
    }

    //Initialise les menus d'action sur les cartes (modifier/supprimer).


    function attachActionIconHandlers() {
        // Gestionnaire pour afficher/masquer le menu déroulant
        document.querySelectorAll('.action-dots').forEach(dots => {
            dots.addEventListener('click', function(e) {
                e.stopPropagation(); // Empêcher la propagation de l'événement
                
                // Fermer tous les autres menus ouverts
                document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
                    if (!dropdown.parentNode.contains(e.target)) {
                        dropdown.classList.remove('show');
                    }
                });
                
                // Basculer l'affichage du menu actuel
                const dropdown = this.nextElementSibling;
                dropdown.classList.toggle('show');
            });
        });
        
        // Gestionnaire pour les actions d'édition
        document.querySelectorAll('.edit-action').forEach(action => {
            action.addEventListener('click', function(e) {
                e.stopPropagation();
                window.location.href = this.dataset.editUrl;
            });
        });
        
        // Gestionnaire pour les actions de suppression
        document.querySelectorAll('.delete-action').forEach(action => {
            action.addEventListener('click', function(e) {
                e.stopPropagation();
                const deleteUrl = this.dataset.deleteUrl;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                // Message de confirmation
                if (confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')) {
                    // Utiliser le formulaire pour soumettre la requête DELETE
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    form.style.display = 'none';
                    
                    // Simuler la méthode DELETE pour Laravel
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    // Ajouter le token CSRF
                    const tokenField = document.createElement('input');
                    tokenField.type = 'hidden';
                    tokenField.name = '_token';
                    tokenField.value = csrfToken;
                    
                    // Ajouter les champs au formulaire et soumettre
                    form.appendChild(methodField);
                    form.appendChild(tokenField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    }

    function initActionMenus() {
        // Fermer tous les menus déroulants lorsqu'on clique en dehors
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.action-menu')) {
                document.querySelectorAll('.action-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
        
        // Réattacher les gestionnaires après chaque mise à jour du DOM
        attachActionIconHandlers();
    }

    //Rôle: Met à jour tous les carrousels de formations (tous/publiés/non publiés).
// //Trie les formations selon leur statut
// Crée les cartes pour chaque formation
// Met à jour les trois carrousels
// Réattache les gestionnaires d'événements

    function updateFormationsCarousels(formations) {
        const allFormations = [];
        const publishedFormations = [];
        const unpublishedFormations = [];
        
        formations.forEach(formation => {
            const formationCard = createFormationCard(formation);
            allFormations.push(formationCard);
            
            if (formation.status) {
                publishedFormations.push(formationCard);
            } else {
                unpublishedFormations.push(formationCard);
            }
        });
        updateSingleCarousel('.formations-carousel', allFormations);
        updateSingleCarousel('.formations-carousel-published', publishedFormations);
        updateSingleCarousel('.formations-carousel-unpublished', unpublishedFormations);
        attachActionIconHandlers();
        activateTab('#top-home-tab', '#top-home');
    }
    
//     Gère les onglets (tous/publiés/non publiés).
// Fonctionnement:

// Met à jour les classes actives des onglets
// Affiche le contenu correspondant
// Initialise le carousel nécessaire si ce n'est pas déjà fait


    function activateTab(tabSelector, contentSelector) {
        const tab = document.querySelector(tabSelector);
        if (!tab) return;
        
        // Mettre à jour l'état actif des onglets
        document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
            tab.classList.remove('active');
            tab.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');
        
        // Afficher le contenu de l'onglet
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('show', 'active');
        });
        const contentPane = document.querySelector(contentSelector);
        if (contentPane) {
            contentPane.classList.add('show', 'active');
            
            // Rafraîchir le carousel visible
            const visibleCarousel = contentPane.querySelector('.slick-initialized');
            if (visibleCarousel) {
                $(visibleCarousel).slick('refresh');
            }
        }
    }
    
    function setupTabHandlers() {
        // Gestion des onglets Bootstrap
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const target = $(e.target).attr("href");
            
            if (target === "#top-contact" && !$('.formations-carousel-published').hasClass('slick-initialized')) {
                initCarousel('.formations-carousel-published');
            } else if (target === "#top-profile" && !$('.formations-carousel-unpublished').hasClass('slick-initialized')) {
                initCarousel('.formations-carousel-unpublished');
            }
            
            // Rafraîchir le carousel visible
            const visibleCarousel = document.querySelector(`${target} .slick-initialized`);
            if (visibleCarousel) {
                $(visibleCarousel).slick('refresh');
            }
        });
        
        // Gestion des onglets standard
        document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                const tabId = this.getAttribute('href');
                activateTab(`#${this.id}`, tabId);
            });
        });
    }

    function init() {
        // Configurer les gestionnaires d'onglets
        setupTabHandlers();
        
        // Initialiser le premier carrousel visible
        initCarousel('.formations-carousel');
        
        // Initialiser les menus d'action
        initActionMenus();
        
        // Mettre à jour les carrousels lors du redimensionnement
        window.addEventListener('resize', updateAllCarousels);
        
        // Écouter l'événement personnalisé pour mettre à jour les formations
        document.addEventListener('updateFormations', function(e) {
            updateFormationsCarousels(e.detail.formations);
        });
    }
    
    // Lancer l'initialisation
    init();
});



// Dans categories.js, lorsqu'une catégorie est cliquée ou que la première catégorie est chargée:

// Les données sont récupérées (depuis le cache ou via AJAX)
// Un événement updateFormations est déclenché avec les données de formations


// Dans formations.js, un écouteur est configuré pour cet événement:
// javascriptdocument.addEventListener('updateFormations', function(e) {
//     updateFormationsCarousels(e.detail.formations);
// });

// Quand l'événement est capturé, la méthode updateFormationsCarousels est appelée avec les données reçues