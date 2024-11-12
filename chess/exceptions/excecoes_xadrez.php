<?php
// chess/exceptions/excecoes_xadrez.php

class ChessException extends Exception {
    protected $type;

    public function __construct($message, $type = 'error') {
        $this->type = $type;
        parent::__construct($message);
    }

    public function getType() {
        return $this->type;
    }

    public function toArray() {
        return [
            'success' => false,
            'type' => $this->type,
            'message' => $this->getMessage()
        ];
    }
}

class CautionException extends ChessException {
    public function __construct($message = "Cuidado!") {
        parent::__construct($message, 'caution');
    }
}

class NotInCheckException extends ChessException {
    public function __construct($message = "Voce nao pode se colocar em Xeque!") {
        parent::__construct($message, 'not_in_check');
    }
}

// Exceções para movimentos inválidos
class InvalidMoveException extends ChessException {
    public function __construct($message = "Movimento inválido") {
        parent::__construct($message, 'invalid_move');
    }
}

// Exceção para quando peça inválida
class InvalidPieceException extends ChessException {
    public function __construct($message = "Peça inválida") {
        parent::__construct($message, 'invalid_piece');
    }
}

// Exceção para quando a peça não é encontrada
class PieceNotFoundException extends ChessException {
    public function __construct($message = "Peça não encontrada na posição de origem") {
        parent::__construct($message, 'piece_not_found');
    }
}

// Exceção para quando a peça não é do jogador
class PieceNotYoursException extends ChessException {
    public function __construct($message = "A peça não é sua") {
        parent::__construct($message, 'piece_not_yours');
    }
}


// Exceção para quando a posição é inválida
class InvalidPositionException extends ChessException {
    public function __construct($message = "Posição inválida") {
        parent::__construct($message, 'invalid_position');
    }
}

// Exceção para quando a posição de destino é inválida
class InvalidPromotionException extends ChessException {
    public function __construct($message = "Promoção inválida") {
        parent::__construct($message, 'invalid_promotion');
    }
}

// Exceção para Xeque-mate
class CheckmateException extends ChessException {
    public function __construct($message = "Xeque-mate!") {
        parent::__construct($message, 'checkmate');
    }
}

// Exceção para quando o jogador está em xeque
class StalemateException extends ChessException {
    public function __construct($message = "Em xeque!") {
        parent::__construct($message, 'stalemate');
    }
}

// Exceção para quando não é a vez do jogador
class TurnMismatchException extends ChessException {
    public function __construct($message = "Não é sua vez de jogar") {
        parent::__construct($message, 'turn_mismatch');
    }
}
