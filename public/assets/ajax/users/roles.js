// document.addEventListener("DOMContentLoaded", function () {
//     const fetchConfig = {
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
//             'Accept': 'application/json',
//             'X-Requested-With': 'XMLHttpRequest'
//         }
//     };

//     let lastLoadedRolesUrl = null;

//     document.addEventListener("click", function (event) {
//         if (event.target.matches("#RoleUser")) {
//             event.preventDefault();
//             const url = event.target.getAttribute('data-edit-url');
//             if (url) {
//                 lastLoadedRolesUrl = url;
//                 loadRolesPage(url);
//             }
//         }

//         // Gestion des clics sur les icônes de suppression
//         if (event.target.closest('.delete-btn')) {
//             event.preventDefault();
//             event.stopPropagation();
//             if (confirm('Voulez-vous vraiment supprimer cet élément ?')) {
//                 const form = event.target.closest('form');
//                 if (form) {
//                     handleFormSubmit(form);
//                 }
//             }
//         }
//     });

//     function loadRolesPage(url) {
//         if (!url) return;

//         const container = document.getElementById("blog-container");
//         if (!container) return;

//         container.innerHTML = "<p>Chargement en cours...</p>";

//         fetch(url, { ...fetchConfig, method: 'GET' })
//             .then(response => response.text())
//             .then(html => {
//                 container.innerHTML = html;
//                 attachFormEvents();
//             })
//             .catch(error => {
//                 container.innerHTML = `<p class="text-danger">Erreur de chargement : ${error.message}</p>`;
//             });
//     }

//     function attachFormEvents() {
//         const forms = document.querySelectorAll("form[action*='roles'], form[action*='permissions']");
//         forms.forEach(form => {
//             form.removeEventListener("submit", handleFormSubmission);
//             form.addEventListener("submit", handleFormSubmission);
//         });
//     }

//     function handleFormSubmission(event) {
//         event.preventDefault();
//         handleFormSubmit(event.target);
//     }

//     function handleFormSubmit(form) {
//         const formData = new FormData(form);
//         const method = form.getAttribute('method').toUpperCase();
//         const url = form.getAttribute('action');

//         if (!url) return;

//         if (method === 'DELETE') {
//             formData.append('_method', 'DELETE');
//         }

//         fetch(url, {
//             ...fetchConfig,
//             method: method === 'DELETE' ? 'POST' : method,
//             body: formData
//         })
//         .then(response => {
//             if (!response.ok) {
//                 return response.json().then(data => Promise.reject(data));
//             }
//             return response.json();
//         })
//         .then(data => {
//             // Afficher une alerte de succès ou d'erreur en fonction de la réponse
//             if (data.danger) {
//                 showAlert(data.message, 'danger'); // Afficher une alerte d'erreur
//             } else if (data.success) {
//                 showAlert(data.message, 'success'); // Afficher une alerte de succès
//             } else {
//                 showAlert(data.message); // Par défaut, afficher une alerte de succès
//             }

//             // Recharger la page après un délai
//             if (lastLoadedRolesUrl) {
//                 setTimeout(() => loadRolesPage(lastLoadedRolesUrl), 1000);
//             } else {
//                 const roleUserButton = document.querySelector("#RoleUser");
//                 const fallbackUrl = roleUserButton?.getAttribute('data-edit-url');
//                 if (fallbackUrl) {
//                     setTimeout(() => loadRolesPage(fallbackUrl), 1000);
//                 }
//             }
//         })
//         .catch(error => {
//             const message = error.message || 'Une erreur est survenue';
//             showAlert(message, 'danger'); // Afficher une alerte d'erreur
//         });
//     }

//     function showAlert(message, type = 'success') {
//         const alertContainer = document.getElementById("alert-container");
//         if (!alertContainer) {
//             console.error("Container d'alerte non trouvé");
//             return;
//         }

//         // Créer l'élément d'alerte
//         const alert = document.createElement("div");
//         alert.className = `custom-alert alert alert-${type} alert-dismissible fade show d-flex align-items-center`;
//         alert.innerHTML = `
//             <span class="alert-icon">${type === 'success' ? '✅' : '❌'}</span>
//             <div class="alert-content">
//                 <div class="alert-message">${message}</div>
//             </div>
//             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//         `;

//         // Ajouter l'alerte au conteneur
//         alertContainer.appendChild(alert);

//         // Auto-suppression après 5 secondes
//         setTimeout(() => {
//             alert.classList.remove("show");
//             setTimeout(() => alert.remove(), 500);
//         }, 5000);
//     }

//     // Attacher les événements initiaux
//     attachFormEvents();
// });




// document.addEventListener("DOMContentLoaded", function () {
//     const fetchConfig = {
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
//             'Accept': 'application/json',
//             'X-Requested-With': 'XMLHttpRequest'
//         }
//     };

//     let lastLoadedRolesUrl = null;

//     document.addEventListener("click", function (event) {
//         // Modification pour cibler l'icône au lieu du lien
//         if (event.target.matches(".edit-user-icon")) {
//             event.preventDefault();
//             const url = event.target.getAttribute('data-editUser-url');
//             if (url) {
//                 lastLoadedRolesUrl = url;
//                 loadRolesPage(url);
//             }
//         }

//         // Gestion des clics sur les icônes de suppression
//         if (event.target.closest('.delete-btn')) {
//             event.preventDefault();
//             event.stopPropagation();
//             if (confirm('Voulez-vous vraiment supprimer cet élément ?')) {
//                 const form = event.target.closest('form');
//                 if (form) {
//                     handleFormSubmit(form);
//                 }
//             }
//         }
//     });

//     function loadRolesPage(url) {
//         if (!url) return;

//         const container = document.getElementById("blog-container");
//         if (!container) return;

//         container.innerHTML = "<p>Chargement en cours...</p>";

//         fetch(url, { ...fetchConfig, method: 'GET' })
//             .then(response => response.text())
//             .then(html => {
//                 container.innerHTML = html;
//                 attachFormEvents();
//             })
//             .catch(error => {
//                 container.innerHTML = `<p class="text-danger">Erreur de chargement : ${error.message}</p>`;
//             });
//     }

//     function attachFormEvents() {
//         const forms = document.querySelectorAll("form[action*='roles'], form[action*='permissions']");
//         forms.forEach(form => {
//             form.removeEventListener("submit", handleFormSubmission);
//             form.addEventListener("submit", handleFormSubmission);
//         });
//     }

//     function handleFormSubmission(event) {
//         event.preventDefault();
//         handleFormSubmit(event.target);
//     }

//     function handleFormSubmit(form) {
//         const formData = new FormData(form);
//         const method = form.getAttribute('method').toUpperCase();
//         const url = form.getAttribute('action');

//         if (!url) return;

//         if (method === 'DELETE') {
//             formData.append('_method', 'DELETE');
//         }

//         fetch(url, {
//             ...fetchConfig,
//             method: method === 'DELETE' ? 'POST' : method,
//             body: formData
//         })
//         .then(response => {
//             if (!response.ok) {
//                 return response.json().then(data => Promise.reject(data));
//             }
//             return response.json();
//         })
//         .then(data => {
//             // Afficher une alerte de succès ou d'erreur en fonction de la réponse
//             if (data.danger) {
//                 showAlert(data.message, 'danger'); // Afficher une alerte d'erreur
//             } else if (data.success) {
//                 showAlert(data.message, 'success'); // Afficher une alerte de succès
//             } else {
//                 showAlert(data.message); // Par défaut, afficher une alerte de succès
//             }

//             // Recharger la page après un délai en utilisant l'icône
//             if (lastLoadedRolesUrl) {
//                 setTimeout(() => loadRolesPage(lastLoadedRolesUrl), 1000);
//             } else {
//                 const editIcon = document.querySelector(".edit-user-icon");
//                 const fallbackUrl = editIcon?.getAttribute('data-editUser-url');
//                 if (fallbackUrl) {
//                     setTimeout(() => loadRolesPage(fallbackUrl), 1000);
//                 }
//             }
//         })
//         .catch(error => {
//             const message = error.message || 'Une erreur est survenue';
//             showAlert(message, 'danger'); // Afficher une alerte d'erreur
//         });
//     }

//     function showAlert(message, type = 'success') {
//         const alertContainer = document.getElementById("alert-container");
//         if (!alertContainer) {
//             console.error("Container d'alerte non trouvé");
//             return;
//         }

//         // Créer l'élément d'alerte
//         const alert = document.createElement("div");
//         alert.className = `custom-alert alert alert-${type} alert-dismissible fade show d-flex align-items-center`;
//         alert.innerHTML = `
//             <span class="alert-icon">${type === 'success' ? '✅' : '❌'}</span>
//             <div class="alert-content">
//                 <div class="alert-message">${message}</div>
//             </div>
//             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//         `;

//         // Ajouter l'alerte au conteneur
//         alertContainer.appendChild(alert);

//         // Auto-suppression après 5 secondes
//         setTimeout(() => {
//             alert.classList.remove("show");
//             setTimeout(() => alert.remove(), 500);
//         }, 5000);
//     }

//     // Attacher les événements initiaux
//     attachFormEvents();
// });



document.addEventListener("DOMContentLoaded", function () {
    const fetchConfig = {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    let lastLoadedRolesUrl = null;

    document.addEventListener("click", function (event) {
        if (event.target.matches(".edit-user-icon")) {
            event.preventDefault();
            const url = event.target.getAttribute('data-editUser-url');
            if (url) {
                lastLoadedRolesUrl = url;
                loadRolesPage(url);
            }
        }

        if (event.target.closest('.delete-btn')) {
            event.preventDefault();
            event.stopPropagation();
            if (confirm('Voulez-vous vraiment supprimer cet élément ?')) {
                const form = event.target.closest('form');
                if (form) {
                    handleFormSubmit(form);
                }
            }
        }
    });

    function loadRolesPage(url) {
        if (!url) return;

        const container = document.getElementById("blog-container");
        if (!container) return;

        container.innerHTML = "<p>Chargement en cours...</p>";

        fetch(url, { ...fetchConfig, method: 'GET' })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                attachFormEvents();
            })
            .catch(error => {
                container.innerHTML = `<p class="text-danger">Erreur de chargement : ${error.message}</p>`;
            });
    }

    function attachFormEvents() {
        const forms = document.querySelectorAll("form[action*='roles'], form[action*='permissions']");
        forms.forEach(form => {
            form.removeEventListener("submit", handleFormSubmission);
            form.addEventListener("submit", handleFormSubmission);
        });
    }

    function handleFormSubmission(event) {
        event.preventDefault();
        handleFormSubmit(event.target);
    }

    function handleFormSubmit(form) {
        const formData = new FormData(form);
        const method = form.getAttribute('method').toUpperCase();
        const url = form.getAttribute('action');

        if (!url) return;

        if (method === 'DELETE') {
            formData.append('_method', 'DELETE');
        }

        fetch(url, {
            ...fetchConfig,
            method: method === 'DELETE' ? 'POST' : method,
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => Promise.reject(data));
            }
            return response.json();
        })
        .then(data => {
            if (data.danger) {
                showAlert(data.message, 'danger');
            } else if (data.success) {
                showAlert(data.message, 'success');
                //Update the DOM instead of reloading the page.
                if(method === 'DELETE'){
                  //Remove the row from the table.
                  form.closest('tr').remove();
                } else {
                    //Reload the roles list.
                    loadRolesPage(lastLoadedRolesUrl);
                }
            } else {
                showAlert(data.message);
            }
        })
        .catch(error => {
            const message = error.message || 'Une erreur est survenue';
            showAlert(message, 'danger');
        });
    }

    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById("alert-container");
        if (!alertContainer) {
            console.error("Container d'alerte non trouvé");
            return;
        }

        const alert = document.createElement("div");
        alert.className = `custom-alert alert alert-${type} alert-dismissible fade show d-flex align-items-center`;
        alert.innerHTML = `
            <span class="alert-icon">${type === 'success' ? '✅' : '❌'}</span>
            <div class="alert-content">
                <div class="alert-message">${message}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        alertContainer.appendChild(alert);

        setTimeout(() => {
            alert.classList.remove("show");
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    }

    attachFormEvents();
});
