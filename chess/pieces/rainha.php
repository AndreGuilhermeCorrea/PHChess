<?php
// chess/pieces/rainha.php
class Queen {
    private $color;
    private $board;

    public function __construct($color, $board, $chessMatch) {
        $this->color = $color;
        $this->board = $board;
        $this->chessMatch = $chessMatch;
    }

    public function getType() {
        return 'queen';
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

    // Movimentos possíveis para a rainha
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
    
        // Para cada direção, verifica se o movimento é válido
        foreach ($directions as $direction) {
            $target = [
                'row' => $position['row'] + $direction['row'], 
                'column' => $position['column'] + $direction['column']
            ];
            
            // Enquanto a posição existir no tabuleiro e não houver peça do mesmo jogador
            while ($this->board->positionExists($target) && !$this->board->hasPieceAt($target, $this->color)) {
                $moves[$target['row']][$target['column']] = true;
                $target = [
                    'row' => $target['row'] + $direction['row'],
                    'column' => $target['column'] + $direction['column']
                ];
            }
    
            // Captura a peça adversária, se houver
            if ($this->board->positionExists($target) && $this->canMove($target)) {
                $moves[$target['row']][$target['column']] = true;
            }
        }
    
        return $moves;
    }
}

// Fim do código