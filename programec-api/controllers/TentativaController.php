<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../helpers/Response.php";
require_once __DIR__ . "/../repositories/TentativaRepository.php";

class TentativaController
{
    // Cria o repository com uma conexao ao banco.
    private function repository(): TentativaRepository
    {
        $database = new Database();
        return new TentativaRepository($database->getConnection());
    }

    // Atende a rota POST /tentativa.
    public function criar(): void
    {
        try {
            // Le os dados enviados pelo app depois do quiz.
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

            // Garante que a nota fique entre 0 e 10.
            $nota = max(0, min(10, (float) $nota));

            // Salva a tentativa no banco.
            $this->repository()->criar((int) $usuarioId, (int) $materiaId, $nota);

            Response::json(1, "Resultado salvo com sucesso!");
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }
}
