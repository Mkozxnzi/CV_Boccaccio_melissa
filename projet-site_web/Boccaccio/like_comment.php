<?php
// like_comment.php
session_start();
require_once("bd.php");
require_once("csrf.php");
$bd = getBD();

header('Content-Type: application/json; charset=utf-8'); //en JSon ca va devoir repondre

function json_fail($m){
     echo json_encode(['success'=>false,'message'=>$m]); 
     exit; 
    }
function json_ok($d=[]){
     echo json_encode(array_merge(['success'=>true], $d)); // renvoie success et les données
     exit; 

    }

if (!csrf_validate()) json_fail("CSRF invalide");
if (!isset($_SESSION['client'])) json_fail("Non connecté");
$id_client = (int)$_SESSION['client']['id_client'];
$id_comment = (int)($_POST['id_comment'] ?? 0); //recup commentaire à liker
if ($id_comment <= 0) json_fail("Commentaire invalide"); 

// Insert like (upsert) and return new count
try {
    $stmt = $bd->prepare("
        INSERT INTO comment_likes (id_comment, id_client, created_at)
        VALUES (?, ?, NOW())
        ON DUPLICATE KEY UPDATE created_at = VALUES(created_at)
    ");// requete pour le like et si like et deja la ca le met a jour
    $stmt->execute([$id_comment, $id_client]);

    $c = $bd->prepare("SELECT COUNT(*) FROM comment_likes WHERE id_comment = ?");//  nombre de like que le comm a 
    $c->execute([$id_comment]);
    $count = (int)$c->fetchColumn();

    json_ok(['likes'=>$count]);
} catch (Exception $e) {
    json_fail("Erreur serveur lors de l'enregistrement du like");
}
