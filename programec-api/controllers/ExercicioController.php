<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../helpers/Response.php";
require_once __DIR__ . "/../repositories/ExercicioRepository.php";

class ExercicioController
{
    private function repository(): ExercicioRepository
    {
        $database = new Database();
        return new ExercicioRepository($database->getConnection());
    }

    public function listarPorMateria(): void
    {
        try {
            $materiaId = $_GET["materia_id"] ?? "";

            if (empty($materiaId) || !is_numeric($materiaId)) {
                Response::json(0, "Informe um materia_id valido.");
                return;
            }

            $exercicios = $this->repository()->listarPorMateria((int) $materiaId);
            if (empty($exercicios)) {
                Response::json(0, "Nenhum exercicio encontrado para esta materia.");
                return;
            }

            Response::json(1, "Exercicios listados com sucesso.", $exercicios);
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }
}
