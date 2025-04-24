
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'affichage du prix selon le type
    const typeSelect = document.getElementById('type');
    const priceContainer = document.getElementById('priceContainer');
    const priceInput = document.getElementById('price');
    function displayFreeLabels() {
        // Sélectionner toutes les cartes de formation
        const formationCards = document.querySelectorAll('.card, .formation-item');

        formationCards.forEach(card => {
            // Vérifier si cette carte a un élément de prix ou un attribut de type
            const priceElement = card.querySelector('.card-price, .price');
            const typeElement = card.querySelector('[data-type]');
            const type = typeElement ? typeElement.getAttribute('data-type') : null;
            
            // Condition pour déterminer si la formation est gratuite
            const isZeroPrice = priceElement && (
                priceElement.textContent.trim() === '0.000' || 
                priceElement.textContent.trim() === '0.000 $US' || 
                priceElement.textContent.trim() === '0.00' || 
                priceElement.textContent.trim() === '0.00 $US'
            );
            const isTypeGratuite = type === 'gratuite';
            
            // Si la formation est gratuite (par le prix ou le type)
            if (isZeroPrice || isTypeGratuite) {
                // Masquer l'affichage du prix si nécessaire
                if (priceElement) {
                    priceElement.style.display = 'none';
                }
                
                // Créer et ajouter le badge "Gratuite"
                const freeLabel = document.createElement('div');
                freeLabel.className = 'free-badge';
                freeLabel.textContent = 'Gratuite';
                freeLabel.style.cssText = `
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background-color: #4CAF50;
                    color: white;
                    padding: 5px 10px;
                    border-radius: 4px;
                    font-weight: bold;
                    z-index: 10;
                `;
                
                // S'assurer que la carte a une position relative pour le positionnement absolu
                card.style.position = 'relative';
                card.appendChild(freeLabel);
            }
        });
    }
    
    // Exécuter la fonction après le chargement complet de la page
    displayFreeLabels();

    function togglePriceField() {
        if (typeSelect.value === 'payante') {
            priceContainer.style.display = 'flex';
            priceInput.required = true;
        } else {
            priceContainer.style.display = 'none';
            priceInput.required = false;
            priceInput.value = '';
        }
    }
    // Écouteur d'événement pour le changement de type
    if (typeSelect) {
        typeSelect.addEventListener('change', togglePriceField);
        // Initialisation au chargement
        togglePriceField();
        
        // Si ancienne valeur en session, afficher le champ correspondant
        if (document.querySelector('input[name="_old_input"]') && document.querySelector('input[name="_old_input"]').value === 'payante') {
            priceContainer.style.display = 'flex';
        }
    }

    // Formatage automatique du prix
    if (priceInput) {
        priceInput.addEventListener('blur', function() {
            let value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(3);
            }
        });
    }

    // Show/hide publication date based on radio button selection
    const publishNowRadio = document.getElementById('publishNow');
    const publishLaterRadio = document.getElementById('publishLater');
    const publishDateContainer = document.getElementById('publishDateContainer');
    const publishDateInput = document.getElementById('publish_date');

    if (publishNowRadio && publishLaterRadio && publishDateContainer) {
        function togglePublishDate() {
            if (publishLaterRadio.checked) {
                publishDateContainer.style.display = 'block';
                publishDateInput.required = true;
            } else {
                publishDateContainer.style.display = 'none';
                publishDateInput.required = false;
                publishDateInput.value = '';
            }
        }

        publishNowRadio.addEventListener('change', togglePublishDate);
        publishLaterRadio.addEventListener('change', togglePublishDate);

        // Initial state
        togglePublishDate();
    }

    // Image preview functionality
    const imageInput = document.getElementById('image');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const deleteImageBtn = document.getElementById('deleteImage');

    if (imageInput && imagePreviewContainer && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                };
                
                reader.readAsDataURL(file);
            }
        });
    }

    if (deleteImageBtn) {
        deleteImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.src = '#';
            imagePreviewContainer.classList.add('hidden');
        });
    }

    // Gérer le bouton switch pour le statut
    const statusToggle = document.getElementById('statusToggle');
    const statusLabel = document.getElementById('statusLabel');
    
    if (statusToggle && statusLabel) {
        statusToggle.addEventListener('change', function() {
            if (this.checked) {
                statusLabel.textContent = 'Publié';
            } else {
                statusLabel.textContent = 'Non publié';
            }
        });
        
        // Initialiser le texte du label au chargement
        if (statusToggle.checked) {
            statusLabel.textContent = 'Publié';
        } else {
            statusLabel.textContent = 'Non publié';
        }
    }
            
});

// Gestion des formations gratuites
// Fonction displayFreeLabels() :

// Parcourt toutes les cartes de formation (éléments avec les classes .card ou .formation-item).

// Vérifie si une formation est gratuite soit par son prix (0.000, 0.00, etc.) ou par son type (data-type="gratuite").

// Masque l'affichage du prix et ajoute un badge vert "Gratuite" en haut à droite de la carte.

// 2. Gestion dynamique du champ de prix
// Événement sur le sélecteur de type (#type) :

// Si le type est "payante", affiche le champ de prix et le rend obligatoire.

// Si le type est autre (probablement "gratuite"), masque le champ de prix et réinitialise sa valeur.

// 3. Formatage automatique du prix
// Événement blur sur le champ de prix (#price) :

// Lorsque l'utilisateur quitte le champ, le prix est formaté avec 3 décimales (ex: 10.500).

// 4. Gestion de la date de publication
// Boutons radio "Publier maintenant" (#publishNow) et "Publier plus tard" (#publishLater) :

// Si "Publier plus tard" est sélectionné, affiche le champ de date et le rend obligatoire.

// Si "Publier maintenant" est sélectionné, masque le champ et réinitialise sa valeur.

// 5. Prévisualisation d'image
// Chargement d'image (#image) :

// Lorsqu'un fichier image est sélectionné, il est affiché en prévisualisation dans #imagePreview.

// Un bouton "Supprimer" (#deleteImage) permet de réinitialiser l'image.

// 6. Bascule de statut (publié/non publié)
// Interrupteur (#statusToggle) :

// Change le texte du label (#statusLabel) entre "Publié" et "Non publié" selon l'état de l'interrupteur.

// Initialise le texte au chargement en fonction de l'état initial.

// Résumé des fonctionnalités principales :
// Affichage conditionnel : Gestion dynamique des champs (prix, date de publication) en fonction des sélections.

// Badge "Gratuite" : Ajout automatique d'un badge pour les formations gratuites.

// Formatage : Prix automatiquement formaté avec 3 décimales.

// Prévisualisation d'image : Aperçu de l'image uploadée.

// Gestion de statut : Bascule entre "Publié" et "Non publié".