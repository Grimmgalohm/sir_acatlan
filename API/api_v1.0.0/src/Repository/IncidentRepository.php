<?php
namespace App\Repository;

use PDO;
use App\Model\Incident;
use App\Model\Toilet;
use App\Model\CategoryIncident;
use App\Model\Buildings;
use App\Model\Metadata;
use App\Model\Zones;

class IncidentRepository {

    public function __construct(private PDO $db) {}

    private function get_incidents_cat(): ?array {
        
        $stmt = $this->db->prepare("SELECT `id`, `clave`, `nombre_es`, `descripcion` FROM siracatlan.cat_incidente_categoria WHERE activo = 1");
        $stmt->execute();        
        $data = $stmt->fetchAll();

        if(!$data) {
            return null;
        }
        
        return array_map(
            fn(array $row) => CategoryIncident::fromIncidentCat($row),
            $data
        );
            
    }

    private function get_toilet_list(): ?array {
        
        $stmt = $this->db->prepare("SELECT * FROM siracatlan.banos WHERE activo = 1");
        $stmt->execute();
        $data = $stmt->fetchAll();

        if(!$data) {
            return null;
        }

        return array_map(
            fn(array $row) => Toilet::fromToilet($row),
            $data
        );
        
    }

    private function get_building_list(): ?array {
        
        $stmt = $this->db->prepare("SELECT * FROM siracatlan.edificios");
        $stmt->execute();
        $data = $stmt->fetchAll();

        if(!$data) {
            return null;
        }
        
        return array_map(
            fn(array $row)=> Buildings::fromBuildings($row),
            $data
        );
    }

    private function get_zones_list(): ?array {

        $stmt = $this->db->prepare("SELECT * FROM siracatlan.zonas");
        $stmt->execute();
        $data = $stmt->fetchAll();

        if(!$data) {
            return null;
        }
        
        return array_map(
            fn(array $row)=> Zones::fromZones($row),
            $data
        );
    }
    
    public function metadata(): ?Metadata {

        $incidents_cat = $this->get_incidents_cat();
        $toilet_list = $this->get_toilet_list();
        $building_list = $this->get_building_list();
        $zone_list = $this->get_zones_list();
        
        return new Metadata(
            $incidents_cat,
            $toilet_list,
            $building_list,
            $zone_list
        );
    }

    public function createIncident(): ?Incident {
        // pass
        return new Incident();
    }
    
}

?>
