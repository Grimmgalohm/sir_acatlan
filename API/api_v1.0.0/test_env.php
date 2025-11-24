<?php
// API/api_v1.0.0/test_env.php

require 'vendor/autoload.php';

echo "<h1>Diagnóstico de Entorno</h1>";
echo "<pre>";

// 1. Verificar ruta
echo "Directorio actual: " . __DIR__ . "\n";

// 2. Verificar si el archivo existe físicamente
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo "✅ El archivo .env EXISTE.\n";
    echo "Permisos: " . substr(sprintf('%o', fileperms($envFile)), -4) . "\n";
    
    // Intentar leerlo "a la bruta"
    $content = file_get_contents($envFile);
    echo "Contenido (primeros 50 chars): " . htmlspecialchars(substr($content, 0, 50)) . "...\n";
} else {
    echo "❌ NO SE ENCUENTRA el archivo .env en: $envFile\n";
    echo "Archivos en esta carpeta:\n";
    print_r(scandir(__DIR__));
}

// 3. Intentar cargar con Dotenv
echo "\n--- Intentando cargar phpdotenv ---\n";
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Sin el '/../' porque estamos en la misma carpeta
    $data = $dotenv->load();
    echo "✅ Carga exitosa. Variables encontradas:\n";
    print_r(array_keys($_ENV));
} catch (Exception $e) {
    echo "❌ Error fatal de Dotenv: " . $e->getMessage() . "\n";
}

echo "</pre>";
