<?php
session_start();
require_once("bd.php");
$bd = getBD();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['client'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

$content = trim($_POST['content'] ?? '');
if ($content === '' || strlen($content) > 256) {
    echo json_encode(['success' => false, 'message' => 'Message invalide']);
    exit;
}

// Vérification via API BERT
$context = stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\n",
        'content' => json_encode(['message' => $content])
    ]
]);

$res = file_get_contents("http://127.0.0.1:8000/check", false, $context);
$data = json_decode($res, true);

if (!$data || !isset($data['valid'])) {
    echo json_encode(['success' => false, 'message' => 'Erreur de communication avec la modération']);
    exit;
}

// Si BERT juge le message inacceptable
if (!$data['valid']) {
    echo json_encode([
        'success' => false,
        'message' => '⛔ Message refusé par la modération',
        'bert_score' => $data['bert']['score']
    ]);
    exit;
}

// Sinon → insertion en base
$id_client = $_SESSION['client']['id_client'];
$username  = $_SESSION['client']['prenom'];

$stmt = $bd->prepare("INSERT INTO messages (id_client, username, content) VALUES (?, ?, ?)");
$stmt->execute([$id_client, $username, $content]);

echo json_encode(['success' => true, 'bert_score' => $data['bert']['score']]);
