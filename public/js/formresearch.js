document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('dateInput');
    
    // Initialiser le datepicker si vous en utilisez un
    $(dateInput).datepicker({
        dateFormat: 'dd/mm/yy',
        minDate: 0 // Bloquer les dates passées
    });

    // Validation manuelle
    dateInput.addEventListener('change', function() {
        const [jour, mois, annee] = this.value.split('/');
        const dateSaisie = new Date(`${annee}-${mois}-${jour}`);
        const aujourdhui = new Date();
        aujourdhui.setHours(0, 0, 0, 0);

        if (dateSaisie < aujourdhui) {
            alert("Veuillez saisir une date future");
            this.value = '';
        }
    });
});