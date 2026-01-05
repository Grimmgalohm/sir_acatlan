<?php
// ReportRepository.php

namespace App\Repository;

use PDO;
use App\Model\Report;

class ReportRepository {

    public function __construct(private PDO $db){}

    public function findByFolio(string $folio): ?Report {}
    
    public function createReport(string $){}
}
?>
