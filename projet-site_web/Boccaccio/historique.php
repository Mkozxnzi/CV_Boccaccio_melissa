<?php
session_start();
require_once("bd.php");
$bdd = getBD();

// vérifie si le client est connecté
if (!isset($_SESSION['client'])) {
    header("Location: index.php");
    exit;
}

$id_client = $_SESSION['client']['id_client'];

// récupère les commandes di client
$req = $bdd->prepare("
    SELECT c.id_commande, c.id_art, a.nom, a.prix, c.quantite, c.envoi
    FROM commandes c
    JOIN articles a ON c.id_art = a.id_art 
    WHERE c.id_client = ?
");
$req->execute([$id_client]);
$commandes = $req->fetchAll(); // tableau ou a toutes les lignes
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des commandes</title>
    <link rel="stylesheet" href="Styles/Styles.css" type="text/css" media="screen" />
</head>
<body>
    <header>
        <h1>Historique de vos commandes</h1>
    </header>

    <main>
        <?php
        // si aucune commande
        if (count($commandes) === 0) {
            echo "<p>Vous n'avez encore passé aucune commande.</p>";
        } else {
            // tableau des commandes
            echo "<table class='historique-table'>";
            echo "<tr><th>ID Commande</th><th>ID Article</th><th>Nom</th><th>Prix</th><th>Quantité</th><th>Envoyée</th></tr>";

            // boucle sur chaque ligne de commande
            foreach ($commandes as $cmd) {
                $etat = $cmd['envoi'] ? "Oui" : "Non"; // champ booléen
                echo "<tr>
                        <td>{$cmd['id_commande']}</td>
                        <td>{$cmd['id_art']}</td>
                        <td>{$cmd['nom']}</td>
                        <td>{$cmd['prix']}</td>
                        <td>{$cmd['quantite']}</td>
                        <td>$etat</td>
                      </tr>";
            }

            echo "</table>";
        }
        echo '<p><a href="index.php">← Retour à l\'accueil</a></p>';
        ?>
    </main>

    <?php include("chat.php"); ?>

</body>
</html>
