<?php

// Camada que conversa com a tabela TENTATIVA.
class TentativaRepository
{
    // Recebe a conexao PDO criada pelo Database.
    public function __construct(private PDO $pdo)
    {
    }

    // Salva a nota final do quiz.
    public function criar(int $usuarioId, int $materiaId, float $nota): void
    {
        // SQL que insere a tentativa no banco.
        $stmt = $this->pdo->prepare(
            "INSERT INTO TENTATIVA (usuario_id, materia_id, nota)
             VALUES (:usuario_id, :materia_id, :nota)"
        );

        // Envia os valores recebidos do controller para o SQL.
        $stmt->execute([
            "usuario_id" => $usuarioId,
            "materia_id" => $materiaId,
            "nota" => $nota,
        ]);
    }
}
