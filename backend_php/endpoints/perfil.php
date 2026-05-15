<?php
// perfil.php — Endpoint: GET /endpoints/perfil.php?id=X
// Retorna os dados públicos de um usuário (sem a senha).
//
// Parâmetro esperado (query string):
//   id → ID do usuário

require_once __DIR__ . '/../config/Banco.php';

$banco = new Banco();

try
{
    $id = $_GET['id'] ?? '';

    if (empty($id) || !is_numeric($id))
    {
        $banco->setMensagem(0, 'Informe um id válido.');
        echo $banco->getRetorno();
        exit;
    }

    $stmt = $banco->conexao->prepare(
        "SELECT id, nome, email, avatar FROM USUARIO WHERE id = :id"
    );
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario)
    {
        $banco->setMensagem(0, 'Usuário não encontrado.');
        echo $banco->getRetorno();
        exit;
    }

    $banco->setMensagem(1, 'Usuário encontrado.');
    $banco->setDados($usuario);
    echo $banco->getRetorno();
}
catch (Exception $e)
{
    $banco->setMensagem(0, 'Erro interno: ' . $e->getMessage());
    echo $banco->getRetorno();
}
