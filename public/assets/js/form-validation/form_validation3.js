// Fonction d'initialisation de validation réutilisable
function initFormValidation() {
    console.log("Initialisation de la validation de formulaire...");
    // Sélectionner tous les formulaires avec la classe needs-validation
    const forms = document.querySelectorAll('form.needs-validation');
    console.log(`Formulaires trouvés: ${forms.length}`);

    forms.forEach(function(form) {
        // Garder une trace des champs qui ont eu des erreurs
        const fieldsWithErrors = new Set();
        // Indicateur pour savoir si le formulaire a déjà été soumis
        let formSubmitted = false;

        // Vérifier s'il y a des erreurs Laravel au chargement de la page
        function checkLaravelErrors() {
            // Si des erreurs Laravel sont présentes, cela signifie que le formulaire a déjà été soumis
            const hasLaravelErrors = form.querySelectorAll('.laravel-error[style*="display: block"]').length > 0;
            if (hasLaravelErrors) {
                formSubmitted = true;
            }

            form.querySelectorAll('.laravel-error').forEach(laravelError => {
                if (laravelError.style.display === 'block') {
                    // Si une erreur Laravel existe, masquer l'erreur JS correspondante
                    // Trouver l'input-group ou form-group parent
                    const inputGroup = laravelError.closest('.input-group');
                    const formGroup = laravelError.closest('.form-group');

                    // Trouver le champ d'entrée associé à cette erreur
                    const container = inputGroup || formGroup;
                    const input = container ? container.querySelector('input, textarea, select') : null;

                    if (input) {
                        // Trouver l'erreur JS associée à ce champ
                        const jsError = findJsErrorForField(input);
                        if (jsError) jsError.style.display = 'none';

                        fieldsWithErrors.add(input.name || input.id);
                        // Ajouter la bordure rouge pour les champs avec erreurs Laravel
                        input.style.borderColor = 'red';
                    }
                }
            });
        }

        // Fonction utilitaire pour trouver le message d'erreur JS d'un champ
        function findJsErrorForField(field) {
            // Essayer de trouver l'erreur JS dans le parent input-group d'abord
            let container = field.closest('.input-group');
            let jsError = container ? container.querySelector('.js-error') : null;

            // Si pas trouvé, essayer dans le parent form-group
            if (!jsError) {
                container = field.closest('.form-group');
                // Si le form-group contient plusieurs input-group, chercher dans celui qui contient ce champ
                if (container) {
                    const inputGroups = container.querySelectorAll('.input-group');
                    if (inputGroups.length > 1) {
                        // Trouver l'input-group qui contient ce champ
                        for (let inputGroup of inputGroups) {
                            if (inputGroup.contains(field)) {
                                jsError = inputGroup.querySelector('.js-error');
                                break;
                            }
                        }
                    } else {
                        // S'il n'y a pas d'input-group multiples, chercher dans le form-group
                        jsError = container.querySelector('.js-error');
                    }
                }
            }

            return jsError;
        }

        // Nettoyer les anciens écouteurs d'événements pour éviter les doublons
        // Stocker une référence à l'écouteur submit dans un attribut de données
        if (form._submitListener) {
            form.removeEventListener('submit', form._submitListener);
        }

        // Créer un nouveau gestionnaire d'événement submit
        const submitListener = function(e) {
            console.log("Formulaire soumis:", form.id || "sans id");
            // Marquer que le formulaire a été soumis
            formSubmitted = true;

            // Supprimer toutes les erreurs Laravel existantes
            form.querySelectorAll('.laravel-error').forEach(error => {
                error.style.display = 'none';
            });

            // Réinitialiser les états d'erreur visuelle, mais garder la trace des champs qui ont eu des erreurs
            form.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
                // Ajouter l'identifiant du champ à notre ensemble de champs en erreur
                fieldsWithErrors.add(field.name || field.id);
            });

            // Masquer toutes les erreurs JS
            form.querySelectorAll('.js-error').forEach(error => {
                error.style.display = 'none';
            });

            let isValid = true;

            // Vérifier chaque champ obligatoire
            form.querySelectorAll('[required]').forEach(field => {
                const fieldId = field.name || field.id;
                const isEmpty = field.type === 'checkbox' ? !field.checked : !field.value.trim();

                if (isEmpty) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');

                    // Ajouter la bordure rouge pour les champs invalides
                    field.style.borderColor = 'red';

                    // Ajouter ce champ à notre ensemble de champs en erreur
                    fieldsWithErrors.add(fieldId);

                    // Afficher l'erreur JS pour ce champ vide en utilisant notre fonction utilitaire
                    const jsError = findJsErrorForField(field);
                    if (jsError) {
                        jsError.style.display = 'block';
                    }

                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                    // Ajouter la bordure verte pour les champs valides
                    field.style.borderColor = 'green';
                }
            });

            if (!isValid) {
                console.log("Validation échouée, empêchement de la soumission");
                e.preventDefault(); // Empêcher la soumission du formulaire s'il y a des erreurs JS
                e.stopPropagation();
            } else {
                console.log("Validation réussie, formulaire soumis");
            }
        };

        // Stocker la référence et ajouter l'écouteur
        form._submitListener = submitListener;
        form.addEventListener('submit', submitListener);

        // Nettoyer les anciens écouteurs d'événements pour les champs
        form.querySelectorAll('input, textarea, select').forEach(field => {
            if (field._inputListener) {
                const eventType = (field.type === 'checkbox' || field.type === 'radio') ? 'change' : 'input';
                field.removeEventListener(eventType, field._inputListener);
            }
        });

        // Créer et attacher les nouveaux écouteurs pour la validation en temps réel
        form.querySelectorAll('input, textarea, select').forEach(field => {
            const eventType = (field.type === 'checkbox' || field.type === 'radio') ? 'change' : 'input';

            const inputListener = function() {
                const fieldId = this.name || this.id;
                const isEmpty = this.type === 'checkbox' ? !this.checked : !this.value.trim();

                // N'appliquer la validation visuelle que si le formulaire a déjà été soumis
                if (!formSubmitted) {
                    // Si le formulaire n'a pas été soumis, on ne fait rien
                    return;
                }

                // Utiliser notre fonction utilitaire pour trouver les erreurs associées
                const jsError = findJsErrorForField(this);

                // Trouver l'erreur Laravel associée (peut être dans input-group ou form-group)
                let laravelError = null;
                const inputGroup = this.closest('.input-group');
                const formGroup = this.closest('.form-group');

                if (inputGroup) {
                    laravelError = inputGroup.querySelector('.laravel-error');
                }

                if (!laravelError && formGroup) {
                    // S'il y a plusieurs input-group, chercher celui qui contient ce champ
                    const inputGroups = formGroup.querySelectorAll('.input-group');
                    if (inputGroups.length > 1) {
                        for (let group of inputGroups) {
                            if (group.contains(this)) {
                                laravelError = group.querySelector('.laravel-error');
                                break;
                            }
                        }
                    } else {
                        laravelError = formGroup.querySelector('.laravel-error');
                    }
                }

                // Si le champ n'est pas vide, on supprime toutes les erreurs
                if (!isEmpty) {
                    // Supprimer l'erreur et ajouter la validation
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    this.style.borderColor = 'green';

                    // Masquer les erreurs (Laravel et JS)
                    if (jsError) jsError.style.display = 'none';
                    if (laravelError) laravelError.style.display = 'none';
                }
                // Si le champ est vide et qu'il a été marqué en erreur auparavant
                else if (isEmpty && fieldsWithErrors.has(fieldId)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    this.style.borderColor = 'red';

                    // Afficher l'erreur JS uniquement (car l'erreur Laravel a été supprimée)
                    if (jsError) jsError.style.display = 'block';
                    if (laravelError) laravelError.style.display = 'none';
                }
            };

            // Stocker la référence et ajouter l'écouteur
            field._inputListener = inputListener;
            field.addEventListener(eventType, inputListener);
        });

        // Exécuter au chargement de la page
        checkLaravelErrors();
    });
}

// Exécuter au chargement initial de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOMContentLoaded - Initialisation de la validation");
    initFormValidation();
});

// Rendre la fonction accessible globalement pour pouvoir l'appeler après un chargement Ajax
window.initFormValidation = initFormValidation;
