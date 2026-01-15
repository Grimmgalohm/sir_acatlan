<?php
/**
* Author: César Luna
* Version: v1.0.0
*/

$host = $_ENV['DB_HOST'];
$db   = $_ENV['DB_NAME'];
$port = $_ENV['DB_PORT'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$charset = $_ENV['DB_CHARSET'];

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    return new PDO($dsn, $user, $pass, $options);
} catch(\PDOException $e) {
    // Si falla, muestra el error exacto para que sepas qué variable falta
    die('Error de conexión DB: ' . $e->getMessage() . " (Host intentado: $host)");
}
?>
