

document.addEventListener("DOMContentLoaded", function() {
    let responseCountInput = document.getElementById("response_count");
    let reponsesContainer = document.getElementById("reponses-container");
    let form = document.getElementById("question-form");
    let submitBtn = document.getElementById("submit-btn");
    let errorMessage = document.getElementById("error-message");

    if (!responseCountInput || !reponsesContainer || !form || !submitBtn || !errorMessage) {
        console.error("Un ou plusieurs éléments du formulaire sont manquants.");
        return;
    }
    function generateResponseFields(responseCount) {
        reponsesContainer.innerHTML = "";
        for (let i = 0; i < responseCount; i++) {
            let reponseDiv = document.createElement("div");
            reponseDiv.classList.add("row", "mb-3", "align-items-center");
            reponseDiv.innerHTML = `
                <div class="col-md-8">
                    <label class="form-label">Réponse ${i + 1} <span class="text-danger">*</span></label>

                    <div class="input-group">

                        <input class="form-control response-input" type="text" name="reponses[${i}][content]" placeholder="Entrez la réponse ${i + 1}" required>
                        <div class="input-group-text">
                            <input type="hidden" name="reponses[${i}][is_correct]" value="0">
                            <input type="checkbox" name="reponses[${i}][is_correct]" value="1" class="form-check-input">
                        </div>
                    </div>
                    <div class="invalid-feedback">Ce champ de réponse est obligatoire.</div>
                </div>
            `;
            reponsesContainer.appendChild(reponseDiv);
        }
    
        // Ajouter un écouteur pour chaque champ généré
        let responseInputs = document.querySelectorAll(".response-input");
        responseInputs.forEach(input => {
            input.addEventListener("input", function() {
                if (this.value.trim() === "") {
                    this.classList.add("is-invalid");
                    this.classList.remove("is-valid");
                } else {
                    this.classList.remove("is-invalid");
                    this.classList.add("is-valid");
                }
            });
        });
    }

    // function generateResponseFields(responseCount) {
    //     reponsesContainer.innerHTML = "";
    //     for (let i = 0; i < responseCount; i++) {
    //         let reponseDiv = document.createElement("div");
    //         reponseDiv.classList.add("row", "mb-3");
    //         reponseDiv.innerHTML = `
    //             <div class="col-md-8">
    //                 <label class="form-label">Réponse ${i + 1} <span class="text-danger">*</span></label>
    //                 <input class="form-control response-input" type="text" name="reponses[${i}][content]" placeholder="Entrez la réponse ${i + 1}" required>
    //                 <div class="invalid-feedback">Ce champ de réponse est obligatoire.</div>
    //             </div>
    //             <div class="col-md-4 d-flex align-items-center">
    //                 <input type="hidden" name="reponses[${i}][is_correct]" value="0">
    //                 <input type="checkbox" name="reponses[${i}][is_correct]" value="1" class="form-check-input me-2">
    //             </div>
    //         `;
    //         reponsesContainer.appendChild(reponseDiv);
    //     }

    //     // Ajouter un écouteur pour chaque champ généré
    //     let responseInputs = document.querySelectorAll(".response-input");
    //     responseInputs.forEach(input => {
    //         input.addEventListener("input", function() {
    //             if (this.value.trim() === "") {
    //                 this.classList.add("is-invalid");
    //                 this.classList.remove("is-valid");
    //             } else {
    //                 this.classList.remove("is-invalid");
    //                 this.classList.add("is-valid");
    //             }
    //         });
    //     });
    // }

    let initialResponseCount = parseInt(responseCountInput.dataset.initial || 1);
    if (initialResponseCount > 0) {
        generateResponseFields(initialResponseCount);
    }

    responseCountInput.addEventListener("change", function() {
        generateResponseFields(parseInt(this.value));
    });

    submitBtn.addEventListener("click", function(event) {
        event.preventDefault();
        let isValid = true;
        let errorMessages = [];

        let statementInput = document.getElementById("statement");
        if (statementInput.value.trim() === "") {
            isValid = false;
            errorMessages.push("Veuillez entrer un énoncé valide.");
            statementInput.classList.add("is-invalid");
            statementInput.classList.remove("is-valid");
        } else {
            statementInput.classList.remove("is-invalid");
            statementInput.classList.add("is-valid");
        }

        // Vérifier si un quiz est sélectionné (soit via select, soit via hidden input)
        let quizIdInput = document.getElementById("quiz_id");
        let quizSelected = false;
        
        if (quizIdInput) {
            // Si le select existe
            if (quizIdInput.value === "") {
                isValid = false;
                errorMessages.push("Veuillez sélectionner un quiz valide.");
                quizIdInput.classList.add("is-invalid");
                quizIdInput.classList.remove("is-valid");
            } else {
                quizIdInput.classList.remove("is-invalid");
                quizIdInput.classList.add("is-valid");
                quizSelected = true;
            }
        } else {
            // Si le select n'existe pas, vérifier l'input hidden
            let hiddenQuizId = document.getElementById("hidden_quiz_id");
            if (hiddenQuizId && hiddenQuizId.value !== "") {
                quizSelected = true;
            } else {
                isValid = false;
                errorMessages.push("Aucun quiz n'est sélectionné.");
            }
        }

        let checkboxes = form.querySelectorAll('input[type="checkbox"]:checked');
        if (checkboxes.length === 0) {
            isValid = false;
            errorMessages.push("Veuillez cocher au moins une réponse comme correcte.");
        }

        let responseFields = form.querySelectorAll('input[name^="reponses"][name$="[content]"]');
        let hasEmptyResponse = false;
        responseFields.forEach(function(input) {
            if (input.value.trim() === '') {
                isValid = false;
                hasEmptyResponse = true;
                input.classList.add("is-invalid");
                input.classList.remove("is-valid");
            } else {
                input.classList.remove("is-invalid");
                input.classList.add("is-valid");
            }
        });

        if (hasEmptyResponse) {
            errorMessages.push("Veuillez remplir tous les champs de réponse.");
        }

        if (!isValid) {
            errorMessage.innerHTML = errorMessages.join("<br>");
            errorMessage.classList.remove("d-none");

            // Masquer le message d'erreur après 2 secondes
            setTimeout(function() {
                errorMessage.classList.add("d-none");
            }, 2000);
        } else {
            form.submit();
        }
    });
});






















