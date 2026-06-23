<?php

class ExercicioRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    // Retorna os exercícios da matéria escolhida.
    public function listarPorMateria(int $materiaId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, materia_id, enunciado, tipo, opcoes_json, correta, codigo
             FROM EXERCICIO
             WHERE materia_id = :materia_id
             ORDER BY id"
        );
        $stmt->execute(["materia_id" => $materiaId]);

        $exercicios = $stmt->fetchAll();

        // O PostgreSQL guarda as opções como texto JSON.
        foreach ($exercicios as &$exercicio) {
            if ($exercicio["opcoes_json"]) {
                $exercicio["opcoes_json"] = json_decode(
                    $exercicio["opcoes_json"],
                    true
                );
            }
        }

        return $exercicios;
    }
}
