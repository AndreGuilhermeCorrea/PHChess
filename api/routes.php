<?php
require_once __DIR__ . '/endpoints/move.php';

$requestUri = $_SERVER['QUERY_STRING'];

if ($requestUri === 'move') {
    // Move a peça
    movePiece();
} elseif($requestUri === 'initialize') {
    // Reinicia a partida
    restart();  
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Endpoint não está respondendo']);
}