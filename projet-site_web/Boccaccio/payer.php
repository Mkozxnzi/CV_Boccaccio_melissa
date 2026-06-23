<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();

require_once "csrf.php";
require_once "bd.php";
require_once "vendor/autoload.php";//librairie stripe
require_once "stripe.php";

$bdd = getBD();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_validate()) {
    die("<h2 style='color:red;'>Action non autorisée.</h2>");
}

if (empty($_SESSION['client'])) {
    header("Location: index.php");
    exit;
}

$client   = $_SESSION['client'];
$idClient = (int)$client['id_client'];

//Récupère le panier 
$req = $bdd->prepare("
    SELECT p.id_art, p.quantite, a.stripe_price_id
    FROM paniers p
    JOIN articles a ON p.id_art = a.id_art
    WHERE p.id_client = ?
");
$req->execute([$idClient]);
$panier = $req->fetchAll(PDO::FETCH_ASSOC);

if (!$panier) {
    die("<h2 style='color:red;'>Panier vide</h2>");
}

//Préparer Stripe
$lineItems = [];//transforme le panier sql en format stripe 
foreach ($panier as $item) {
    if ($item['stripe_price_id']) {
        $lineItems[] = [
            'price'    => $item['stripe_price_id'],
            'quantity' => (int)$item['quantite']
        ];
    }
}

if (!$lineItems) {
    die("<h2 style='color:red;'>Produits Stripe invalides</h2>");
}

//Client Stripe 
if (empty($client['id_stripe'])) {
    $customer = $stripe->customers->create([
        'email' => $client['mail'],
        'name'  => $client['prenom'].' '.$client['nom'],
    ]);

    $bdd->prepare("
        UPDATE clients SET id_stripe = ? WHERE id_client = ?
    ")->execute([$customer->id, $idClient]);

    $_SESSION['client']['id_stripe'] = $customer->id;
    $client['id_stripe'] = $customer->id;
}

//Session Checkout
$domain = "http://localhost/Boccaccio";
// création session de paiement Stripe
$checkout_session = $stripe->checkout->sessions->create([
    'customer'            => $client['id_stripe'], //clé => valeur
    'mode'                => 'payment',
    'line_items'          => $lineItems,
    'success_url'         => $domain.'/success.php',
    'cancel_url'          => $domain.'/cancel.php',
    'client_reference_id' => $idClient,
]);

//Réservation du stock (SANS le décrémenter)
try {
    $bdd->beginTransaction();

    foreach ($panier as $item) {
        $lock = $bdd->prepare("
            SELECT quantite FROM articles WHERE id_art = ? FOR UPDATE
        "); // For update permet a un autre client d'acheter en meme temps
        $lock->execute([$item['id_art']]);
        $stock = $lock->fetchColumn();

        if ($stock < $item['quantite']) {
            throw new Exception("Stock insuffisant");
        }

        $bdd->prepare("
            INSERT INTO reservations
            (id_art, id_client, quantite, expire_at, stripe_session_id)
            VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 15 MINUTE), ?)
            ON DUPLICATE KEY UPDATE
                quantite = VALUES(quantite),
                expire_at = VALUES(expire_at),
                stripe_session_id = VALUES(stripe_session_id)
        ")->execute([
            $item['id_art'],
            $idClient,
            $item['quantite'],
            $checkout_session->id
        ]);
    }

    $bdd->commit();
} catch (Exception $e) {
    $bdd->rollBack();
    die("<h2 style='color:red;'>".$e->getMessage()."</h2>");
}

//Redirection Stripe 
header("Location: ".$checkout_session->url, true, 303);
exit;
