<?php

require_once 'chess/tabuleiro.php';
require_once 'chess/partida.php';
require_once 'pieces/peao.php';
require_once 'pieces/torre.php';
require_once 'pieces/cavalo.php';
require_once 'pieces/rei.php';
require_once 'pieces/rainha.php';
require_once 'pieces/bispo.php';

// Instancia o tabuleiro e a partida
$board = new Board(8, 8);
$chessMatch = new ChessMatch($board);

// Cria e posiciona as peças no tabuleiro
$pieces = [

    // Peças brancas
    ['piece' => new Pawn('white', $board, $chessMatch), 'position' => ['row' => 1, 'column' => 0]],
    ['piece' => new Pawn('white', $board, $chessMatch), 'position' => ['row' => 1, 'column' => 1]],
    ['piece' => new Pawn('white', $board, $chessMatch), 'position' => ['row' => 1, 'column' => 2]],
    ['piece' => new Pawn('white', $board, $chessMatch), 'position' => ['row' => 1, 'column' => 3]],
    ['piece' => new Pawn('white', $board, $chessMatch), 'position' => ['row' => 1, 'column' => 4]],
    ['piece' => new Pawn('white', $board, $chessMatch), 'position' => ['row' => 1, 'column' => 5]],
    ['piece' => new Pawn('white', $board, $chessMatch), 'position' => ['row' => 1, 'column' => 6]],
    ['piece' => new Pawn('white', $board, $chessMatch), 'position' => ['row' => 1, 'column' => 7]],
    ['piece' => new Rook('white', $board, $chessMatch), 'position' => ['row' => 0, 'column' => 0]],
    ['piece' => new Rook('white', $board, $chessMatch), 'position' => ['row' => 0, 'column' => 7]],
    ['piece' => new Knight('white', $board, $chessMatch), 'position' => ['row' => 0, 'column' => 1]],
    ['piece' => new Knight('white', $board, $chessMatch), 'position' => ['row' => 0, 'column' => 6]],
    ['piece' => new Bishop('white', $board, $chessMatch), 'position' => ['row' => 0, 'column' => 2]],
    ['piece' => new Bishop('white', $board, $chessMatch), 'position' => ['row' => 0, 'column' => 5]],
    ['piece' => new Queen('white', $board, $chessMatch), 'position' => ['row' => 0, 'column' => 3]],
    ['piece' => new King('white', $board, $chessMatch), 'position' => ['row' => 0, 'column' => 4]],

    // Peças pretas
    ['piece' => new Pawn('black', $board, $chessMatch), 'position' => ['row' => 6, 'column' => 0]],
    ['piece' => new Pawn('black', $board, $chessMatch), 'position' => ['row' => 6, 'column' => 1]],
    ['piece' => new Pawn('black', $board, $chessMatch), 'position' => ['row' => 6, 'column' => 2]],
    ['piece' => new Pawn('black', $board, $chessMatch), 'position' => ['row' => 6, 'column' => 3]],
    ['piece' => new Pawn('black', $board, $chessMatch), 'position' => ['row' => 6, 'column' => 4]],
    ['piece' => new Pawn('black', $board, $chessMatch), 'position' => ['row' => 6, 'column' => 5]],
    ['piece' => new Pawn('black', $board, $chessMatch), 'position' => ['row' => 6, 'column' => 6]],
    ['piece' => new Pawn('black', $board, $chessMatch), 'position' => ['row' => 6, 'column' => 7]],
    ['piece' => new Rook('black', $board, $chessMatch), 'position' => ['row' => 7, 'column' => 0]],
    ['piece' => new Rook('black', $board, $chessMatch), 'position' => ['row' => 7, 'column' => 7]],
    ['piece' => new Knight('black', $board, $chessMatch), 'position' => ['row' => 7, 'column' => 1]],
    ['piece' => new Knight('black', $board, $chessMatch), 'position' => ['row' => 7, 'column' => 6]],
    ['piece' => new Bishop('black', $board, $chessMatch), 'position' => ['row' => 7, 'column' => 2]],
    ['piece' => new Bishop('black', $board, $chessMatch), 'position' => ['row' => 7, 'column' => 5]],
    ['piece' => new Queen('black', $board, $chessMatch), 'position' => ['row' => 7, 'column' => 3]],
    ['piece' => new King('black', $board, $chessMatch), 'position' => ['row' => 7, 'column' => 4]],
];

// Passa as peças para o controle da partida
$chessMatch->setPieces($pieces);

?>
