// document.addEventListener("DOMContentLoaded", function() {
//     let ratingInput = document.getElementById('nombre_rate');
//     let ratingDisplay = document.getElementById('rating-value');

//     // Affichage par défaut vide
//     ratingDisplay.textContent = "Pas de note";
//     ratingInput.value = "";

//     document.querySelectorAll('.star-half-left, .star-half-right').forEach(star => {
//         star.addEventListener('click', function() {
//             let rating = parseFloat(this.dataset.value);
//             ratingInput.value = rating;
//             ratingDisplay.textContent = rating.toFixed(1);
//             updateStars(rating);
//         });
//     });

//     function updateStars(rating) {
//         document.querySelectorAll('.star-container').forEach(container => {
//             let value = parseFloat(container.dataset.value);
//             let starIcon = container.querySelector('i');

//             starIcon.classList.remove('fa-star', 'fa-star-half-o');
//             starIcon.classList.add('fa-star-o');

//             if (rating >= value) {
//                 starIcon.classList.remove('fa-star-o', 'fa-star-half-o');
//                 starIcon.classList.add('fa-star');
//             } else if (rating + 0.5 >= value && rating < value) {
//                 starIcon.classList.remove('fa-star-o', 'fa-star');
//                 starIcon.classList.add('fa-star-half-o');
//             }
//         });
//     }
// });


// $(document).ready(function() {
//     var feedbackTable = $('#feedback-table').DataTable({
//         language: {
//             url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
//         },
//         responsive: true,
//         pageLength: 10,
//         lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
//         dom: '<"top"fl>rt<"bottom"ip>',
//         order: [[1, 'asc']]
//     });

//     function updateSelectedCount() {
//         var selectedCount = $('.feedback-checkbox:checked').length;
//         $('#selected-count').text(selectedCount);
//         $('#selected-count-badge').text(selectedCount);
//         $('#bulk-delete-btn').prop('disabled', selectedCount === 0);
//     }

//     $('#select-all').on('click', function() {
//         if(this.checked) {
//             feedbackTable.rows().every(function() {
//                 var checkbox = $(this.node()).find('.feedback-checkbox')[0];
//                 if(checkbox) {
//                     checkbox.checked = true;
//                 }
//             });
//         } else {
//             feedbackTable.rows().every(function() {
//                 var checkbox = $(this.node()).find('.feedback-checkbox')[0];
//                 if(checkbox) {
//                     checkbox.checked = false;
//                 }
//             });
//         }
        
//         updateSelectedCount();
//     });

//     $(document).on('change', '.feedback-checkbox', function() {
//         updateSelectedCount();
        
//         // Vérifier si toutes les checkboxes sont cochées
//         var allChecked = $('.feedback-checkbox:not(:checked)').length === 0;
//         $('#select-all').prop('checked', allChecked && $('.feedback-checkbox').length > 0);
//     });

//     // Correction: S'assurer que les boutons pour fermer la modal fonctionnent
//     $('.close, .btn-secondary').on('click', function() {
//         $('#deleteModal').modal('hide');
//     });

//     // Ajout d'une confirmation avant la suppression multiple
//     $('#bulk-delete-form').on('submit', function(event) {
//         event.preventDefault(); // Annule l'envoi du formulaire
        
//         $('#deleteModalLabel').text('Confirmation de suppression multiple');
//         $('.modal-body').text('Êtes-vous sûr de vouloir supprimer les ' + $('.feedback-checkbox:checked').length + ' feedbacks sélectionnés ? Cette action est irréversible.');
        
//         // On change l'action du bouton de confirmation
//         $('#confirm-delete').off('click').on('click', function() {
//             $('#bulk-delete-form')[0].submit(); // On soumet le formulaire
//             $('#deleteModal').modal('hide');
//         });
        
//         $('#deleteModal').modal('show');
//     });

//     // Filtrer les lignes du tableau en fonction de la note sélectionnée
//     $('#rate-filter').on('change', function() {
//         var selectedRate = $(this).val(); // Récupère la note sélectionnée
        
//         // Utiliser l'API de filtrage de DataTable
//         if (selectedRate === "") {
//             // Si aucun filtre, réinitialiser le tableau
//             feedbackTable.search('').columns().search('').draw();
//         } else {
//             // Sinon, filtrer par la valeur de rate
//             feedbackTable.column(2).search(selectedRate).draw();
//         }
//     });
    
//     // Gestion de la suppression individuelle - CORRECTION
//     $(document).on('click', '.delete-button', function() {
//         var deleteUrl = $(this).data('delete-url');
//         var csrf = $(this).data('csrf');
        
//         $('#deleteModalLabel').text('Confirmation de suppression');
//         $('.modal-body').text('Êtes-vous sûr de vouloir supprimer ce feedback ? Cette action est irréversible.');
        
//         // On change l'action du bouton de confirmation
//         $('#confirm-delete').off('click').on('click', function() {
//             $.ajax({
//                 url: deleteUrl,
//                 type: 'POST',
//                 data: {
//                     _token: csrf,
//                     _method: 'DELETE' // Simule une requête DELETE

//                 },
//                 success: function(response) {
//                     // Recharger la page après suppression
//                     window.location.reload();
//                 },
//                 error: function(xhr, status, error) {
//                     console.error("Erreur de suppression:", error);
//                     console.error("Statut HTTP:", xhr.status);
//                     console.error("Réponse:", xhr.responseText);
                    
//                     // Si le code de statut est 419, c'est probablement un problème de token CSRF expiré
//                     if (xhr.status === 419) {
//                         alert('Session expirée. Veuillez rafraîchir la page et réessayer.');
//                     } else if (xhr.status === 404) {
//                         alert('Le feedback a déjà été supprimé. La page va être rafraîchie.');
//                         window.location.reload();
//                     } else {
//                         alert('Une erreur est survenue lors de la suppression: ' + error);
//                     }
//                 },
//                 complete: function() {
//                     $('#deleteModal').modal('hide');
//                 }
//             });
//         });
        
//         $('#deleteModal').modal('show');
//     });
    
//     // Initialiser les tooltips
//     $('[data-toggle="tooltip"]').tooltip();
// });


// code yekhdm fih el selection tout w el supprimer 

document.addEventListener("DOMContentLoaded", function() {
    let ratingInput = document.getElementById('nombre_rate');
    let ratingDisplay = document.getElementById('rating-value');

    // Affichage par défaut vide
    ratingDisplay.textContent = "Pas de note";
    ratingInput.value = "";

    document.querySelectorAll('.star-half-left, .star-half-right').forEach(star => {
        star.addEventListener('click', function() {
            let rating = parseFloat(this.dataset.value);
            ratingInput.value = rating;
            ratingDisplay.textContent = rating.toFixed(1);
            updateStars(rating);
        });
    });

    function updateStars(rating) {
        document.querySelectorAll('.star-container').forEach(container => {
            let value = parseFloat(container.dataset.value);
            let starIcon = container.querySelector('i');

            starIcon.classList.remove('fa-star', 'fa-star-half-o');
            starIcon.classList.add('fa-star-o');

            if (rating >= value) {
                starIcon.classList.remove('fa-star-o', 'fa-star-half-o');
                starIcon.classList.add('fa-star');
            } else if (rating + 0.5 >= value && rating < value) {
                starIcon.classList.remove('fa-star-o', 'fa-star');
                starIcon.classList.add('fa-star-half-o');
            }
        });
    }
});



$(document).ready(function() {
    var feedbackTable = $('#feedback-table').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
        dom: '<"top"fl>rt<"bottom"ip>',
        order: [[1, 'asc']]
    });

    function updateSelectedCount() {
        var selectedCount = $('.feedback-checkbox:checked').length;
        $('#selected-count').text(selectedCount);
        $('#selected-count-badge').text(selectedCount);
        $('#bulk-delete-btn').prop('disabled', selectedCount === 0);
    }

    $('#select-all').on('click', function() {
        if(this.checked) {
            feedbackTable.rows().every(function() {
                var checkbox = $(this.node()).find('.feedback-checkbox')[0];
                if(checkbox) {
                    checkbox.checked = true;
                }
            });
        } else {
            feedbackTable.rows().every(function() {
                var checkbox = $(this.node()).find('.feedback-checkbox')[0];
                if(checkbox) {
                    checkbox.checked = false;
                }
            });
        }
        
        updateSelectedCount();
    });

    $(document).on('change', '.feedback-checkbox', function() {
        updateSelectedCount();
        
        // Vérifier si toutes les checkboxes sont cochées
        var allChecked = $('.feedback-checkbox:not(:checked)').length === 0;
        $('#select-all').prop('checked', allChecked && $('.feedback-checkbox').length > 0);
    });

    // Correction: S'assurer que les boutons pour fermer la modal fonctionnent
    $('.close, .btn-secondary').on('click', function() {
        $('#deleteModal').modal('hide');
    });

    // Modification de la gestion de la suppression groupée
    $('#bulk-delete-btn').on('click', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du bouton
        
        var selectedCount = $('.feedback-checkbox:checked').length;
        if (selectedCount === 0) return; // Ne rien faire si aucun élément n'est sélectionné
        
        $('#deleteModalLabel').text('Confirmation de suppression multiple');
        $('.modal-body').text('Êtes-vous sûr de vouloir supprimer les ' + selectedCount + ' feedbacks sélectionnés ? Cette action est irréversible.');
        
        // On change l'action du bouton de confirmation
        $('#confirm-delete').off('click').on('click', function() {
            // Important: on soumet effectivement le formulaire
            $('#bulk-delete-form')[0].submit();
        });
        
        $('#deleteModal').modal('show');
    });

    // Filtrer les lignes du tableau en fonction de la note sélectionnée
    $('#rate-filter').on('change', function() {
        var selectedRate = $(this).val(); // Récupère la note sélectionnée
        
        // Utiliser l'API de filtrage de DataTable
        if (selectedRate === "") {
            // Si aucun filtre, réinitialiser le tableau
            feedbackTable.search('').columns().search('').draw();
        } else {
            // Sinon, filtrer par la valeur de rate
            feedbackTable.column(2).search(selectedRate).draw();
        }
    });
    
    // Gestion de la suppression individuelle
    $(document).on('click', '.delete-button', function() {
        var deleteUrl = $(this).data('delete-url');
        var csrf = $(this).data('csrf');
        
        $('#deleteModalLabel').text('Confirmation de suppression');
        $('.modal-body').text('Êtes-vous sûr de vouloir supprimer ce feedback ? Cette action est irréversible.');
        
        // On change l'action du bouton de confirmation
        $('#confirm-delete').off('click').on('click', function() {
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: {
                    _token: csrf,
                    _method: 'DELETE' // Simule une requête DELETE
                },
                success: function(response) {
                    // Recharger la page après suppression
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("Erreur de suppression:", error);
                    console.error("Statut HTTP:", xhr.status);
                    console.error("Réponse:", xhr.responseText);
                    
                    if (xhr.status === 419) {
                        alert('Session expirée. Veuillez rafraîchir la page et réessayer.');
                    } else if (xhr.status === 404) {
                        alert('Le feedback a déjà été supprimé. La page va être rafraîchie.');
                        window.location.reload();
                    } else {
                        alert('Une erreur est survenue lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    $('#deleteModal').modal('hide');
                }
            });
        });
        
        $('#deleteModal').modal('show');
    });
    
    // Initialiser les tooltips
    $('[data-toggle="tooltip"]').tooltip();
});



