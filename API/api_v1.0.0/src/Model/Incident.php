<?php

namespace App\Model;

class Incident {
    public function __construct(
        public string $tracking_code,
        public int $bano_id,
        public int $categoria_id,
        public int $estado_id,
        public string $descripcion,
        public int $prioridad,
        public string $canal_reporte
    ){}
}

?>
