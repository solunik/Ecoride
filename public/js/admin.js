class AdminDashboard {
    constructor() {
        this.messageEl = document.getElementById('data-message');
        this.totalCreditsEl = document.getElementById('totalCredits');
        this.ridesChart = null;
        this.creditsChart = null;
        this.init();
    }

    async init() {
        try {
            const data = await this.fetchData();
            this.renderCharts(data);
        } catch (error) {
            this.showMessage(error.message, true);
        }
    }

    async fetchData() {
        try {
            const response = await fetch('index.php?page=stats');

            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Erreur inconnue');
            }

            return data;
        } catch (error) {
            this.showMessage("Impossible de charger les données : " + error.message, true);
            throw error;
        }
    }

    renderCharts(data) {
        if (!data.dates || data.dates.length === 0) {
            this.showMessage('Aucune donnée disponible', false);
            return;
        }

        this.destroyCharts(); // Supprime les anciens graphiques avant d'en créer de nouveaux

        // Ajout d'une hauteur fixe pour éviter le chevauchement
        document.getElementById('ridesChart').parentElement.style.height = "350px";
        document.getElementById('creditsChart').parentElement.style.height = "350px";

        this.ridesChart = this.createChart('ridesChart', 'bar', data.dates, data.rides, 'Covoiturages', '#36a2eb');
        this.creditsChart = this.createChart('creditsChart', 'line', data.dates, data.credits, 'Crédits', '#4bc0c0');
        this.totalCreditsEl.textContent = data.totalCredits;
    }

    createChart(id, type, labels, data, label, color) {
        const ctx = document.getElementById(id);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: `${color}20`,
                    borderColor: color,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Correction du chevauchement
                scales: { 
                    y: { beginAtZero: true } 
                }
            }
        });
    }

    destroyCharts() {
        if (this.ridesChart) {
            this.ridesChart.destroy();
            this.ridesChart = null;
        }
        if (this.creditsChart) {
            this.creditsChart.destroy();
            this.creditsChart = null;
        }
    }

    showMessage(text, isError = true) {
        this.messageEl.textContent = text;
        this.messageEl.className = `alert ${isError ? 'error' : 'info'}`;
        this.messageEl.style.display = 'block';
    }
}

// Initialisation après chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    if (document.body.classList.contains('admin-page')) {
        new AdminDashboard();
    }
});
