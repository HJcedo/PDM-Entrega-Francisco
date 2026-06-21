<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/ExercicioRepository.php";

class ExercicioController
{
    // Cria o repository com uma conexao ao banco.
    private function repository(): ExercicioRepository
    {
        $database = new Database();
        return new ExercicioRepository($database->getConnection());
    }

    // Atende a rota GET /exercicios?materia_id=X.
    public function listarPorMateria(): void
    {
        try {
            // Le o id da materia enviado na URL.
            $materiaId = $_GET["materia_id"] ?? "";

            if (empty($materiaId) || !is_numeric($materiaId)) {
                Response::json(0, "Informe um materia_id valido.");
                return;
            }

            // Busca os exercicios da materia escolhida.
            $exercicios = $this->repository()->listarPorMateria((int) $materiaId);
            if (empty($exercicios)) {
                Response::json(0, "Nenhum exercicio encontrado para esta materia.");
                return;
            }

            // Devolve os exercicios para o app.
            Response::json(1, "Exercicios listados com sucesso.", $exercicios);
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }
}
