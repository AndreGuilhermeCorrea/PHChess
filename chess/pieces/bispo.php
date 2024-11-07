<?php

class Bishop {
    private $color;
    private $board;

    public function __construct($color, $board) {
        $this->color = $color;
        $this->board = $board;
    }

    private function canMove($position) {
        $piece = $this->board->pieceAt($position);
        return $piece === null || $piece->getColor() !== $this->color;
    }

    public function possibleMoves($position) {
        $moves = array_fill(0, 8, array_fill(0, 8, false));
        $directions = [
            ['row' => -1, 'column' => -1], // Noroeste
            ['row' => -1, 'column' => 1],  // Nordeste
            ['row' => 1, 'column' => 1],   // Sudeste
            ['row' => 1, 'column' => -1]   // Sudoeste
        ];

        foreach ($directions as $direction) {
            $target = [
                'row' => $position['row'] + $direction['row'],
                'column' => $position['column'] + $direction['column']
            ];

            while ($this->board->positionExists($target) && !$this->board->hasPieceAt($target)) {
                $moves[$target['row']][$target['column']] = true;
                $target = [
                    'row' => $target['row'] + $direction['row'],
                    'column' => $target['column'] + $direction['column']
                ];
            }

            if ($this->board->positionExists($target) && $this->canMove($target)) {
                $moves[$target['row']][$target['column']] = true;
            }
        }

        return $moves;
    }

    public function getColor() {
        return $this->color;
    }
}
?>
