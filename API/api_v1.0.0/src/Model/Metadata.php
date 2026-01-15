<?php
namespace App\Model;

class Metadata {
    public function __construct(
        public array $CategoryIncident,
        public array $Toilet,
        public array $Buildings,
        public array $Zone
    ) {}
}


?>
