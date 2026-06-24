<?php

// Camada que conversa com a tabela MATERIA.
class MateriaRepository
{
    // Recebe a conexao PDO criada pelo Database.
    public function __construct(private PDO $pdo)
    {
    }

    // Retorna todas as materias cadastradas.
    public function listar(): array
    {
        // query() e usado porque nao ha parametro externo nessa consulta.
        $stmt = $this->pdo->query(
            "SELECT id, nome, icone FROM MATERIA ORDER BY id"
        );

        // fetchAll() transforma o resultado SQL em array PHP.
        return $stmt->fetchAll();
    }
}
