<?php
// chess/chess/tabuleiro.php

require_once __DIR__ . '/../exceptions/excecoes_xadrez.php';

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
            throw new InvalidPieceException();
        } elseif ($this->pieceAt($position) !== null) {
            throw new InvalidPositionException();
        }else{
            $this->grid[$position['row']][$position['column']] = $piece;
        }
    }

    // Método para remover uma peça de uma posição
    public function removePiece($position) {
        if (!$this->positionExists($position)) {

            throw new InvalidPositionException();
        }else{
            $piece = $this->grid[$position['row']][$position['column']];
            $this->grid[$position['row']][$position['column']] = null;
            return $piece;
        }
    }

    // Método para obter a peça em uma posição específica
    public function pieceAt($position) {
        if ($this->positionExists($position)) {
            return $this->grid[$position['row']][$position['column']];
        }
        return null;
    }

    // Método para mover uma peça para uma nova posição
    public function movePiece($oldPosition, $newPosition)
    {
        if (!$this->positionExists($newPosition) || !$this->positionExists($oldPosition)) {
            throw new InvalidPositionException();
        }else{
            $piece = $this->removePiece($oldPosition);
            $this->placePiece($piece, $newPosition);
        }
    }

    // Método para verificar se a posição existe no tabuleiro
    public function positionExists($position) {
        return $position['row'] >= 0 && $position['row'] < $this->rows &&
               $position['column'] >= 0 && $position['column'] < $this->columns;
    }

    // Método para verificar se a peça na posição é adversária
    public function hasPieceAt($position, $color) {
        $piece = $this->pieceAt($position);
        return $piece !== null && $piece->getColor() !== $color;
    }

    // Método para obter a matriz de peças no tabuleiro
    public function getMatrixPieces()
    {
        $matrix = [];
        $columns = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
    
        for ($row = 0; $row < $this->rows; $row++) {
            for ($col = 0; $col < $this->columns; $col++) {
                $piece = $this->grid[$row][$col];
                $position = $columns[$col] . (8 - $row);
    
                if ($piece) {
                    // Cria o código da peça.
                    $pieceCode = substr($piece->getType(), 0, 1) . ($piece->getColor() === 'black' ? 'p' : 'b');
                    $matrix[$position] = $pieceCode;
                } else {
                    $matrix[$position] = null;
                }
            }
        }
        
        return $matrix;
    }
}

// Fim do código
