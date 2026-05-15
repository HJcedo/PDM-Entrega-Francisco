<?php
// cadastro.php — Endpoint: POST /endpoints/cadastro.php
// Registra um novo usuário no banco.
//
// Parâmetros esperados (POST):
//   nome, email, senha

require_once __DIR__ . '/../config/Banco.php';

$banco = new Banco();

try
{
    $nome  = $_POST['nome']  ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha))
    {
        $banco->setMensagem(0, 'Preencha todos os campos: nome, email e senha.');
        echo $banco->getRetorno();
        exit;
    }

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

    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

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
