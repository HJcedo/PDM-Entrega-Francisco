<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../helpers/Response.php";
require_once __DIR__ . "/../repositories/MateriaRepository.php";

class MateriaController
{
    private function repository(): MateriaRepository
    {
        $database = new Database();
        return new MateriaRepository($database->getConnection());
    }

    public function listar(): void
    {
        try {
            $materias = $this->repository()->listar();

            if (empty($materias)) {
                Response::json(0, "Nenhuma materia cadastrada.");
                return;
            }

            Response::json(1, "Materias listadas com sucesso.", $materias);
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }
}
