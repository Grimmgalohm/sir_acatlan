<?php

namespace App\Model;

class Toilet {

    private function __construct(
        public int $id,
        public int $edificio_id,
        public string $tipo,
        public string $codigo_interno,
        public int $activo
    ){}

    public static function fromToilet(array $data): self {
        return new self (
            (int) $data['id'],
            (int) $data['edificio_id'],
            (string) $data['tipo'],
            (string) $data['codigo_interno'],
            (int) $data['activo']
        );
    }
}

?>
