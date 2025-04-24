document.getElementById("load-permission").addEventListener("click", function () {
    let permissionUrl = this.getAttribute('data-permission-url'); // Récupérer l'URL depuis l'attribut data
    let container = document.getElementById("blog-container");
    container.innerHTML = "<p>Chargement en cours...</p>";

    fetch(permissionUrl)
        .then(response => response.text())
        .then(data => {
            container.innerHTML = data;
            $(document).ready(function() {
                $('#permission-table').DataTable();
            });
        })
        .catch(error => {
            container.innerHTML = "<p style='color: red;'>Erreur de chargement !</p>";
            console.error("Erreur lors du chargement des rôles :", error);
        });
});
