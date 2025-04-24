

// // Fichier: select2-global.js
// $(document).ready(function () {
//     // Configuration commune pour tous les sélecteurs Select2
//     const selectConfig = {
//         width: '100%',
//         allowClear: true
//     };
    
//     // Définir les configurations spécifiques pour chaque type de sélecteur
//     const selectorsConfig = {
//         'select2-categorie': {
//             placeholder: "Sélectionner une catégorie"
//         },
//         'select2-cours': {
//             placeholder: "Sélectionner un cours"
//         },
//         'select2-professeur': {
//             placeholder: "Sélectionnez un professeur"
//         },
//         'select2-formation': {
//             placeholder: "Sélectionner une formation"
//         },
//         'select2-chapitre': {
//             placeholder: "Choisir un chapitre"
//         },
//         'select2-question': {
//             placeholder: "Choisir une question"
//         },
//         'select2-quiz': {
//             placeholder: "Sélectionnez un quiz"
//         }
//     };
    
//     // Initialiser tous les sélecteurs Select2
//     Object.keys(selectorsConfig).forEach(function(selectorClass) {
//         const $selector = $('.' + selectorClass);
        
//         if ($selector.length) {
//             // Fusionner la configuration commune avec la configuration spécifique
//             const config = {...selectConfig, ...selectorsConfig[selectorClass]};
            
//             // Initialiser Select2
//             $selector.select2(config)
//                 .on('select2:select', function(e) {
//                     // Met à jour la validation dès qu'une option est sélectionnée
//                     $(this).removeClass('is-invalid').addClass('is-valid');
//                     // Assurer que la valeur est correctement mise à jour
//                     $(this).trigger('change');
//                 })
//                 .on('select2:unselect', function(e) {
//                     $(this).removeClass('is-valid').addClass('is-invalid');
//                 });
//         }
//     });
    
//     // Validation manuelle pour tous les formulaires
//     $('form').on('submit', function(event) {
//         var isValid = true;
        
//         // Vérifier tous les sélecteurs Select2 requis dans le formulaire
//         $(this).find('select[required]').each(function() {
//             const $select = $(this);
            
//             if (!$select.val()) {
//                 $select.addClass('is-invalid');
//                 isValid = false;
//             } else {
//                 $select.removeClass('is-invalid').addClass('is-valid');
//             }
//         });
        
//         // Empêcher la soumission du formulaire si la validation échoue
//         if (!isValid) {
//             event.preventDefault();
//             event.stopPropagation();
//         }
        
//         // Ajouter la classe was-validated pour afficher les messages d'erreur
//         $(this).addClass('was-validated');
//     });
    
//     // Spécifique aux pages d'édition : détection automatique
//     const isEditPage = window.location.href.includes('edit') || 
//                        $('form[method="PUT"]').length > 0 || 
//                        $('input[name="_method"][value="PUT"]').length > 0;
    
//     if (isEditPage) {
//         // Forcer Select2 à reconnaître les valeurs pré-sélectionnées sur les pages d'édition
//         setTimeout(function() {
//             $('select.form-select, select.form-control').each(function() {
//                 if ($(this).val() && $(this).hasClass('select2-hidden-accessible')) {
//                     $(this).trigger('change');
//                 }
//             });
//         }, 100);
//     } else {
//         // Pour les pages non-édition, réinitialiser certains sélecteurs au besoin
//         // (vous pouvez ajouter ici une logique spécifique aux pages de création)
//     }
    
//     // Fonction globale pour réinitialiser un sélecteur Select2
//     window.resetSelect2 = function(selector) {
//         $(selector).val(null).trigger('change');
//     };
    
//     // Fonction globale pour définir une valeur dans un sélecteur Select2
//     window.setSelect2Value = function(selector, value) {
//         $(selector).val(value).trigger('change');
//     };
// });



//zedtou tww


$(document).ready(function () {
    // Configuration commune pour tous les sélecteurs Select2
    const selectConfig = {
        width: '100%',
        allowClear: true,
        templateResult: function(data) {
            // Personnaliser l'affichage des options dans le dropdown
            if (!data.id) {
                return data.text; // Retourner le texte par défaut pour l'option "placeholder"
            }
            return $('<span>').text(data.text).addClass('select2-option');
        },
        templateSelection: function(data) {
            // Personnaliser l'affichage de l'option sélectionnée dans le champ
            return $('<span>').text(data.text).addClass('select2-selection');
        }
    };

    // Définir les configurations spécifiques pour chaque type de sélecteur
    const selectorsConfig = {
        'select2-categorie': {
            placeholder: "Sélectionner une catégorie"
        },
        'select2-cours': {
            placeholder: "Sélectionner un cours"
        },
        'select2-professeur': {
            placeholder: "Sélectionnez un professeur"
        },
        'select2-formation': {
            placeholder: "Sélectionner une formation"
        },
        'select2-chapitre': {
            placeholder: "Choisir un chapitre"
        },
        'select2-question': {
            placeholder: "Choisir une question"
        },
        'select2-quiz': {
            placeholder: "Sélectionnez un quiz"
        }
    };

    // Initialiser tous les sélecteurs Select2
    Object.keys(selectorsConfig).forEach(function(selectorClass) {
        const $selector = $('.' + selectorClass);
        
        if ($selector.length) {
            // Fusionner la configuration commune avec la configuration spécifique
            const config = {...selectConfig, ...selectorsConfig[selectorClass]};
            
            // Initialiser Select2
            $selector.select2(config)
                .on('select2:select', function(e) {
                    // Met à jour la validation dès qu'une option est sélectionnée
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    // Assurer que la valeur est correctement mise à jour
                    $(this).trigger('change');
                })
                .on('select2:unselect', function(e) {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                });
        }
    });

    // Validation manuelle pour tous les formulaires
    $('form').on('submit', function(event) {
        var isValid = true;
        
        // Vérifier tous les sélecteurs Select2 requis dans le formulaire
        $(this).find('select[required]').each(function() {
            const $select = $(this);
            
            if (!$select.val()) {
                $select.addClass('is-invalid');
                isValid = false;
            } else {
                $select.removeClass('is-invalid').addClass('is-valid');
            }
        });
        
        // Empêcher la soumission du formulaire si la validation échoue
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Ajouter la classe was-validated pour afficher les messages d'erreur
        $(this).addClass('was-validated');
    });
    
    // Spécifique aux pages d'édition : détection automatique
    const isEditPage = window.location.href.includes('edit') || 
                       $('form[method="PUT"]').length > 0 || 
                       $('input[name="_method"][value="PUT"]').length > 0;
    
    if (isEditPage) {
        // Forcer Select2 à reconnaître les valeurs pré-sélectionnées sur les pages d'édition
        setTimeout(function() {
            $('select.form-select, select.form-control').each(function() {
                if ($(this).val() && $(this).hasClass('select2-hidden-accessible')) {
                    $(this).trigger('change');
                }
            });
        }, 100);
    } else {
        // Pour les pages non-édition, réinitialiser certains sélecteurs au besoin
        // (vous pouvez ajouter ici une logique spécifique aux pages de création)
    }
    
    // Fonction globale pour réinitialiser un sélecteur Select2
    window.resetSelect2 = function(selector) {
        $(selector).val(null).trigger('change');
    };
    
    // Fonction globale pour définir une valeur dans un sélecteur Select2
    window.setSelect2Value = function(selector, value) {
        $(selector).val(value).trigger('change');
    };
});