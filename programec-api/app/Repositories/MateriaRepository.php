<?php

class MateriaRepository
{
    // Recebe a conexao PDO que veio de Database/Banco.
    public function __construct(private PDO $pdo)
    {
    }

    // Busca todas as materias cadastradas.
    public function listar(): array
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, icone FROM MATERIA ORDER BY id");
        $stmt->execute();

        // Retorna todas as linhas como array associativo.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
