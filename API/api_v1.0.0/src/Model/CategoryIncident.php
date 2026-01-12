<?php

namespace App\Model;

class CategoryIncident {

    public function __construct(
        public int $id,
        public string $clave,
        public string $nombre_es,
        public string $descripcion
    ) {}

}

?>
