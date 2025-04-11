document.addEventListener('DOMContentLoaded', () => {

    // === Sélection des éléments ===
    const passengerBtn = document.getElementById('passengerBtn');
    const driverBtn = document.getElementById('driverBtn');
    const passengerSection = document.getElementById('passengerSection');
    const driverSection = document.getElementById('driverSection');
    const driverFormSection = document.getElementById('driverFormSection');
    const rideOfferForm = document.getElementById('rideOfferForm');
    const updateUserForm = document.getElementById('updateUserForm');
    const addVehicleForm = document.getElementById('addVehicleForm');
    const proposeRideForm = document.getElementById('proposeRideForm');
    const historicalRides = document.getElementById('historicalRides');

    // === Vérifier si les éléments existent avant d'ajouter des événements ===
    if (passengerBtn) {
        passengerBtn.addEventListener('click', () => {
            showSection(passengerSection);
            hideSection(driverSection);
            loadPassengerData();
        });
    }

    if (driverBtn) {
        driverBtn.addEventListener('click', () => {
            showSection(driverSection);
            hideSection(passengerSection);
            checkDriverRole();
        });
    }

    // === Fonctions utilitaires ===
    function showSection(section) {
        if (section) {
            section.style.display = 'block';
        }
    }

    function hideSection(section) {
        if (section) {
            section.style.display = 'none';
        }
    }

    // === Chargement des données passager ===
    function loadPassengerData() {
        if (updateUserForm) {
            updateUserForm.reset();
        }
        if (historicalRides) {
            historicalRides.innerHTML = "<p>Chargement des historiques...</p>";
            setTimeout(() => {
                historicalRides.innerHTML = `
                    <ul>
                        <li>Covoiturage 1 - Paris -> Lyon</li>
                        <li>Covoiturage 2 - Marseille -> Toulouse</li>
                    </ul>
                `;
            }, 1000);
        }
    }

    // === Vérifie si l'utilisateur est conducteur ===
    function checkDriverRole() {
        const userHasDriverRole = false; // À remplacer par une vraie vérification côté serveur

        if (userHasDriverRole) {
            showSection(rideOfferForm);
            hideSection(driverFormSection);
        } else {
            showSection(driverFormSection);
            hideSection(rideOfferForm);
        }
    }

    // === Soumission du formulaire d'ajout de véhicule ===
    if (addVehicleForm) {
        addVehicleForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(addVehicleForm);

            fetch('/api/add-vehicle', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message || "Véhicule ajouté !");
                addVehicleForm.reset();
            })
            .catch(error => console.error('Erreur:', error));
        });
    }

    // === Soumission du formulaire de mise à jour utilisateur ===
    if (updateUserForm) {
        updateUserForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(updateUserForm);

            fetch('/api/update-user', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message || 'Informations mises à jour.');
            })
            .catch(error => console.error('Erreur:', error));
        });
    }

    // === Soumission du formulaire de covoiturage ===
    if (proposeRideForm) {
        proposeRideForm.addEventListener('submit', (e) => {
            e.preventDefault();
            // Ajoute ici ta logique de soumission réelle
            console.log('Covoiturage proposé');
        });
    }

});
