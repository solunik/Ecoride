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

        <section>
            <?php echo "concevoir les comptes des employés"; ?>
        </section>

        <section>
            <?php echo "suspendre un compte, aussi bien utilisateur qu’employé"; ?>
        </section>

    </main>

    <script src="js/admin.js" defer></script>
</body>
</html>