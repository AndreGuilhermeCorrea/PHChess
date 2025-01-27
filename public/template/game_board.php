<?php include '../header.php'; ?>
<?php

// Define as posições iniciais das peças no tabuleiro
$initialPieces = [
    // Peças brancas
    'a1' => ['id' => 'tb', 'src' => '../img/pieces/tb.png'],
    'h1' => ['id' => 'tb', 'src' => '../img/pieces/tb.png'],
    'b1' => ['id' => 'cb', 'src' => '../img/pieces/cb.png'],
    'g1' => ['id' => 'cb', 'src' => '../img/pieces/cb.png'],
    'e1' => ['id' => 'kb', 'src' => '../img/pieces/kb.png'],
    'd1' => ['id' => 'qb', 'src' => '../img/pieces/qb.png'],
    'c1' => ['id' => 'bb', 'src' => '../img/pieces/bb.png'],
    'f1' => ['id' => 'bb', 'src' => '../img/pieces/bb.png'],
    'a2' => ['id' => 'pb', 'src' => '../img/pieces/pb.png'],
    'b2' => ['id' => 'pb', 'src' => '../img/pieces/pb.png'],
    'c2' => ['id' => 'pb', 'src' => '../img/pieces/pb.png'],
    'd2' => ['id' => 'pb', 'src' => '../img/pieces/pb.png'],
    'e2' => ['id' => 'pb', 'src' => '../img/pieces/pb.png'],
    'f2' => ['id' => 'pb', 'src' => '../img/pieces/pb.png'],
    'g2' => ['id' => 'pb', 'src' => '../img/pieces/pb.png'],
    'h2' => ['id' => 'pb', 'src' => '../img/pieces/pb.png'],
    // Peças pretas
    'a8' => ['id' => 'tp', 'src' => '../img/pieces/tp.png'],
    'h8' => ['id' => 'tp', 'src' => '../img/pieces/tp.png'],
    'b8' => ['id' => 'cp', 'src' => '../img/pieces/cp.png'],
    'g8' => ['id' => 'cp', 'src' => '../img/pieces/cp.png'],
    'e8' => ['id' => 'kp', 'src' => '../img/pieces/kp.png'],
    'd8' => ['id' => 'qp', 'src' => '../img/pieces/qp.png'],
    'c8' => ['id' => 'bb', 'src' => '../img/pieces/bp.png'],
    'f8' => ['id' => 'bb', 'src' => '../img/pieces/bp.png'],
    'a7' => ['id' => 'pp', 'src' => '../img/pieces/pp.png'],
    'b7' => ['id' => 'pp', 'src' => '../img/pieces/pp.png'],
    'c7' => ['id' => 'pp', 'src' => '../img/pieces/pp.png'],
    'd7' => ['id' => 'pp', 'src' => '../img/pieces/pp.png'],
    'e7' => ['id' => 'pp', 'src' => '../img/pieces/pp.png'],
    'f7' => ['id' => 'pp', 'src' => '../img/pieces/pp.png'],
    'g7' => ['id' => 'pp', 'src' => '../img/pieces/pp.png'],
    'h7' => ['id' => 'pp', 'src' => '../img/pieces/pp.png'],

];

// Define a estrutura do tabuleiro e coloca as peças iniciais
?>
        <div id="board-container">
            <h2>PHChess</h2>
            <div class="chess-board">
                <?php
                // Exibir as peças com base no array inicial
                $rows = range(1, 8);
                $columns = range('a', 'h');
                
                foreach ($rows as $row) {
                    foreach ($columns as $col) {
                        $id = $col . $row;
                        $isLight = ($row + ord($col)) % 2 === 0;
                        $class = $isLight ? 'square light' : 'square dark';
                        echo "<div class='$class' id='$id'>";
                        
                        // Coloca a peça na posição inicial, se existir
                        if (isset($initialPieces[$id])) {
                            $piece = $initialPieces[$id];
                            $pieceId = $piece['id'];
                            $pieceSrc = $piece['src'];
                            echo "<img src='$pieceSrc' class='piece' id='$pieceId' draggable='true'>";
                        }
                        
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
<!-- Painel de informações e rodapé -->
<?php
require_once('painel.php');
require_once('../footer.php');
?>
<!-- Script de movimentação das peças -->
<script src="../js/move.js"></script> 

