class UserManager {
    constructor() {
        this.users = [];
        this.init();
    }

    async init() {
        // Éléments DOM essentiels seulement
        this.elements = {
            tbody: document.getElementById('users-tbody'),
            template: document.getElementById('user-row-template'),
            message: document.getElementById('user-message'),
            refreshBtn: document.getElementById('refresh-users')
        };

        // Événements de base
        this.elements.refreshBtn.addEventListener('click', () => this.loadUsers());

        // Chargement initial
        await this.loadUsers();
    }

    async loadUsers() {
        const url = `index.php?page=suspend&getUsers=1`;

    console.log("Requesting URL:", url); // Ajoutez ce log
    
    try {
            this.showLoading();
            
            const response = await fetch(`index.php?page=suspend&getUsers=1`);
            const rawResponse = await response.text(); // Lire d'abord comme texte
            console.log("Raw response:", rawResponse); // Vérifiez ce qui est réellement retourné
            
            // Ensuite seulement parser en JSON
            const data = JSON.parse(rawResponse);
            
            if (data.success) {
                this.users = data.users;
                this.renderUsers();
            } else {
                this.showMessage(data.error || 'Erreur de chargement', 'error');
            }
        } catch (error) {
            console.error("Full error:", error);
            this.showMessage('Erreur réseau: ' + error.message, 'error');
        }
    }

    renderUsers() {
        this.elements.tbody.innerHTML = '';
        
        this.users.forEach(user => {
            const clone = this.elements.template.content.cloneNode(true);
            const row = clone.querySelector('tr');
            
            // Remplissage des données - utilisez les mêmes noms que le backend
            row.dataset.id = user.utilisateur_id;
            clone.querySelector('.user-nom').textContent = user.nom || 'N/A';
            clone.querySelector('.user-prenom').textContent = user.prenom || 'N/A';
            clone.querySelector('.user-email').textContent = user.email || 'N/A';
            clone.querySelector('.user-role').textContent = user.roles?.split(',')[0] || 'utilisateur';
            
            // Gestion de l'état
            const statusBadge = clone.querySelector('.status-badge');
            const suspendBtn = clone.querySelector('.btn-suspend');
            
            if (user.suspended) {
                statusBadge.textContent = 'Suspendu';
                statusBadge.className = 'status-badge status-suspended';
                suspendBtn.textContent = 'Réactiver';
            } else {
                statusBadge.textContent = 'Actif';
                statusBadge.className = 'status-badge status-active';
                suspendBtn.textContent = 'Suspendre';
            }
            
            // Vérifiez que les boutons existent avant d'ajouter des listeners
            if (suspendBtn) {
                suspendBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleSuspend(user.utilisateur_id);
                });
            }
    
            const editBtn = clone.querySelector('.btn-edit');
            if (editBtn) {
                editBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.editUser(user.utilisateur_id);
                });
            }
            
            this.elements.tbody.appendChild(clone);
        });
    }

    async toggleSuspend(userId) {
        try {
            const response = await fetch(`index.php?page=suspend`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `utilisateur_id=${userId}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showMessage(data.message, 'success');
                await this.loadUsers(); // Rafraîchit la liste
            } else {
                this.showMessage(data.error || 'Échec de la modification', 'error');
            }
        } catch (error) {
            this.showMessage('Erreur réseau: ' + error.message, 'error');
        }
    }

    showMessage(message, type) {
        this.elements.message.textContent = message;
        this.elements.message.className = `alert-message ${type}`;
        this.elements.message.style.display = 'block';
        
        setTimeout(() => {
            this.elements.message.style.display = 'none';
        }, 5000);
    }

    showLoading() {
        this.elements.tbody.innerHTML = '<tr><td colspan="6">Chargement en cours...</td></tr>';
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new UserManager();
});