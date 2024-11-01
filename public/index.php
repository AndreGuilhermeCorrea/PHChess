<?php
// Index.php
// Inclui o cabeçalho da página
include __DIR__ . '/template/header.php';
?>

<div id="home-container">
    <h2>PHChess</h2>
    <h3>PHChess é um jogo de xadrez online.</h3>
    <h3>Registre-se para jogar.</h3>
    <p class="chess-benefits">
        O xadrez é um jogo de raciocínio lógico, excelente atividade para o desenvolvimento mental. Melhora a memória, promove o pensamento estratégico e aumenta a capacidade de resolver problemas. Além disso, jogar xadrez é uma ótima maneira de aprimorar a concentração e a paciência, benefícios que podem ser aplicados em muitas áreas da vida.
    </p>
    <img src="img/chess.png" alt="Jogo de Xadrez" class="chess-image">
    <div class="button-container">
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Cadastre-se</a>
        <a href="rules.php" class="btn btn-large">Regras do Jogo</a>
    </div>
</div>

<?php
// Inclui o rodapé
include __DIR__ . '/template/footer.php';
?>