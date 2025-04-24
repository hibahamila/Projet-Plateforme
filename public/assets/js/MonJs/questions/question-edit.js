
document.addEventListener("DOMContentLoaded", function () {
    let responseCountInput = document.getElementById("response_count");
    let reponsesContainer = document.getElementById("reponses-container");
    let existingResponses = JSON.parse(document.getElementById("existing-responses")?.textContent || "[]");
    let errorMessage = document.getElementById("error-message");
    let dynamicResponsesInput = document.getElementById("dynamic-responses");

    // Fonction pour vider le conteneur des réponses
    function clearResponseFields() {
        reponsesContainer.innerHTML = ''; // Vide le conteneur des réponses
    }

    // Fonction pour ajouter un champ de réponse
    function addResponseField(index, existingReponse = { content: "", is_correct: 0, id: "" }) {
        let reponseDiv = document.createElement("div");
        reponseDiv.classList.add("mb-3", "d-flex", "align-items-center", "reponse-item");

        reponseDiv.innerHTML = `
            <input type="text" name="reponses[${index}][content]" class="form-control me-2 response-input"
                   value="${existingReponse.content}" required>
            <input type="hidden" name="reponses[${index}][id]" value="${existingReponse.id}">
            <input type="hidden" name="reponses[${index}][is_correct]" value="0">
            <input type="checkbox" name="reponses[${index}][is_correct]" value="1" ${existingReponse.is_correct ? 'checked' : ''}>
            <button type="button" class="ms-2 remove-btn" style="background: none; border: none; padding: 0; color: red;">
                <i class="fas fa-trash"></i>
            </button>
            <div class="invalid-feedback">Veuillez entrer une réponse valide.</div>
        `;

        reponsesContainer.appendChild(reponseDiv);

        // Ajouter un écouteur d'événement pour mettre à jour la valeur de is_correct
        let checkbox = reponseDiv.querySelector('input[type="checkbox"]');
        checkbox.addEventListener("change", function () {
            existingReponse.is_correct = this.checked ? 1 : 0;
            updateDynamicResponsesInput();
            checkAtLeastOneChecked();  // Vérification si au moins une réponse est correcte
        });

        // Ajouter un écouteur d'événement pour mettre à jour dynamicResponsesInput lors de la modification du texte
        let textInput = reponseDiv.querySelector('input[type="text"]');
        textInput.addEventListener("input", function () {
            updateDynamicResponsesInput();
        });

        updateRemoveButtons();
        updateDynamicResponsesInput();
    }

    // Vérification qu'au moins une case est cochée
    function checkAtLeastOneChecked() {
        let atLeastOneChecked = false;
        document.querySelectorAll('input[type="checkbox"][name*="[is_correct]"]').forEach(checkbox => {
            if (checkbox.checked) {
                atLeastOneChecked = true;
            }
        });

        if (!atLeastOneChecked) {
            errorMessage.style.display = "block";
            errorMessage.textContent = "Vous devez sélectionner au moins une réponse correcte.";
        } else {
            errorMessage.style.display = "none";
        }
    }

    function updateRemoveButtons() {
        document.querySelectorAll(".remove-btn").forEach(button => {
            button.onclick = function () {
                if (document.querySelectorAll(".reponse-item").length > 1) {
                    this.parentElement.remove();
                    reindexResponseFields();
                    responseCountInput.value = document.querySelectorAll(".reponse-item").length;
                    updateDynamicResponsesInput();
                }
            };
        });
    }

    function reindexResponseFields() {
        document.querySelectorAll(".reponse-item").forEach((item, index) => {
            item.querySelector('input[name*="[content]"]').setAttribute("name", `reponses[${index}][content]`);
            item.querySelector('input[name*="[id]"]').setAttribute("name", `reponses[${index}][id]`);
            item.querySelector('input[name*="[is_correct]"]').setAttribute("name", `reponses[${index}][is_correct]`);
            item.querySelector('input[type="checkbox"]').setAttribute("name", `reponses[${index}][is_correct]`);
        });
    }

    function updateDynamicResponsesInput() {
        let responses = [];
        document.querySelectorAll(".reponse-item").forEach((item, index) => {
            let content = item.querySelector('input[name*="[content]"]').value;
            let id = item.querySelector('input[name*="[id]"]').value;
            let is_correct = item.querySelector('input[type="checkbox"]').checked ? 1 : 0;
            responses.push({ content, id, is_correct });
        });
        dynamicResponsesInput.value = JSON.stringify(responses);
    }

    function generateResponseFields(responseCount) {
        let currentFields = document.querySelectorAll(".reponse-item").length;

        for (let i = currentFields; i < responseCount; i++) {
            addResponseField(i);
        }

        while (document.querySelectorAll(".reponse-item").length > responseCount) {
            reponsesContainer.lastChild.remove();
        }

        responseCountInput.value = responseCount;
        updateDynamicResponsesInput();
    }

    // Vider les réponses existantes avant de générer de nouvelles réponses
    clearResponseFields();

    // Charger les réponses existantes ou dynamiques
    if (dynamicResponsesInput.value) {
        let dynamicResponses = JSON.parse(dynamicResponsesInput.value);
        dynamicResponses.forEach((reponse, index) => {
            addResponseField(index, reponse);
        });
    } else if (existingResponses.length > 0) {
        existingResponses.forEach((reponse, index) => {
            addResponseField(index, reponse);
        });
    }

    responseCountInput.addEventListener("change", function () {
        let responseCount = parseInt(this.value);
        if (responseCount < 1) responseCount = 1;
        generateResponseFields(responseCount);
    });

    document.querySelector("form").addEventListener("submit", function (event) {
        let isEmptyField = false;
        let isNoChecked = false;

        // Mettre à jour dynamicResponsesInput avant la validation
        updateDynamicResponsesInput();

        // Vérification des champs vides
        document.querySelectorAll(".response-input").forEach(input => {
            input.classList.remove("is-invalid");
        });
        document.querySelectorAll(".invalid-feedback").forEach(feedback => {
            feedback.style.display = "none";
        });

        document.querySelectorAll(".response-input").forEach(input => {
            if (!input.value.trim()) {
                input.classList.add("is-invalid");
                input.parentElement.querySelector(".invalid-feedback").style.display = "block";
                isEmptyField = true;
            }
        });

        // Vérification des cases cochées
        checkAtLeastOneChecked();

        // Empêcher la soumission du formulaire si des erreurs sont présentes
        if (isEmptyField || errorMessage.style.display === "block") {
            event.preventDefault();
        }
    });
});
