<?php
// ReportRepository.php

namespace App\Repository;

use PDO;
use App\Model\Report;

class ReportRepository {

    public function __construct(private PDO $db) {}
    
    public function getOne(int $folio): ?Report {
        
        $stmt = $this->db->prepare("SELECT ");
        $stmt->bindValue();

        $stmt->execute();

        $data = $stmt->fetch();

        if(!$data) {
            return null;
        }
        
        return new Report();
    }
    
}
?>
