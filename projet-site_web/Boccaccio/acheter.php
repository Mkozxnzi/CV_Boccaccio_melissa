<?php
error_reporting(E_ALL); // erreur php
ini_set('display_errors', 1);//autorise l'affichage à l'écran
session_start();
require_once("csrf.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_validate()) { // refuse tout accès qui n'est pas un POST et si token nul
    die("<h2 style='color: red;'>Action non autorisée ou token CSRF manquant.</h2>");
}

echo "<h2 style='color: green;'>Commande enregistrée !</h2>"; 
echo "<p>Le paiement doit être finalisé pour valider la commande.</p>";
echo '<p><a href="index.php">← Retour à l\'accueil</a></p>';
?>
