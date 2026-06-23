<?php
// delete_comment.php
session_start();
require_once("bd.php");
require_once("csrf.php");
$bd = getBD();

header('Content-Type: application/json; charset=utf-8');

function json_fail($m){ 
    echo json_encode(['success'=>false,'message'=>$m]); 
    exit; 
}
function json_ok($d=[]){ 
    echo json_encode(array_merge(['success'=>true], $d)); 
    exit; 
}

if (!csrf_validate()) json_fail("CSRF invalide");
if (!isset($_SESSION['client'])) json_fail("Non connecté");

$id_client = (int)$_SESSION['client']['id_client'];
$id_comment = (int)($_POST['id_comment'] ?? 0);
if ($id_comment <= 0) json_fail("Commentaire invalide");

// a qui appartient com
$chk = $bd->prepare("SELECT id_client FROM comments WHERE id_comment = ?");
$chk->execute([$id_comment]);
$owner = $chk->fetchColumn();
if (!$owner) json_fail("Commentaire introuvable");
if ((int)$owner !== $id_client) json_fail("Vous n'êtes pas autorisé à supprimer ce commentaire");

// Delete 
try {
    $bd->beginTransaction();// demare transaction soit tout marche soit rien ne change
    $bd->prepare("DELETE FROM comment_likes WHERE id_comment = ?")->execute([$id_comment]);
    $bd->prepare("DELETE FROM comments WHERE id_comment = ?")->execute([$id_comment]);
    $bd->commit();
    json_ok();
} catch (Exception $e) {
    if ($bd->inTransaction()) $bd->rollBack();
    json_fail("Erreur serveur lors de la suppression");
}
