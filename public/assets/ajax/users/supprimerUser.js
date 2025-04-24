document.addEventListener("click", function (event) {
    // Gestion du clic sur l'icône de suppression
    if (event.target.classList.contains("delete-user-icon")) {
        if (confirm('Voulez-vous vraiment supprimer cet user ?')) {
            const deleteUrl = event.target.getAttribute('data-deleteUser-url');
            const csrf = event.target.getAttribute('data-csrf');

            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessAlert(data.success);

                    // Supprimer la ligne du rôle du tableau
                    const userRow = event.target.closest('tr'); // Trouver la ligne du rôle
                    if (userRow) {
                        userRow.remove(); // Supprimer la ligne du DOM
                    }
                } else if (data.danger) {
                    showErrorAlert(data.danger);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorAlert('Une erreur est survenue lors de la suppression du rôle');
            });
        }
    }
});

// Fonction pour afficher une alerte de succès
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

// Fonction pour afficher une alerte d'erreur
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
