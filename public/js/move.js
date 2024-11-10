// public/js/move.js

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
                    // Cria o elemento da peça
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

    // Reatribui os eventos de drag and drop para garantir o funcionamento correto
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

    console.log("Dados enviados:", data);

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
        console.log('Resposta bruta Tabuleiro:', text);

        const jsonStart = text.indexOf('{');
        if (jsonStart === -1) {
            throw new Error("Resposta não contém JSON válido.");
        }

        const jsonText = text.substring(jsonStart);
        const dtajson = JSON.parse(jsonText);

        if (dtajson.success === false) {
            alert(dtajson.message);
            console.warn("Movimento inválido:", dtajson.message);
        } else {
            console.log("Estado recebido do backend:", dtajson.board);
            atualizarTabuleiro(dtajson.board);
            console.log("Tabuleiro atualizado no frontend.");
        }
        origin = null;
        pieceId = null;
        destino = null;
        console.log("Setando variáveis de controle para null.", origin, pieceId, destino);

    } catch (error) {
        console.error("Erro ao mover a peça:", error.message);
    } finally {
        isMoving = false; 
    }
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

            // Verifica se o elemento de destino é um quadrado e não uma peça
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
        console.log('Resposta bruta Tabuleiro reiniciado:', text);
        
        const jsonStart = text.indexOf('{');
        if (jsonStart === -1) {
            throw new Error("Resposta não contém JSON válido.");
        }
        
        const jsonText = text.substring(jsonStart);
        const dtajson = JSON.parse(jsonText);

        if (dtajson.success === false) {
            alert(dtajson.message);
        } else {
            console.log('JSON parseado Tabuleiro reiniciado:', dtajson);
            atualizarTabuleiro(dtajson.board);
        }

    } catch (error) {
        console.error("Erro ao reiniciar o tabuleiro:", error.message);
    }
});
