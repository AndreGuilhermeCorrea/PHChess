<?php

class ChessMatch
{
    private $turn;
    private $currentPlayer;
    private $board;
    private $check;
    private $checkMate;
    private $enPassantVulnerable;
    private $promotion;

    private $piecesOnBoard = [];
    private $capturedPieces = [];

    public function __construct()
    {
        $this->board = new Board(8, 8);
        $this->turn = 1;
        $this->currentPlayer = 'white';
        $this->initializeMatch();
    }

    public function getTurn()
    {
        return $this->turn;
    }

    public function getCurrentPlayer()
    {
        return $this->currentPlayer;
    }

    public function isCheck()
    {
        return $this->check;
    }

    public function isCheckMate()
    {
        return $this->checkMate;
    }

    public function getEnPassantVulnerable()
    {
        return $this->enPassantVulnerable;
    }

    public function getPromotion()
    {
        return $this->promotion;
    }

    public function getPieces()
    {
        return $this->board->getMatrixPieces();
    }

    public function promotePiece($position, $newPieceType)
    {
        $piece = $this->board->pieceAt($position);

        if ($piece instanceof Pawn && $this->promotion) {
            $this->promotion = $newPieceType;
            $this->replacePieceAt($position, $newPieceType, $piece->getColor());
        } else {
            throw new Exception("Promoção inválida! Apenas peões podem ser promovidos.");
        }
    }

    private function replacePieceAt($position, $type, $color)
    {
        $this->board->removePiece($position);
        $newPiece = $this->createPiece($type, $color);
        $this->board->placePiece($newPiece, $position);
        $this->piecesOnBoard[] = $newPiece;
    }

    private function createPiece($type, $color)
    {
        switch ($type) {
            case 'Bishop':
                return new Bishop($this->board, $color);
            case 'Knight':
                return new Knight($this->board, $color);
            case 'Queen':
                return new Queen($this->board, $color);
            case 'Rook':
                return new Rook($this->board, $color);
            default:
                throw new Exception("Tipo de peça inválido para promoção.");
        }
    }

    public function setPieces($piecesWithPositions)
    {
        foreach ($piecesWithPositions as $entry) {
            $piece = $entry['piece'];
            $position = $entry['position'];
            $piece->setPosition($position); 
            $this->board->placePiece($piece, $position);
        }
    }

}

