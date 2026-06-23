<?php
require_once 'vendor/autoload.php';
require_once 'bd.php';
require_once 'stripe.php';

$endpoint_secret = "whsec_2b61bededcc2e46d9b6b755303fee0018af6dcdcaf08f4bb3880d24f5bdb69a0";

$payload = file_get_contents('php://input');//corps de la requête envoyée par Strip
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';// signature envoyé par stripe 

try {
    $event = \Stripe\Webhook::constructEvent( // fct qui verifie la signature du webhook 
    // et transform le JSON en objet PHP
        $payload,
        $sig_header,
        $endpoint_secret
    );
} catch (Exception $e) {
    http_response_code(400);
    exit;
}

if ($event->type !== 'checkout.session.completed') {
    http_response_code(200);
    exit;
}// payement reussi que avec CHeckout 

$session = $event->data->object; // obje de la session stripe

if ($session->payment_status !== 'paid') {
    exit;
}

$id_client = (int)$session->client_reference_id;
$stripeSessionId = $session->id;

$bdd = getBD();

try {
    $bdd->beginTransaction();

    // Anti double paiement
    $check = $bdd->prepare("
        SELECT COUNT(*) FROM commandes
        WHERE stripe_session_id = ?
    ");
    $check->execute([$stripeSessionId]);

    if ($check->fetchColumn() > 0) {
        $bdd->commit();
        exit;
    }

    // Récupérer les réservations liées à CE paiement
    $req = $bdd->prepare("
        SELECT id_art, quantite
        FROM reservations
        WHERE stripe_session_id = ?
    ");
    $req->execute([$stripeSessionId]);
    $reservations = $req->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reservations as $res) {

        // 🔒 Sécurise le stock
        $upd = $bdd->prepare("
            UPDATE articles
            SET quantite = quantite - ?
            WHERE id_art = ? AND quantite >= ?
        ");
        $upd->execute([
            $res['quantite'],
            $res['id_art'],
            $res['quantite']
        ]);

        if ($upd->rowCount() === 0) {
            throw new Exception("Stock insuffisant !");
        }

        // Crée la commande
        $bdd->prepare("
            INSERT INTO commandes
            (id_art, id_client, quantite, date_commande, stripe_session_id)
            VALUES (?, ?, ?, NOW(), ?)
        ")->execute([
            $res['id_art'],
            $id_client,
            $res['quantite'],
            $stripeSessionId
        ]);
    }

    // Nettoyage
    $bdd->prepare("DELETE FROM reservations WHERE stripe_session_id = ?")
        ->execute([$stripeSessionId]);

    $bdd->prepare("DELETE FROM paniers WHERE id_client = ?")
        ->execute([$id_client]);

    $bdd->commit();

} catch (Exception $e) {
    $bdd->rollBack();
    error_log($e->getMessage());
}

http_response_code(200);
