
// public/js/chess/chess.js 
// Arquivo que inicia a partida e gerencia os eventos

import Pawn from '../pieces/peao.js';
import Knight from '../pieces/cavalo.js';
import Rook from '../pieces/torre.js';
import King from '../pieces/rei.js';
import Queen from '../pieces/rainha.js';
import Bishop from '../pieces/bispo.js';
import ChessMatch from './partida.js';
import Board from './tabuleiro.js';

// Instanciação do tabuleiro
const board = new Board(8, 8);
// Intanciação da lógica do jogo
const chessMatch = new ChessMatch();
// Criação das peças no tabuleiro com suas posições iniciais
const pieces = {
    // Peças brancas
    'pawn-white-a2': new Pawn('white', board, chessMatch),
    'pawn-white-b2': new Pawn('white', board, chessMatch),
    'pawn-white-c2': new Pawn('white', board, chessMatch),
    'pawn-white-d2': new Pawn('white', board, chessMatch),
    'pawn-white-e2': new Pawn('white', board, chessMatch),
    'pawn-white-f2': new Pawn('white', board, chessMatch),
    'pawn-white-g2': new Pawn('white', board, chessMatch),
    'pawn-white-h2': new Pawn('white', board, chessMatch),
    'knight-white-b1': new Knight('white', board, chessMatch),
    'knight-white-g1': new Knight('white', board, chessMatch),
    'rook-white-a1': new Rook('white', board, chessMatch),
    'rook-white-h1': new Rook('white', board, chessMatch),
    'bishop-white-c1': new Bishop('white', board, chessMatch),
    'bishop-white-f1': new Bishop('white', board, chessMatch),
    'queen-white-d1': new Queen('white', board, chessMatch),
    'king-white-e1': new King('white', board, chessMatch),
    // Peças pretas
    'pawn-black-a7': new Pawn('black', board, chessMatch),
    'pawn-black-b7': new Pawn('black', board, chessMatch),
    'pawn-black-c7': new Pawn('black', board, chessMatch),
    'pawn-black-d7': new Pawn('black', board, chessMatch),
    'pawn-black-e7': new Pawn('black', board, chessMatch),
    'pawn-black-f7': new Pawn('black', board, chessMatch),
    'pawn-black-g7': new Pawn('black', board, chessMatch),
    'pawn-black-h7': new Pawn('black', board, chessMatch),
    'knight-black-b8': new Knight('black', board, chessMatch),
    'knight-black-g8': new Knight('black', board, chessMatch),
    'rook-black-a8': new Rook('black', board, chessMatch),
    'rook-black-h8': new Rook('black', board, chessMatch),
    'bishop-black-c8': new Bishop('black', board, chessMatch),
    'bishop-black-f8': new Bishop('black', board, chessMatch),
    'queen-black-d8': new Queen('black', board, chessMatch),
    'king-black-e8': new King('black', board, chessMatch),   
};

// Atribui as peças ao objeto chessMatch
chessMatch.setPieces(pieces);

// Evento para capturar a peça arrastada e a posição de origem
document.querySelectorAll('.piece').forEach(piece => {
    piece.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('pieceId', e.target.id); // ID da peça
        e.dataTransfer.setData('squareId', e.target.parentElement.id); // ID da posição de origem
    });
});

// Evento para permitir o drop nas casas do tabuleiro
document.querySelectorAll('.square').forEach(square => {
    square.addEventListener('dragover', (e) => {
        e.preventDefault();
    });

    // Evento de drop para mover a peça
    square.addEventListener('drop', (e) => {
        e.preventDefault();

        const pieceId = e.dataTransfer.getData('pieceId');
        const sourceSquareId = e.dataTransfer.getData('squareId');
        const targetSquare = e.target.closest('.square');

        // Valida o movimento
        const isValid = chessMatch.validateMove(pieceId, sourceSquareId, targetSquare.id); 
        if (isValid) {
            const piece = document.getElementById(pieceId);
            targetSquare.appendChild(piece);
        } else {
            alert("Movimento inválido!");
        }
    });
});

// Fim do código