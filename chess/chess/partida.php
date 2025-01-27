<?php
// chess/chess/partida.php

require_once __DIR__ . '/../exceptions/excecoes_xadrez.php';
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

    // Método para obter a vulnerabilidade en passant
    public function getEnPassantVulnerable() {
        return $this->enPassantVulnerable;
    }

    // Método para obter as peças no tabuleiro
    public function getPieces()
    {
        return $this->board->getMatrixPieces();
    }

    // Método para obter o turno atual
    public function getTurn(): int {
        return $this->turn;
    }

    // Método para obter o jogador atual
    public function getCurrentPlayer(): string {
        return $this->currentPlayer;
    }

    // Método para verificar se o jogador atual está em xeque
    public function isCheck(): bool
    {
        return $this->check;
    }

    // Método para verificar se o jogador atual está em xeque-mate
    public function isCheckMate(): bool
    {
        return $this->checkMate;
    }

    // Método para obter as peças capturadas
    public function getPiecesCaptured(): array{
        $capturedPiecesFormatted = [];
        foreach ($this->capturedPieces as $piece) {
            $pieceCode = substr($piece->getType(), 0, 1) . ($piece->getColor() === 'black' ? 'p' : 'b');
            $capturedPiecesFormatted[] = $pieceCode;
        }
        return $capturedPiecesFormatted;
    }

    // Método para converter a posição de entrada
    private function convertPosition($position)
    {
        $column = ord($position[0]) - ord('a'); 
        $row = 8 - (int)$position[1];           
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

    // Método para verificar se o jogador está em xeque-mate
    private function determineCheckMate($color): bool
    {
        foreach ($this->piecesOnBoard as $piece) {
            if ($piece->getColor() === $color) {
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


    // Encontra a posição do rei para uma determinada cor
    private function findKingPosition($color)
    {
        foreach ($this->piecesOnBoard as $piece) {
            if ($piece instanceof King && $piece->getColor() === $color) {
                return $piece->getPosition();
            }
        }
        throw new CautionException();
    }
    // Método para verificar se o jogador está em xeque-mate após um movimento
    private function testCheckAfterMove($sourcePos, $destinationPos): bool
    {
        $piece = $this->board->pieceAt($sourcePos);
        $capturedPiece = $this->board->pieceAt($destinationPos);

        $this->board->movePiece($sourcePos, $destinationPos);
        $inCheck = $this->isInCheck($piece->getColor());

        $this->board->movePiece($destinationPos, $sourcePos);
        if ($capturedPiece) {
            $this->board->placePiece($capturedPiece, $destinationPos);
        }
        return $inCheck;
    }

    // Método para verificar se o jogador está em xeque-mate
    public function isInCheck($color): bool
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
   
    // Método para realizar um movimento
    public function performMove($source, $destination)
    {
        $sourcePos = $this->convertPosition($source);
        $destinationPos = $this->convertPosition($destination);
        $piece = $this->board->pieceAt($sourcePos);

        if ($piece === null) {
            throw new InvalidPositionException();
        }

        if ($piece->getColor() !== $this->currentPlayer) {
            throw new TurnMismatchException();
        }

        $allowedMoves = $piece->possibleMoves($sourcePos);
        if (!$allowedMoves[$destinationPos['row']][$destinationPos['column']]) {
            throw new InvalidMoveException();
        }

        $capturedPiece = null;

        if ($piece instanceof Pawn && $destinationPos === $this->enPassantVulnerable) {
            $capturedPawnPosition = ['row' => $sourcePos['row'], 'column' => $destinationPos['column']];
            $capturedPiece = $this->board->removePiece($capturedPawnPosition);
        } elseif ($this->board->hasPieceAt($destinationPos, $piece->getColor())) {
            $capturedPiece = $this->board->removePiece($destinationPos);
        }

        if ($capturedPiece) {
            $this->capturedPieces[] = $capturedPiece;
            $this->piecesOnBoard = array_filter($this->piecesOnBoard, fn($p) => $p !== $capturedPiece);
        }

        $this->board->movePiece($sourcePos, $destinationPos);
        $piece->setPosition($destinationPos);

        $this->enPassantVulnerable = ($piece instanceof Pawn && abs($sourcePos['row'] - $destinationPos['row']) === 2)
            ? ['row' => ($sourcePos['row'] + $destinationPos['row']) / 2, 'column' => $sourcePos['column']]
            : null;

        $opponentColor = $this->currentPlayer === 'white' ? 'black' : 'white';
        $this->check = $this->isInCheck($opponentColor);

        $this->checkMate = $this->check && $this->determineCheckMate($opponentColor);

        $this->turn++;
        $this->currentPlayer = $opponentColor;
        $_SESSION['chessMatch'] = $this;
    }

}

// Fim do código