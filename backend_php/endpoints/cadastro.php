<?php
// ============================================================
// cadastro.php — Endpoint: POST /endpoints/cadastro.php
// Registra um novo usuário no banco.
//
// Parâmetros esperados (POST body ou form-data):
//   nome  → nome completo do usuário
//   email → email (deve ser único)
//   senha → senha em texto puro (será transformada em hash)
// ============================================================

require_once __DIR__ . '/../config/Banco.php';

$banco = new Banco();

try
{
    // Lê os dados enviados pelo app (POST)
    $nome  = $_POST['nome']  ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Validação básica: nenhum campo pode estar vazio
    if (empty($nome) || empty($email) || empty($senha))
    {
        $banco->setMensagem(0, 'Preencha todos os campos: nome, email e senha.');
        echo $banco->getRetorno();
        exit;
    }

    // Verifica se o email já está cadastrado
    $stmt = $banco->conexao->prepare(
        "SELECT id FROM USUARIO WHERE email = :email"
    );
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0)
    {
        $banco->setMensagem(0, 'Este e-mail já está cadastrado.');
        echo $banco->getRetorno();
        exit;
    }

    // Gera o hash seguro da senha (bcrypt)
    // NUNCA salvamos a senha em texto puro no banco
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    // Insere o novo usuário
    $stmt = $banco->conexao->prepare(
        "INSERT INTO USUARIO (nome, email, senha) VALUES (:nome, :email, :senha)"
    );
    $stmt->bindValue(':nome',  $nome);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senhaHash);
    $stmt->execute();

    $banco->setMensagem(1, 'Cadastro realizado com sucesso!');
    echo $banco->getRetorno();
}
catch (Exception $e)
{
    $banco->setMensagem(0, 'Erro interno: ' . $e->getMessage());
    echo $banco->getRetorno();
}
