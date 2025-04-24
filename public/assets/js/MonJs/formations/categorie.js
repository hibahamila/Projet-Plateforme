// categories.js - Gestion des catégories et filtrage
document.addEventListener('DOMContentLoaded', function() {
    const formationsCache = {};

    function setupLoadingIndicators() {
        // Création de l'indicateur de chargement
        const loadingIndicator = `
            <div class="loading-overlay" style="display:none; position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.7); z-index:9999; text-align:center; padding-top:100px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
            </div>
        `;
        
        // Ajout de l'indicateur à chaque conteneur de carousel
        document.querySelectorAll('.carousel-container').forEach(container => {
            container.style.position = 'relative';
            container.insertAdjacentHTML('beforeend', loadingIndicator);
        });
    }
    
    function showLoaders() {
        document.querySelectorAll('.loading-overlay').forEach(loader => {
            loader.style.display = 'block';
        });
    }
    
    function hideLoaders() {
        document.querySelectorAll('.loading-overlay').forEach(loader => {
            loader.style.display = 'none';
        });
    }

    function initCategoriesSlider() {
        const categoriesSlider = document.querySelector('.categories-slider');
        const nextButton = document.querySelector('.next-button');
        const prevButton = document.querySelector('.prev-button');
        
        if (!categoriesSlider || !nextButton || !prevButton) return;
        
        function updateNavButtons() {
            // Masquer/afficher le bouton suivant
            if (categoriesSlider.scrollLeft + categoriesSlider.clientWidth >= categoriesSlider.scrollWidth - 10) {
                nextButton.style.display = 'none';
            } else {
                nextButton.style.display = 'flex';
            }
            
            // Masquer/afficher le bouton précédent
            if (categoriesSlider.scrollLeft <= 10) {
                prevButton.style.display = 'none';
            } else {
                prevButton.style.display = 'flex';
            }
            
            // Si tout le contenu est visible, masquer les deux boutons
            if (categoriesSlider.scrollWidth <= categoriesSlider.clientWidth) {
                nextButton.style.display = 'none';
                prevButton.style.display = 'none';
            }
        }
        
        // Navigation vers la droite
        nextButton.addEventListener('click', function() {
            const scrollDistance = Math.min(categoriesSlider.clientWidth * 0.8, 500);
            categoriesSlider.scrollBy({
                left: scrollDistance,
                behavior: 'smooth'
            });
        });
        
        // Navigation vers la gauche
        prevButton.addEventListener('click', function() {
            const scrollDistance = Math.min(categoriesSlider.clientWidth * 0.8, 500);
            categoriesSlider.scrollBy({
                left: -scrollDistance,
                behavior: 'smooth'
            });
        });
        
        // Mettre à jour l'état des boutons lors du défilement
        categoriesSlider.addEventListener('scroll', updateNavButtons);
        
        // Mettre à jour l'état des boutons lors du redimensionnement
        window.addEventListener('resize', updateNavButtons);
        
        // État initial des boutons
        updateNavButtons();
    }
// Gère le clic sur une catégorie.
    function handleCategoryClick(e) {
        e.preventDefault();
        
        const categoryId = this.dataset.categoryId;
        const categoryUrl = this.href;
        
        // Vérifier si la catégorie est déjà active
        if (this.closest('.category-item').classList.contains('active')) {
            return;
        }
        
        // Mettre à jour la classe active
        document.querySelectorAll('.category-item').forEach(item => {
            item.classList.remove('active');
        });
        this.closest('.category-item').classList.add('active');
        
        // Afficher l'indicateur de chargement
        showLoaders();
        
        // Mettre à jour l'URL sans rafraîchir la page
        history.pushState({}, '', categoryUrl);
        
        // Vérifier le cache
        if (formationsCache[categoryId]) {
            // Déclencher un événement personnalisé pour mettre à jour les formations
            const event = new CustomEvent('updateFormations', {
                detail: { formations: formationsCache[categoryId] }
            });
            document.dispatchEvent(event);
            hideLoaders();
            return;
        }
        
        // Requête AJAX
        fetch(categoryUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            formationsCache[categoryId] = data.formations;
            
            // Déclencher un événement personnalisé pour mettre à jour les formations
            const event = new CustomEvent('updateFormations', {
                detail: { formations: data.formations }
            });
            document.dispatchEvent(event);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des formations:', error);
        })
        .finally(() => {
            hideLoaders();
        });
    }

    //Rôle: Initialise et charge la première catégorie au chargement de la page.

    
    function initializeFirstCategory() {
        // Supprimer toutes les classes 'active' d'abord
        document.querySelectorAll('.category-item').forEach(item => {
            item.classList.remove('active');
        });
        
        const firstCategory = document.querySelector('.category-link');
        if (firstCategory) {
            // Définir cette catégorie comme active
            firstCategory.closest('.category-item').classList.add('active');
            
            // Récupérer les données de cette catégorie
            const categoryId = firstCategory.dataset.categoryId;
            const categoryUrl = firstCategory.href;
            
            // Afficher l'indicateur de chargement
            showLoaders();
            
            // Mettre à jour l'URL sans rafraîchir la page
            history.pushState({}, '', categoryUrl);
            
            // Requête AJAX pour récupérer les formations
            fetch(categoryUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                formationsCache[categoryId] = data.formations;
                
                // Déclencher un événement personnalisé pour mettre à jour les formations
                const event = new CustomEvent('updateFormations', {
                    detail: { formations: data.formations }
                });
                document.dispatchEvent(event);
            })
            .catch(error => {
                console.error('Erreur lors du chargement initial des formations:', error);
            })
            .finally(() => {
                hideLoaders();
            });
        }
    }

    function handleFlashMessages() {
        ['success-message', 'delete-message', 'create-message'].forEach(id => {
            const message = document.getElementById(id);
            if (message) {
                message.style.opacity = 1;
                setTimeout(() => {
                    message.style.opacity = 0;
                }, 2000);
            }
        });
    }

    //Point d'entrée principal qui initialise toutes les fonctionnalités.
    function init() {
        // Gérer les messages flash
        handleFlashMessages();
        
        // Configurer les indicateurs de chargement
        setupLoadingIndicators();
        
        // Initialiser le slider de catégories
        initCategoriesSlider();
        
        // Attacher les gestionnaires d'événements aux liens de catégorie
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', handleCategoryClick);
        });
        
        // Initialiser la première catégorie après un court délai
        setTimeout(initializeFirstCategory, 100);
    }
    
    // Lancer l'initialisation
    init();
});