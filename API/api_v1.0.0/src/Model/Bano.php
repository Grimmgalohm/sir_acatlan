<?php

namespace App\Model;

class Bano {

    public function __construct(
        public int $id,
        public string $tipo,
        public string $codigo_interno,
        public int $activo
    ){}

}

?>
