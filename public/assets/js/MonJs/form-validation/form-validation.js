
// (function () {
//     'use strict';
//     window.addEventListener('click', function () {
//         var forms = document.querySelectorAll('.needs-validation');
//         console.log("test");
        
        
//         forms.forEach(function (form) {
//             // Écoute en temps réel pour chaque champ
//             var inputFields = form.querySelectorAll('input, textarea, select');
            
//             inputFields.forEach(function (input) {
//                 const validateInput = function() {
//                     if (input.checkValidity()) {
//                         input.classList.remove('is-invalid');
//                         input.classList.add('is-valid');
//                     } else {
//                         input.classList.remove('is-valid');
//                         input.classList.add('is-invalid');
//                     }
//                 };

//                 // Pour les inputs et textarea
//                 input.addEventListener('input', validateInput);
                
//                 // Pour les select
//                 if (input.tagName.toLowerCase() === 'select') {
//                     input.addEventListener('change', validateInput);
//                 }

//                 // Validation initiale
//                 validateInput();
//             });
            
//             // Gestion de la soumission du formulaire
//             form.addEventListener('submit', function (event) {
//                 event.preventDefault(); // Empêcher la soumission par défaut
                
//                 var isValid = true;
//                 inputFields.forEach(function (input) {
//                     if (!input.checkValidity()) {
//                         isValid = false;
//                         input.classList.add('is-invalid');
//                         input.classList.remove('is-valid');
//                     }
//                 });

//                 form.classList.add('was-validated');

//                 if (isValid) {
//                     // Si le formulaire est valide, le soumettre
//                     form.submit();
//                 }
//             });
//         });
//     });
// })();




(function () {
    'use strict';
    window.addEventListener('load', function () {  // Utilisez 'load' au lieu de 'click'
        var forms = document.querySelectorAll('.needs-validation');
        console.log("test");

        forms.forEach(function (form) {
            // Écoute en temps réel pour chaque champ
            var inputFields = form.querySelectorAll('input, textarea, select');

            inputFields.forEach(function (input) {
                const validateInput = function() {
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                };

                // Pour les inputs et textarea
                input.addEventListener('input', validateInput);

                // Pour les select
                if (input.tagName.toLowerCase() === 'select') {
                    input.addEventListener('change', validateInput);
                }

                // Supprimez l'appel initial : ne pas appeler validateInput() ici
                // validateInput();
            });

            // Gestion de la soumission du formulaire
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Empêcher la soumission par défaut

                var isValid = true;
                inputFields.forEach(function (input) {
                    if (!input.checkValidity()) {
                        isValid = false;
                        input.classList.add('is-invalid');
                        input.classList.remove('is-valid');
                    }
                });

                form.classList.add('was-validated');
                if (isValid) {
                    // Si le formulaire est valide, le soumettre
                    form.submit();
                }
            });
        });
    });
})();
