<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // éviter HTML dans la réponse JSON
session_start();

require_once("csrf.php");
require_once('vendor/autoload.php');
require_once('stripe.php'); 
header('Content-Type: application/json; charset=utf-8'); // reponse en jSON

if (!csrf_validate()) {
    echo json_encode(['success' => false, 'message' => 'Jeton CSRF invalide.']);
    exit;
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=valar_morghulis;charset=utf8', 'root', 'root');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nom     = trim($_POST['n'] ?? '');
    $prenom  = trim($_POST['p'] ?? '');
    $adresse = trim($_POST['adr'] ?? '');
    $num     = trim($_POST['num'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $mdp1    = $_POST['mdp1'] ?? '';
    $mdp2    = $_POST['mdp2'] ?? '';

    if (!$nom || !$prenom || !$adresse || !$num || !$email || !$mdp1 || !$mdp2) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Adresse e-mail invalide.']);
        exit;
    }
    if ($mdp1 !== $mdp2) {
        echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
        exit;
    }

    $check = $bdd->prepare("SELECT COUNT(*) FROM clients WHERE mail = ?");
    $check->execute([$email]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Adresse e-mail déjà utilisée.']);
        exit;
    }

    // Création client Stripe
    $customer = $stripe->customers->create([
        'email'   => $email,
        'name'    => $prenom . ' ' . $nom,
        'address' => ['line1' => $adresse],
        'phone'   => $num,
    ]);

    // Insertion en base
    $insert = $bdd->prepare("INSERT INTO clients (nom, prenom, adresse, numero, mail, mdp, id_stripe) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insert->execute([
        $nom,
        $prenom,
        $adresse,
        $num,
        $email,
        password_hash($mdp1, PASSWORD_DEFAULT),
        $customer->id
    ]);
    $id_client = $bdd->lastInsertId(); //récup ID du nouveau client 

    // Relire l’utilisateur depuis la base pour être sûr
    $stmt = $bdd->prepare("SELECT * FROM clients WHERE id_client = ?");
    $stmt->execute([$id_client]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Stockage en session
    $_SESSION['client'] = [
        'id_client' => $user['id_client'],
        'nom'       => $user['nom'],
        'prenom'    => $user['prenom'],
        'mail'      => $user['mail'],
        'adresse'   => $user['adresse'],
        'num'       => $user['numero'],
        'id_stripe' => $user['id_stripe']
    ];

    echo json_encode(['success' => true, 'message' => 'Compte créé avec succès.', 'stripe_id' => $user['id_stripe']]);

} catch (Exception $e) {
    error_log("Erreur serveur: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
}
