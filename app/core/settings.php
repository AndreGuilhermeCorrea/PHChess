
<?php
// Path: app/core/settings.php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

class Settings {
    public static string $API_V1_STR;
    public static string $DB_DRIVER;
    public static string $DB_HOST;
    public static string $DB_PORT;
    public static string $DB_NAME;
    public static string $DB_USER;
    public static string $DB_PASSWORD;
    public static string $DB_URL;

    public static function init() {
        // Carregar as variáveis do .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Definir as variáveis a partir do .env
        self::$API_V1_STR = $_ENV['API_V1_STR'];
        self::$DB_DRIVER = $_ENV['DB_DRIVER'];
        self::$DB_HOST = $_ENV['DB_HOST'];
        self::$DB_PORT = $_ENV['DB_PORT'];
        self::$DB_NAME = $_ENV['DB_NAME'];
        self::$DB_USER = $_ENV['DB_USER'];
        self::$DB_PASSWORD = $_ENV['DB_PASSWORD'];

        // Construir a URL do banco de dados
        self::$DB_URL = self::$DB_DRIVER . '://' .
                        self::$DB_USER . ':' . self::$DB_PASSWORD . '@' .
                        self::$DB_HOST . ':' . self::$DB_PORT . '/' .
                        self::$DB_NAME;
    }
}

// Inicializar as configurações ao carregar o arquivo
Settings::init();

// Fim do código