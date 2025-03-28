document.addEventListener('DOMContentLoaded', function () {
    const selectFiltre = document.getElementById('filtre-covoiturages');
    
    selectFiltre.addEventListener('change', function () {
        const selectedValue = selectFiltre.value;

        // Récupérer toutes les cartes de covoiturage
        const covoiturages = document.querySelectorAll('.carte-covoiturage');
        
        // Afficher ou masquer les cartes en fonction du filtre sélectionné
        covoiturages.forEach(covoiturage => {
            // Réinitialisation de la visibilité de chaque carte
            let visible = true;

            // Si un filtre est sélectionné, on applique la logique de filtrage
            if (selectedValue) {
                // Vérification de chaque condition de filtrage
                switch (selectedValue) {
                    case 'ecologique':
                        visible = covoiturage.getAttribute('data-ecologique') === 'ecologique';
                        break;
                }
            }
            
            // Appliquer ou retirer la classe 'hidden' pour masquer ou afficher la carte
            if (visible) {
                covoiturage.style.display = 'block';
            } else {
                covoiturage.style.display = 'none';
            }
        });
    });
});
