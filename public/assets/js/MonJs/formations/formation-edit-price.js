// Gestion du type de formation (payante/gratuite)
const typeSelect = document.getElementById('type');
const priceContainer = document.getElementById('priceContainer');
const discountContainer = document.getElementById('discountContainer');
const finalPriceContainer = document.getElementById('finalPriceContainer');
const priceInput = document.getElementById('price');
const discountInput = document.getElementById('discount');

function togglePriceFields() {
    if (typeSelect.value === 'payante') {
        priceContainer.style.display = 'flex';
        discountContainer.style.display = 'flex';
        finalPriceContainer.style.display = 'flex';
        priceInput.required = true;
        calculateFinalPrice();
    } else {
        priceContainer.style.display = 'none';
        discountContainer.style.display = 'none';
        finalPriceContainer.style.display = 'none';
        priceInput.required = false;
        priceInput.value = '0.000';
        document.getElementById('final_price').value = '0.000';
    }
}

// Calcul du prix final
function calculateFinalPrice() {
    if (typeSelect.value === 'payante') {
        const price = parseFloat(priceInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        
        // Calcul du prix après remise
        const finalPrice = price * (1 - discount / 100);
        
        // Affichage
        document.getElementById('originalPriceDisplay').textContent = price.toFixed(3) + ' DT';
        document.getElementById('finalPriceDisplay').textContent = finalPrice.toFixed(3) + ' DT';
        document.getElementById('final_price').value = finalPrice.toFixed(3);
    }
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    togglePriceFields();
    
    // Écouteur pour le changement de type
    typeSelect.addEventListener('change', togglePriceFields);

    // Écouteurs pour les changements de prix et remise
    priceInput.addEventListener('input', calculateFinalPrice);
    discountInput.addEventListener('input', calculateFinalPrice);

    // Formatage automatique du prix
    priceInput.addEventListener('blur', function() {
        let value = parseFloat(this.value);
        if (!isNaN(value)) {
            this.value = value.toFixed(3);
            calculateFinalPrice();
        }
    });

    // Gestion de la date de publication
    const publishNowRadio = document.getElementById('publishNow');
    const publishLaterRadio = document.getElementById('publishLater');
    const publishDateContainer = document.getElementById('publishDateContainer');
    const publishDateInput = document.getElementById('publish_date');

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

    // Écouteurs d'événements
    publishNowRadio.addEventListener('change', togglePublishDate);
    publishLaterRadio.addEventListener('change', togglePublishDate);
    togglePublishDate(); // Initial state

    // Calcul initial du prix final
    calculateFinalPrice();
});