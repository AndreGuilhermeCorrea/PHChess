// Seleciona todas as peças para permitir arrastar
document.querySelectorAll('.piece').forEach(piece => {
    piece.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('pieceId', e.target.id); // Armazena o ID da peça sendo arrastada
        e.dataTransfer.setData('squareId', e.target.parentElement.id); // Armazena o ID da casa de origem
    });
});

// Configura cada casa do tabuleiro para permitir o drop
document.querySelectorAll('.square').forEach(square => {
    square.addEventListener('dragover', (e) => {
        e.preventDefault(); // Necessário para permitir o drop
    });

    square.addEventListener('drop', (e) => {
        e.preventDefault();
        const pieceId = e.dataTransfer.getData('pieceId'); // Recupera o ID da peça
        const sourceSquareId = e.dataTransfer.getData('squareId'); // Casa de origem
        const targetSquare = e.target.closest('.square'); // Casa de destino
        console.log(`Movendo peça ${pieceId} de ${sourceSquareId} para ${targetSquare.id}`);

        if (isValidMove(pieceId, sourceSquareId, targetSquare.id)) {
            const piece = document.getElementById(pieceId); // Seleciona a peça pelo ID

            // Verifica se a casa de destino possui uma peça
            const targetPiece = targetSquare.querySelector('.piece');
            if (targetPiece) {
                // Verifica se a peça é da mesma cor (não permite capturas da mesma cor)
                if (targetPiece.id.includes(pieceId.includes('black') ? 'black' : 'white')) {
                    alert("Movimento inválido! A casa de destino possui uma peça da mesma cor.");
                    return;
                } else {
                    // Remove a peça adversária da casa (simulando uma captura)
                    targetPiece.remove();
                }
            }
            // Move a peça para a nova casa
            targetSquare.appendChild(piece);
        } else {
            alert("Movimento inválido!"); // Notifica sobre movimento inválido
            console.log("Falha na validação de movimento.");
        }
    });
});

// Função para validar o movimento
function isValidMove(pieceId, sourceSquareId, targetSquareId) {
    const pieceType = pieceId.split('-')[0]; // Obtém o tipo de peça
    const [sourceCol, sourceRow] = [sourceSquareId.charAt(0), parseInt(sourceSquareId.charAt(1))];
    const [targetCol, targetRow] = [targetSquareId.charAt(0), parseInt(targetSquareId.charAt(1))];
    console.log(`Validando ${pieceType} de ${sourceSquareId} para ${targetSquareId}`);
    switch (pieceType) {
        case 'pawn':
            return isValidPawnMove(sourceCol, sourceRow, targetCol, targetRow, pieceId.includes('black'));
        case 'rook':
            return isValidRookMove(sourceCol, sourceRow, targetCol, targetRow);
        case 'knight':
            return isValidKnightMove(sourceCol, sourceRow, targetCol, targetRow);
        case 'bishop':
            return isValidBishopMove(sourceCol, sourceRow, targetCol, targetRow);
        case 'queen':
            return isValidQueenMove(sourceCol, sourceRow, targetCol, targetRow);
        case 'king':
            return isValidKingMove(sourceCol, sourceRow, targetCol, targetRow);
        default:
            console.log(`Tipo de peça desconhecido: ${pieceType}`);
            return false;
    }
}

// Função de validação para peão
function isValidPawnMove(sourceCol, sourceRow, targetCol, targetRow, isBlack) {
    const direction = isBlack ? 1 : -1; 
    const startingRow = isBlack ? 2 : 7;
    
    // Movimento de um passo
    if (sourceCol === targetCol && targetRow === sourceRow + direction) {
        return true;
    }
    // Movimento de dois passos no início
    if (sourceCol === targetCol && sourceRow === startingRow && targetRow === sourceRow + (2 * direction)) {
        return true;
    }
    // Captura diagonal
    if (Math.abs(targetCol.charCodeAt(0) - sourceCol.charCodeAt(0)) === 1 && targetRow === sourceRow + direction) {
        return true;
    }
    return false;
}
// Função de validação para torre
function isValidRookMove(sourceCol, sourceRow, targetCol, targetRow) {
    return sourceCol === targetCol || sourceRow === targetRow;
}
// Função de validação para cavalo
function isValidKnightMove(sourceCol, sourceRow, targetCol, targetRow) {
    const colDiff = Math.abs(targetCol.charCodeAt(0) - sourceCol.charCodeAt(0));
    const rowDiff = Math.abs(targetRow - sourceRow);
    return (colDiff === 2 && rowDiff === 1) || (colDiff === 1 && rowDiff === 2);
}
// Função de validação para bispo
function isValidBishopMove(sourceCol, sourceRow, targetCol, targetRow) {
    const colDiff = Math.abs(targetCol.charCodeAt(0) - sourceCol.charCodeAt(0));
    const rowDiff = Math.abs(targetRow - sourceRow);
    return colDiff === rowDiff;
}
// Função de validação para rainha
function isValidQueenMove(sourceCol, sourceRow, targetCol, targetRow) {
    return isValidRookMove(sourceCol, sourceRow, targetCol, targetRow) || isValidBishopMove(sourceCol, sourceRow, targetCol, targetRow);
}
// Função de validação para rei
function isValidKingMove(sourceCol, sourceRow, targetCol, targetRow) {
    const colDiff = Math.abs(targetCol.charCodeAt(0) - sourceCol.charCodeAt(0));
    const rowDiff = Math.abs(targetRow - sourceRow);
    return colDiff <= 1 && rowDiff <= 1;
}
