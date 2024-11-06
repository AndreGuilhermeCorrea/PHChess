document.addEventListener("DOMContentLoaded", function() {
    let seconds = 0;
    let minutes = 0;
    let hours = 0;

    function updateGameTime() {
        seconds++;
        if (seconds === 60) {
            seconds = 0;
            minutes++;
        }
        if (minutes === 60) {
            minutes = 0;
            hours++;
        }

        // Formatação do tempo para exibir em dois dígitos
        const formattedTime = 
            String(hours).padStart(2, '0') + ":" +
            String(minutes).padStart(2, '0') + ":" +
            String(seconds).padStart(2, '0');

        document.getElementById("tempo-jogo").innerText = formattedTime;
    }

    // Inicia o cronômetro e atualiza a cada segundo
    setInterval(updateGameTime, 1000);
});

