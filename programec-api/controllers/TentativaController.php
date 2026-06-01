<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../helpers/Response.php";
require_once __DIR__ . "/../repositories/TentativaRepository.php";

class TentativaController
{
    private function repository(): TentativaRepository
    {
        $database = new Database();
        return new TentativaRepository($database->getConnection());
    }

    public function criar(): void
    {
        try {
            $usuarioId = $_POST["usuario_id"] ?? "";
            $materiaId = $_POST["materia_id"] ?? "";
            $nota = $_POST["nota"] ?? "";

            if (empty($usuarioId) || empty($materiaId) || $nota === "") {
                Response::json(0, "Informe usuario_id, materia_id e nota.");
                return;
            }

            if (!is_numeric($usuarioId) || !is_numeric($materiaId) || !is_numeric($nota)) {
                Response::json(0, "Os valores de usuario_id, materia_id e nota devem ser numericos.");
                return;
            }

            $nota = max(0, min(10, (float) $nota));
            $this->repository()->criar((int) $usuarioId, (int) $materiaId, $nota);

            Response::json(1, "Resultado salvo com sucesso!");
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }
}
