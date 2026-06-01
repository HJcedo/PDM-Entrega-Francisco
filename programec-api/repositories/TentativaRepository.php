<?php

class TentativaRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function criar(int $usuarioId, int $materiaId, float $nota): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO TENTATIVA (usuario_id, materia_id, nota)
             VALUES (:usuario_id, :materia_id, :nota)"
        );
        $stmt->bindValue(":usuario_id", $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(":materia_id", $materiaId, PDO::PARAM_INT);
        $stmt->bindValue(":nota", $nota);
        $stmt->execute();
    }
}
