<?php
// ============================================================
// atualizar_usuario.php — Endpoint: POST /endpoints/atualizar_usuario.php
// Atualiza nome e/ou avatar do usuário logado.
//
// Parâmetros esperados (POST body ou form-data):
//   id     → ID do usuário (obrigatório)
//   nome   → novo nome (opcional)
//   avatar → novo avatar emoji (opcional)
//
// Pelo menos nome ou avatar deve ser informado.
// ============================================================

require_once __DIR__ . '/../config/Banco.php';

$banco = new Banco();

try
{
    $id     = $_POST['id']     ?? '';
    $nome   = $_POST['nome']   ?? null;
    $avatar = $_POST['avatar'] ?? null;

    if (empty($id) || !is_numeric($id))
    {
        $banco->setMensagem(0, 'Informe um id válido.');
        echo $banco->getRetorno();
        exit;
    }

    // Pelo menos um campo precisa ser atualizado
    if (empty($nome) && empty($avatar))
    {
        $banco->setMensagem(0, 'Informe ao menos nome ou avatar para atualizar.');
        echo $banco->getRetorno();
        exit;
    }

    // Monta a query dinamicamente com apenas os campos enviados
    $campos = [];
    $params = [':id' => (int)$id];

    if (!empty($nome)) {
        $campos[] = "nome = :nome";
        $params[':nome'] = $nome;
    }
    if (!empty($avatar)) {
        $campos[] = "avatar = :avatar";
        $params[':avatar'] = $avatar;
    }

    $sql  = "UPDATE USUARIO SET " . implode(', ', $campos) . " WHERE id = :id";
    $stmt = $banco->conexao->prepare($sql);
    $stmt->execute($params);

    $banco->setMensagem(1, 'Perfil atualizado com sucesso!');
    echo $banco->getRetorno();
}
catch (Exception $e)
{
    $banco->setMensagem(0, 'Erro interno: ' . $e->getMessage());
    echo $banco->getRetorno();
}
