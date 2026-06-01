<?php

class MateriaRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function listar(): array
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, icone FROM MATERIA ORDER BY id");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
