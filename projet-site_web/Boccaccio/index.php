<?php
session_start();
require_once('bd.php');
$bd = getBD(); 

// Sélection des articles avec leur note moyenne
$resultat = $bd->query("
    SELECT a.id_art, a.nom, a.quantite, a.prix, a.description,
           COALESCE(AVG(pr.note), 0) AS avg_note
    FROM articles a
    LEFT JOIN product_ratings pr ON a.id_art = pr.id_art
    GROUP BY a.id_art
    ORDER BY avg_note DESC, a.nom ASC
");
?>

<!DOCTYPE html>
<html lang="fr"> 
<head>
    <meta charset="UTF-8">
    <title>Valar Morghulis</title>
    <link rel="stylesheet" href="Styles/Styles.css" type="text/css" media="screen" />
</head>
<body>
<header>
    <h1>Valar Morghulis</h1>
    <h2>Le seul site de vente de dragon au monde</h2>
</header>

<table>
    <tr>
        <th>Nom</th>
        <th>Identifiant</th>
        <th>Quantité</th>
        <th>Prix</th>
        <th>Note moyenne</th>
    </tr>

    <?php while ($ligne = $resultat->fetch()) { ?> 
    <tr>
        <td>
            <a href="Articles/article.php?id_art=<?php echo ($ligne['id_art']); ?>">
                <?php echo ($ligne['nom']); ?>
            </a>
        </td>
        <td><?php echo ($ligne['id_art']); ?></td>
        <td><?php echo ($ligne['quantite']); ?></td>
        <td><?php echo ($ligne['prix']) . " couronnes d'or"; ?></td>
        <td>
            <?php 
            $avg = (float)$ligne['avg_note'];
            $stars = max(0, min(5, (int)round($avg)));
            echo str_repeat("⭐", $stars) . " (" . number_format($avg, 1) . ")";
            ?>
        </td>
    </tr>
    <?php } ?>
</table>

<?php
if (isset($_SESSION['client'])) {
    echo "<p>Bonjour " . ($_SESSION['client']['prenom']) . " " . ($_SESSION['client']['nom']) . "</p>";
    echo '<a href="deconnexion.php">Se déconnecter</a><br>';
    echo '<a href="panier.php">Voir mon panier</a><br>';
    echo '<a href="historique.php">Historique de commande</a>';
} else {
    echo '<a href="nouveau.php">Nouveau client</a><br>';
    echo '<a href="connexion.php">Se connecter</a>';
}
?>

<p><a href="Contact/Contact.html">Contact</a></p>

<?php include("chat.php"); ?>

</body>
</html>
