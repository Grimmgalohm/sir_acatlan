<?php

namespace App\Model;

class Buildings {

    private function __construct(
        public int $id,
        public string $codigo,
        public string $nombre,
        public int $zona_id
    ) {}

    public static function fromBuildings(array $data): self {
        return new self (
            (int) $data['id'],
            (string) $data['codigo'],
            (string) $data['nombre'],
            (int) $data['zona_id']
        );
    }

}

?>
