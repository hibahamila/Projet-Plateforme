document.getElementById("load-roles").addEventListener("click", function () {
    let rolesUrl = this.getAttribute('data-roles-url'); // Récupérer l'URL depuis l'attribut data
    let container = document.getElementById("blog-container");
    container.innerHTML = "<p>Chargement en cours...</p>";

    fetch(rolesUrl)
        .then(response => response.text())
        .then(data => {
            container.innerHTML = data;
            $(document).ready(function() {
                $('#roles-table').DataTable();
            });
        })
        .catch(error => {
            container.innerHTML = "<p style='color: red;'>Erreur de chargement !</p>";
            console.error("Erreur lors du chargement des rôles :", error);
        });
});
