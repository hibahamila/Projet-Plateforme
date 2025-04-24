
document.addEventListener("DOMContentLoaded", function () {
    // Récupérer l'élément du bouton
    const loadButton = document.getElementById("loadCreateUserForm");

    // S'assurer que l'élément existe
    if (loadButton) {
        loadButton.addEventListener("click", function (event) {
            // Récupérer l'URL à partir de l'attribut data
            const createUrl = this.getAttribute("data-create-url");

            if (!createUrl) {
                console.error('URL de création non définie');
                return;
            }

            // Ajouter un timestamp pour éviter le cache
            const timestamp = new Date().getTime();
            const urlWithTimestamp = createUrl + (createUrl.includes('?') ? '&' : '?') + '_=' + timestamp;

            // Charger le formulaire via AJAX
            fetch(urlWithTimestamp, {
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.text();
            })
            .then(html => {
                // Créer un conteneur temporaire pour la modal
                const modalContainer = document.createElement('div');
                modalContainer.innerHTML = html;
                document.body.appendChild(modalContainer);

                // Récupérer la modal et l'initialiser
                const modal = modalContainer.querySelector('.modal');
                if (!modal) {
                    throw new Error('Modal non trouvée dans le contenu chargé');
                }

                // Initialiser la modal Bootstrap
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // Configurer le formulaire
                const form = modalContainer.querySelector('#createUserForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        handleFormSubmit(e, modalContainer);
                    });
                }

                // Supprimer la modal du DOM après fermeture
                modal.addEventListener('hidden.bs.modal', function () {
                    modalContainer.remove();
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement:', error);
                alert(`Erreur de chargement: ${error.message}`);
            });
        });
    }

    // Gestionnaire de soumission du formulaire
    function handleFormSubmit(event, modalContainer) {
        event.preventDefault();
        const form = event.currentTarget;

        // Vérifier la validité du formulaire
        if (!form.checkValidity()) {
            event.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        // Préparer les données du formulaire
        const formData = new FormData(form);

        // Récupérer le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Désactiver le bouton de soumission
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement...';
        }

        // Envoyer la requête AJAX
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // Si la réponse est 422 (validation échouée), extraire les erreurs
                if (response.status === 422) {
                    return response.json().then(errors => {
                        throw errors;
                    });
                }
                throw new Error('Erreur serveur: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Afficher le mot de passe généré
                let successMessage = data.message || 'Utilisateur créé avec succès';
                if (data.password) {
                    successMessage += `<br><strong>Mot de passe généré: ${data.password}</strong>`;
                }

                // Fermer la modal
                const modal = modalContainer.querySelector('.modal');
                if (modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) {
                        bsModal.hide();
                    }
                }

                // Afficher le message de succès
                showAlert('success', successMessage);

                // Recharger les données si nécessaire
                if (typeof refreshUsersList === 'function') {
                    refreshUsersList();
                }
            } else {
                // Afficher l'erreur dans la modal
                showAlert('danger', data.message || 'Erreur lors de la création de l\'utilisateur', modalContainer);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la soumission:', error);

            // Si c'est une erreur de validation (422), afficher les messages d'erreur
            if (error.errors) {
                for (const [field, messages] of Object.entries(error.errors)) {
                    showAlert('danger', messages.join('<br>'), modalContainer);
                }
            } else {
                showAlert('danger', error.message || 'Une erreur est survenue lors du traitement de votre demande', modalContainer);
            }
        })
        .finally(() => {
            // Réactiver le bouton de soumission
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Enregistrer';
            }
        });
    }

    // Fonction pour afficher une alerte
    function showAlert(type, message, modalContainer = null) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        if (modalContainer) {
            // Afficher l'alerte dans la modal
            const modalBody = modalContainer.querySelector('.modal-body');
            if (modalBody) {
                modalBody.insertBefore(alertDiv, modalBody.firstChild);
            }
        } else {
            // Afficher l'alerte dans la page
            const container = document.querySelector('.container-fluid') || document.body;
            container.insertBefore(alertDiv, container.firstChild);
        }

        // Supprimer l'alerte après 10 secondes
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 10000);
    }
});
