<?php

namespace App\Model;

class Zones {

    private function __construct(
        public int $id,
        public string $nombre,
        public string $descripcion
    ) {}

    public static function fromZones(array $data): self {
        return new self(
            (int) $data['id'],
            (string) $data['nombre'],
            (string) $data['descripcion']
        );
    }

}

?>
