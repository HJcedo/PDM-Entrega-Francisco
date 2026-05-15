<?php
// exercicios.php — Endpoint: GET /endpoints/exercicios.php?materia_id=X
// Retorna todos os exercícios de uma matéria.
//
// Parâmetro esperado (query string):
//   materia_id → ID da matéria (ex: ?materia_id=1)

require_once __DIR__ . '/../config/Banco.php';

$banco = new Banco();

try
{
    $materia_id = $_GET['materia_id'] ?? '';

    if (empty($materia_id) || !is_numeric($materia_id))
    {
        $banco->setMensagem(0, 'Informe um materia_id válido.');
        echo $banco->getRetorno();
        exit;
    }

    $stmt = $banco->conexao->prepare(
        "SELECT id, enunciado, tipo, opcoes_json, correta, codigo
         FROM EXERCICIO
         WHERE materia_id = :materia_id
         ORDER BY id"
    );
    $stmt->bindValue(':materia_id', (int)$materia_id, PDO::PARAM_INT);
    $stmt->execute();

    $exercicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($exercicios))
    {
        $banco->setMensagem(0, 'Nenhum exercício encontrado para esta matéria.');
        echo $banco->getRetorno();
        exit;
    }

    foreach ($exercicios as &$ex)
    {
        if (!empty($ex['opcoes_json']))
        {
            $ex['opcoes_json'] = json_decode($ex['opcoes_json'], true);
        }
    }

    $banco->setMensagem(1, 'Exercícios listados com sucesso.');
    $banco->setDados($exercicios);
    echo $banco->getRetorno();
}
catch (Exception $e)
{
    $banco->setMensagem(0, 'Erro interno: ' . $e->getMessage());
    echo $banco->getRetorno();
}
