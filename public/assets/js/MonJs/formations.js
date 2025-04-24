


$(document).ready(function() {
    // Sélecteurs
    const formationsContainer = $('.formations-container');
    const searchInput = $('#search-formations');
    const categoryFilter = $('input[name="category_filter"]');
    const statusFilter = $('.status-filter');
    
    // Handler pour la suppression de formation
    $(document).on('click', '.delete-formation', function() {
        const formationId = $(this).data('id');
        const deleteForm = $('#deleteFormationForm');
        
        // Mettre à jour l'action du formulaire
        deleteForm.attr('action', `/formation/${formationId}`);
        
        // Afficher le modal de confirmation
        $('#deleteConfirmationModal').modal('show');
    });
    
    // Appliquer les styles nécessaires
   
    
    // Filtrage par catégorie et statut avec AJAX
    categoryFilter.on('change', loadFilteredFormations);
    statusFilter.on('change', loadFilteredFormations);
    searchInput.on('keyup', debounce(loadFilteredFormations, 30)); // Ajouter un délai pour éviter trop de requêtes
    
    // Fonction pour débouncer les événements
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }
    
    function initStatusFilter() {
        // Vérifier si un statut est déjà dans l'URL
        const urlParams = new URLSearchParams(window.location.search);
        if (!urlParams.has('status')) {
            // Si aucun statut n'est spécifié dans l'URL, définir sur "Publiée" par défaut
            statusFilter.val('1');
        }
        
        // Si le select utilise select2, mettre à jour l'interface
        if ($.fn.select2 && statusFilter.hasClass('select2-hidden-accessible')) {
            statusFilter.trigger('change.select2');
        }
    }
    
    function loadFilteredFormations() {
        const categoryId = $('input[name="category_filter"]:checked').val();
        const status = statusFilter.val();
        const searchTerm = searchInput.val();
        
        console.log("Filtrage:", { categoryId, status, searchTerm });
        
        // Mise à jour de l'URL
        const url = new URL(window.location);
        
        if (categoryId) url.searchParams.set('category_id', categoryId);
        else url.searchParams.delete('category_id');
        
        if (status !== '') url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        
        if (searchTerm) url.searchParams.set('search', searchTerm);
        else url.searchParams.delete('search');
        
        window.history.pushState({}, '', url);
        
        // Préparation des paramètres AJAX
        const ajaxParams = {};
        
        if (categoryId) ajaxParams.category_id = categoryId;
        if (status !== '') ajaxParams.status = status;
        if (searchTerm) ajaxParams.search = searchTerm;
        
        // Requête AJAX
        $.ajax({
            url: window.location.pathname,
            type: 'GET',
            data: ajaxParams,
            dataType: 'json',
            beforeSend: function() {
                formationsContainer.html('<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div></div>');
            },
            success: function(response) {
                console.log("Réponse:", response);
                updateFormationsDisplay(response);
            },
            error: function(xhr, status, error) {
                console.error("Erreur:", error);
                formationsContainer.html(`<div class="col-12"><div class="alert alert-danger">Une erreur s'est produite.</div></div>`);
            }
        });
    }

   
    function updateFormationsDisplay(data) {
    formationsContainer.empty();
    
    if (!data.formations || data.formations.length === 0) {
        formationsContainer.html(`
            <div class="col-12">
                <div class="alert alert-info">
                    Aucune formation disponible.
                </div>
            </div>
        `);
        return;
    }
    
    // Mettre à jour le titre si nécessaire
    if (data.title) {
        $('.breadcrumb_title h3').text('Formations: ' + data.title);
    }
    
    // Ajouter chaque formation en utilisant la fonction du fichier formations-details.js
    data.formations.forEach((formation, index) => {
        const formationHtml = createFormationCard(formation);
        
        // Ajouter un espace après la première ligne (ajustez le nombre selon votre mise en page)
        // Supposons que vous avez 3 formations par ligne
        if (index === 3) {
            formationsContainer.append('<div class="w-100 mb-4"></div>'); // Ajoute un espacement vertical
        }
        
        formationsContainer.append(formationHtml);
    });
    
    // Réinitialiser les tooltips et autres plugins
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    if ($.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }
}
    // Initialisation des plugins
    if ($.fn.select2) {
        $('.select2').select2();
    }
    
    function applyUrlFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Si l'URL contient des paramètres de filtre, les appliquer
        if (urlParams.has('category_id')) {
            const categoryId = urlParams.get('category_id');
            // Sélectionner la catégorie correspondante
            $(`input[name="category_filter"][value="${categoryId}"]`).prop('checked', true);
        } else {
            // Sinon, sélectionner "Tous"
            $('#category-all').prop('checked', true);
        }
        
        if (urlParams.has('status')) {
            const status = urlParams.get('status');
            statusFilter.val(status);
        } else {
            // Sinon, sélectionner "Publiée" par défaut
            statusFilter.val('1');
        }
        
        // Si le select utilise select2, mettre à jour l'interface
        if ($.fn.select2 && statusFilter.hasClass('select2-hidden-accessible')) {
            statusFilter.trigger('change.select2');
        }
        
        if (urlParams.has('search')) {
            searchInput.val(urlParams.get('search'));
        }
    }
    
    // Lorsque l'utilisateur rafraîchit la page, réinitialiser les filtres
    $(window).on('beforeunload', function() {
        // Supprimer les paramètres de l'URL
        const url = new URL(window.location);
        url.searchParams.delete('category_id');
        url.searchParams.delete('status');
        url.searchParams.delete('search');
        window.history.replaceState({}, '', url);
    });
    
    // Assurer que les filtres par défaut soient appliqués au chargement initial
    applyUrlFilters();
    initStatusFilter();
    
    // Chargement initial pour afficher toutes les formations
    loadFilteredFormations();
});