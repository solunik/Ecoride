<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Utilisateur </title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/user.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->

    <script src="js/evenements.js" defer></script>
</head>

<body>
    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    </header>

<main>

<section class="top-main">
    <label class="switch">
        <input type="checkbox" id="roleSwitch" <?= ($_SESSION['role_actif'] === 'chauffeur') ? 'checked' : '' ?>>
        <span class="slider"></span>
    </label>
    <span id="switchLabel">
        <?= ($_SESSION['role_actif'] === 'chauffeur') ? 'Espace Chauffeur' : 'Espace Utilisateur' ?>
    </span>
</section>

    <!-- Section Utilisateur -->
<section id="utilisateurSection" class="user-section" style="display: block;">
    <div id="updateUserInfo">
        <h1>Vos informations</h1>
        <form id="updateUserForm" enctype="multipart/form-data" method="POST">

            <!-- Champs de formulaire avec les valeurs dynamiques -->
            <label for="userAddress">Adresse:</label>
            <input type="text" id="userAddress" name="adresse" value="<?= htmlspecialchars( $_SESSION['adresse'] ?? '') ?>" required>

            <label for="userPhone">Téléphone:</label>
            <input type="tel" id="userPhone" name="telephone" value="<?= htmlspecialchars($_SESSION['telephone'] ?? '') ?>" required>

            <label for="userEmail">Email:</label>
            <input type="email" id="userEmail" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>

            <label for="userProfilePicture">Photo de profil:</label>
            <input type="file" id="userProfilePicture" name="photo" accept="image/*">

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
</section>
    
     <!-- Section Gestion des véhicules -->
<section id="vehiculeManagementSection" class="user-section">
        <h1>Gestion des véhicules</h1>

        <!-- Liste des véhicules enregistrés -->
        <div class="vehicule-list" id="vehicule-list">
            <?php if (!empty($vehicules)) : ?>
                <?php foreach ($vehicules as $voiture): ?>
                    <div class="vehicule-card" data-id="<?= htmlspecialchars($voiture->voiture_id) ?>">
                        <h4><?= htmlspecialchars($voiture->marque_nom) ?> <?= htmlspecialchars($voiture->modele) ?></h4>
                        <p><strong>Couleur :</strong> <?= htmlspecialchars($voiture->couleur) ?></p>
                        <p><strong>Plaque :</strong> <?= htmlspecialchars($voiture->immatriculation) ?></p>
                        <p><strong>Énergie :</strong> 
                            <?php
                            $icon = '';
                            if ($voiture->energie === 'electrique') {
                                $icon = '⚡';
                            } elseif ($voiture->energie === 'essence') {
                                $icon = '⛽';
                            } elseif ($voiture->energie === 'diesel') {
                                $icon = '🔥';
                            }
                            ?>
                            <?= $icon ?> <?= htmlspecialchars($voiture->energie) ?>
                        </p>


                        <!-- Boutons Modifier et Supprimer -->
                        <div class="vehicule-actions">
                            <button class="edit-btn">Modifier</button>
                            <button class="delete-btn">Supprimer</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun véhicule enregistré.</p>
            <?php endif; ?>
            
        </div>

</section>

    <!-- Bouton pour ajouter un véhicule -->
<div class="btn-container">
    <button id="addVehiculeBtn"><span class="plus-icon">+</span></button>
</div>

    <!-- Section Ajout de véhicule -->
    <section id="addVehiculeSection" class="user-section" style="display: none;">
        <h2>Ajouter un véhicule</h2>

        <!-- Formulaire pour ajouter un véhicule -->
        <div id="driverFormSection">
            <form id="addVehiculeForm">
            <label for="vehiculeMarque">Marque:</label>
            <select id="vehiculeMarque" name="marque_id">
                <?php foreach ($marques as $marque): ?>
                    <option value="<?= htmlspecialchars(string: $marque['marque_id']) ?>">
                        <?= htmlspecialchars($marque['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>


                <label for="vehiculeModel">Modèle:</label>
                <input type="text" id="vehiculeModel" name="modele" required>

                <label for="vehiculeColor">Couleur:</label>
                <input type="text" id="vehiculeColor" name="couleur" required>

                <label for="vehiculePlate">Plaque d'immatriculation:</label>
                <input type="text" id="vehiculePlate" name="immatriculation" required>

                <label for="vehiculeEnergy">Énergie:</label>
                <select id="vehiculeEnergy" name="energy">
                    <option value="essence">Essence</option>
                    <option value="diesel">Diesel</option>
                    <option value="electrique">Électrique</option>
                </select>

                <label for="vehiculeFirstRegistration">Date 1ère mise en circulation:</label>
                <input type="date" id="vehiculeFirstRegistration" name="date_premiere_immatriculation" required>

                <input type="hidden" name="user_id" value="<?= $_SESSION['utilisateur_id'] ?>"> <!-- ID de l'utilisateur actuel -->
                <button type="submit">Ajouter le véhicule</button>
            </form>
        </div>
        
    <div class="btn-container">
        <button id="cancelAddVehiculeBtn" type="button">-</button>
    </div>
    </section>   
 

<!-- Historique des covoiturages proposés -->
<section id="historySection" class="form-section">
    <h2>Mes covoiturages</h2>

    <?php if (!empty($historiqueCovoiturages)) : ?>

        <?php
        // Fonction de traduction des statuts
        function traduireStatut($statut) {
            $traductions = [
                'pending' => 'En attente',
                'approved' => 'Approuvé',
                'cancelled' => 'Annulé',
                'completed' => 'Terminé'
            ];
            return $traductions[strtolower($statut)] ?? ucfirst($statut);
        }
        ?>

        <div class="history-list">
            <?php foreach ($historiqueCovoiturages as $covoiturage) : ?>
                <div class="history-card" data-id="<?= htmlspecialchars($covoiturage['covoiturage_id']) ?>">
                    <div class="history-info">
                        <p><strong>📍 Départ :</strong> <?= htmlspecialchars($covoiturage['lieu_depart']) ?></p>
                        <p><strong>🏁 Arrivée :</strong> <?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></p>
                        <p><strong>👥 Nombre de places :</strong> <?= htmlspecialchars($covoiturage['nb_place']) ?></p>
                        <p><strong>📅 Date :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($covoiturage['date_depart']))) ?></p>
                        <p><strong>⚙️ Statut :</strong> 
                            <span class="status <?= htmlspecialchars(strtolower($covoiturage['statut'])) ?>">
                                <?= htmlspecialchars(traduireStatut($covoiturage['statut'])) ?>
                            </span>
                        </p>
                    </div>

                    <?php if ($covoiturage['statut'] !== 'completed') : ?>
                        <div class="history-actions">
                            <button class="delete-ride-btn">Annuler</button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else : ?>
        <div class="empty-history">
            <p>Vous n'avez proposé aucun covoiturage pour l'instant.</p>
        </div>
    <?php endif; ?>
</section>


    <section class="covoiturage-section">
        <h1>Proposer un covoiturage</h1>

        <div class="vehicule-choose-container">
            <?php if (!empty($vehicules)):?>
                <div class="vehicule-list">
                    <?php foreach ($vehicules as $vehicule): ?>
                        <div class="vehicule-card" data-id="<?= htmlspecialchars($vehicule->voiture_id) ?>">
                            <h4><?= htmlspecialchars($vehicule->modele) ?> (<?= htmlspecialchars($vehicule->immatriculation) ?>)</h4>
                            <button class="select-vehicule-btn">Choisir ce véhicule</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            <?php else: ?>
                <div id="noVehiculeWarning" class="alert-warning">
                    Vous n'avez aucun véhicule enregistré.<br>
                    <a>Ajoutez-en un ici</a>.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Formulaire de covoiturage (caché tant qu'un véhicule n'est pas choisi) -->
    <section id="rideOfferForm" class="form-section" style="display: none;">
        <h3>Informations sur le trajet</h3>

        <form id="proposeRideForm" method="POST" action="index.php?page=submit_covoiturage">
            <input type="hidden" id="vehiculeId" name="vehiculeId">
            <label for="departureLocation">Lieu de départ:</label>
            <input type="text" id="departureLocation" name="departureLocation" required>
            <label for="arrivalLocation">Lieu d'arrivée:</label>
            <input type="text" id="arrivalLocation" name="arrivalLocation" required>
            <label for="rideDate">Date:</label>
            <input type="date" id="rideDate" name="rideDate" required>
            <label for="rideTime">Heure:</label>
            <input type="time" id="rideTime" name="rideTime" required>
            <label for="seatCount">Nombre de places:</label>
                <select id="seatCount" name="seatCount" required>
                    <option value="">-- Sélectionnez --</option>
                    <option value="1">1 place</option>
                    <option value="2">2 places</option>
                    <option value="3">3 places</option>
                    <option value="4">4 places</option>
                </select>
            <label for="pricePerPerson">Credit par personne:</label>
            <input type="number" id="pricePerPerson" name="pricePerPerson" step="0.5" min="0">
            <button type="submit">Proposer le covoiturage</button>
            
        </form>
    </section>
    
</main>

<script>
    window.roleActif = <?= json_encode($_SESSION['role_actif'] ?? null) ?>;
    window.userId = <?= json_encode($_SESSION['utilisateur_id'] ?? null) ?>;
</script>

<script src="js/espace_utilisateur.js" defer></script>
<script src="js/mesvoitures.js" defer></script>
<script src="js/proposerCovoiturage.js" defer></script>

</body>
</html>