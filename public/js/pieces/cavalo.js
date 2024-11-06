class Knight {
    constructor(color, board) {
        this.color = color;
        this.board = board;
    }

    // Método auxiliar para verificar se o cavalo pode mover-se para uma posição
    canMove(position) {
        const piece = this.board.piece(position);
        return piece == null || piece.color !== this.color; // Válido se a posição estiver vazia ou tiver uma peça oponente
    }

    // Método para verificar os movimentos possíveis do cavalo
    possibleMoves(position) {
        const mat = Array.from({ length: 8 }, () => Array(8).fill(false)); // Matriz de movimentos
        const moves = [
            { row: -1, column: -2 },
            { row: -2, column: -1 },
            { row: -2, column: +1 },
            { row: -1, column: +2 },
            { row: +1, column: +2 },
            { row: +2, column: +1 },
            { row: +2, column: -1 },
            { row: +1, column: -2 },
        ];

        moves.forEach(move => {
            const target = { row: position.row + move.row, column: position.column + move.column };
            if (this.board.positionExists(target) && this.canMove(target)) {
                mat[target.row][target.column] = true;
            }
        });

        return mat;
    }
}

export default Knight;
