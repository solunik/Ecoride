document.addEventListener('DOMContentLoaded', () => {
    const roleActif = window.roleActif; // Le rôle actif récupéré depuis la session
    const userId = window.userId; // L'ID utilisateur récupéré depuis la session
    const vehiculeManagementSection = document.getElementById('vehiculeManagementSection');
    const addVehiculeSection = document.getElementById('addVehiculeSection');
    const vehiculeList = document.getElementById('vehicule-list');
    const addVehiculeBtn = document.getElementById('addVehiculeBtn');
    const addVehiculeForm = document.getElementById('addVehiculeForm');
    const driverFormSection = document.getElementById('driverFormSection');
    const cancelAddVehiculeBtn = document.getElementById('cancelAddVehiculeBtn');
    
    // Appel AJAX pour récupérer les véhicules d'un utilisateur
    function getVehicules() {
        if (!userId) {
            console.error('ID utilisateur non défini');
            return;
        }
    
        fetch(`index.php?page=api_get_all_vehicules&user_id=${userId}`)
            .then(response => response.json())
            .then(vehicules => {
                vehiculeList.innerHTML = '';
    
                vehicules.forEach(vehicule => {
                    
                    const card = document.createElement('div');
                    card.classList.add('vehicule-card');
                    card.setAttribute('data-id', vehicule.voiture_id);
                    console.log(vehicule);

                        
                    card.innerHTML = `
                        <h4> ${vehicule.marque_nom} ${vehicule.modele}</h4>
                        <p><strong>Couleur :</strong> ${vehicule.couleur}</p>
                        <p><strong>Plaque :</strong> ${vehicule.immatriculation}</p>
                        <p><strong>Énergie :</strong> ${vehicule.energie}</p>
                        <div class="vehicule-actions">
                            <button class="edit-btn">Modifier</button>
                            <button class="delete-btn">Supprimer</button>
                        </div>
                    `;
                    
                    vehiculeList.appendChild(card);
                    
                });
    
                bindDeleteButtons(); // Reattacher les événements aux nouveaux boutons créés
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des véhicules:', error);
            });
    }
    

 // Lorsque le bouton "Ajouter un véhicule" est cliqué
 addVehiculeBtn.addEventListener('click', () => {
    // Masquer la section de gestion des véhicules
    vehiculeManagementSection.style.display = 'none';
    
    // Afficher la section d'ajout de véhicule
    addVehiculeSection.style.display = 'block';
    
    // Masquer le bouton "Ajouter un véhicule" et afficher le bouton "Annuler"
    addVehiculeBtn.style.display = 'none';
    cancelAddVehiculeBtn.style.display = 'inline-block';
});

// Lorsque le bouton "Annuler" est cliqué
cancelAddVehiculeBtn.addEventListener('click', () => {
    // Masquer la section d'ajout de véhicule
    addVehiculeSection.style.display = 'none';
    
    // Réafficher la section de gestion des véhicules
    vehiculeManagementSection.style.display = 'block';
    
    // Réafficher le bouton "Ajouter un véhicule" et masquer le bouton "Annuler"
    addVehiculeBtn.style.display = 'inline-block';
    cancelAddVehiculeBtn.style.display = 'none';
});

    // Soumettre le formulaire pour ajouter un véhicule
    addVehiculeForm.addEventListener('submit', (e) => {
        e.preventDefault(); // Empêcher la soumission classique du formulaire
    
        const formData = new FormData(addVehiculeForm);
        formData.append('user_id', userId); // Ajouter dynamiquement l'ID utilisateur
    
        fetch('index.php?page=api_add_vehicule', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                Swal.fire({
                    title: 'Succès !',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php?page=espace_utilisateur';
                    }
                });
            } else {
                Swal.fire({
                    title: 'Erreur !',
                    text: data.error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        
        .catch(error => {
            console.error('Erreur lors de l\'ajout du véhicule:', error);
            alert("Erreur lors de l'ajout du véhicule.");
        });
    });
    
    // Fonction pour attacher les événements aux boutons Supprimer
    function bindDeleteButtons() {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const card = e.target.closest('.vehicule-card');
                const vehiculeId = card.getAttribute('data-id');

                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('index.php?page=api_delete_vehicule', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `voiture_id=${vehiculeId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Supprimé !',
                                    data.message,
                                    'success'
                                );

                                // Supprimer la carte du DOM sans recharger la page
                                card.remove();
                            } else {
                                Swal.fire(
                                    'Erreur !',
                                    data.error,
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Erreur lors de la suppression:', error);
                            Swal.fire(
                                'Erreur !',
                                'Erreur lors de la suppression.',
                                'error'
                            );
                        });
                    }
                });
            });
        });
    }

    bindDeleteButtons()
    getVehicules();
   
});
