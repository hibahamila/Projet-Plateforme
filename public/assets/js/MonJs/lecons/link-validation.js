// Fonction de validation des liens
function validateLinks() {
    var linksField = document.getElementById("link");
    var links = linksField.value.trim(); // Supprimer les espaces avant et après la chaîne

    // Si le champ est vide, on accepte
    if (links === "") {
        linksField.setCustomValidity("");
        return true;
    }

    // Diviser les liens séparés par des sauts de ligne
    links = links.split('\n');  
    var valid = true;

    links.forEach(function(link) {
        var trimmedLink = link.trim(); // Supprimer les espaces autour de chaque lien
        if (trimmedLink === "") return; // Ignorer les entrées vides
        
        // Vérifier si le lien commence par http:// ou https://
        if (!trimmedLink.startsWith('http://') && !trimmedLink.startsWith('https://')) {
            valid = false;
        }
    });

    // Si des liens invalides sont trouvés
    if (!valid) {
        linksField.setCustomValidity("Veuillez entrer des liens valides (commençant par http:// ou https://).");
        return false;
    }

    // Si tous les liens sont valides, réinitialiser la validation personnalisée
    linksField.setCustomValidity(""); 
    return true;
}

// Valider les liens lors de la saisie
document.getElementById("link").addEventListener("input", validateLinks);

// Ajout d'un écouteur d'événement sur le formulaire pour valider avant la soumission
document.querySelector("form").addEventListener("submit", function(event) {
    if (!validateLinks()) {
        event.preventDefault();  // Empêcher la soumission du formulaire si les liens ne sont pas valides
    }
});
