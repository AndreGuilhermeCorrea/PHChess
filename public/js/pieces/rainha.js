class Queen {
    constructor(color, board) {
        this.color = color;
        this.board = board;
    }

    // Método auxiliar para verificar se a rainha pode mover-se para uma posição
    canMove(position) {
        const piece = this.board.piece(position);
        return piece == null || piece.color !== this.color; // Válido se a posição estiver vazia ou tiver uma peça adversária
    }

    // Método para verificar os movimentos possíveis da rainha
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

        // Movimentos em todas as direções até encontrar uma peça ou o final do tabuleiro
        directions.forEach(direction => {
            let target = { row: position.row + direction.row, column: position.column + direction.column };

            while (this.board.positionExists(target) && !this.board.thereIsAPiece(target)) {
                mat[target.row][target.column] = true;
                target = { row: target.row + direction.row, column: target.column + direction.column };
            }

            // Se encontrar uma peça adversária na posição final, a captura é permitida
            if (this.board.positionExists(target) && this.canMove(target)) {
                mat[target.row][target.column] = true;
            }
        });

        return mat;
    }
}

export default Queen;
