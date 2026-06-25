<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/MateriaRepository.php";

class MateriaController
{
    private function repository(): MateriaRepository
    {
        $database = new Database();
        return new MateriaRepository($database->getConnection());
    }

    // GET /materias
    public function listar(): void
    {
        try {
            $materias = $this->repository()->listar();
            Response::json(1, "Matérias listadas com sucesso.", $materias);
        } catch (Exception $e) {
            Response::json(0, "Erro ao listar matérias.", null, 500);
        }
    }
}
