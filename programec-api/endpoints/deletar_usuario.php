<?php
// ============================================================
// deletar_usuario.php — Endpoint: POST /endpoints/deletar_usuario.php
// Remove um usuário e todas as suas tentativas do banco.
//
// Parâmetros esperados (POST body ou form-data):
//   id → ID do usuário a ser removido
// ============================================================

require_once __DIR__ . '/../config/Banco.php';

$banco = new Banco();

try
{
    $id = $_POST['id'] ?? '';

    if (empty($id) || !is_numeric($id))
    {
        $banco->setMensagem(0, 'Informe um id válido.');
        echo $banco->getRetorno();
        exit;
    }

    // Remove primeiro as tentativas (FK impede deletar o usuário antes)
    $stmt = $banco->conexao->prepare(
        "DELETE FROM TENTATIVA WHERE usuario_id = :id"
    );
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();

    // Verifica se o usuário existe antes de deletar
    $check = $banco->conexao->prepare("SELECT id FROM USUARIO WHERE id = :id");
    $check->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $check->execute();

    if (!$check->fetch())
    {
        $banco->setMensagem(0, 'Usuário não encontrado.');
        echo $banco->getRetorno();
        exit;
    }

    // Agora remove o usuário
    $stmt = $banco->conexao->prepare(
        "DELETE FROM USUARIO WHERE id = :id"
    );
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();

    $banco->setMensagem(1, 'Usuário removido com sucesso.');
    echo $banco->getRetorno();
}
catch (Exception $e)
{
    $banco->setMensagem(0, 'Erro interno: ' . $e->getMessage());
    echo $banco->getRetorno();
}
