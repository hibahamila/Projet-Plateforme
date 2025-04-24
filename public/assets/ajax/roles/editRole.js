document.addEventListener("click", function (event) {
    if (event.target.classList.contains("edit-icon")) {
        let editUrl = event.target.getAttribute('data-edit-url');
        console.log('URL de modification :', editUrl); // Log l'URL

        fetch(editUrl)
            .then(response => {
                console.log('Réponse du serveur :', response); // Log la réponse
                if (!response.ok) {
                    throw new Error('Erreur réseau : ' + response.status);
                }
                return response.text();
            })
            .then(data => {
                console.log('Données reçues :', data); // Log les données reçues

                // Supprimer toute modale existante
                const existingModal = document.querySelector('#EditRoleModal');
                if (existingModal) {
                    existingModal.remove();
                }

                // Ajouter la nouvelle modale
                document.body.insertAdjacentHTML('beforeend', data);

                // Sélectionner la nouvelle modale
                const modal = document.querySelector('#EditRoleModal');
                if (!modal) {
                    throw new Error('La modale n\'a pas été trouvée dans la réponse.');
                }

                // Initialiser Select2 sur les selects multiples
                $(modal).find('.js-example-basic-multiple').select2({
                    placeholder: 'Sélectionnez des permissions',
                    allowClear: true,
                    width: '100%'
                });

                // Initialiser la modale Bootstrap
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // Nettoyer après la fermeture
                modal.addEventListener('hidden.bs.modal', function () {
                    // Détruire les instances Select2 avant de supprimer la modale
                    $(modal).find('.js-example-basic-multiple').select2('destroy');
                    modal.remove();
                });

                // Gestion de la soumission du formulaire
                const editRoleForm = modal.querySelector('#editRoleForm');
                if (editRoleForm) {
                    editRoleForm.addEventListener('submit', function (event) {
                        event.preventDefault();

                        const formData = new FormData(this);

                        fetch(this.action, {
                            method: this.method,
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': window.csrfToken,
                                'Accept': 'application/json',
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showSuccessAlert(data.success);
                                bsModal.hide();
                                document.getElementById("load-roles").click();
                            }
                            if (data.danger) {
                                showErrorAlert(data.danger);
                                bsModal.hide();
                                document.getElementById("load-roles").click();
                            }
                        })
                        .catch(error => {
                            if (error.errors) {
                                displayFormErrors(error.errors, modal);
                            } else {
                                console.error('Erreur:', error);
                                showErrorAlert('Une erreur est survenue lors de la modification du rôle.');
                            }
                        });
                    });
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement de la modale :', error);
                showErrorAlert('Erreur lors du chargement de la modale : ' + error.message);
            });
    }
});

// Fonction pour afficher les erreurs dans le formulaire
function displayFormErrors(errors, modal) {
    const errorElements = modal.querySelectorAll('.invalid-feedback');
    errorElements.forEach(el => el.remove());

    for (let field in errors) {
        const input = modal.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('invalid-feedback');
            errorMessage.textContent = errors[field];
            input.parentNode.appendChild(errorMessage);
        }
    }
}

// Fonctions d'alerte (les mêmes que dans deleteRole.js)
function showSuccessAlert(message) {
    let alertContainer = document.getElementById("alert-container");
    let alertSuccess = document.createElement("div");
    alertSuccess.className = "custom-alert alert alert-success alert-dismissible fade show d-flex align-items-center";
    alertSuccess.role = "alert";
    alertSuccess.innerHTML = `
        <span class="alert-icon">✅</span>
        <div class="alert-content">
            <div class="alert-message">${message}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    alertContainer.appendChild(alertSuccess);
    setTimeout(() => {
        alertSuccess.classList.remove("show");
        setTimeout(() => alertSuccess.remove(), 500);
    }, 5000);
}

function showErrorAlert(message) {
    let alertContainer = document.getElementById("alert-container");
    let alertError = document.createElement("div");
    alertError.className = "custom-alert alert alert-danger alert-dismissible fade show d-flex align-items-center";
    alertError.role = "alert";
    alertError.innerHTML = `
        <span class="alert-icon">❌</span>
        <div class="alert-content">
            <div class="alert-message">${message}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    alertContainer.appendChild(alertError);
    setTimeout(() => {
        alertError.classList.remove("show");
        setTimeout(() => alertError.remove(), 500);
    }, 5000);
}

