<?php
// DTO (Data transfer object)
//

namespace App\DTOs;

readonly class CreateReportDTO {

    public function __construct(
        public string $trackingCode,
        public int $banoId,
        public int $categoriaId,
        public int $estadoId,
        public string $descripcion,
        public string $prioridad,
        public string $canal_reporte
    ) {}

    public static function fromRequest(array $data): self {
        return new self(
            trackingCode: $data['trackingCode'],
            banoId: $data['banoId'],
            categoriaId: $data['categoriaId'],
            estadoId: $data['estadoId'],
            descripcion: $data['descripcion'],
            prioridad: $data['prioridad'],
            canal_reporte: $data['canal_reporte']
        );
    }
}

?>
