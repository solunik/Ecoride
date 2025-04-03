<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administrateur</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-page">
    
    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    </header>

    <main class="admin-dashboard">
        <div id="data-message" class="data-message"></div>

        <section class="chart-container">
            <h2>Covoiturages par jour</h2>
            <canvas id="ridesChart"></canvas>
        </section>

        <section class="chart-container">
            <h2>Crédits gagnés</h2>
            <canvas id="creditsChart"></canvas>
            <div class="total-credits">
                Total: <span id="totalCredits">0</span> crédits
            </div>
        </section>

        <section class="employee-creation">
    <h2>Création de compte employé</h2>
    
    <form id="createEmployeeForm" class="employee-form">
        <div class="form-group">
            <label for="employeeLastName">Nom </label>
            <input type="text" id="employeeLastName" name="lastName" required>
        </div>
        
        <div class="form-group">
            <label for="employeeFirstName">Prénom </label>
            <input type="text" id="employeeFirstName" name="firstName" required>
        </div>
        
        <div class="form-group">
            <label for="employeeEmail">Email </label>
            <input type="email" id="employeeEmail" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="employeePassword">Mot de passe </label>
            <input type="password" id="employeePassword" name="password" required>
        </div>
        
        <button type="submit" class="btn-submit">Créer</button>
    </form>
    
    <div id="employeeConfirmation" class="confirmation-message" style="display:none;">
        <h3>Compte employé créé avec succès</h3>
        <div class="employee-details">
            <p><span class="detail-label">Nom complet :</span> <span id="confirmFullName"></span></p>
            <p><span class="detail-label">Email :</span> <span id="confirmEmail"></span></p>
            <p><span class="detail-label">Rôle :</span> Employé</p>
        </div>
    </div>
</section>

        <section>
            <?php echo "suspendre un compte, aussi bien utilisateur qu’employé"; ?>
        </section>

    </main>

    <script src="js/admin.js" defer></script>
</body>
</html>