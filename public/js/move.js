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
    const errorMessages = {
        'invalid_move': "Movimento inválido!",
        'piece_not_yours': "A peça não pertence a você.",
        'turn_mismatch': "Não é sua vez de jogar.",
        'not_in_check': "Você não pode se colocar em xeque.",
        'checkmate': "Xeque-mate! O jogo terminou.",
    };

    alert(errorMessages[errorData.type] || errorData.message || "Erro desconhecido.");
    console.warn("Erro do backend:", errorData.message);
}

// Função para atualizar o status do jogo no painel
function atualizarStatusJogo(statusData) {
    const turnoAtualElem = document.getElementById('turno-atual');
    const jogadorAtualElem = document.getElementById('jogador-atual');
    const estadoXequeElem = document.getElementById('estado-xeque');
    const estadoXequeMateElem = document.getElementById('estado-xeque-mate');

    if (turnoAtualElem) turnoAtualElem.innerText = `Turno: ${statusData.turn}`;
    if (jogadorAtualElem) jogadorAtualElem.innerText = `Jogador: ${statusData.currentPlayer}`;
    if (estadoXequeElem) estadoXequeElem.innerText = statusData.check ? "Em Xeque!" : "";
    if (estadoXequeMateElem) estadoXequeMateElem.innerText = statusData.checkMate ? "Xeque-Mate!" : "";
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
document.addEventListener('DOMContentLoaded', () => {
    atribuirEventosDragDrop();

    const btnReload = document.getElementById('btn-reload');
    if (btnReload) {
        btnReload.addEventListener('click', async () => {
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
    } else {
        console.error("Erro inesperado com o botão");
    }
});

// Função para atualizar o painel de peças capturadas
function atualizarPecasCapturadas(capturedPieces) {
    const capturedPretas = document.getElementById('captured-pretas');
    const capturedBrancas = document.getElementById('captured-brancas');

    if (capturedPretas && capturedBrancas) {
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
    } else {
        console.error("Erro ao atualizar as peças capturadas.");
    }
}
