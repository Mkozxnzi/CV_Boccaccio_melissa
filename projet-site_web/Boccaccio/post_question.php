<?php
// post_question.php
session_start();
require_once("bd.php");
require_once("csrf.php");
$bd = getBD();

header('Content-Type: application/json; charset=utf-8');

function json_fail($m){ echo json_encode(['success'=>false,'message'=>$m]); exit; }
function json_ok($d=[]){ echo json_encode(array_merge(['success'=>true], $d)); exit; }

if (!csrf_validate()) json_fail("CSRF invalide");
if (!isset($_SESSION['client'])) json_fail("Non connecté");
$id_client = (int)$_SESSION['client']['id_client'];

$id_art = (int)($_POST['id_art'] ?? 0);
$content = trim($_POST['content'] ?? '');
if ($id_art <= 0) json_fail("Article invalide");
if ($content === '' || mb_strlen($content) > 1024) json_fail("Contenu invalide (vide ou trop long)");

// Modération
$ctx = stream_context_create(['http'=>[
    'method'=>'POST','header'=>"Content-Type: application/json\r\n",
    'content'=>json_encode(['message'=>$content])
]]);
$res = @file_get_contents("http://127.0.0.1:8000/check", false, $ctx);
if ($res === false) json_fail("Erreur de modération (API inaccessible)");
$data = json_decode($res, true);
if (!is_array($data) || !array_key_exists('valid', $data)) json_fail("Réponse de modération invalide");
if (!$data['valid']) json_fail("⛔ Votre question a été refusée par la modération.");

// Insert
try {
    $stmt = $bd->prepare("INSERT INTO questions (id_art, id_client, content, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$id_art, $id_client, $content]);

    json_ok([
        'username'=>$_SESSION['client']['prenom'],
        'content'=>$content,
        'created_at'=>date('Y-m-d H:i:s')
    ]);
} catch (Exception $e) {
    json_fail("Erreur serveur lors de l'enregistrement");
}
