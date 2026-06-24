<?php

// Camada que conversa com a tabela USUARIO.
class UsuarioRepository
{
    // Recebe a conexao PDO criada pelo Database.
    public function __construct(private PDO $pdo)
    {
    }

    // Busca o usuario usado no login.
    public function buscarPorEmail(string $email): ?array
    {
        // Procura usuario pelo email informado.
        $stmt = $this->pdo->prepare(
            "SELECT id, nome, email, senha, avatar
             FROM USUARIO
             WHERE email = :email"
        );

        // Substitui :email pelo email recebido do controller.
        $stmt->execute(["email" => $email]);

        // Retorna o usuario ou null se nao encontrar.
        return $stmt->fetch() ?: null;
    }

    // Busca os dados publicos do perfil.
    public function buscarPorId(int $id): ?array
    {
        // Busca usuario pelo id da URL.
        $stmt = $this->pdo->prepare(
            "SELECT id, nome, email, avatar
             FROM USUARIO
             WHERE id = :id"
        );

        // Substitui :id pelo id recebido do controller.
        $stmt->execute(["id" => $id]);

        // Retorna o usuario ou null se nao encontrar.
        return $stmt->fetch() ?: null;
    }

    // Cadastra um usuario com senha ja transformada em hash.
    public function criar(string $nome, string $email, string $senha): void
    {
        // SQL de cadastro do usuario.
        $stmt = $this->pdo->prepare(
            "INSERT INTO USUARIO (nome, email, senha)
             VALUES (:nome, :email, :senha)"
        );

        // Envia os dados recebidos do controller para o SQL.
        $stmt->execute([
            "nome" => $nome,
            "email" => $email,
            "senha" => $senha,
        ]);
    }

    // Atualiza somente o avatar do perfil.
    public function atualizarAvatar(int $id, string $avatar): void
    {
        // SQL que altera o avatar do usuario.
        $stmt = $this->pdo->prepare(
            "UPDATE USUARIO SET avatar = :avatar WHERE id = :id"
        );

        // Envia avatar e id para o SQL.
        $stmt->execute([
            "avatar" => $avatar,
            "id" => $id,
        ]);
    }

    // Remove a conta do usuario.
    public function deletar(int $id): void
    {
        // Primeiro remove tentativas ligadas ao usuario.
        $stmt = $this->pdo->prepare(
            "DELETE FROM TENTATIVA WHERE usuario_id = :id"
        );
        $stmt->execute(["id" => $id]);

        // Depois remove o usuario.
        $stmt = $this->pdo->prepare(
            "DELETE FROM USUARIO WHERE id = :id"
        );
        $stmt->execute(["id" => $id]);
    }
}
