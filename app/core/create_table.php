

<?php
// Path: app/core/create_table.php
require_once 'app/Config/database.php';

function createTables() {
    $conn = Database::getConnection();

    try {
        // Criação da tabela de jogadores
        $sql = "CREATE TABLE IF NOT EXISTS players (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($sql);
        echo "Tabela 'players' criada com sucesso!\n";

        // Criação da tabela de jogos
        $sql = "CREATE TABLE IF NOT EXISTS games (
            id INT AUTO_INCREMENT PRIMARY KEY,
            player1_id INT NOT NULL,
            player2_id INT NOT NULL,
            winner_id INT NULL,
            start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            end_time TIMESTAMP NULL,
            FOREIGN KEY (player1_id) REFERENCES players(id),
            FOREIGN KEY (player2_id) REFERENCES players(id),
            FOREIGN KEY (winner_id) REFERENCES players(id)
        )";
        $conn->exec($sql);
        echo "Tabela 'games' criada com sucesso!\n";

        // Criação da tabela de movimentos
        $sql = "CREATE TABLE IF NOT EXISTS moves (
            id INT AUTO_INCREMENT PRIMARY KEY,
            game_id INT NOT NULL,
            player_id INT NOT NULL,
            move_number INT NOT NULL,
            from_position VARCHAR(5) NOT NULL,
            to_position VARCHAR(5) NOT NULL,
            move_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (game_id) REFERENCES games(id),
            FOREIGN KEY (player_id) REFERENCES players(id)
        )";
        $conn->exec($sql);
        echo "Tabela 'moves' criada com sucesso!\n";

    } catch (PDOException $e) {
        echo "Erro ao criar as tabelas: " . $e->getMessage() . "\n";
    }
}

// Executa a função para criar as tabelas
createTables();

// Fim do código