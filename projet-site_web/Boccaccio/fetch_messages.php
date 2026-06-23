<?php
session_start();
require_once("bd.php");
$bd = getBD();

header('Content-Type: application/json; charset=utf-8');

$id_me = $_SESSION['client']['id_client'] ?? 0;

// supprimer messages > 10 min
$bd->query("DELETE FROM messages WHERE created_at < (NOW() - INTERVAL 10 MINUTE)");

$stmt = $bd->query("SELECT id_client, username, content FROM messages ORDER BY created_at ASC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'messages' => $messages,
    'me' => $id_me
]);
