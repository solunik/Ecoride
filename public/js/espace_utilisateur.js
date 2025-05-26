document.addEventListener('DOMContentLoaded', () => {
    const role = window.roleActif;
    const userId = window.userId;

    const addVehiculeBtn = document.getElementById('addVehiculeBtn');
    const updateForm = document.getElementById('updateUserForm');
    const roleSwitch = document.getElementById('roleSwitch');
    const switchLabel = document.getElementById('switchLabel');
    
    
    updateSectionVisibility(role);
    

    roleSwitch.addEventListener('change', async () => {
        const newRole = roleSwitch.checked ? 'chauffeur' : 'utilisateur';
        console.log('Nouveau rôle sélectionné:', newRole);

        try {
            const response = await fetch('index.php?page=api_change_role', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ role: newRole })
            });

            const result = await response.json();
            if (result.success) {
                switchLabel.textContent = newRole === 'chauffeur' ? 'Mode Chauffeur' : 'Mode Utilisateur';

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `Passé en ${newRole}`,
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                }).then(() => {
                    
                    updateSectionVisibility(newRole);
                    window.location.href = 'index.php?page=espace_utilisateur';
                });
            } else {
                Swal.fire('Erreur', result.error || 'Erreur inconnue', 'error');
            }
        } catch (error) {
            console.error('Erreur switch rôle:', error);
            Swal.fire('Erreur', 'Problème de communication.', 'error');
        }
    });

        
  

    function updateSectionVisibility(currentRole) {
        const allSections = document.querySelectorAll('main > section');
        const userControlsSection = document.querySelector('.top-main');
        console.log('Rôle dans updateSectionVisibility:', currentRole);
        
        // La section user-controls doit toujours être visible
        if (userControlsSection) {
            userControlsSection.style.display = 'block';  // On s'assure que la section des contrôles de l'utilisateur est toujours visible
        }
    
        // Si le rôle est 'utilisateur', on montre la section utilisateur et on cache les autres
        if (currentRole === 'utilisateur') {
            allSections.forEach(section => {
                if (section.id === 'utilisateurSection') {
                    section.style.display = 'block'; // Affiche la section utilisateur
                    userControlsSection.style.display = 'block'; 
                } else {
                    section.style.display = 'none'; // Cache les autres sections
                    userControlsSection.style.display = 'block';
                }
            });
    
            // Cache le bouton 'Ajouter un véhicule' si l'utilisateur n'est pas conducteur
            if (addVehiculeBtn) addVehiculeBtn.style.display = 'none';
        } else { // Si le rôle est 'chauffeur'
            allSections.forEach(section => {
                if (section.id === 'utilisateurSection') {
                    section.style.display = 'none'; // Cache la section utilisateur
                    userControlsSection.style.display = 'block';
                } else {
                    section.style.display = 'block'; // Affiche les autres sections
                    userControlsSection.style.display = 'block';
                }
            });
    
            
        }
    }
    
    
    if (updateForm) {
        updateForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(updateForm);
            try {
                const response = await fetch('index.php?page=api_update_user', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    Swal.fire('Succès', 'Informations mises à jour !', 'success').then(() => window.location.reload());
                } else {
                    Swal.fire('Erreur', result.message, 'error');
                }
            } catch (err) {
                Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
            }
        });
    }


});
