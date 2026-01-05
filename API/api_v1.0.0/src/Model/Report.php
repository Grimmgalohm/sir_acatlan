<?php
//model\Report.php

namespace App\Model;

class Report {
    public function __construct(
        public string $trackingCode,
        public int $banoId,
        public int $categoriaId,
        public int $estadoId,
        public string $descripcion,
        public int $prioridad,
        public string $canal_reporte
    ){}
}

?>
