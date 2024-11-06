class Pawn {
    constructor(color, board, chessMatch) {
        this.color = color;
        this.board = board;
        this.chessMatch = chessMatch;
        this.moveCount = 0;
    }

    possibleMoves(position) {
        const mat = Array.from({ length: 8 }, () => Array(8).fill(false)); 
        const direction = this.color === 'white' ? -1 : 1; 
        const startingRow = this.color === 'white' ? 6 : 1;

        let p = { row: position.row + direction, column: position.column };
        if (this.board.positionExists(p) && !this.board.thereIsAPiece(p)) {
            mat[p.row][p.column] = true;
        }

        if (position.row === startingRow) {
            let p2 = { row: position.row + (2 * direction), column: position.column };
            let p1 = { row: position.row + direction, column: position.column };
            if (this.board.positionExists(p2) && !this.board.thereIsAPiece(p2) &&
                this.board.positionExists(p1) && !this.board.thereIsAPiece(p1)) {
                mat[p2.row][p2.column] = true;
            }
        }

        p = { row: position.row + direction, column: position.column - 1 };
        if (this.board.positionExists(p) && this.isThereOpponentPiece(p)) {
            mat[p.row][p.column] = true;
        }

        p = { row: position.row + direction, column: position.column + 1 };
        if (this.board.positionExists(p) && this.isThereOpponentPiece(p)) {
            mat[p.row][p.column] = true;
        }

        if ((this.color === 'white' && position.row === 3) || (this.color === 'black' && position.row === 4)) {
            const left = { row: position.row, column: position.column - 1 };
            if (this.board.positionExists(left) && this.isThereOpponentPiece(left) &&
                this.board.piece(left) === this.chessMatch.getEnPassantVulnerable()) {
                mat[left.row + direction][left.column] = true;
            }

            const right = { row: position.row, column: position.column + 1 };
            if (this.board.positionExists(right) && this.isThereOpponentPiece(right) &&
                this.board.piece(right) === this.chessMatch.getEnPassantVulnerable()) {
                mat[right.row + direction][right.column] = true;
            }
        }

        return mat;
    }

    isThereOpponentPiece(position) {
        const piece = this.board.piece(position);
        return piece != null && piece.color !== this.color;
    }
}

export default Pawn;
