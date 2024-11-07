// public/js/tabuleiro.js

class Board {
    constructor(rows, columns) {
        this.rows = rows;
        this.columns = columns;
        this.grid = Array.from({ length: rows }, () => Array(columns).fill(null));
    }

    // Converte IDs do DOM (ex: "a1") para coordenadas do tabuleiro
    convertDOMIdToPosition(squareId) {
        const col = squareId.charCodeAt(0) - 'a'.charCodeAt(0); // Converte coluna para índice (0-7)
        const row = 8 - parseInt(squareId.charAt(1), 10); // Converte linha (1-8) para índice (7-0)
        return { row, column: col };
    }

    // Verifica se a posição está dentro dos limites do tabuleiro
    positionExists(position) {
        return (
            position.row >= 0 && position.row < this.rows &&
            position.column >= 0 && position.column < this.columns
        );
    }

    // Coloca uma peça na posição especificada
    placePiece(piece, position) {
        if (this.positionExists(position)) {
            this.grid[position.row][position.column] = piece;
            piece.position = position; // Atualiza a posição da peça
        }
    }

    // Remove uma peça de uma posição
    removePiece(position) {
        if (this.positionExists(position)) {
            const removedPiece = this.grid[position.row][position.column];
            this.grid[position.row][position.column] = null;
            return removedPiece;
        }
        return null;
    }

    // Retorna a peça na posição especificada (ou null se estiver vazia)
    piece(position) {
        if (this.positionExists(position)) {
            return this.grid[position.row][position.column];
        }
        return null;
    }

    // Verifica se uma posição contém uma peça
    thereIsAPiece(position) {
        return this.piece(position) !== null;
    }
}

export default Board;
// Fim do codigo
