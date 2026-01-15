<?php

namespace App\Model;

class CategoryIncident {

    private function __construct(
        public int $id,
        public string $clave,
        public string $nombre_es,
        public string $descripcion
    ) {}

    public static function fromIncidentCat(array $data): self {
        return new self (
            (int) $data['id'],
            (string) $data['clave'],
            (string) $data['nombre_es'],
            (string) $data['descripcion']
        );
    }

}

?>
