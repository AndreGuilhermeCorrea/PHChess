<?php

class Pawn {
    private $color;
    private $board;
    private $chessMatch;

    public function __construct($color, $board, $chessMatch) {
        $this->color = $color;
        $this->board = $board;
        $this->chessMatch = $chessMatch;
    }

    public function possibleMoves($position) {
        $moves = array_fill(0, 8, array_fill(0, 8, false)); 
        $direction = $this->color === 'white' ? -1 : 1;
        $startingRow = $this->color === 'white' ? 6 : 1;

        // Movimento para frente
        $p = ['row' => $position['row'] + $direction, 'column' => $position['column']];
        if ($this->board->positionExists($p) && !$this->board->hasPieceAt($p)) {
            $moves[$p['row']][$p['column']] = true;
        }

        // Movimento inicial de duas casas
        if ($position['row'] === $startingRow) {
            $p2 = ['row' => $position['row'] + (2 * $direction), 'column' => $position['column']];
            $p1 = ['row' => $position['row'] + $direction, 'column' => $position['column']];
            if ($this->board->positionExists($p2) && !$this->board->hasPieceAt($p2) &&
                $this->board->positionExists($p1) && !$this->board->hasPieceAt($p1)
            ) {
                $moves[$p2['row']][$p2['column']] = true;
            }
        }

        // Captura à esquerda
        $p = ['row' => $position['row'] + $direction, 'column' => $position['column'] - 1];
        if ($this->board->positionExists($p) && $this->isThereOpponentPiece($p)) {
            $moves[$p['row']][$p['column']] = true;
        }

        // Captura à direita
        $p = ['row' => $position['row'] + $direction, 'column' => $position['column'] + 1];
        if ($this->board->positionExists($p) && $this->isThereOpponentPiece($p)) {
            $moves[$p['row']][$p['column']] = true;
        }

        // Captura en passant
        if (($this->color === 'white' && $position['row'] === 3) || ($this->color === 'black' && $position['row'] === 4)) {
            $left = ['row' => $position['row'], 'column' => $position['column'] - 1];
            if ($this->board->positionExists($left) && $this->isThereOpponentPiece($left) &&
                $this->board->pieceAt($left) === $this->chessMatch->getEnPassantVulnerable()
            ) {
                $moves[$left['row'] + $direction][$left['column']] = true;
            }

            $right = ['row' => $position['row'], 'column' => $position['column'] + 1];
            if ($this->board->positionExists($right) && $this->isThereOpponentPiece($right) &&
                $this->board->pieceAt($right) === $this->chessMatch->getEnPassantVulnerable()
            ) {
                $moves[$right['row'] + $direction][$right['column']] = true;
            }
        }

        return $moves;
    }

    private function isThereOpponentPiece($position) {
        $piece = $this->board->pieceAt($position);
        return $piece !== null && $piece->getColor() !== $this->color;
    }

    public function getColor() {
        return $this->color;
    }
}

?>
