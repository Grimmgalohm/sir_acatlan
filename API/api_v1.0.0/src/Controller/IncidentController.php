<?php

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Service\IncidentService;

class IncidentController {

    public function __construct(private IncidentService $incidentService) {}

    public function getMetadata(): void {
        $data = $this->incidentService->getMetadata();
        Response::json($data, 200);
    }

}

?>
