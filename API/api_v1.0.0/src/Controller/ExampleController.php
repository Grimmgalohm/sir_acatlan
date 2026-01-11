<?php
namespace App\Controller;

use App\Core\Request;
use App\Core\Response;

class ExampleController {

    public function __construct(private \App\Service\ExampleService $service) {}

    // GET /api/examples/{id}
    public function getOne(Request $request, $id) {
        try {
            $data = $this->service->getExample((int)$id);
            Response::json($data);
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 404);
        }
    }

    // PUT /api/examples/{id}
    public function update(Request $request, $id) {
        // En un caso real llamarías a $this->service->update(...)
        $body = $request->getBody();
        Response::json([
            'message' => "Actualizando el recurso $id desde el servicio (simulado)",
            'received_data' => $body
        ]);
    }

    // DELETE /api/examples/{id}
    public function delete(Request $request, $id) {
         // En un caso real llamarías a $this->service->delete(...)
        Response::json([
            'message' => "Eliminando el recurso $id desde el servicio (simulado)"
        ]);
    }
}
