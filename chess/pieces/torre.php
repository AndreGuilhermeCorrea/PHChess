<?php
// chess/pieces/torre.php
class Rook {
    private $color;
    private $board;

    public function __construct($color, $board, $chessMatch) {
        $this->color = $color;
        $this->board = $board;
        $this->chessMatch = $chessMatch;
    }

    public function setPosition(array $position): void {
        $this->position = $position;
    }

    public function getPosition(): array {
        return $this->position;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getType(): string {
        return 'torre';
    }

    // Verifica se a peça pode se mover para a posição de acordo com a cor
    private function canMove($position) {
        $piece = $this->board->pieceAt($position);
        return $piece === null || $piece->getColor() !== $this->color;
    }

    // Movimentos possíveis para a torre
    public function possibleMoves(array $position): array {
        $moves = array_fill(0, 8, array_fill(0, 8, false));
        $directions = [
            ['row' => -1, 'column' => 0],  // Acima
            ['row' => 1, 'column' => 0],   // Abaixo
            ['row' => 0, 'column' => -1],  // Esquerda
            ['row' => 0, 'column' => 1],   // Direita
        ];

        foreach ($directions as $direction) {
            $target = [
                'row' => $position['row'] + $direction['row'],
                'column' => $position['column'] + $direction['column']
            ];

            // Move-se na direção especificada até atingir o limite do tabuleiro ou uma peça
            while ($this->board->positionExists($target) && !$this->board->hasPieceAt($target, $this->color)) {
                $moves[$target['row']][$target['column']] = true;
                $target = [
                    'row' => $target['row'] + $direction['row'],
                    'column' => $target['column'] + $direction['column']
                ];
            }

            // Se encontrar uma peça adversária na posição final, a captura é permitida
            if ($this->board->positionExists($target) && $this->canMove($target)) {
                $moves[$target['row']][$target['column']] = true;
            }
        }

        return $moves;
    }
}

// Fim do código