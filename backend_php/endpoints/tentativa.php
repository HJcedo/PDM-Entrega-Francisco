<?php
// tentativa.php — Endpoint: POST /endpoints/tentativa.php
// Salva o resultado de um quiz feito pelo usuário.
//
// Parâmetros esperados (POST):
//   usuario_id, materia_id, nota

require_once __DIR__ . '/../config/Banco.php';

$banco = new Banco();

try
{
    $usuario_id = $_POST['usuario_id'] ?? '';
    $materia_id = $_POST['materia_id'] ?? '';
    $nota       = $_POST['nota']       ?? '';

    if (empty($usuario_id) || empty($materia_id) || $nota === '')
    {
        $banco->setMensagem(0, 'Informe usuario_id, materia_id e nota.');
        echo $banco->getRetorno();
        exit;
    }

    if (!is_numeric($usuario_id) || !is_numeric($materia_id) || !is_numeric($nota))
    {
        $banco->setMensagem(0, 'Os valores de usuario_id, materia_id e nota devem ser numéricos.');
        echo $banco->getRetorno();
        exit;
    }

    $nota = max(0, min(10, (float)$nota));

    $stmt = $banco->conexao->prepare(
        "INSERT INTO TENTATIVA (usuario_id, materia_id, nota)
         VALUES (:usuario_id, :materia_id, :nota)"
    );
    $stmt->bindValue(':usuario_id', (int)$usuario_id, PDO::PARAM_INT);
    $stmt->bindValue(':materia_id', (int)$materia_id, PDO::PARAM_INT);
    $stmt->bindValue(':nota',       $nota);
    $stmt->execute();

    $banco->setMensagem(1, 'Resultado salvo com sucesso!');
    echo $banco->getRetorno();
}
catch (Exception $e)
{
    $banco->setMensagem(0, 'Erro interno: ' . $e->getMessage());
    echo $banco->getRetorno();
}
