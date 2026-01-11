<?php
namespace App\Repository;

use App\Model\Example;
use PDO;

class ExampleRepository {
    
    public function __construct(private PDO $db) {}

    public function findById(int $id): ?Example {
        // En un caso real:
        // $stmt = $this->db->prepare("SELECT * FROM examples WHERE id = ?");
        // $stmt->execute([$id]);
        // ... hydrate object ...
        
        // Simulación para el ejemplo:
        if ($id === 999) return null; // Simular no encontrado

        return new Example($id, "Ejemplo Simulado #$id", "activo");
    }

    public function save(Example $example): bool {
        // En un caso real: 
        // INSERT INTO ...
        return true;
    }
}
