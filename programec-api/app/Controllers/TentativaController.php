<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/TentativaRepository.php";

// Recebe chamadas relacionadas ao resultado do quiz.
class TentativaController
{
    // Cria o Repository ja conectado ao banco.
    private function repository(): TentativaRepository
    {
        $database = new Database();
        return new TentativaRepository($database->getConnection());
    }

    // POST /tentativas
    public function criar(): void
    {
        try {
            // Le usuario_id, materia_id e nota enviados pelo Flutter.
            $dados = Request::body();

            // Salva a tentativa no banco.
            $this->repository()->criar(
                (int) $dados["usuario_id"],
                (int) $dados["materia_id"],
                (float) $dados["nota"]
            );

            // Confirma para o Flutter que o resultado foi salvo.
            Response::json(1, "Resultado salvo com sucesso!", null, 201);
        } catch (Exception $e) {
            // Se algo falhar, devolve erro padronizado.
            Response::json(0, "Erro ao salvar tentativa.", null, 500);
        }
    }
}
