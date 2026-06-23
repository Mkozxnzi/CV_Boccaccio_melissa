<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once("bd.php");
require_once("csrf.php");
$bdd = getBD();

if (!isset($_SESSION['client'])) {
    header("Location: index.php");
    exit;
}

$client = $_SESSION['client'];
$idClient = (int)$client['id_client'];

$req = $bdd->prepare("SELECT p.id_art, p.quantite, a.nom, a.prix 
                      FROM paniers p 
                      JOIN articles a ON p.id_art = a.id_art 
                      WHERE p.id_client = ?");
$req->execute([$idClient]);
$panier = $req->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récapitulatif de commande</title>
    <link rel="stylesheet" href="Styles/Styles.css" type="text/css" media="screen" />
</head>
<body>
<header>
    <h1>Récapitulatif de votre commande :</h1>
</header>
<main>
<?php
if (!$panier || count($panier) === 0) {
    echo "<h2 style='color:red;'>Votre panier est vide.</h2>";
    echo "<p><a href='index.php'>← Retour à l'accueil</a></p>";
} else {
    $total_general = 0;
    echo "<table class='recap-table'>";
    echo "<tr><th>ID Article</th><th>Nom</th><th>Quantité</th><th>Prix Unitaire</th><th>Total</th></tr>";

    foreach ($panier as $item) {
        $id = $item['id_art'];
        $nom = $item['nom'];
        $qte = $item['quantite'];
        $prix = $item['prix'];
        $total = $prix * $qte;
        $total_general += $total;
        echo "<tr><td>$id</td><td>$nom</td><td>$qte</td><td>$prix</td><td>$total</td></tr>";
    }
    echo "</table>";
    echo "<h3>Montant de votre commande : <strong>$total_general</strong> €</h3>";

    echo "<h4>La commande sera expédiée à :</h4>";
    echo "<p>{$client['nom']} {$client['prenom']}<br>";
    echo isset($client['adresse']) ? $client['adresse'] : "<em>Adresse non renseignée</em>";
    echo "</p>";

    echo '<form action="payer.php" method="post">';
    echo csrf_input();
    echo '<button type="submit">Procéder au paiement</button>';
    echo '</form>';
    echo '<p><a href="index.php">← Retour à l\'accueil</a></p>';
}
?>
</main>
<?php include("chat.php"); ?>
</body>
</html>
