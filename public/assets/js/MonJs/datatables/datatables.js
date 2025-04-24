$(document).ready(function() {
    // Initialisation de DataTable pour toutes les tables avec la classe .dataTable
    $('.dataTable').DataTable({
        // searching: false,  // Désactive la barre de recherche

        language: {
            url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json"

            // url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json" // Langue française
        },
        responsive: true,
        paging: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],  // Options de pagination
        pageLength: 10,
        order: [[0, 'asc']]  // Tri par défaut sur la première colonne
    });
    //zedtha tw

    table.columns.adjust().draw();

     // Filtrage par première lettre
     $('.filter-letter').on('click', function() {
        var letter = $(this).data('letter');

        if (letter === "") {
            table.search('').draw(); // Affiche tout si "Tous" est sélectionné
        } else {
            table.column(0).search('^' + letter, true, false).draw();
        }
    });

});