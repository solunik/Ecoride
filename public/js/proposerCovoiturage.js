document.addEventListener("DOMContentLoaded", () => {
    const rideFormContainer = document.getElementById("rideOfferForm");
    const vehiculeIdInput = document.getElementById("vehiculeId");
    const proposeRideForm = document.getElementById("proposeRideForm");
    const noVehiculeWarning = document.getElementById("noVehiculeWarning");
    const vehiculeChooseContainer = document.querySelector('.vehicule-choose-container');

    const vehiculeCards = document.querySelectorAll('.vehicule-card');

    if (vehiculeCards.length === 0) {
        if (noVehiculeWarning) noVehiculeWarning.style.display = "block";
        if (rideFormContainer) rideFormContainer.style.display = "none";
        return;
    }

    function resetVehiculeChoice() {
        vehiculeCards.forEach(card => card.style.display = "block");
        const summary = document.querySelector('.selected-vehicule-summary');
        if (summary) summary.remove();
        const changeButton = document.querySelector('#changeVehiculeBtn');
        if (changeButton) changeButton.remove();
        rideFormContainer.classList.remove('show');
        setTimeout(() => {
            rideFormContainer.style.display = 'none';
        }, 500);
        vehiculeIdInput.value = "";
    }

    document.querySelectorAll('.select-vehicule-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            const card = e.target.closest('.vehicule-card');
            const selectedVehiculeId = card.getAttribute('data-id');

            if (selectedVehiculeId) {
                vehiculeCards.forEach(c => {
                    if (c !== card) {
                        c.style.display = "none";
                    }
                });

                const summary = document.createElement('div');
                summary.classList.add('selected-vehicule-summary');
                summary.innerHTML = `
                    <h4>🚗 Véhicule sélectionné :</h4>
                    <p><strong>Modèle :</strong> ${card.querySelector('h4').textContent}</p>
                `;
                vehiculeChooseContainer.appendChild(summary);

                const changeButton = document.createElement('button');
                changeButton.id = "changeVehiculeBtn";
                changeButton.textContent = "Changer de véhicule";
                changeButton.classList.add('change-vehicule-btn');
                vehiculeChooseContainer.appendChild(changeButton);

                changeButton.addEventListener('click', resetVehiculeChoice);

                rideFormContainer.style.display = "block";
                setTimeout(() => {
                    rideFormContainer.classList.add('show');
                }, 50);

                vehiculeIdInput.value = selectedVehiculeId;
                window.scrollTo({ top: rideFormContainer.offsetTop - 100, behavior: 'smooth' });
            }
        });
    });

    proposeRideForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const submitButton = proposeRideForm.querySelector('button[type="submit"]');
        const originalButtonText = "Proposer le covoiturage"; // Remets ça après chaque soumission
        submitButton.disabled = true;
        submitButton.innerText = "Envoi...";

        const seatCount = parseInt(document.getElementById("seatCount").value, 10);
        if (isNaN(seatCount) || seatCount < 1 || seatCount > 5) {
            Swal.fire('Attention', 'Veuillez choisir un nombre de places valide entre 1 et 5.', 'warning');
            submitButton.disabled = false;
            submitButton.innerText = originalButtonText;
            return;
        }

        const today = new Date();
        const rideDateInput = document.getElementById("rideDate");
        const rideTimeInput = document.getElementById("rideTime");

        const selectedDate = new Date(rideDateInput.value);
        const [hours, minutes] = rideTimeInput.value.split(":");
        selectedDate.setHours(hours, minutes);

        const now = new Date();
        const minimumValidTime = new Date(now.getTime() + 60 * 60 * 1000); // +1h

        if (rideDateInput.value === now.toISOString().split('T')[0] && selectedDate < minimumValidTime) {
            await Swal.fire('Attention', "L'heure doit être au moins 1h après l'heure actuelle.", 'warning');
            submitButton.disabled = false;
            submitButton.innerText = originalButtonText;
            return;
        }

        const payload = {
            departureLocation: document.getElementById("departureLocation").value,
            arrivalLocation: document.getElementById("arrivalLocation").value,
            rideDate: rideDateInput.value,
            rideTime: rideTimeInput.value,
            seatCount: document.getElementById("seatCount").value,
            vehiculeId: vehiculeIdInput.value,
            pricePerPerson: document.getElementById("pricePerPerson").value
        };

        if (!payload.departureLocation || !payload.arrivalLocation || !payload.rideDate || !payload.rideTime || !payload.seatCount || !payload.vehiculeId) {
            Swal.fire('Attention', 'Veuillez remplir tous les champs requis.', 'warning');
            submitButton.disabled = false;
            submitButton.innerText = originalButtonText;
            return;
        }

        try {
            const response = await fetch("index.php?page=api_proposer_covoiturage", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    title: 'Bravo !',
                    text: 'Covoiturage proposé avec succès !',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    refreshHistorySection(); // 🆕
                    resetVehiculeChoice();     // 🆕 réinitialiser formulaire
                });        
            } else {
                Swal.fire('Erreur', result.message || "Impossible de proposer le covoiturage.", 'error');
                submitButton.disabled = false;
                submitButton.innerText = originalButtonText;
            }
            
        } catch (err) {
            console.error("Erreur lors de la requête :", err);
            Swal.fire('Erreur', 'Erreur technique.', 'error');
            submitButton.disabled = false;
            submitButton.innerText = originalButtonText;
        }
    });

    // Gestion de la suppression d'un covoiturage avec animation
    document.addEventListener('click', async (e) => {
        if (e.target.classList.contains('delete-ride-btn')) {
            const card = e.target.closest('.history-card');
            const rideId = card.getAttribute('data-id');

            if (!rideId) return;

            const confirmResult = await Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Ce covoiturage sera supprimé définitivement.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            });

            if (confirmResult.isConfirmed) {
                try {
                    const response = await fetch('index.php?page=api_delete_covoiturage', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `covoiturage_id=${rideId}`
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire('Supprimé !', 'Le covoiturage a été supprimé.', 'success');

                        // ➡️ Ajouter une transition avant suppression
                        card.style.transition = "opacity 0.5s, transform 0.5s";
                        card.style.opacity = 0;
                        card.style.transform = "translateX(100px)";
                        
                        setTimeout(() => {
                            card.remove();
                        }, 500); // Supprime après l'animation

                    } else {
                        Swal.fire('Erreur', result.message || 'Suppression impossible.', 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors de la suppression:', error);
                    Swal.fire('Erreur', 'Erreur technique.', 'error');
                }
            }
        }
    });

    async function refreshHistorySection() {
        try {
            const response = await fetch('index.php?page=api_get_user_covoiturages');
            const data = await response.json();
    
            if (data.success) {
                const historySection = document.getElementById('historySection');
                const historyListHTML = data.historique.map(covoiturage => `
                    <div class="history-card" data-id="${covoiturage.covoiturage_id}">
                        <div class="history-info">
                            <p><strong>📍 Départ :</strong> ${covoiturage.lieu_depart}</p>
                            <p><strong>🏁 Arrivée :</strong> ${covoiturage.lieu_arrivee}</p>
                            <p><strong>📅 Date :</strong> ${new Date(covoiturage.date_depart).toLocaleDateString('fr-FR')}</p>
                            <p><strong>⚙️ Statut :</strong> <span class="status ${covoiturage.statut}">${covoiturage.statut.charAt(0).toUpperCase() + covoiturage.statut.slice(1)}</span></p>
                        </div>
                        ${covoiturage.statut !== 'completed' ? `
                            <div class="history-actions">
                                <button class="delete-ride-btn">🗑️ Supprimer</button>
                            </div>` : ''
                        }
                    </div>
                `).join('');
    
                historySection.querySelector('.history-list').innerHTML = historyListHTML;
            } else {
                console.error('Erreur lors du rechargement historique :', data.message);
            }
        } catch (error) {
            console.error('Erreur technique refreshHistorySection:', error);
        }
    }
});
