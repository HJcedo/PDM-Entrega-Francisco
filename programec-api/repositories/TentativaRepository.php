<?php

class TentativaRepository
{
    // Recebe a conexao PDO que veio de Database/Banco.
    public function __construct(private PDO $pdo)
    {
    }

    // Insere no banco a nota final de uma tentativa de quiz.
    public function criar(int $usuarioId, int $materiaId, float $nota): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO TENTATIVA (usuario_id, materia_id, nota)
             VALUES (:usuario_id, :materia_id, :nota)"
        );

        // bindValue evita montar SQL com texto concatenado.
        $stmt->bindValue(":usuario_id", $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(":materia_id", $materiaId, PDO::PARAM_INT);
        $stmt->bindValue(":nota", $nota);
        $stmt->execute();
    }
}
