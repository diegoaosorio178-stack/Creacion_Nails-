<?php

class Database {
    private static ?PDO $connection = null;

    // Evitar instanciación directa
    private function __construct() {}
    private function __clone() {}

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $host = '127.0.0.1';
            $db   = 'bdnewcreacion';
            $user = 'root';
            $pass = '';
            $port = 3306;
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$connection = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                die('<h2 style="color:red; text-align:center; padding:20px;">Error Crítico de Conexión: Base de datos no encontrada.</h2><p style="text-align:center;">Detalle: ' . $e->getMessage() . '</p>');
            }
        }
        return self::$connection;
    }
}