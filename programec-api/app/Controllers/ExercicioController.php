<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/ExercicioRepository.php";

// Recebe chamadas relacionadas aos exercicios.
class ExercicioController
{
    // Cria o Repository ja conectado ao banco.
    private function repository(): ExercicioRepository
    {
        $database = new Database();
        return new ExercicioRepository($database->getConnection());
    }

    // GET /materias/{materiaId}/exercicios
    public function listarPorMateria(string $materiaId): void
    {
        try {
            // Busca exercicios da materia escolhida.
            $exercicios = $this
                ->repository()
                ->listarPorMateria((int) $materiaId);

            // Devolve a lista para o Flutter.
            Response::json(
                1,
                "Exercicios listados com sucesso.",
                $exercicios
            );
        } catch (Exception $e) {
            // Se algo falhar no banco, devolve erro padronizado.
            Response::json(0, "Erro ao listar exercicios.", null, 500);
        }
    }
}
