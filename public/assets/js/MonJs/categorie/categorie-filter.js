
    // document.addEventListener('DOMContentLoaded', function() {
    //     const categoriesSlider = document.querySelector('.categories-slider');
    //     const nextButton = document.querySelector('.next-button');
    //     const prevButton = document.querySelector('.prev-button');
        
    //     if (categoriesSlider && nextButton && prevButton) {
    //         // Navigation de la barre de catégories
    //         nextButton.addEventListener('click', function() {
    //             const scrollDistance = Math.min(categoriesSlider.clientWidth * 0.8, 500);
    //             categoriesSlider.scrollBy({
    //                 left: scrollDistance,
    //                 behavior: 'smooth'
    //             });
    //         });
            
    //         prevButton.addEventListener('click', function() {
    //             const scrollDistance = Math.min(categoriesSlider.clientWidth * 0.8, 500);
    //             categoriesSlider.scrollBy({
    //                 left: -scrollDistance,
    //                 behavior: 'smooth'
    //             });
    //         });
            
    //         function updateNavButtons() {
    //             if (categoriesSlider.scrollLeft + categoriesSlider.clientWidth >= categoriesSlider.scrollWidth - 10) {
    //                 nextButton.style.display = 'none';
    //             } else {
    //                 nextButton.style.display = 'flex';
    //             }
                
    //             if (categoriesSlider.scrollLeft <= 10) {
    //                 prevButton.style.display = 'none';
    //             } else {
    //                 prevButton.style.display = 'flex';
    //             }
                
    //             if (categoriesSlider.scrollWidth <= categoriesSlider.clientWidth) {
    //                 nextButton.style.display = 'none';
    //                 prevButton.style.display = 'none';
    //             }
    //         }
            
    //         categoriesSlider.addEventListener('scroll', updateNavButtons);
    //         window.addEventListener('resize', updateNavButtons);
    //         updateNavButtons();
    //     }
        
    //     // AJOUT: Forcez la sélection de la première catégorie après le chargement complet
    //     window.addEventListener('load', function() {
    //         // Sélectionner explicitement la première catégorie
    //         const firstCategoryElement = document.querySelector('.category-item, .category-link, [data-category]');
    //         if (firstCategoryElement) {
    //             console.log("Première catégorie trouvée :", firstCategoryElement);
                
    //             // Simuler un clic sur la première catégorie
    //             setTimeout(() => {
    //                 firstCategoryElement.click();
    //                 console.log("Clic sur première catégorie déclenché");
    //             }, 300);
    //         } else {
    //             console.warn("Aucune catégorie trouvée sur la page");
    //         }
    //     });
    // });