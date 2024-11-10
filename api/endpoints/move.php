<?php
// api/endpoints/move.php

// Carregue as classes antes de manipular a sessão.
require_once __DIR__ . '/../../chess/chess/partida.php';
require_once __DIR__ . '/../../chess/chess/tabuleiro.php';
require_once __DIR__ . '/../../chess/pieces/rei.php';
require_once __DIR__ . '/../../chess/pieces/rainha.php';
require_once __DIR__ . '/../../chess/pieces/bispo.php';
require_once __DIR__ . '/../../chess/pieces/cavalo.php';
require_once __DIR__ . '/../../chess/pieces/torre.php';
require_once __DIR__ . '/../../chess/pieces/peao.php';

session_start();

function movePiece() {
    $dados = file_get_contents("php://input");

    $dataDecoded = json_decode($dados, true);

    if (!$dataDecoded) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Nenhum dado recebido ou falha na decodificação']);
        return;
    }

    // Verifica se a partida de xadrez está na sessão e inicializa se necessário
    if (!isset($_SESSION['chessMatch']) || !($_SESSION['chessMatch'] instanceof ChessMatch)) {
        error_log("Objeto `ChessMatch` ausente ou incompleto na sessão, recriando o objeto.");
        $_SESSION['chessMatch'] = new ChessMatch();
    }

    $chessMatch = $_SESSION['chessMatch'];

    $origin = $dataDecoded['origem'] ?? null;
    $destination = $dataDecoded['destino'] ?? null;
    $pieceId = $dataDecoded['peca'] ?? null;

    if (!$origin || !$destination || !$pieceId) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Dados de origem, destino ou peça ausentes']);
        return;
    }

    try {
        $chessMatch->performMove($origin, $destination);

        // Atualiza o objeto na sessão
        $_SESSION['chessMatch'] = $chessMatch;
        session_write_close();
    
        // Obtém o estado atual do tabuleiro e as peças capturadas
        $tabuleiroArray = $chessMatch->getPieces();
        $capturedPieces = $chessMatch->getPiecesCaptured();
    
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'board' => $tabuleiroArray,
            'capturedPieces' => $capturedPieces
        ]);
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}


function restart(){
    try {
        $_SESSION['chessMatch'] = new ChessMatch();
        $chessMatch = $_SESSION['chessMatch'];
        
        $tabuleiroArray = $chessMatch->getPieces();
        $capturedPieces = $chessMatch->getPiecesCaptured();
    
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'board' => $tabuleiroArray,
            'capturedPieces' => $capturedPieces
        ]);
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
