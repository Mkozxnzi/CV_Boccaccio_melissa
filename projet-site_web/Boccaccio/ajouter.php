<?php
session_start();
require_once("csrf.php");
require_once("bd.php");
$bdd = getBD();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_validate()) {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['client'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['id_art']) && isset($_POST['quantite'])) {
    $idClient = (int)$_SESSION['client']['id_client'];
    $idArt = (int)$_POST['id_art'];
    $quantite = max(1, (int)$_POST['quantite']);

    // verifie si il y a deja l'article dans le panier
    $req = $bdd->prepare("SELECT quantite FROM paniers WHERE id_client = ? AND id_art = ?");
    $req->execute([$idClient, $idArt]);
    $existant = $req->fetch(PDO::FETCH_ASSOC); // req appel la methode fetch

    if ($existant) {
        $update = $bdd->prepare("UPDATE paniers SET quantite = ? WHERE id_client = ? AND id_art = ?");
        $update->execute([$quantite, $idClient, $idArt]); // si deja présent juste update 
    } else {
        // verifie stock trés con
        //r.quantié : sommes des quantité reservé pour cette articles
        //p.quantité : sommes quantité présentes dans les paniers
        //c'est des moins 
        $stockCheck = $bdd->prepare(" 
    SELECT 
        a.quantite 
        - IFNULL(SUM(r.quantite),0)
        - IFNULL(SUM(p.quantite),0) AS stock_dispo 
    FROM articles a
    LEFT JOIN reservations r 
        ON a.id_art = r.id_art AND r.expire_at > NOW()
    LEFT JOIN paniers p 
        ON a.id_art = p.id_art
    WHERE a.id_art = ?
    GROUP BY a.id_art
");
$stockCheck->execute([$idArt]);
$stockDispo = (int)$stockCheck->fetchColumn(); //stock dispo après enlevement des articles en reservation et panier 

if ($stockDispo < $quantite) {
    die("<h2 style='color:red;'>Stock insuffisant pour ce produit</h2>");
}
        $insert = $bdd->prepare("INSERT INTO paniers (id_client, id_art, quantite) VALUES (?,?,?)");
        $insert->execute([$idClient, $idArt, $quantite]);
    }

    header("Location: panier.php");
    exit;
}

header("Location: index.php");
exit;
