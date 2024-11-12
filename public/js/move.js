// Variáveis globais para controle de movimento e estado das peças
let isMoving = false;
let origin = null;
let pieceId = null;
let destino = null;

// Função para atualizar o DOM do tabuleiro com o novo estado das peças
function atualizarTabuleiro(boardData) {
    document.querySelectorAll('.square').forEach(square => {
        square.innerHTML = ''; 
    });

    Object.keys(boardData).forEach(position => {
        if (/^[a-h][1-8]$/.test(position)) {
            const piece = boardData[position];
            const tile = document.getElementById(position);

            if (tile) {
                tile.innerHTML = ''; 

                if (piece) {
                    const pieceImage = document.createElement('img');
                    pieceImage.src = `../img/pieces/${piece}.png`;
                    pieceImage.className = 'piece';
                    pieceImage.id = piece;
                    pieceImage.draggable = true;
                    tile.appendChild(pieceImage);
                }
            } else {
                console.warn(`Posição ${position} não encontrada no DOM.`);
            }
        }
    });

    atribuirEventosDragDrop();
}

// Função para mover a peça e evitar requisições simultâneas
async function handleMove() {
    if (isMoving) return; 
    isMoving = true;

    const data = {
        origem: origin,
        destino: destino,
        peca: pieceId,
    };

    try {
        const response = await fetch('/api/routes.php?move', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const text = await response.text();
        console.log("Resposta do back:", text); 
        const jsonStart = text.indexOf('{');
        if (jsonStart === -1) {
            throw new Error("Resposta não contém JSON válido.");
        }

        const jsonText = text.substring(jsonStart);
        const dtajson = JSON.parse(jsonText);

        if (dtajson.success === false) {
            handleBackendError(dtajson);
        } else {
            atualizarTabuleiro(dtajson.board);
            atualizarPecasCapturadas(dtajson.capturedPieces); 
            atualizarStatusJogo(dtajson);
        }
        origin = null;
        pieceId = null;
        destino = null;

    } catch (error) {
        console.error("Erro ao mover a peça:", error.message);
    } finally {
        isMoving = false; 
    }
}

// Função para tratar os erros retornados pelo backend
function handleBackendError(errorData) {
    switch (errorData.type) {
        case 'invalid_move':
            alert("Movimento inválido!");
            break;
        case 'piece_not_yours':
            alert("A peça não pertence a você.");
            break;
        case 'turn_mismatch':
            alert("Não é sua vez de jogar.");
            break;
        case 'not_in_check':
            alert("Você não pode se colocar em xeque.");
            break;
        case 'checkmate':
            alert("Xeque-mate! O jogo terminou.");
            break;
        default:
            alert(errorData.message || "Erro desconhecido.");
    }
    console.warn("Erro do backend:", errorData.message);
}

// Função para atualizar o status do jogo no painel
function atualizarStatusJogo(statusData) {
    document.getElementById('turno-atual').innerText = `Turno: ${statusData.turn}`;
    document.getElementById('jogador-atual').innerText = `Jogador: ${statusData.currentPlayer}`;
    document.getElementById('estado-xeque').innerText = statusData.check ? "Em Xeque!" : "";
    document.getElementById('estado-xeque-mate').innerText = statusData.checkMate ? "Xeque-Mate!" : "";
}

// Função para atribuir os eventos de drag and drop
function atribuirEventosDragDrop() {
    const pieces = document.querySelectorAll('.piece');
    const squares = document.querySelectorAll('.square');

    pieces.forEach(piece => {
        piece.addEventListener('dragstart', (event) => {
            origin = event.target.parentNode.id;
            pieceId = event.target.id;
            event.dataTransfer.setData('pieceId', pieceId);
        });
    });

    squares.forEach(square => {
        square.addEventListener('dragover', (event) => {
            event.preventDefault();
        });

        square.addEventListener('drop', (event) => {
            event.preventDefault();
            const targetElement = event.target;
            destino = targetElement.classList.contains('square') ? targetElement.id : targetElement.parentNode.id;
            handleMove();
        });
    });
}

// Inicializa os eventos de drag and drop ao carregar a página
document.addEventListener('DOMContentLoaded', atribuirEventosDragDrop);

// Função para reiniciar o jogo
document.getElementById('btn-reload').addEventListener('click', async () => {
    try {
        const response = await fetch('/api/routes.php?initialize', { method: 'POST' });
        
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const text = await response.text(); 
        console.log("Resposta do back:", text); 
        const jsonStart = text.indexOf('{');
        if (jsonStart === -1) {
            throw new Error("Resposta não contém JSON válido.");
        }
        
        const jsonText = text.substring(jsonStart);
        const dtajson = JSON.parse(jsonText);

        if (dtajson.success === false) {
            alert(dtajson.message);
        } else {
            atualizarTabuleiro(dtajson.board);
            atualizarPecasCapturadas(dtajson.capturedPieces);
            atualizarStatusJogo(dtajson);
        }

    } catch (error) {
        console.error("Erro ao reiniciar o tabuleiro:", error.message);
    }
});

// Função para atualizar o painel de peças capturadas
function atualizarPecasCapturadas(capturedPieces) {
    const capturedPretas = document.getElementById('captured-pretas');
    const capturedBrancas = document.getElementById('captured-brancas');
    
    capturedPretas.innerHTML = '';
    capturedBrancas.innerHTML = '';

    capturedPieces.forEach(pieceCode => {
        const img = document.createElement('img');
        img.src = `../img/pieces/${pieceCode}.png`;
        img.classList.add('captured-piece');

        if (pieceCode.endsWith('p')) {
            capturedPretas.appendChild(img); 
        } else {
            capturedBrancas.appendChild(img); 
        }
    });
}
