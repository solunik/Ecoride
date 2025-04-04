<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administrateur</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/evenements.js" defer></script>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-page">
    
    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    </header>

    <main class="admin-dashboard">
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

        <div id="data-message" class="data-message"></div>

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

        <div class="form-group">
            <label for="employeeConfirmPassword">Confirmer le mot de passe</label>
            <input type="password" id="employeeConfirmPassword" name="confirmPassword" required>
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


<section class="user-management">
    <h2>Gestion des utilisateurs / employés</h2>
    
    <!-- Message d'état -->
    <div id="user-message" class="alert-message" style="display: none;"></div>
    
    <!-- Contrôles simplifiés -->
    <div class="user-controls">
        <button id="refresh-users" class="btn-refresh">🔄 Actualiser</button>
    </div>

    <!-- Tableau principal -->
    <div class="table-responsive">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="users-tbody">
                <!-- Rempli dynamiquement par JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Template pour une ligne utilisateur -->
    <template id="user-row-template">
        <tr>
            <td class="user-nom"></td>
            <td class="user-prenom"></td>
            <td class="user-email"></td>
            <td class="user-role"></td>
            <td class="user-status">
                <span class="status-badge"></span>
            </td>
            <td class="user-actions">
                <button class="btn-suspend"></button>
            </td>
        </tr>
    </template>
</section>

    </main>

    <script src="js/admin.js" defer></script>
    <script src="js/adminmanager.js" defer></script>
</body>
</html>