<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/ExercicioRepository.php";

class ExercicioController
{
    private function repository(): ExercicioRepository
    {
        $database = new Database();
        return new ExercicioRepository($database->getConnection());
    }

    // GET /materias/{materiaId}/exercicios
    public function listarPorMateria(string $materiaId): void
    {
        try {
            $exercicios = $this
                ->repository()
                ->listarPorMateria((int) $materiaId);

            Response::json(
                1,
                "Exercícios listados com sucesso.",
                $exercicios
            );
        } catch (Exception $e) {
            Response::json(0, "Erro ao listar exercícios.", null, 500);
        }
    }
}
