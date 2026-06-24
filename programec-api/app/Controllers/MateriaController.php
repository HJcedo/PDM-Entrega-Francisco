<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/MateriaRepository.php";

// Recebe chamadas relacionadas a materias.
class MateriaController
{
    // Cria o Repository ja conectado ao banco.
    private function repository(): MateriaRepository
    {
        $database = new Database();
        return new MateriaRepository($database->getConnection());
    }

    // GET /materias
    public function listar(): void
    {
        try {
            // Busca as materias no banco pelo Repository.
            $materias = $this->repository()->listar();

            // Devolve as materias em JSON para o Flutter.
            Response::json(1, "Materias listadas com sucesso.", $materias);
        } catch (Exception $e) {
            // Se algo der errado, devolve erro padronizado.
            Response::json(0, "Erro ao listar materias.", null, 500);
        }
    }
}
