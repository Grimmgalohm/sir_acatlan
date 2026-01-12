<?php

namespace App\Service;

use App\Repository\IncidentRepository;

class IncidentService {

    public function __construct(private IncidentRepository $incidentRepository) {}

    public function getMetadata(): array {

        $data = $this->incidentRepository->metadata();
        if(!$data) {
            return ['status' => 'error', 'message'=>'Something went wrong...'];
        }
        
        return ['status'=>'success', 'metadata'=>$data];
    }
    
}

?>
