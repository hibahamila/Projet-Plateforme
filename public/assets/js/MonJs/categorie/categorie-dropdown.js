document.addEventListener('DOMContentLoaded', function() {
    // Gérer les boutons de toggle pour les dropdowns
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Fermer tous les autres menus ouverts
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                if (menu !== this.nextElementSibling) {
                    menu.classList.remove('show');
                }
            });
            
            // Ouvrir/fermer ce menu
            const dropdownMenu = this.nextElementSibling;
            dropdownMenu.classList.toggle('show');
        });
    });
    
    // Empêcher la propagation des clics sur le menu dropdown
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Fermer le dropdown quand on clique ailleurs sur la page
    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });
    });
    
    // Gérer les clics sur le bouton supprimer
    document.querySelectorAll('.delete-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            const categoryId = this.getAttribute('data-id');
            const deleteRoute = this.getAttribute('data-route');
            
            if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
                // Envoi d'une requête AJAX pour supprimer la catégorie
                fetch(deleteRoute, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.successMessage) {
                        // Afficher un message de succès
                        alert(data.successMessage);
                        // Recharger la page ou supprimer l'élément du DOM
                        const categoryItem = item.closest('.category-item');
                        categoryItem.remove();
                    } else {
                        alert(data.errorMessage || 'Une erreur est survenue lors de la suppression.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la suppression.');
                });
            }
        });
    });
});