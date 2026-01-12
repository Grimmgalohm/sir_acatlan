<?php
namespace App\Model;

class Metadata {
    public function __construct(
        public object $CategoryIncident,
        public object $Bano,
        public object $Buildings,
        public object $Zone
    ) {}
}


?>
