<?php
namespace App\Repository;

use PDO;
use App\Model\Incident;
use App\Model\Bano;
use App\Model\CategoryIncident;
use App\Model\Buildings;
use App\Model\Metadata;
use App\Model\Zones;

class IncidentRepository {

    public function __construct(private PDO $db) {}

    private function get_incidents_cat(): ?CategoryIncident {
        
        $stmt = $this->db->prepare("SELECT `id`, `clave`, `nombre_es`, `descripcion` FROM siracatlan.cat_incidente_categoria WHERE activo = 1");
        $stmt->execute();
        
        $data = $stmt->fetch();

        if(!$data) {
            return null;
        }
        
        return new CategoryIncident(
            $data['id'],
            $data['clave'],
            $data['nombre_es'],
            $data['descripcion']
        );
            
    }

    private function get_banos_list(): ?Bano {
        
        $stmt = $this->db->prepare("SELECT id, tipo, codigo_interno, activo FROM siracatlan.banos WHERE activo = 1");
        $stmt->execute();
        $data = $stmt->fetch();
        
        return new Bano(
            $data['id'],
            $data['tipo'],
            $data['codigo_interno'],
            $data['activo']
        );
    }

    private function get_edificios_list(): ?Buildings {
        
        $stmt = $this->db->prepare("SELECT id, codigo, nombre FROM siracatlan.edificios");
        $stmt->execute();
        $data = $stmt->fetch();

        return new Buildings(
            $data['id'],
            $data['codigo'],
            $data['nombre']
        );
    }

    private function get_zonas_list(): ?Zones {

        $stmt = $this->db->prepare("SELECT * FROM siracatlan.zonas");
        $stmt->execute();
        $data = $stmt->fetch();
        
        return new Zones(
            $data['id'],
            $data['nombre'],
            $data['descripcion']
        );
    }
    
    public function metadata(): ?Metadata {

        $incidents_cat = $this->get_incidents_cat();
        $lista_banos = $this->get_banos_list();
        $lista_edificios = $this->get_edificios_list();
        $lista_zonas = $this->get_zonas_list();
        
        return new Metadata(
            $incidents_cat,
            $lista_banos,
            $lista_edificios,
            $lista_zonas
        );
    }

    public function createIncident(): ?Incident {
        // pass
        return new Incident();
    }
    
}

?>
