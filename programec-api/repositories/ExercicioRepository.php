<?php

class ExercicioRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function listarPorMateria(int $materiaId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, materia_id, enunciado, tipo, opcoes_json, correta, codigo
             FROM EXERCICIO
             WHERE materia_id = :materia_id
             ORDER BY id"
        );
        $stmt->bindValue(":materia_id", $materiaId, PDO::PARAM_INT);
        $stmt->execute();

        $exercicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($exercicios as &$exercicio) {
            if (!empty($exercicio["opcoes_json"])) {
                $exercicio["opcoes_json"] = json_decode($exercicio["opcoes_json"], true);
            }
        }

        return $exercicios;
    }
}
