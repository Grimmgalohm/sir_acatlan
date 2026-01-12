<?php

namespace App\Model;

class Buildings {

    public function __construct(
        public int $id,
        public string $codigo,
        public string $nombre
    ) {}

}

?>
