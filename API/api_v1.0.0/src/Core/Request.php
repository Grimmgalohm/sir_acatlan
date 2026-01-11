<?php
namespace App\Core;

class Request {
    public function getPath(): string {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        return $path;
    }

    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getBody(): array {
        // Obtenemos el JSON raw y lo decodificamos
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            return [];
        }
        return $data;
    }

    public function getQuery(string $key = null): mixed {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? null;
    }
}

?>
