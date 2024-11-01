

<?php
// Path: app/config/database.php
require_once __DIR__ . '/../core/settings.php';

class Database {
    private static $conn = null;

    // Método para retornar a conexão com o banco de dados
    public static function getConnection() {
        if (!self::$conn) {
            try {
                // Construindo a DSN (Data Source Name) usando as variáveis de ambiente carregadas
                $dsn = Settings::$DB_DRIVER . ":host=" . Settings::$DB_HOST . ";port=" . Settings::$DB_PORT . ";dbname=" . Settings::$DB_NAME;
                
                // Criando a conexão com o banco de dados
                self::$conn = new PDO($dsn, Settings::$DB_USER, Settings::$DB_PASSWORD);
                
                // Configurações adicionais para o PDO
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
            } catch (PDOException $e) {
                // Tratamento de erro na conexão
                echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
                exit;
            }
        }
        
        return self::$conn;
    }
}
// Fim do código