<?php
// post_comment.php
session_start();
require_once("bd.php");
require_once("csrf.php");
$bd = getBD();

header('Content-Type: application/json; charset=utf-8');

function json_fail($m){ echo json_encode(['success'=>false,'message'=>$m]); exit; }
function json_ok($d=[]){ echo json_encode(array_merge(['success'=>true], $d)); exit; }

// CSRF + session
if (!csrf_validate()) json_fail("CSRF invalide");
if (!isset($_SESSION['client'])) json_fail("Non connecté");
$id_client = (int)$_SESSION['client']['id_client'];

// Input
$id_art = (int)($_POST['id_art'] ?? 0);
$content = trim($_POST['content'] ?? '');
$note = (int)($_POST['note'] ?? 0);

if ($id_art <= 0) json_fail("Article invalide");
if ($content === '' || mb_strlen($content) > 1024) json_fail("Contenu invalide (vide ou trop long)");
if ($note < 1 || $note > 5) json_fail("Note invalide");

// Vérifier achat (si tu veux garder cette règle)
$chk = $bd->prepare("
    SELECT 1 FROM commandes c
    JOIN paniers p ON p.id_commande = c.id_commande
    WHERE c.id_client = ? AND p.id_art = ? AND c.statut IN ('paid','completed') LIMIT 1
");
$chk->execute([$id_client, $id_art]);
if (!$chk->fetchColumn()) json_fail("Vous devez avoir acheté ce produit pour laisser un commentaire.");

// Appel modération FastAPI (strict)
$ctx = stream_context_create(['http'=>[
    'method'=>'POST',
    'header'=>"Content-Type: application/json\r\n",
    'content'=>json_encode(['message'=>$content])
]]);
$res = @file_get_contents("http://127.0.0.1:8000/check", false, $ctx);
if ($res === false) json_fail("Erreur de modération (API inaccessible)");
$resp = json_decode($res, true);
if (!is_array($resp) || !array_key_exists('valid', $resp)) json_fail("Réponse de modération invalide");
if (!$resp['valid']) {
    // Rejeter strictement
    json_fail("⛔ Votre commentaire a été refusé par la modération.");
}

// Everything OK -> transaction: product_ratings + comments
try {
    $bd->beginTransaction();

    // product_ratings : insert or update
    $stmt = $bd->prepare("
        INSERT INTO product_ratings (id_client, id_art, note, created_at)
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE note = VALUES(note), created_at = VALUES(created_at)
    ");
    $stmt->execute([$id_client, $id_art, $note]);

    // comments : insert or update (unique key must exist for upsert behavior; otherwise simple insert)
    $stmt = $bd->prepare("
        INSERT INTO comments (id_client, id_art, content, note, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$id_client, $id_art, $content, $note]);

    $bd->commit();

    json_ok([
        'username'=>$_SESSION['client']['prenom'],
        'content'=>$content,
        'note'=>$note,
        'created_at'=>date('Y-m-d H:i:s')
    ]);
} catch (Exception $e) {
    if ($bd->inTransaction()) $bd->rollBack();
    json_fail("Erreur serveur lors de l'enregistrement");
}
