import King from './pieces/king.js';

const board = new Board();
const chessMatch = new ChessMatch();
const kingWhite = new King('white', board, chessMatch);

// Função de validação para o movimento do rei
function validateKingMove(piece, position, target) {
    const possibleMoves = piece.possibleMoves(position);
    return possibleMoves[target.row][target.column];
}

// Exemplo de uso
const position = { row: 7, column: 4 }; // posição inicial do rei
const target = { row: 7, column: 6 }; // destino para roque pequeno
if (validateKingMove(kingWhite, position, target)) {
    console.log("Movimento válido!");
} else {
    console.log("Movimento inválido!");
}
