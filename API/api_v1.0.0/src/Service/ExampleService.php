<?php
namespace App\Service;

use App\Repository\ExampleRepository;
use App\Model\Example;

class ExampleService {

    public function __construct(private ExampleRepository $repo) {}

    public function getExample(int $id): array {
        $example = $this->repo->findById($id);
        
        if (!$example) {
            throw new \Exception("Ejemplo no encontrado");
        }

        return [
            'id' => $example->id,
            'name' => $example->name,
            'status' => $example->status,
            'processed_at' => date('Y-m-d H:i:s')
        ];
    }

    public function createExample(array $data): array {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException("El nombre es requerido");
        }

        // Lógica de negocio simulada
        $newExample = new Example(rand(1,1000), $data['name'], 'nuevo');
        
        $this->repo->save($newExample);

        return ['message' => 'Ejemplo creado exitosamente', 'id' => $newExample->id];
    }
}
