document.addEventListener('DOMContentLoaded', function () {
    const selectFiltre = document.getElementById('filtre-covoiturages');

    // Vérifie si l'élément existe avant d'ajouter l'event listener
    if (selectFiltre) {
        selectFiltre.addEventListener('change', function () {
            const selectedValue = selectFiltre.value;

            // Récupérer toutes les cartes de covoiturage
            const covoiturages = document.querySelectorAll('.carte-covoiturage');

            covoiturages.forEach(covoiturage => {
                let visible = true;

                if (selectedValue) {
                    switch (selectedValue) {
                        case 'ecologique':
                            visible = covoiturage.getAttribute('data-ecologique') === 'ecologique';
                            break;
                    }
                }

                covoiturage.style.display = visible ? 'block' : 'none';
            });
        });
    } else {
        console.warn("⚠️ Aucun filtre trouvé sur cette page.");
    }

    // Gestion du menu burger
    const menuToggle = document.getElementById("menu-toggle");
    const menuMobile = document.getElementById("menu-mobile");

    if (menuToggle && menuMobile) {
        menuToggle.addEventListener("click", function () {
            menuMobile.style.display = (menuMobile.style.display === "block") ? "none" : "block";
        });
    } else {
        console.warn("⚠️ Menu toggle ou menu mobile introuvable sur cette page.");
    }
});
