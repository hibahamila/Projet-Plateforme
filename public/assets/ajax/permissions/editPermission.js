document.addEventListener("click", function (event) {
    // Gestion du clic sur l'icône de modification des permissions
    if (event.target.classList.contains("edit-permission-icon")) {
        let editUrl = event.target.getAttribute('data-edit-permission-url');
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
                const existingModal = document.querySelector('#EditPermissionModal');
                if (existingModal) {
                    existingModal.remove();
                }

                // Ajouter la nouvelle modale
                document.body.insertAdjacentHTML('beforeend', data);

                // Sélectionner la nouvelle modale
                const modal = document.querySelector('#EditPermissionModal');
                if (!modal) {
                    throw new Error('La modale n\'a pas été trouvée dans la réponse.');
                }

                // Initialiser la modale Bootstrap
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // Nettoyer après la fermeture
                modal.addEventListener('hidden.bs.modal', function () {
                    modal.remove();
                });

                // Gestion de la soumission du formulaire
                const editPermissionForm = modal.querySelector('form');
                if (editPermissionForm) {
                    editPermissionForm.addEventListener('submit', function (event) {
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
                                reloadPermissions(); // Recharger la liste des permissions
                            } else if (data.errors) {
                                // Afficher les erreurs de validation sous les champs
                                displayFormErrors(data.errors, modal);
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            showErrorAlert('Une erreur est survenue lors de la création de la permission.');
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


function displayFormErrors(errors, modal) {
    console.log('Début de displayFormErrors'); // Vérifiez si la fonction est appelée
    console.log('Erreurs reçues :', errors); // Affichez les erreurs reçues

    // Supprimer les messages d'erreur précédents
    const errorElements = modal.querySelectorAll('.invalid-feedback');
    errorElements.forEach(el => el.remove());

    // Réinitialiser les classes 'is-invalid' sur tous les champs
    const inputs = modal.querySelectorAll('input, select, textarea');
    inputs.forEach(input => input.classList.remove('is-invalid'));

    // Afficher les nouvelles erreurs
    for (let field in errors) {
        console.log(`Traitement du champ ${field}`); // Vérifiez chaque champ en erreur

        // Trouver le champ correspondant dans le formulaire
        const input = modal.querySelector(`[name="${field}"]`);
        if (input) {
            console.log(`Champ trouvé : ${field}`); // Vérifiez si le champ est trouvé

            // Ajouter la classe 'is-invalid' au champ
            input.classList.add('is-invalid');

            // Créer un message d'erreur
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('invalid-feedback');

            // Gérer les erreurs sous forme de chaîne ou de tableau
            if (typeof errors[field] === 'string') {
                errorMessage.textContent = errors[field]; // Utiliser directement la chaîne
            } else if (Array.isArray(errors[field])) {
                errorMessage.textContent = errors[field].join(', '); // Joindre les messages d'erreur si plusieurs
            }

            // Ajouter le message d'erreur après le champ
            const parent = input.parentElement;
            if (parent) {
                parent.appendChild(errorMessage);
            } else {
                console.error(`Le champ ${field} n'a pas de parent pour afficher l'erreur.`);
            }
        } else {
            console.error(`Champ non trouvé : ${field}`); // Affichez un message si le champ n'est pas trouvé
        }
    }

    console.log('Fin de displayFormErrors'); // Vérifiez si la fonction se termine correctement
}
// Fonction pour recharger les permissions
function reloadPermissions() {
    const loadPermissionsButton = document.getElementById("load-permission");
    if (loadPermissionsButton) {
        loadPermissionsButton.click(); // Déclenche le rechargement des permissions
    } else {
        console.error("Le bouton de rechargement des permissions n'a pas été trouvé.");
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
