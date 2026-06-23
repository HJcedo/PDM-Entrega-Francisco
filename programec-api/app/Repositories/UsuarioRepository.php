<?php

class UsuarioRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    // Busca o usuário usado no login.
    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nome, email, senha, avatar
             FROM USUARIO
             WHERE email = :email"
        );
        $stmt->execute(["email" => $email]);

        return $stmt->fetch() ?: null;
    }

    // Busca os dados públicos do perfil.
    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nome, email, avatar
             FROM USUARIO
             WHERE id = :id"
        );
        $stmt->execute(["id" => $id]);

        return $stmt->fetch() ?: null;
    }

    // Cadastra um usuário com a senha já transformada em hash.
    public function criar(string $nome, string $email, string $senha): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO USUARIO (nome, email, senha)
             VALUES (:nome, :email, :senha)"
        );
        $stmt->execute([
            "nome" => $nome,
            "email" => $email,
            "senha" => $senha,
        ]);
    }

    // A tela de perfil altera somente o avatar.
    public function atualizarAvatar(int $id, string $avatar): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE USUARIO SET avatar = :avatar WHERE id = :id"
        );
        $stmt->execute([
            "avatar" => $avatar,
            "id" => $id,
        ]);
    }

    // Remove as tentativas e depois a conta.
    public function deletar(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM TENTATIVA WHERE usuario_id = :id"
        );
        $stmt->execute(["id" => $id]);

        $stmt = $this->pdo->prepare(
            "DELETE FROM USUARIO WHERE id = :id"
        );
        $stmt->execute(["id" => $id]);
    }
}
