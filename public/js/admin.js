class AdminDashboard {
    constructor() {
        this.messageEl = document.getElementById('data-message');
        this.init();
    }

    async init() {
        try {
            const data = await this.fetchData();
            this.renderCharts(data);
        } catch (error) {
            // Correction: Utilisation de showMessage au lieu de showError
            this.showMessage(error.message, true);
        }
    }

    async fetchData() {
        const response = await fetch('index.php?page=stats');
        
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const data = await response.json();

        console.log("Données reçues :", data);  // Ajoute ceci pour voir la réponse


        if (!data.success) {
            throw new Error(data.error || 'Erreur inconnue');
        }

        return data;
    }

    renderCharts(data) {
        if (data.dates.length === 0) {
            this.showMessage('Aucune donnée disponible', false);
            return;
        }

        this.createChart('ridesChart', 'bar', data.dates, data.rides, 'Covoiturages', '#36a2eb');
        this.createChart('creditsChart', 'line', data.dates, data.credits, 'Crédits', '#4bc0c0');
        document.getElementById('totalCredits').textContent = data.totalCredits;
    }

    createChart(id, type, labels, data, label, color) {
        const ctx = document.getElementById(id);
        if (!ctx) return;

        if (ctx.chart) {
            ctx.chart.destroy();
        }

        return new Chart(ctx.getContext('2d'), {
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
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    showMessage(text, isError = true) {
        this.messageEl.textContent = text;
        this.messageEl.className = `alert ${isError ? 'error' : 'info'}`;
        this.messageEl.style.display = 'block';
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    if (document.body.classList.contains('admin-page')) {
        new AdminDashboard();
    }
});