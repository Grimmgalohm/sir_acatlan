<?php
// service/ReportService.php

namespace App\Service;

use App\Repository\ReportRepository;

class ReportService {

    public function __construct(private ReportRepository $reportRepository){}

    public function createNewReport(array $input): array {

        // 1. Lógica de validación (Regla de negocio)

        // 2. Regla: No duplicados

        // 3. Lógica de seguridad

        // 4. Persistencia (delegada al repo)

        return ['status'=>'success', 'message'=>'Usuario creado'];

    }
}

?>
