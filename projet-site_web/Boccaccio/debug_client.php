<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=valar_morghulis;charset=utf8', 'root', 'root');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>Debug session</h2>";
    if (isset($_SESSION['client'])) {
        echo "<pre>";
        print_r($_SESSION['client']);
        echo "</pre>";
    } else {
        echo "<p style='color:red;'>Aucun client en session.</p>";
    }

    echo "<h2>Debug base de données</h2>";
    $stmt = $bdd->query("SELECT id_client, nom, prenom, mail, id_stripe FROM clients ORDER BY id_client DESC LIMIT 5");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>ID Stripe</th></tr>";
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>".$row['id_client']."</td>";
        echo "<td>".$row['nom']."</td>";
        echo "<td>".$row['prenom']."</td>";
        echo "<td>".$row['mail']."</td>";
        echo "<td>".$row['id_stripe']."</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (Exception $e) {
    echo "<p style='color:red;'>Erreur serveur : ".$e->getMessage()."</p>";
}
