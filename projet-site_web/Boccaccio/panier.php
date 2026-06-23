<?php
session_start();
require_once("csrf.php");
require_once("bd.php");
$bdd = getBD();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['client'])) {
    header("Location: index.php");
    exit;
}

$idClient = (int)$_SESSION['client']['id_client'];

// Suppression d'un article du panier (en base)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_validate() && isset($_POST['supprimer_id_art'])) {
    $idArt = (int)$_POST['supprimer_id_art'];
    $del = $bdd->prepare("DELETE FROM paniers WHERE id_client = ? AND id_art = ?");
    $del->execute([$idClient, $idArt]);
    header("Location: panier.php");
    exit;
}

// Récupérer le panier en base
$req = $bdd->prepare("SELECT p.id_art, p.quantite, a.nom, a.prix 
                      FROM paniers p 
                      JOIN articles a ON p.id_art = a.id_art 
                      WHERE p.id_client = ?");
$req->execute([$idClient]);
$panier = $req->fetchAll(PDO::FETCH_ASSOC);

if (!$panier || count($panier) === 0) {
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <title>Votre panier</title>
        <link rel='stylesheet' href='Styles/Styles.css' type='text/css' media='screen' />
    </head>
    <body>
        <header><h1>Votre panier</h1></header>
        <main>
            <h2>Votre panier est vide</h2>
            <p><a href='index.php'>← Retour à l'accueil</a></p>
        </main>
        ".(file_exists("chat.php") ? include("chat.php") : "")."
    </body>
    </html>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre panier</title>
    <link rel="stylesheet" href="Styles/Styles.css" type="text/css" media="screen" />
</head>
<body>
<header>
    <h1>Votre panier</h1>
</header>
<main>
<?php
echo "<table class='panier-table'>";
echo "<tr><th>ID Article</th><th>Nom</th><th>Prix Unitaire</th><th>Quantité</th><th>Total</th><th>Action</th></tr>";

$total_general = 0;

foreach ($panier as $item) {
    $id = (int)$item['id_art'];
    $nom = $item['nom'];
    $qte = (int)$item['quantite'];
    $prix = (float)$item['prix'];
    $total = $prix * $qte;
    $total_general += $total;

    echo "<tr>
            <td>$id</td>
            <td>$nom</td>
            <td>$prix</td>
            <td>$qte</td>
            <td>$total</td>
            <td>
                <form method='post' action='panier.php'>
                    ".csrf_input()."
                    <input type='hidden' name='supprimer_id_art' value='$id'>
                    <button type='submit'>Supprimer</button>
                </form>
            </td>
          </tr>";
}
echo "</table>";
echo "<h3>Total à payer : <strong>$total_general</strong> €</h3>";

echo '<form method="post" action="payer.php" style="margin-top:1rem;">'.csrf_input().'
        <button type="submit">Passer la commande</button>
      </form>';

echo '<p><a href="index.php">← Retour à l\'accueil</a></p>';
?>
</main>
<?php include("chat.php"); ?>
</body>
</html>
