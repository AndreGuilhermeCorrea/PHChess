

// public/js/pieces/rei.js
class King {
    constructor(color, board, chessMatch) {
        this.color = color;
        this.board = board;
        this.chessMatch = chessMatch;
        this.moveCount = 0;
    }

    // Método auxiliar para verificar se o rei pode mover-se para uma posição
    canMove(position) {
        const piece = this.board.piece(position);
        return piece == null || piece.color !== this.color; // Válido se a posição estiver vazia ou tiver uma peça adversária
    }

    // Método auxiliar para verificar se a torre está apta para o roque
    testRookCastling(position) {
        const piece = this.board.piece(position);
        return piece != null && piece instanceof Rook && piece.color === this.color && piece.moveCount === 0;
    }

    // Método para verificar os movimentos possíveis do rei
    possibleMoves(position) {
        const mat = Array.from({ length: 8 }, () => Array(8).fill(false)); // Matriz de movimentos
        const directions = [
            { row: -1, column: 0 },  // Acima
            { row: 1, column: 0 },   // Abaixo
            { row: 0, column: -1 },  // Esquerda
            { row: 0, column: 1 },   // Direita
            { row: -1, column: -1 }, // Noroeste
            { row: -1, column: 1 },  // Nordeste
            { row: 1, column: 1 },   // Sudeste
            { row: 1, column: -1 }   // Sudoeste
        ];

        // Movimentos normais do rei
        directions.forEach(direction => {
            const target = { row: position.row + direction.row, column: position.column + direction.column };
            if (this.board.positionExists(target) && this.canMove(target)) {
                mat[target.row][target.column] = true;
            }
        });

        // Jogada especial roque
        if (this.moveCount === 0 && !this.chessMatch.inCheck(this.color)) {
            // Roque pequeno
            const kingSideRookPosition = { row: position.row, column: position.column + 3 };
            if (this.testRookCastling(kingSideRookPosition)) {
                const p1 = { row: position.row, column: position.column + 1 };
                const p2 = { row: position.row, column: position.column + 2 };
                if (!this.board.thereIsAPiece(p1) && !this.board.thereIsAPiece(p2)) {
                    mat[position.row][position.column + 2] = true; // Movimento de roque pequeno
                }
            }

            // Roque grande
            const queenSideRookPosition = { row: position.row, column: position.column - 4 };
            if (this.testRookCastling(queenSideRookPosition)) {
                const p1 = { row: position.row, column: position.column - 1 };
                const p2 = { row: position.row, column: position.column - 2 };
                const p3 = { row: position.row, column: position.column - 3 };
                if (!this.board.thereIsAPiece(p1) && !this.board.thereIsAPiece(p2) && !this.board.thereIsAPiece(p3)) {
                    mat[position.row][position.column - 2] = true; // Movimento de roque grande
                }
            }
        }

        return mat;
    }
}

export default King;
// Fim do codigo