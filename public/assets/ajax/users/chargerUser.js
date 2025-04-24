document.getElementById("load-users").addEventListener("click", function () {
    let userUrl = this.getAttribute('data-user-url'); 
    let container = document.getElementById("blog-container");
    container.innerHTML = "<p>Chargement en cours...</p>";

    fetch(userUrl)
        .then(response => response.text())
        .then(data => {
            container.innerHTML = data;
            $(document).ready(function() {
                $('#user-table').DataTable();
            });
        })
        .catch(error => {
            container.innerHTML = "<p style='color: red;'>Erreur de chargement !</p>";
            console.error("Erreur lors du chargement des users :", error);
        });
});
