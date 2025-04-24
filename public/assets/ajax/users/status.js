document.addEventListener("DOMContentLoaded", function () {
    // Attacher l'écouteur d'événement au document ou à un élément parent existant
    document.addEventListener("change", function (event) {
        // Vérifier si l'élément déclencheur est un bouton switch
        if (event.target && event.target.classList.contains("toggle-status")) {
            const switchElement = event.target;
            const userId = switchElement.getAttribute("data-user-id");
            const isChecked = switchElement.checked;

            fetch(`/admin/users/${userId}/toggle-status`, {
                method: "PATCH",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erreur réseau : " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Mettre à jour l'état du switch selon la réponse du serveur
                    switchElement.checked = data.status === 'active';
                    // Afficher un message de succès
                    showSuccessAlert(data.message);
                } else {
                    // Remettre le switch dans son état précédent
                    switchElement.checked = !isChecked;
                    showErrorAlert(data.message);
                }
            })
            .catch(error => {
                console.error("Erreur :", error);
                // Remettre le switch dans son état précédent
                switchElement.checked = !isChecked;
                showErrorAlert("Erreur lors de la communication avec le serveur");
            });
        }
    });
});

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
