<?php

// Camada que conversa com a tabela EXERCICIO.
class ExercicioRepository
{
    // Recebe a conexao PDO criada pelo Database.
    public function __construct(private PDO $pdo)
    {
    }

    // Retorna os exercicios da materia escolhida.
    public function listarPorMateria(int $materiaId): array
    {
        // prepare() permite usar parametro com seguranca.
        $stmt = $this->pdo->prepare(
            "SELECT id, materia_id, enunciado, tipo, opcoes_json, correta, codigo
             FROM EXERCICIO
             WHERE materia_id = :materia_id
             ORDER BY id"
        );

        // Substitui :materia_id pelo id recebido do controller.
        $stmt->execute(["materia_id" => $materiaId]);

        // Pega todos os exercicios encontrados.
        $exercicios = $stmt->fetchAll();

        // Converte opcoes_json de texto JSON para array PHP.
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
