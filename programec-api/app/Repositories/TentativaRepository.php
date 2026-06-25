<?php

class TentativaRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    // Salva a nota final do quiz.
    public function criar(int $usuarioId, int $materiaId, float $nota): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO TENTATIVA (usuario_id, materia_id, nota)
             VALUES (:usuario_id, :materia_id, :nota)"
        );
        $stmt->execute([
            "usuario_id" => $usuarioId,
            "materia_id" => $materiaId,
            "nota" => $nota,
        ]);
    }
}
