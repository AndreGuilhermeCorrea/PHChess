// public/js/chess/partida.js
// Controla o estado da partida de xadrez

import Board from "./tabueiro.js";
import Pawn from '../pieces/peao.js';
import Rook from '../pieces/torre.js';
import Knight from '../pieces/cavalo.js';
import Bishop from '../pieces/bispo.js';
import Queen from '../pieces/rainha.js';
import King from '../pieces/rei.js';

class ChessMatch {
    constructor(pieces) {
        this.board = new Board(8, 8);
        this.turn = 1;
        this.currentPlayer = 'white';
        this.inCheck = false;
        this.inCheckmate = false;
        this.enPassantVulnerable = null;
        this.promotion = null;
        this.piecesOnBoard = pieces;
        this.capturedPieces = [];
        
        this.initializeGame();
    }

    // Método validateMove para validar o movimento
    validateMove(pieceId, sourceSquareId, targetSquareId) {
        const piece = this.piecesOnBoard[pieceId];

        if (!piece) {
            console.log("Nenhuma peça encontrada na posição de origem.");
            return false;
        }

        // Verifica se a peça pertence ao jogador atual
        if (piece.color !== this.currentPlayer) {
            console.log("Movimento inválido: peça pertence ao oponente.");
            return false;
        }

        // Converte IDs do DOM para posições no tabuleiro
        const startPosition = this.board.convertDOMIdToPosition(sourceSquareId);
        const endPosition = this.board.convertDOMIdToPosition(targetSquareId);

        // Verifica se o movimento está entre os movimentos possíveis da peça
        const possibleMoves = piece.possibleMoves(startPosition);
        if (!possibleMoves[endPosition.row][endPosition.column]) {
            console.log("Movimento inválido: fora dos movimentos permitidos.");
            return false;
        }

        // Simula o movimento e verifica se o jogador fica em xeque
        const capturedPiece = this.makeMove(startPosition, endPosition);
        const inCheckAfterMove = this.isInCheck(this.currentPlayer);
        this.undoMove(startPosition, endPosition, capturedPiece);

        if (inCheckAfterMove) {
            console.log("Movimento inválido: o movimento deixaria o rei em xeque.");
            return false;
        }

        // Movimento válido
        return true;
    }

    // Inicializa a partida de acordo com as posições definidas no HTML
    initializeGame(pieces) {
        Object.keys(this.piecesOnBoard).forEach(pieceId => {
            const piece = this.piecesOnBoard[pieceId];
            const square = document.getElementById(pieceId).parentElement;
            const position = this.board.convertDOMIdToPosition(square.id); // Função auxiliar
            piece.position = position;
            this.board.placePiece(piece, position);
        });
    }

    // Alterna o turno entre os jogadores
    nextTurn() {
        this.turn++;
        this.currentPlayer = this.currentPlayer === 'white' ? 'black' : 'white';
    }

    // Verifica se um jogador está em xeque
    isInCheck(color) {
        const kingPosition = this.findKingPosition(color);
        return this.isUnderAttack(kingPosition, this.getOpponent(color));
    }

    // Verifica se a posição de um rei está sob ataque
    isUnderAttack(position, opponentColor) {
        return this.piecesOnBoard.some(piece => {
            if (piece.color === opponentColor) {
                return piece.possibleMoves(piece.position).some(move => move.row === position.row && move.column === position.column);
            }
            return false;
        });
    }

    // Encontra a posição do rei de uma determinada cor
    findKingPosition(color) {
        const king = this.piecesOnBoard.find(piece => piece instanceof King && piece.color === color);
        return king ? king.position : null;
    }

    // Executa um movimento e retorna a peça capturada, se houver
    makeMove(startPosition, endPosition) {
        const piece = this.board.piece(startPosition);
        const capturedPiece = this.board.removePiece(endPosition);

        this.board.movePiece(startPosition, endPosition);

        if (piece instanceof Pawn) {
            if (Math.abs(startPosition.row - endPosition.row) === 2) {
                this.enPassantVulnerable = piece;
            } else {
                this.enPassantVulnerable = null;
            }
        } else {
            this.enPassantVulnerable = null;
        }

        if (this.isInCheck(this.currentPlayer)) {
            this.undoMove(startPosition, endPosition, capturedPiece);
            throw new Error("Não é possível se colocar em xeque!");
        }

        if (capturedPiece) {
            this.capturedPieces.push(capturedPiece);
        }

        this.nextTurn();

        return capturedPiece;
    }

    // Desfaz um movimento para corrigir jogadas inválidas
    undoMove(startPosition, endPosition, capturedPiece) {
        const piece = this.board.removePiece(endPosition);
        this.board.placePiece(piece, startPosition);

        if (capturedPiece) {
            this.board.placePiece(capturedPiece, endPosition);
            this.capturedPieces.pop();
        }

        this.turn--;
        this.currentPlayer = this.currentPlayer === 'white' ? 'black' : 'white';
    }

    // Retorna a cor do oponente
    getOpponent(color) {
        return color === 'white' ? 'black' : 'white';
    }
}

export default ChessMatch;
// Fim do codigo