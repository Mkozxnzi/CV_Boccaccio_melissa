<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

try {
    $bdd = new PDO('mysql:host=localhost;dbname=valar_morghulis;charset=utf8', 'root', 'root');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = $_POST['email'] ?? $_GET['email'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['exists' => false]);
        exit;
    }

    $req = $bdd->prepare("SELECT COUNT(*) FROM clients WHERE mail = ?");
    $req->execute([$email]);
    $exists = $req->fetchColumn() > 0;// renvoie 0 si aucun compte ou 1

    echo json_encode(['exists' => $exists]);
} catch (Exception $e) {
    echo json_encode(['exists' => false, 'error' => $e->getMessage()]);
}
?>
