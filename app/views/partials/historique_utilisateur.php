<?php
require_once __DIR__ . '/../../models/Covoiturage.php';
$covoiturageModel = new Covoiturage();
$historique = $covoiturageModel->getHistoriqueByUserId($_SESSION['utilisateur_id']);
?>

<ul>
    <?php if (empty($historique)): ?>
        <li>Aucun covoiturage trouvé.</li>
    <?php else: ?>
        <?php foreach ($historique as $trajet): ?>
            <li><?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrivee']) ?> le <?= htmlspecialchars($trajet['date']) ?></li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
