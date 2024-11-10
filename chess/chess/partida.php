<?php
// chess/chess/partida.php

require_once __DIR__ . '/../pieces/rei.php';
require_once __DIR__ . '/../pieces/rainha.php';
require_once __DIR__ . '/../pieces/torre.php';
require_once __DIR__ . '/../pieces/cavalo.php';
require_once __DIR__ . '/../pieces/bispo.php';
require_once __DIR__ . '/../pieces/peao.php';
require_once __DIR__ . '/tabuleiro.php';
class ChessMatch
{
    private $turn;
    private $currentPlayer;
    private $board;
    private $check;
    private $checkMate;
    private $promotion;
    private $piecesOnBoard = [];
    private $capturedPieces = [];
    private $enPassantVulnerable = null;

    public function __construct()
    {
        $this->board = new Board(8, 8);
        $this->turn = 1;
        $this->currentPlayer = 'white';
        $this->check = false;
        $this->checkMate = false;
        $this->initializeBoard();
    }

    // Método para inicializar o tabuleiro
    public function initializeBoard()
    {
        $piecesWithPositions = [
            // Peças brancas
            ['piece' => new Rook('white', $this->board, $this), 'position' => 'a1'],
            ['piece' => new Knight('white', $this->board, $this), 'position' => 'b1'],
            ['piece' => new Bishop('white', $this->board, $this), 'position' => 'c1'],
            ['piece' => new Queen('white', $this->board, $this), 'position' => 'd1'],
            ['piece' => new King('white', $this->board, $this), 'position' => 'e1'],
            ['piece' => new Bishop('white', $this->board, $this), 'position' => 'f1'],
            ['piece' => new Knight('white', $this->board, $this), 'position' => 'g1'],
            ['piece' => new Rook('white', $this->board, $this), 'position' => 'h1'],
            ['piece' => new Pawn('white', $this->board, $this), 'position' => 'a2'],
            ['piece' => new Pawn('white', $this->board, $this), 'position' => 'b2'],
            ['piece' => new Pawn('white', $this->board, $this), 'position' => 'c2'],
            ['piece' => new Pawn('white', $this->board, $this), 'position' => 'd2'],
            ['piece' => new Pawn('white', $this->board, $this), 'position' => 'e2'],
            ['piece' => new Pawn('white', $this->board, $this), 'position' => 'f2'],
            ['piece' => new Pawn('white', $this->board, $this), 'position' => 'g2'],
            ['piece' => new Pawn('white', $this->board, $this), 'position' => 'h2'],
            // Peças pretas
            ['piece' => new Rook('black', $this->board, $this), 'position' => 'a8'],
            ['piece' => new Knight('black', $this->board, $this), 'position' => 'b8'],
            ['piece' => new Bishop('black', $this->board, $this), 'position' => 'c8'],
            ['piece' => new Queen('black', $this->board, $this), 'position' => 'd8'],
            ['piece' => new King('black', $this->board, $this), 'position' => 'e8'],
            ['piece' => new Bishop('black', $this->board, $this), 'position' => 'f8'],
            ['piece' => new Knight('black', $this->board, $this), 'position' => 'g8'],
            ['piece' => new Rook('black', $this->board, $this), 'position' => 'h8'],
            ['piece' => new Pawn('black', $this->board, $this), 'position' => 'a7'],
            ['piece' => new Pawn('black', $this->board, $this), 'position' => 'b7'],
            ['piece' => new Pawn('black', $this->board, $this), 'position' => 'c7'],
            ['piece' => new Pawn('black', $this->board, $this), 'position' => 'd7'],
            ['piece' => new Pawn('black', $this->board, $this), 'position' => 'e7'],
            ['piece' => new Pawn('black', $this->board, $this), 'position' => 'f7'],
            ['piece' => new Pawn('black', $this->board, $this), 'position' => 'g7'],
            ['piece' => new Pawn('black', $this->board, $this), 'position' => 'h7'],
            
        ];
        $this->setPieces($piecesWithPositions);
    }

    public function getEnPassantVulnerable() {
        return $this->enPassantVulnerable;
    }

    // Método para obter as peças no tabuleiro
    public function getPieces()
    {
        return $this->board->getMatrixPieces();
    }

    // Método para obter as peças capturadas
    public function getCapturedPieces() {
        return $this->capturedPieces;
    }

    // Método para converter a posição de entrada
    private function convertPosition($position)
    {
        $column = ord($position[0]) - ord('a');  // Conversão de coluna para índice
        $row = 8 - (int)$position[1];           // Conversão de linha para índice
        return ['row' => $row, 'column' => $column];
    }

    
    // Método para definir as peças no tabuleiro
    public function setPieces($piecesWithPositions)
    {
        foreach ($piecesWithPositions as $entry) {
            $piece = $entry['piece'];
            $position = $this->convertPosition($entry['position']);
            $piece->setPosition($position); 
            $this->board->placePiece($piece, $position);
            $this->piecesOnBoard[] = $piece;
        }   
    }

    // Retorna o status de xeque
    public function isCheck()
    {
        return $this->check;
    }

    // Verifica se o rei do jogador atual está em xeque
    public function isInCheck($color)
    {
        $kingPosition = $this->findKingPosition($color);
        foreach ($this->piecesOnBoard as $piece) {
            if ($piece->getColor() !== $color) {
                $allowedMoves = $piece->possibleMoves($piece->getPosition());
                if ($allowedMoves[$kingPosition['row']][$kingPosition['column']]) {
                    return true;
                }
            }
        }
        return false;
    }

    // Encontra a posição do rei para uma determinada cor
    private function findKingPosition($color)
    {
        foreach ($this->piecesOnBoard as $piece) {
            if ($piece instanceof King && $piece->getColor() === $color) {
                return $piece->getPosition();
            }
        }
        throw new Exception("Rei não encontrado no tabuleiro");
    }

    // Verifica se o movimento deixa o jogador em xeque
    private function testCheckAfterMove($sourcePos, $destinationPos)
    {
        $piece = $this->board->pieceAt($sourcePos);
        $capturedPiece = $this->board->pieceAt($destinationPos);

        // Realiza o movimento temporário
        $this->board->movePiece($sourcePos, $destinationPos);
        $inCheck = $this->isInCheck($piece->getColor());
        
        // Reverte o movimento
        $this->board->movePiece($destinationPos, $sourcePos);
        if ($capturedPiece) {
            $this->board->placePiece($capturedPiece, $destinationPos);
        }
        return $inCheck;
    }

    // Verifica xeque-mate ao tentar todos os movimentos
    private function isCheckMate()
    {
        foreach ($this->piecesOnBoard as $piece) {
            if ($piece->getColor() === $this->currentPlayer) {
                $allowedMoves = $piece->possibleMoves($piece->getPosition());
                foreach ($allowedMoves as $row => $columns) {
                    foreach ($columns as $col => $canMove) {
                        if ($canMove) {
                            $sourcePos = $piece->getPosition();
                            $destinationPos = ['row' => $row, 'column' => $col];
                            if (!$this->testCheckAfterMove($sourcePos, $destinationPos)) {
                                return false;
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
    /*
    // Método para obter a peça na posição
    public function performMove($source, $destination) {
        $sourcePos = $this->convertPosition($source);
        $destinationPos = $this->convertPosition($destination);
    
        $piece = $this->board->pieceAt($sourcePos);
    
        if ($piece === null) {
            throw new Exception("Nenhuma peça na posição de origem.");
        }
    
        if ($piece->getColor() !== $this->currentPlayer) {
            throw new Exception("Agora é o turno das peças " . ($this->currentPlayer === 'white' ? 'brancas' : 'pretas') . "!!!");
        }
    
        $allowedMoves = $piece->possibleMoves($sourcePos);
        if (!$allowedMoves[$destinationPos['row']][$destinationPos['column']]) {
            throw new Exception("Movimento inválido para esta peça.");
        }
    
        $capturedPiece = null;
    
        // Verifica se o movimento é uma captura en passant
        if ($piece instanceof Pawn && $destinationPos === $this->enPassantVulnerable) {
            $capturedPawnPosition = [
                'row' => $sourcePos['row'],
                'column' => $destinationPos['column']
            ];
            $capturedPiece = $this->board->removePiece($capturedPawnPosition);
            error_log("Captura en passant realizada.");
        } else if ($this->board->hasPieceAt($destinationPos, $piece->getColor())) {
            $capturedPiece = $this->board->removePiece($destinationPos);
            error_log("Peça capturada: " . $capturedPiece->getType());
        }
    
        $this->board->movePiece($sourcePos, $destinationPos);
        $piece->setPosition($destinationPos);
    
        // Atualiza vulnerabilidade en passant para peões que se movem duas casas
        if ($piece instanceof Pawn && abs($sourcePos['row'] - $destinationPos['row']) === 2) {
            $this->enPassantVulnerable = [
                'row' => ($sourcePos['row'] + $destinationPos['row']) / 2,
                'column' => $sourcePos['column']
            ];
        } else {
            $this->enPassantVulnerable = null;
        }
    
        $this->turn++;
        $this->currentPlayer = $this->currentPlayer === 'white' ? 'black' : 'white';
    
        $_SESSION['chessMatch'] = $this;
    }
    */
    // Método para realizar um movimento
    public function performMove($source, $destination) {
        $sourcePos = $this->convertPosition($source);
        $destinationPos = $this->convertPosition($destination);

        $piece = $this->board->pieceAt($sourcePos);

        if ($piece === null) {
            throw new Exception("Nenhuma peça na posição de origem.");
        }

        if ($piece->getColor() !== $this->currentPlayer) {
            throw new Exception("Agora é o turno das peças " . ($this->currentPlayer === 'white' ? 'brancas' : 'pretas') . "!");
        }

        $allowedMoves = $piece->possibleMoves($sourcePos);
        if (!$allowedMoves[$destinationPos['row']][$destinationPos['column']]) {
            throw new Exception("Movimento inválido para esta peça.");
        }

        $capturedPiece = null;

        // Verifica se o movimento é uma captura en passant
        if ($piece instanceof Pawn && $destinationPos === $this->enPassantVulnerable) {
            $capturedPawnPosition = [
                'row' => $sourcePos['row'],
                'column' => $destinationPos['column']
            ];
            $capturedPiece = $this->board->removePiece($capturedPawnPosition);
            error_log("Captura en passant realizada.");
        } else if ($this->board->hasPieceAt($destinationPos, $piece->getColor())) {
            // Captura normal
            $capturedPiece = $this->board->removePiece($destinationPos);
            error_log("Peça capturada: " . $capturedPiece->getType());
        }

        // Adiciona a peça capturada à lista de peças capturadas
        if ($capturedPiece) {
            $this->capturedPieces[] = $capturedPiece;
            $this->piecesOnBoard = array_filter($this->piecesOnBoard, function($p) use ($capturedPiece) {
                return $p !== $capturedPiece;
            });
        
            // Log das peças capturadas
            error_log("Peça capturada: " . $capturedPiece->getType() . " (" . $capturedPiece->getColor() . ")");
            error_log("Peças capturadas até agora:");
            foreach ($this->capturedPieces as $captured) {
                error_log("- " . $captured->getType() . " (" . $captured->getColor() . ")");
            }
        }

        // Move a peça para a nova posição
        $this->board->movePiece($sourcePos, $destinationPos);
        $piece->setPosition($destinationPos);

        // Atualiza vulnerabilidade en passant
        if ($piece instanceof Pawn && abs($sourcePos['row'] - $destinationPos['row']) === 2) {
            $this->enPassantVulnerable = [
                'row' => ($sourcePos['row'] + $destinationPos['row']) / 2,
                'column' => $sourcePos['column']
            ];
        } else {
            $this->enPassantVulnerable = null;
        }

        // Verifica se o jogador adversário está em xeque
        $opponentColor = $this->currentPlayer === 'white' ? 'black' : 'white';
        $this->check = $this->isInCheck($opponentColor);

        if ($this->check && $this->isCheckMate($opponentColor)) {
            $this->checkMate = true;
            throw new Exception("Xeque-mate! As " . ($this->currentPlayer === 'white' ? 'pretas' : 'brancas') . " venceram!");
        }

        // Alterna o turno
        $this->turn++;
        $this->currentPlayer = $opponentColor;

        $_SESSION['chessMatch'] = $this;
    }

}

// Fim do código