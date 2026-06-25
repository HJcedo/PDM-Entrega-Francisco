<?php

class MateriaRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    // Retorna todas as matérias.
    public function listar(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id, nome, icone FROM MATERIA ORDER BY id"
        );

        return $stmt->fetchAll();
    }
}
