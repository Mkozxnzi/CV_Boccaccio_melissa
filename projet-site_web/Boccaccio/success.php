<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['client'])) {
    die("<h2 style='color: red;'>Erreur : utilisateur non connecté.</h2>");
}

echo "<h2 style='color: green;'>✅ Paiement réussi !</h2>";
echo "<p>Votre commande est en cours de validation par notre système.</p>";
echo '<p><a href="index.php">← Retour à l\'accueil</a></p>';
?>
