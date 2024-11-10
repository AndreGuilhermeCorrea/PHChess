<?php
// chess/pieces/cavalo.php
class Knight {
    private $color;
    private $board;

    public function __construct($color, $board, $chessMatch) {
        $this->color = $color;
        $this->board = $board;
        $this->chessMatch = $chessMatch;
    }

    public function getType() {
        return 'cavalo';
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

    // Movimentos possíveis para o cavalo
    public function possibleMoves($position) {
        $moves = array_fill(0, 8, array_fill(0, 8, false));
        $directions = [
            ['row' => -1, 'column' => -2],
            ['row' => -2, 'column' => -1],
            ['row' => -2, 'column' => +1],
            ['row' => -1, 'column' => +2],
            ['row' => +1, 'column' => +2],
            ['row' => +2, 'column' => +1],
            ['row' => +2, 'column' => -1],
            ['row' => +1, 'column' => -2],
        ];
    
        // Para cada direção, verifica se o movimento é válido
        foreach ($directions as $move) {
            $target = [
                'row' => $position['row'] + $move['row'], 
                'column' => $position['column'] + $move['column']
            ];
    
            // Verifica se o movimento está dentro dos limites e se pode capturar uma peça adversária
            if ($this->board->positionExists($target)) {
                $targetPiece = $this->board->pieceAt($target);
                if ($targetPiece === null || $targetPiece->getColor() !== $this->color) {
                    $moves[$target['row']][$target['column']] = true;
                }
            }
        }
    
        return $moves;
    }
}

//Fim do código