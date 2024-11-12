<?php
// chess/pieces/peao.php

require_once __DIR__ . '/../interfaces/pecas_interface.php';

class Pawn implements ChessPieceInterface {
    private $color;
    private $board;
    private $chessMatch;

    public function __construct($color, $board, $chessMatch) {
        $this->color = $color;
        $this->board = $board;
        $this->chessMatch = $chessMatch;
    }

    public function getType() {
        return 'peao';
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

    // Verifica se a peça pode se mover para a posição de acordo com a cor
    private function canMove($position) {
        $piece = $this->board->pieceAt($position);
        return $piece === null || $piece->getColor() !== $this->color;
    }

    // Movimentos possíveis para o peão
    public function possibleMoves($position) {
        $moves = array_fill(0, 8, array_fill(0, 8, false)); 
        $direction = $this->color === 'white' ? -1 : 1;
        $startingRow = $this->color === 'white' ? 6 : 1;
    
        // Movimento para frente
        $p = ['row' => $position['row'] + $direction, 'column' => $position['column']];
        if ($this->board->positionExists($p) && !$this->board->hasPieceAt($p, $this->color)) {
            $moves[$p['row']][$p['column']] = true;
        }
    
        // Movimento inicial de duas casas
        if ($position['row'] === $startingRow) {
            $p2 = ['row' => $position['row'] + (2 * $direction), 'column' => $position['column']];
            $p1 = ['row' => $position['row'] + $direction, 'column' => $position['column']];
            if ($this->board->positionExists($p2) && !$this->board->hasPieceAt($p2, $this->color) &&
                $this->board->positionExists($p1) && !$this->board->hasPieceAt($p1, $this->color)
            ) {
                $moves[$p2['row']][$p2['column']] = true;
            }
        }
    
        // Captura à esquerda
        $p = ['row' => $position['row'] + $direction, 'column' => $position['column'] - 1];
        if ($this->board->positionExists($p) && $this->canMove($p)) {
            $moves[$p['row']][$p['column']] = true;
        }
    
        // Captura à direita
        $p = ['row' => $position['row'] + $direction, 'column' => $position['column'] + 1];
        if ($this->board->positionExists($p) && $this->canMove($p)) {
            $moves[$p['row']][$p['column']] = true;
        }
    
        // Captura en passant
        if (($this->color === 'white' && $position['row'] === 3) || ($this->color === 'black' && $position['row'] === 4)) {
            $left = ['row' => $position['row'], 'column' => $position['column'] - 1];
            if ($this->board->positionExists($left) && $this->canMove($left) &&
                $this->board->pieceAt($left) === $this->chessMatch->getEnPassantVulnerable()
            ) {
                $moves[$left['row'] + $direction][$left['column']] = true;
            }
    
            $right = ['row' => $position['row'], 'column' => $position['column'] + 1];
            if ($this->board->positionExists($right) && $this->canMove($right) &&
                $this->board->pieceAt($right) === $this->chessMatch->getEnPassantVulnerable()
            ) {
                $moves[$right['row'] + $direction][$right['column']] = true;
            }
        }
    
        return $moves;
    }
    
}

// Fim do código
