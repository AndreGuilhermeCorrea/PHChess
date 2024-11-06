// Ultima peça movida
let lastMovedColor = null;

// Inicializa o tabuleiro de xadrez
document.querySelectorAll('.piece').forEach(piece => {
    piece.addEventListener('dragstart', (e) => {
        // Armazena o ID da peça 
        e.dataTransfer.setData('pieceId', e.target.id); 
        // Armazena o ID da casa
        e.dataTransfer.setData('squareId', e.target.parentElement.id); 
    });
});

// Configura tuplas de peças e casas
document.querySelectorAll('.square').forEach(square => {
    square.addEventListener('dragover', (e) => {
        e.preventDefault(); 
    });

    // Configura o evento de soltar a peça
    square.addEventListener('drop', (e) => {
        e.preventDefault();
        // Obtém o ID da peça
        const pieceId = e.dataTransfer.getData('pieceId'); 
        // Obtém o ID da casa 
        const sourceSquareId = e.dataTransfer.getData('squareId'); 
        // Obtém o ID da casa de destino
        const targetSquare = e.target.closest('.square'); 
        // Verifica se a peça é preta
        const isBlack = pieceId.includes('black');
        console.log(`Movendo peça ${pieceId} de ${sourceSquareId} para ${targetSquare.id}`);

        // Verifica se a peça é da mesma cor que a última movida
        if (lastMovedColor !== null && lastMovedColor === (isBlack ? 'black' : 'white')) {
            alert("Movimento inválido! Não é permitido mover a mesma cor duas vezes consecutivas.");
            return;
        }

        // Valida o movimento
        if (isValidMove(pieceId, sourceSquareId, targetSquare.id)) {

            const piece = document.getElementById(pieceId); 

            // Verifica se a casa de destino possui uma peça
            const targetPiece = targetSquare.querySelector('.piece');
            if (targetPiece) {
                // Verifica se a peça é da mesma cor
                if (targetPiece.id.includes(pieceId.includes('black') ? 'black' : 'white')) {
                    alert("Movimento inválido! A casa de destino possui uma peça da mesma cor.");
                    return;
                } else {
                    // Remove a peça adversária da casa
                    targetPiece.remove();
                }
            }
            // Move a peça para a nova casa
            targetSquare.appendChild(piece);
            // Atualiza a cor da última peça movida 
            lastMovedColor = isBlack ? 'black' : 'white';
        } else {
            alert("Movimento inválido!"); // Notifica sobre movimento inválido
            console.log("Falha na validação de movimento.");
        }
    });
});

// Função para validar o movimento
function isValidMove(pieceId, sourceSquareId, targetSquareId) {
    // Obtém o tipo de peça
    const pieceType = pieceId.split('-')[0];
    // Obtém as coordenadas da casa de origem e destino
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
    // Captura
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
