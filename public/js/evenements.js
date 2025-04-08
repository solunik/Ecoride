document.addEventListener('DOMContentLoaded', function () {
    // Gestion du menu burger uniquement
    const menuToggle = document.getElementById("menu-toggle");
    const menuMobile = document.getElementById("menu-mobile");

    if (menuToggle && menuMobile) {
        menuToggle.addEventListener("click", function () {
            // Basculle entre affiché et caché
            menuMobile.style.display = (menuMobile.style.display === "block") ? "none" : "block";
        });
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

// Fermeture Modale Détails
document.querySelector('#modalDetails .close-modal').addEventListener('click', function() {
    document.getElementById('modalDetails').style.display = 'none';
  });

// Fermer la modale en cliquant à l'extérieur
window.addEventListener('click', function(event) {
    const modal = document.getElementById('modalDetails');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

// Gestion de la modale de filtres
document.addEventListener('DOMContentLoaded', function () {
    // Éléments de la modale
    const modalFiltres = document.getElementById('modalFiltres');
    const btnFiltres = document.getElementById('btn-filtres-avances');
    const btnFermer = document.querySelector('#modalFiltres .close-modal');
    const btnAppliquer = document.getElementById('btn-appliquer-filtres');
    const btnReinitialiser = document.getElementById('btn-reinitialiser-filtres');
    
    // Sliders avec affichage de la valeur
    const filtrePrix = document.getElementById('filtre-prix');
    const prixValue = document.getElementById('prix-value');
    const filtreDuree = document.getElementById('filtre-duree');
    const dureeValue = document.getElementById('duree-value');
    const filtreNote = document.getElementById('filtre-note');
    const noteValue = document.getElementById('note-value');
    
    // Ouvrir la modale
    if (btnFiltres) {
        btnFiltres.addEventListener('click', function() {
            modalFiltres.style.display = 'block';
        });
    }
    
    // Fermer la modale
    if (btnFermer) {
        btnFermer.addEventListener('click', function() {
            modalFiltres.style.display = 'none';
        });
    }
    
    // Fermer en cliquant à l'extérieur
    window.addEventListener('click', function(event) {
        if (event.target === modalFiltres) {
            modalFiltres.style.display = 'none';
        }
    });
    
    // Mise à jour des valeurs des sliders
    if (filtrePrix && prixValue) {
        filtrePrix.addEventListener('input', function() {
            prixValue.textContent = this.value;
        });
    }
    
    if (filtreDuree && dureeValue) {
        filtreDuree.addEventListener('input', function() {
            dureeValue.textContent = this.value;
        });
    }
    
    if (filtreNote && noteValue) {
        filtreNote.addEventListener('input', function() {
            noteValue.textContent = this.value;
        });
    }
    
    // Appliquer les filtres
    if (btnAppliquer) {
        btnAppliquer.addEventListener('click', function() {
            appliquerFiltres();
            modalFiltres.style.display = 'none';
        });
    }
    
    // Réinitialiser les filtres
    if (btnReinitialiser) {
        btnReinitialiser.addEventListener('click', function() {
            document.getElementById('form-filtres').reset();
            prixValue.textContent = '50';
            dureeValue.textContent = '24';
            noteValue.textContent = '0';
            appliquerFiltres(); // Pour tout réafficher
        });
    }
    
    function appliquerFiltres() {
        const filtreEcologique = document.getElementById('filtre-ecologique').checked ? 'ecologique' : '';
        const prixMax = parseFloat(document.getElementById('filtre-prix').value);
        const dureeMax = parseFloat(document.getElementById('filtre-duree').value);
        const noteMin = parseFloat(document.getElementById('filtre-note').value);
    
        document.querySelectorAll('.carte-covoiturage').forEach(covoiturage => {
            // 1. Filtre écologique
            const estEcologique = covoiturage.dataset.ecologique === 'ecologique';
    
            // 2. Extraction du prix (depuis data-attribute)
            const prix = parseFloat(covoiturage.dataset.prix) || 0;
    
            // 3. Extraction de la note (depuis data-attribute)
            const note = parseFloat(covoiturage.dataset.note) || 0;
    
            // 4. Calcul de la durée
            const heureDepartText = covoiturage.querySelector('.heure-depart')?.textContent.match(/\d{2}:\d{2}/)?.[0] || '';
            const heureArriveeText = covoiturage.querySelector('.heure-arrivee')?.textContent.match(/\d{2}:\d{2}/)?.[0] || '';
            
            let duree = 0;
            if (heureDepartText && heureArriveeText) {
                const [hDep, mDep] = heureDepartText.split(':').map(Number);
                const [hArr, mArr] = heureArriveeText.split(':').map(Number);
                duree = (hArr - hDep) + (mArr - mDep) / 60;
            }
    
            // Application des filtres
            const isVisible = (
                (!filtreEcologique || estEcologique) &&
                (prix <= prixMax) &&
                (duree <= dureeMax) &&
                (note >= noteMin)
            );
    
            covoiturage.style.display = isVisible ? 'block' : 'none';
        });
    }

    //Evenement Vue Utilisateur 
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');
    
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                // Réinitialise tous les onglets
                tabPanes.forEach(pane => pane.style.display = 'none');
                buttons.forEach(btn => btn.classList.remove('active'));
    
                // Active le bon onglet
                const tab = button.getAttribute('data-tab');
                document.getElementById(`tab-${tab}`).style.display = 'block';
                button.classList.add('active');
    
                // Charger l'historique en AJAX si nécessaire
                if (tab === 'historique') {
                    fetchHistorique();
                }
            });
        });
    
        function fetchHistorique() {
            const container = document.getElementById('historique-content');
            fetch('index.php?page=historique_ajax')
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                })
                .catch(err => {
                    container.innerHTML = 'Erreur de chargement.';
                    console.error(err);
                });
        }
    });
    
});