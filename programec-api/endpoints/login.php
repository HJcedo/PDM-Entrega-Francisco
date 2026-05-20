<?php
// ============================================================
// login.php — Endpoint: POST /endpoints/login.php
// Autentica um usuário existente.
//
// Parâmetros esperados (POST body ou form-data):
//   email → email cadastrado
//   senha → senha em texto puro (será comparada com o hash)
//
// Retorno em caso de sucesso:
//   dados → { id, nome, email, avatar }
// ============================================================

require_once __DIR__ . '/../config/Banco.php';

$banco = new Banco();

try
{
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha))
    {
        $banco->setMensagem(0, 'Informe e-mail e senha.');
        echo $banco->getRetorno();
        exit;
    }

    // Busca o usuário pelo email
    $stmt = $banco->conexao->prepare(
        "SELECT id, nome, email, senha, avatar FROM USUARIO WHERE email = :email"
    );
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário existe e se a senha confere com o hash
    // password_verify compara a senha digitada com o hash salvo no banco
    if (!$usuario || !password_verify($senha, $usuario['senha']))
    {
        $banco->setMensagem(0, 'E-mail ou senha incorretos.');
        echo $banco->getRetorno();
        exit;
    }

    // Remove a senha do retorno — nunca enviamos o hash para o app
    unset($usuario['senha']);

    $banco->setMensagem(1, 'Login realizado com sucesso!');
    $banco->setDados($usuario);
    echo $banco->getRetorno();
}
catch (Exception $e)
{
    $banco->setMensagem(0, 'Erro interno: ' . $e->getMessage());
    echo $banco->getRetorno();
}
