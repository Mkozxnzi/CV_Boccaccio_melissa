<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();

require_once("csrf.php");
header('Content-Type: application/json; charset=utf-8'); //indique que reponse en Json

if (!csrf_validate()) {
    echo json_encode(['success' => false, 'message' => 'Jeton CSRF invalide.']);
    exit;
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=valar_morghulis;charset=utf8', 'root', 'root');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = trim($_POST['mail'] ?? '');
    $mdp   = $_POST['mdp1'] ?? '';

    if ($email === '' || $mdp === '') {
        echo json_encode(['success' => false, 'message' => 'Champs manquants.']);
        exit;
    }

    $stmt = $bdd->prepare("SELECT * FROM clients WHERE mail = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);// récupère une seule ligne sous forme de tableau associatif

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Adresse e-mail inconnue.']);
        exit;
    }

    if (!password_verify($mdp, $user['mdp'])) {
        echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect.']);
        exit;
    }

    // Stockage en session avec id_stripe pour que tout les pages puissent s'en servir
    $_SESSION['client'] = [
        'id_client' => $user['id_client'],
        'nom'       => $user['nom'],
        'prenom'    => $user['prenom'],
        'mail'      => $user['mail'],
        'adresse'   => $user['adresse'],
        'num'       => $user['numero'],
        'id_stripe' => $user['id_stripe']
    ];

    echo json_encode(['success' => true, 'message' => 'Connexion réussie.']);
} catch (Exception $e) {
    error_log("Erreur serveur: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
}
