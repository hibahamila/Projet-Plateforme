
// $(document).ready(function() {
//     // Gestion du clic sur l'icône Modifier
//     $('.edit-icon').on('click', function() {
//         var url = $(this).data('edit-url');
//         window.location.href = url;  // Redirige vers la page de modification
//     });

//     // Gestion du clic sur l'icône Supprimer
//     $('.delete-icon').on('click', function() {
//         var url = $(this).data('delete-url');
//         var csrfToken = $(this).data('csrf');

//         // Demande de confirmation avant suppression
//         if (confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')) {
//             $.ajax({
//                 url: url,
//                 method: 'POST',  // Utilisation de POST pour gérer le DELETE
//                 data: {
//                     _method: 'DELETE',  // Simule une requête DELETE
//                     _token: csrfToken
//                 },
//                 success: function(response) {
//                     // Traite la réponse de la suppression
//                     alert('Formation supprimée avec succès');
//                     location.reload();  // Recharge la page pour voir la suppression
//                 },
//                 error: function(xhr, status, error) {
//                     // Affiche une erreur en cas de problème
//                     alert('Erreur lors de la suppression');
//                 }
//             });
//         }
//     });
// });








$(document).ready(function() {
    // Gestion du clic sur l'icône Modifier
    $('.edit-icon').on('click', function() {
        var url = $(this).data('edit-url');
        window.location.href = url;  // Redirige vers la page d'édition
    });

    // Gestion du clic sur l'icône Supprimer
    $('.delete-icon').on('click', function() {
        var url = $(this).data('delete-url');
        var csrfToken = $(this).data('csrf');

        // Récupération du nom de l'élément (ex: Formation, Catégorie, etc.)
        var itemType = $(this).closest('tr').find('td:first').text().trim();  

        // Message de confirmation avec le type d'élément détecté
        if (confirm(`Êtes-vous sûr de vouloir supprimer "${itemType}" ?`)) {
            $.ajax({
                url: url,
                method: 'POST',  // Utilisation de POST pour gérer le DELETE
                data: {
                    _method: 'DELETE',  // Simule une requête DELETE
                    _token: csrfToken
                },
                success: function(response) {
                    alert(`${itemType} supprimé(e) avec succès !`);
                    location.reload();  // Recharge la page après suppression
                },
                error: function(xhr, status, error) {
                    alert(`Erreur lors de la suppression de ${itemType}`);
                }
            });
        }
    });
});

