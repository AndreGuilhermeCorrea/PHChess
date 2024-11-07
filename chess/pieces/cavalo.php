<?php

class Knight {
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
        $knightMoves = [
            ['row' => -1, 'column' => -2],
            ['row' => -2, 'column' => -1],
            ['row' => -2, 'column' => +1],
            ['row' => -1, 'column' => +2],
            ['row' => +1, 'column' => +2],
            ['row' => +2, 'column' => +1],
            ['row' => +2, 'column' => -1],
            ['row' => +1, 'column' => -2],
        ];

        foreach ($knightMoves as $move) {
            $target = [
                'row' => $position['row'] + $move['row'],
                'column' => $position['column'] + $move['column']
            ];
            
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
