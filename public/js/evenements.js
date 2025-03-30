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


document.querySelectorAll('.btn-detail').forEach(button => {
    button.addEventListener('click', async function() {
        const rideId = this.getAttribute('data-id');
        const modal = document.getElementById('modalDetails');
        const modalContent = document.getElementById('modalContent');
        
        if (!modal || !modalContent) {
            console.error('Éléments modaux introuvables');
            return;
        }

        // Afficher la modale et le loader
        modal.style.display = 'block';
        modalContent.innerHTML = '<div class="loader">Chargement...</div>';

        try {
            const response = await fetch(`index.php?page=ridedetails&id=${rideId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            const data = await response.json();
            
            if (!data.success) throw new Error(data.error || 'Erreur inconnue');
            
            // Construction du HTML détaillé
            modalContent.innerHTML = `
                <div class="modal-header">
                    <h2>Détails du trajet</h2>
                </div>
                <div class="modal-body">
                    <div class="driver-info">
                        <h3>Conducteur</h3>
                        <p><strong>${data.data.conducteur.pseudo}</strong></p>
                        <p>Note: ${data.data.conducteur.note_moyenne}/5</p>
                        <p>${data.data.conducteur.commentaire}</p>
                    </div>
                    
                    <div class="ride-info">
                        <h3>Trajet</h3>
                        <p><strong>Départ:</strong> ${data.data.trajet.depart}</p>
                        <p><strong>Arrivée:</strong> ${data.data.trajet.arrivee}</p>
                        <p><strong>Date:</strong> ${new Date(data.data.trajet.date).toLocaleDateString()}</p>
                        <p><strong>Heure:</strong> ${data.data.trajet.heure_depart.substring(0,5)}</p>
                        <p><strong>Prix:</strong> ${data.data.trajet.prix} crédits</p>
                    </div>
                    
                    <div class="car-info">
                        <h3>Véhicule</h3>
                        <p><strong>Marque:</strong> ${data.data.voiture.marque}</p>
                        <p><strong>Modèle:</strong> ${data.data.voiture.modele}</p>
                        <p><strong>Énergie:</strong> ${data.data.voiture.energie}</p>
                    </div>
                </div>
            `;
            
        } catch (error) {
            console.error('Erreur:', error);
            modalContent.innerHTML = `
                <div class="error">
                    <p>Erreur de chargement</p>
                    <p>${error.message}</p>
                </div>
            `;
        }
    });
});

// Gestion de la fermeture de la modale
document.querySelector('.close-modal').addEventListener('click', function() {
    document.getElementById('modalDetails').style.display = 'none';
});

// Fermer la modale en cliquant à l'extérieur
window.addEventListener('click', function(event) {
    const modal = document.getElementById('modalDetails');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});