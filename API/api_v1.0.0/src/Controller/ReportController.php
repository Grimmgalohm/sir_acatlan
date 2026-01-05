<?php
// src/Controller/ReportController

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\DTOs\CreateReportDTO;
use App\Service\ReportService;

class ReportController {

    public function __construct(private ReportService $reportService){}

    public function createReport(Request $request): void {
        $data = json_decode(file_get_contents('php://input'), true);
        try{
            $dto = CreateReportDTO::fromRequest($data);
        } catch(\InvalidArgumentException $e){
            // Retornar error 400 Bad Request
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos o inválidos']);
            return;
        } catch(\Exception $e) {
            Response::json(['error'=>$e->getMessage()], 500);
        }
        
    }

    public function getReport(Request $request): void {

        

    }

}

?>
