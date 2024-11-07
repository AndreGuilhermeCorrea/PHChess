<?php
// game_board.php
// Inclui o cabeçalho da página
include __DIR__ . '/template/header.php';
?>
        <div id="board-container">
            <h2>PHChess</h2>

            <div class="chess-board">
                <!-- Linha 1 -->
                <div class="square light" id="a1"><img src="img/pieces/tp.png" class="piece" id="rook-black-a" draggable="true"></div>
                <div class="square dark" id="b1"><img src="img/pieces/cp.png" class="piece" id="knight-black-b" draggable="true"></div>
                <div class="square light" id="c1"><img src="img/pieces/bp.png" class="piece" id="bishop-black-c" draggable="true"></div>
                <div class="square dark" id="d1"><img src="img/pieces/qp.png" class="piece" id="queen-black" draggable="true"></div>
                <div class="square light" id="e1"><img src="img/pieces/kp.png" class="piece" id="king-black" draggable="true"></div>
                <div class="square dark" id="f1"><img src="img/pieces/bp.png" class="piece" id="bishop-black-f" draggable="true"></div>
                <div class="square light" id="g1"><img src="img/pieces/cp.png" class="piece" id="knight-black-g" draggable="true"></div>
                <div class="square dark" id="h1"><img src="img/pieces/tp.png" class="piece" id="rook-black-h" draggable="true"></div>
                <!-- Linha 2 -->
                <div class="square dark" id="a2"><img src="img/pieces/pp.png" class="piece" id="pawn-black-a2" draggable="true"></div>
                <div class="square light" id="b2"><img src="img/pieces/pp.png" class="piece" id="pawn-black-b2" draggable="true"></div>
                <div class="square dark" id="c2"><img src="img/pieces/pp.png" class="piece" id="pawn-black-c2" draggable="true"></div>
                <div class="square light" id="d2"><img src="img/pieces/pp.png" class="piece" id="pawn-black-d2" draggable="true"></div>
                <div class="square dark" id="e2"><img src="img/pieces/pp.png" class="piece" id="pawn-black-e2" draggable="true"></div>
                <div class="square light" id="f2"><img src="img/pieces/pp.png" class="piece" id="pawn-black-f2" draggable="true"></div>
                <div class="square dark" id="g2"><img src="img/pieces/pp.png" class="piece" id="pawn-black-g2" draggable="true"></div>
                <div class="square light" id="h2"><img src="img/pieces/pp.png" class="piece" id="pawn-black-h2" draggable="true"></div>
                <!-- Linha 3 -->
                <div class="square light" id="a3"></div>
                <div class="square dark" id="b3"></div>
                <div class="square light" id="c3"></div>
                <div class="square dark" id="d3"></div>
                <div class="square light" id="e3"></div>
                <div class="square dark" id="f3"></div>
                <div class="square light" id="g3"></div>
                <div class="square dark" id="h3"></div>
                <!-- Linha 4 -->
                <div class="square dark" id="a4"></div>
                <div class="square light" id="b4"></div>
                <div class="square dark" id="c4"></div>
                <div class="square light" id="d4"></div>
                <div class="square dark" id="e4"></div>
                <div class="square light" id="f4"></div>
                <div class="square dark" id="g4"></div>
                <div class="square light" id="h4"></div>
                <!-- Linha 5 -->
                <div class="square light" id="a5"></div>    
                <div class="square dark" id="b5"></div>
                <div class="square light" id="c5"></div>
                <div class="square dark" id="d5"></div>
                <div class="square light" id="e5"></div>
                <div class="square dark" id="f5"></div>
                <div class="square light" id="g5"></div>
                <div class="square dark" id="h5"></div>
                <!-- Linha 6 -->
                <div class="square dark" id="a6"></div>
                <div class="square light" id="b6"></div>
                <div class="square dark" id="c6"></div> 
                <div class="square light" id="d6"></div>
                <div class="square dark" id="e6"></div>
                <div class="square light" id="f6"></div>
                <div class="square dark" id="g6"></div>
                <div class="square light" id="h6"></div>
                <!-- Linha 7 -->
                <div class="square light" id="a7"><img src="img/pieces/pb.png" class="piece" id="pawn-white-a7" draggable="true"></div>
                <div class="square dark" id="b7"><img src="img/pieces/pb.png" class="piece" id="pawn-white-b7" draggable="true"></div>
                <div class="square light" id="c7"><img src="img/pieces/pb.png" class="piece" id="pawn-white-c7" draggable="true"></div>
                <div class="square dark" id="d7"><img src="img/pieces/pb.png" class="piece" id="pawn-white-d7" draggable="true"></div>
                <div class="square light" id="e7"><img src="img/pieces/pb.png" class="piece" id="pawn-white-e7" draggable="true"></div>
                <div class="square dark" id="f7"><img src="img/pieces/pb.png" class="piece" id="pawn-white-f7" draggable="true"></div>
                <div class="square light" id="g7"><img src="img/pieces/pb.png" class="piece" id="pawn-white-g7" draggable="true"></div>
                <div class="square dark" id="h7"><img src="img/pieces/pb.png" class="piece" id="pawn-white-h7" draggable="true"></div>
                <!-- Linha 8 -->
                <div class="square dark" id="a8"><img src="img/pieces/tb.png" class="piece" id="rook-white-a" draggable="true"></div>
                <div class="square light" id="b8"><img src="img/pieces/cb.png" class="piece" id="knight-white-b" draggable="true"></div>
                <div class="square dark" id="c8"><img src="img/pieces/bb.png" class="piece" id="bishop-white-c" draggable="true"></div>
                <div class="square light" id="d8"><img src="img/pieces/kb.png" class="piece" id="king-white" draggable="true"></div>
                <div class="square dark" id="e8"><img src="img/pieces/qb.png" class="piece" id="queen-white" draggable="true"></div>
                <div class="square light" id="f8"><img src="img/pieces/bb.png" class="piece" id="bishop-white-f" draggable="true"></div>
                <div class="square dark" id="g8"><img src="img/pieces/cb.png" class="piece" id="knight-white-g" draggable="true"></div>
                <div class="square light" id="h8"><img src="img/pieces/tb.png" class="piece" id="rook-white-h" draggable="true"></div>
            </div>
<?php
// Inclui o painel de informações
include __DIR__ . '/painel.php';
// Inclui o rodapé
include __DIR__ . '/template/footer.php';
?>