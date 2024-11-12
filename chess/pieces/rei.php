<?php
// chess/pieces/Rei.php

require_once __DIR__ . '/../interfaces/pecas_interface.php';

class King implements ChessPieceInterface {
    private $color;
    private $board;
    private $chessMatch;
    private $moveCount;

    public function __construct($color, $board, $chessMatch) {
        $this->color = $color;
        $this->board = $board;
        $this->chessMatch = $chessMatch;
    }

    public function getType() {
        return 'king';
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function getPosition() {
        return $this->position;
    }

    public function getColor() {
        return $this->color;
    }

    public function getMoveCount() {
        return $this->moveCount;
    }

    // Verifica se a torre na posição especificada pode fazer roque
    private function testRookCastling($position) {
        $piece = $this->board->pieceAt($position);
        return $piece !== null && $piece instanceof Rook && $piece->getColor() === $this->color && $piece->getMoveCount() === 0;
    }

    // Verifica se a peça pode se mover para a posição
    private function canMove($position) {
        $piece = $this->board->pieceAt($position);
        return $piece === null || $piece->getColor() !== $this->color;
    }

    public function possibleMoves($position) {
        $moves = array_fill(0, 8, array_fill(0, 8, false));
        $directions = [
            ['row' => -1, 'column' => 0],   // Acima
            ['row' => 1, 'column' => 0],    // Abaixo
            ['row' => 0, 'column' => -1],   // Esquerda
            ['row' => 0, 'column' => 1],    // Direita
            ['row' => -1, 'column' => -1],  // Noroeste
            ['row' => -1, 'column' => 1],   // Nordeste
            ['row' => 1, 'column' => 1],    // Sudeste
            ['row' => 1, 'column' => -1]    // Sudoeste
        ];
    
        foreach ($directions as $direction) {
            $target = [
                'row' => $position['row'] + $direction['row'], 
                'column' => $position['column'] + $direction['column']
            ];
            
            if ($this->board->positionExists($target) && $this->canMove($target)) {
                $moves[$target['row']][$target['column']] = true;
            }
        }
    
        // Jogada especial: roque
        if ($this->moveCount === 0 && !$this->chessMatch->isCheck()) {
            // Roque pequeno
            $kingSideRookPosition = ['row' => $position['row'], 'column' => $position['column'] + 3];
            if ($this->testRookCastling($kingSideRookPosition)) {
                $p1 = ['row' => $position['row'], 'column' => $position['column'] + 1];
                $p2 = ['row' => $position['row'], 'column' => $position['column'] + 2];
                if (!$this->board->hasPieceAt($p1) && !$this->board->hasPieceAt($p2) &&
                    !$this->chessMatch->isUnderAttack($p1, $this->color) &&
                    !$this->chessMatch->isUnderAttack($p2, $this->color)) {
                    $moves[$position['row']][$position['column'] + 2] = true;
                }
            }
    
            // Roque grande
            $queenSideRookPosition = ['row' => $position['row'], 'column' => $position['column'] - 4];
            if ($this->testRookCastling($queenSideRookPosition)) {
                $p1 = ['row' => $position['row'], 'column' => $position['column'] - 1];
                $p2 = ['row' => $position['row'], 'column' => $position['column'] - 2];
                $p3 = ['row' => $position['row'], 'column' => $position['column'] - 3];
                if (!$this->board->hasPieceAt($p1) && !$this->board->hasPieceAt($p2) && !$this->board->hasPieceAt($p3) &&
                    !$this->chessMatch->isUnderAttack($p1, $this->color) &&
                    !$this->chessMatch->isUnderAttack($p2, $this->color)) {
                    $moves[$position['row']][$position['column'] - 2] = true;
                }
            }
        }
    
        return $moves;
    }   

}

// Fim do código