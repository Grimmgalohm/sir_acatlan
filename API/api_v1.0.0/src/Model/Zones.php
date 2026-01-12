<?php

namespace App\Model;

class Zones {

    public function __construct(
        public int $id,
        public string $nombre,
        public string $descripcion
    ) {}

}

?>
