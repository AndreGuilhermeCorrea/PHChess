<?php
// chess/pieces/bispo.php

require_once __DIR__ . '/../interfaces/pecas_interface.php';

class Bishop implements ChessPieceInterface {
    private $color;
    private $board;

    public function __construct($color, $board, $chessMatch) {
        $this->color = $color;
        $this->board = $board;
        $this->chessMatch = $chessMatch;
    }

    public function getType() {
        return 'bispo';
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

    // Verifica se a peça pode se mover para a posição
    private function canMove($position) {
        $piece = $this->board->pieceAt($position);
        return $piece === null || $piece->getColor() !== $this->color;
    }

    // Movimentos possíveis para o bispo
    public function possibleMoves($position) {
        $moves = array_fill(0, 8, array_fill(0, 8, false));
        $directions = [
            ['row' => -1, 'column' => -1], // Noroeste
            ['row' => -1, 'column' => 1],  // Nordeste
            ['row' => 1, 'column' => 1],   // Sudeste
            ['row' => 1, 'column' => -1]   // Sudoeste
        ];
        
        // Para cada direção, verifica se o movimento é válido
        foreach ($directions as $direction) {
            $target = [
                'row' => $position['row'] + $direction['row'],
                'column' => $position['column'] + $direction['column']
            ];
            // Enquanto a posição existir no tabuleiro
            while ($this->board->positionExists($target)) {
                // Se a posição contém uma peça adversária
                if ($this->board->hasPieceAt($target, $this->color)) {
                    // Permite captura da peça adversária
                    $moves[$target['row']][$target['column']] = true;
                    break;
                } // E se a posição estiver vazia 
                elseif ($this->board->pieceAt($target) === null) {
                    // Espaço vazio - movimento permitido
                    $moves[$target['row']][$target['column']] = true;
                } // Se nenhuma das condições acima for atendida
                else {
                    // Interrompe a direção
                    break;
                }

                // Move para a próxima posição na direção
                $target['row'] += $direction['row'];
                $target['column'] += $direction['column'];
            }
        }
        return $moves;
    }
    

}
// Fim do código
