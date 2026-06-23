<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/TentativaRepository.php";

class TentativaController
{
    private function repository(): TentativaRepository
    {
        $database = new Database();
        return new TentativaRepository($database->getConnection());
    }

    // POST /tentativas
    public function criar(): void
    {
        try {
            $dados = Request::body();

            $this->repository()->criar(
                (int) $dados["usuario_id"],
                (int) $dados["materia_id"],
                (float) $dados["nota"]
            );

            Response::json(1, "Resultado salvo com sucesso!", null, 201);
        } catch (Exception $e) {
            Response::json(0, "Erro ao salvar tentativa.", null, 500);
        }
    }
}
