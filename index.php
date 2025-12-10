<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculateur de Temps de Travail</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Calculateur de Temps de Travail</h1>

        <form method="POST" action="">
            <div class="form-group">
                <label for="arrivee">Heure d'arrivée :</label>
                <input type="time" id="arrivee" name="arrivee" required
                       value="<?php echo isset($_POST['arrivee']) ? htmlspecialchars($_POST['arrivee']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="depart_repas">Départ repas :</label>
                <input type="time" id="depart_repas" name="depart_repas" required
                       value="<?php echo isset($_POST['depart_repas']) ? htmlspecialchars($_POST['depart_repas']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="retour_repas">Retour repas :</label>
                <input type="time" id="retour_repas" name="retour_repas" required
                       value="<?php echo isset($_POST['retour_repas']) ? htmlspecialchars($_POST['retour_repas']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="depart">Heure de départ :</label>
                <input type="time" id="depart" name="depart" required
                       value="<?php echo isset($_POST['depart']) ? htmlspecialchars($_POST['depart']) : ''; ?>">
            </div>

            <button type="submit">Calculer</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $arrivee = $_POST['arrivee'];
            $depart_repas = $_POST['depart_repas'];
            $retour_repas = $_POST['retour_repas'];
            $depart = $_POST['depart'];

            // Convertir en minutes depuis minuit
            function timeToMinutes($time) {
                $parts = explode(':', $time);
                return (int)$parts[0] * 60 + (int)$parts[1];
            }

            // Convertir minutes en format heures:minutes
            function minutesToTime($minutes) {
                $sign = $minutes < 0 ? '-' : '+';
                $minutes = abs($minutes);
                $h = floor($minutes / 60);
                $m = $minutes % 60;
                return sprintf("%s%02dh%02d", $sign, $h, $m);
            }

            $arrivee_min = timeToMinutes($arrivee);
            $depart_repas_min = timeToMinutes($depart_repas);
            $retour_repas_min = timeToMinutes($retour_repas);
            $depart_min = timeToMinutes($depart);

            // Calcul du temps de travail
            $temps_matin = $depart_repas_min - $arrivee_min;
            $temps_aprem = $depart_min - $retour_repas_min;
            $temps_total = $temps_matin + $temps_aprem;

            // Bonus de 6 minutes offertes
            $bonus = 6;
            $temps_avec_bonus = $temps_total + $bonus;

            // Objectif : 7h48 = 468 minutes
            $objectif = 7 * 60 + 48; // 468 minutes

            // Différence
            $difference = $temps_avec_bonus - $objectif;

            // Pause repas
            $pause_repas = $retour_repas_min - $depart_repas_min;

            // Formatage pour affichage
            $temps_total_h = floor($temps_total / 60);
            $temps_total_m = $temps_total % 60;

            $temps_bonus_h = floor($temps_avec_bonus / 60);
            $temps_bonus_m = $temps_avec_bonus % 60;

            $pause_h = floor($pause_repas / 60);
            $pause_m = $pause_repas % 60;

            echo '<div class="resultat">';
            echo '<h2>Résultat</h2>';
            echo '<div class="detail">';
            echo '<p><strong>Temps matin :</strong> ' . floor($temps_matin / 60) . 'h' . sprintf('%02d', $temps_matin % 60) . '</p>';
            echo '<p><strong>Pause repas :</strong> ' . $pause_h . 'h' . sprintf('%02d', $pause_m) . '</p>';
            echo '<p><strong>Temps après-midi :</strong> ' . floor($temps_aprem / 60) . 'h' . sprintf('%02d', $temps_aprem % 60) . '</p>';
            echo '</div>';
            echo '<div class="total">';
            echo '<p><strong>Temps travaillé :</strong> ' . $temps_total_h . 'h' . sprintf('%02d', $temps_total_m) . '</p>';
            echo '<p><strong>Avec bonus (+6min) :</strong> ' . $temps_bonus_h . 'h' . sprintf('%02d', $temps_bonus_m) . '</p>';
            echo '<p><strong>Objectif du jour :</strong> 7h48</p>';

            $diff_class = $difference >= 0 ? 'positif' : 'negatif';
            echo '<p class="difference ' . $diff_class . '"><strong>Différence :</strong> ' . minutesToTime($difference) . '</p>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
