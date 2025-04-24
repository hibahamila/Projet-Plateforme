document.addEventListener("click", function (event) {
    if (event.target.matches("#open-create-permission-modal")) {
        let createUrl = event.target.getAttribute('data-create-url');
        fetch(createUrl)
            .then(response => response.text())
            .then(data => {
                // Créer un conteneur pour la modale
                let modalContainer = document.createElement('div');
                modalContainer.innerHTML = data;
                document.body.appendChild(modalContainer);

                // Initialiser la modale Bootstrap
                let modal = modalContainer.querySelector('.modal');
                let bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // Supprimer la modale après fermeture
                modal.addEventListener('hidden.bs.modal', function () {
                    modalContainer.remove();
                });

                // Gérer la soumission AJAX du formulaire
                modal.querySelector('form').addEventListener('submit', function (event) {
                    event.preventDefault();

                    let formData = new FormData(this);

                    fetch(this.action, {
                        method: this.method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken,
                            'Accept': 'application/json', // Pour gérer les réponses JSON
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw err; // Lancer les erreurs de validation
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showSuccessAlert(data.success); // Afficher l'alerte de succès
                            bsModal.hide(); // Fermer la modale
                            document.getElementById("load-permission").click(); // Recharger la liste
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
            })
            .catch(error => console.error('Erreur lors du chargement de la modale :', error));
    }
});

// Fonction pour afficher les erreurs dans le formulaire
function displayFormErrors(errors, modal) {
    // Réinitialiser les erreurs précédentes
    const errorElements = modal.querySelectorAll('.invalid-feedback');
    errorElements.forEach(el => el.remove());

    // Réinitialiser les classes d'erreur sur les champs
    const inputs = modal.querySelectorAll('.is-invalid');
    inputs.forEach(input => input.classList.remove('is-invalid'));

    // Afficher les erreurs sous les champs
    for (let field in errors) {
        const input = modal.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid'); // Ajouter la classe d'erreur
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('invalid-feedback');
            errorMessage.textContent = errors[field][0]; // Afficher le premier message d'erreur
            input.parentNode.appendChild(errorMessage); // Ajouter le message d'erreur sous le champ
        }
    }
}

// Fonction générique pour afficher une alerte (success ou danger)
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

    // Fermeture automatique après 5 secondes
    setTimeout(() => {
        alertSuccess.classList.remove("show"); // Cache avec animation
        setTimeout(() => alertSuccess.remove(), 500); // Supprime après animation
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

    // Fermeture automatique après 5 secondes
    setTimeout(() => {
        alertError.classList.remove("show"); // Cache avec animation
        setTimeout(() => alertError.remove(), 500); // Supprime après animation
    }, 5000);
}
