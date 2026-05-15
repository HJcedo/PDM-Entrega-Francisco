<?php
// materias.php — Endpoint: GET /endpoints/materias.php
// Retorna a lista de todas as matérias cadastradas.

require_once __DIR__ . '/../config/Banco.php';

$banco = new Banco();

try
{
    $stmt = $banco->conexao->prepare(
        "SELECT id, nome, icone FROM MATERIA ORDER BY id"
    );
    $stmt->execute();

    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($materias))
    {
        $banco->setMensagem(0, 'Nenhuma matéria cadastrada.');
        echo $banco->getRetorno();
        exit;
    }

    $banco->setMensagem(1, 'Matérias listadas com sucesso.');
    $banco->setDados($materias);
    echo $banco->getRetorno();
}
catch (Exception $e)
{
    $banco->setMensagem(0, 'Erro interno: ' . $e->getMessage());
    echo $banco->getRetorno();
}
