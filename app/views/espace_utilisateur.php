
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Utilisateur</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/user.css">
</head>
<body class="user-page">
<header>
    <?php include __DIR__ . '/../partials/header.php'; ?>
</header>
<main class="user-dashboard">
    <section class="user-controls">
        <button id="passengerBtn" class="btn-toggle">Passager</button>
        <button id="driverBtn" class="btn-toggle">Conducteur</button>
    </section>
    <!-- Section Passager -->
    <section id="passengerSection" class="user-section" style="display: none;">
        <h2>Passager</h2>
        <!-- Mise à jour des informations de l'utilisateur -->
        <div id="updateUserInfo">
            <h3>Mise à jour de vos informations</h3>
            <form id="updateUserForm">
                <label for="userAddress">Adresse:</label>
                <input type="text" id="userAddress" name="address" required>
                <label for="userPhone">Téléphone:</label>
                <input type="tel" id="userPhone" name="telephone" required>
                <label for="userPassword">Mot de passe:</label>
                <input type="password" id="userPassword" name="userPassword" required>
                <label for="userEmail">Email:</label>
                <input type="email" id="userEmail" name="userEmail" required>
                <label for="userProfilePicture">Photo de profil:</label>
                <input type="file" id="userProfilePicture" name="userProfilePicture" accept="image/*">
                <button type="submit">Mettre à jour</button>
            </form>
        </div>
    </section>
    <!-- Section Conducteur -->
    <section id="driverSection" class="user-section" style="display: none;">
        <h2>Conducteur</h2>
        <!-- Vérification du rôle -->
        <div id="driverFormSection" style="display: none;">
            <h3>Ajouter un véhicule</h3>
            <form id="addVehicleForm">
                <label for="vehicleMarque">Marque:</label>
                <input type="text" id="vehicleMarque" name="marque" required>
                <label for="vehicleModel">Modèle:</label>
                <input type="text" id="vehicleModel" name="modele" required>
                <label for="vehicleColor">Couleur:</label>
                <input type="text" id="vehicleColor" name="couleur" required>
                <label for="vehiclePlate">Plaque d'immatriculation:</label>
                <input type="text" id="vehiclePlate" name="immatriculation" required>
                <label for="vehicleEnergy">Énergie:</label>
                <select id="vehicleEnergy" name="energy">
                    <option value="essence">Essence</option>
                    <option value="diesel">Diesel</option>
                    <option value="electrique">Électrique</option>
                </select>
                <label for="vehicleFirstRegistration">Date 1ère mise en circulation:</label>
                <input type="date" id="vehicleFirstRegistration" name="date_premiere_immatriculation" required>
                <input type="hidden" name="user_id" value="1"> <!-- ID de l'utilisateur actuel -->
                <button type="submit">Ajouter le véhicule</button>
            </form>
        </div>
        <!-- Formulaire pour proposer un covoiturage -->
        <div id="rideOfferForm" style="display: none;">
            <h3>Proposer un covoiturage</h3>
            <form id="proposeRideForm">
                <label for="departureLocation">Lieu de départ:</label>
                <input type="text" id="departureLocation" name="departureLocation" required>
                <label for="arrivalLocation">Lieu d'arrivée:</label>
                <input type="text" id="arrivalLocation" name="arrivalLocation" required>
                <label for="rideDate">Date:</label>
                <input type="date" id="rideDate" name="rideDate" required>
                <label for="rideTime">Heure:</label>
                <input type="time" id="rideTime" name="rideTime" required>
                <label for="seatCount">Nombre de places:</label>
                <input type="number" id="seatCount" name="seatCount" required>
                <button type="submit">Proposer le covoiturage</button>
            </form>
        </div>
    </section>
</main>
<script src="js/user.js" defer></script>
</body>
</html>
