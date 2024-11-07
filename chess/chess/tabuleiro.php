<?php

class Board {
    private $rows;
    private $columns;
    private $grid;

    public function __construct($rows, $columns) {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->grid = array_fill(0, $rows, array_fill(0, $columns, null));
    }

    // Método para colocar uma peça em uma posição
    public function placePiece($piece, $position) {
        if (!$this->positionExists($position)) {
            throw new Exception("Posição inválida no tabuleiro!");
        }
        if ($this->pieceAt($position) !== null) {
            throw new Exception("Já existe uma peça nessa posição!");
        }
        $this->grid[$position['row']][$position['column']] = $piece;
    }

    // Método para remover uma peça de uma posição
    public function removePiece($position) {
        if (!$this->positionExists($position)) {
            throw new Exception("Posição inválida no tabuleiro!");
        }
        $piece = $this->grid[$position['row']][$position['column']];
        $this->grid[$position['row']][$position['column']] = null;
        return $piece;
    }

    // Método para obter a peça em uma posição específica
    public function pieceAt($position) {
        if ($this->positionExists($position)) {
            return $this->grid[$position['row']][$position['column']];
        }
        return null;
    }

    // Método para mover uma peça para uma nova posição
    public function movePiece($oldPosition, $newPosition) {
        if (!$this->positionExists($newPosition) || !$this->positionExists($oldPosition)) {
            throw new Exception("Posição inválida no tabuleiro!");
        }
        
        $piece = $this->removePiece($oldPosition);
        $this->placePiece($piece, $newPosition);
    }

    // Método para verificar se a posição existe no tabuleiro
    public function positionExists($position) {
        return $position['row'] >= 0 && $position['row'] < $this->rows &&
               $position['column'] >= 0 && $position['column'] < $this->columns;
    }

    // Método para verificar se existe uma peça na posição especificada
    public function hasPieceAt($position) {
        return $this->pieceAt($position) !== null;
    }

    // Método para retornar a matriz completa de peças para visualização do frontend
    public function getMatrixPieces() {
        return $this->grid;
    }
}

?>
