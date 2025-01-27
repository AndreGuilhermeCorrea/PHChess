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
require_once __DIR__ . '/../../chess/exceptions/excecoes_xadrez.php';

session_start();

function movePiece() {
    $dados = file_get_contents("php://input");

    $dataDecoded = json_decode($dados, true);

    if (!$dataDecoded) {
        respondWithJson(['success' => false, 'message' => 'Nenhum dado recebido ou falha na decodificação']);
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
        respondWithJson(['success' => false, 'message' => 'Dados de origem, destino ou peça ausentes']);
        return;
    }

    try {
        $chessMatch->performMove($origin, $destination);

        // Atualiza o objeto na sessão
        $_SESSION['chessMatch'] = $chessMatch;
        session_write_close();
    
        respondWithGameStatus($chessMatch);
    } catch (ChessException $e) {
        respondWithJson($e->toArray());
    } catch (Exception $e) {
        respondWithJson(['success' => false, 'message' => 'Erro desconhecido: ' . $e->getMessage()]);
    }
}

// Função para reiniciar o jogo
function restart() {
    try {
        $_SESSION['chessMatch'] = new ChessMatch();
        $chessMatch = $_SESSION['chessMatch'];
        respondWithGameStatus($chessMatch);
    } catch (Exception $e) {
        respondWithJson(['success' => false, 'message' => 'Erro ao reiniciar o jogo: ' . $e->getMessage()]);
    }
}

// Função com o estado do jogo
function respondWithGameStatus($chessMatch) {
    respondWithJson([
        'success' => true,
        'board' => $chessMatch->getPieces(),
        'capturedPieces' => $chessMatch->getPiecesCaptured(),
        'turn' => $chessMatch->getTurn(),
        'currentPlayer' => $chessMatch->getCurrentPlayer(),
        'check' => $chessMatch->isCheck(),
        'checkMate' => $chessMatch->isCheckMate()
    ]);
}

// Função para responder com JSON
function respondWithJson($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}