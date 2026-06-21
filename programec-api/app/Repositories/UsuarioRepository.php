<?php

class UsuarioRepository
{
    // Recebe a conexao PDO que veio de Database/Banco.
    public function __construct(private PDO $pdo)
    {
    }

    // Busca um usuario pelo e-mail para login.
    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nome, email, senha, avatar FROM USUARIO WHERE email = :email"
        );
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }

    // Busca os dados publicos do usuario pelo id.
    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nome, email, avatar FROM USUARIO WHERE id = :id"
        );
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }

    // Verifica se ja existe usuario com este e-mail.
    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM USUARIO WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cadastra um novo usuario com senha ja criptografada.
    public function criar(string $nome, string $email, string $senhaHash): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO USUARIO (nome, email, senha) VALUES (:nome, :email, :senha)"
        );
        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":senha", $senhaHash);
        $stmt->execute();
    }

    // Atualiza nome e/ou avatar, dependendo do que foi enviado.
    public function atualizar(int $id, ?string $nome, ?string $avatar): void
    {
        $campos = [];
        $params = [":id" => $id];

        if (!empty($nome)) {
            $campos[] = "nome = :nome";
            $params[":nome"] = $nome;
        }

        if (!empty($avatar)) {
            $campos[] = "avatar = :avatar";
            $params[":avatar"] = $avatar;
        }

        $sql = "UPDATE USUARIO SET " . implode(", ", $campos) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    // Remove primeiro as tentativas e depois o usuario.
    public function deletar(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM TENTATIVA WHERE usuario_id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->pdo->prepare("DELETE FROM USUARIO WHERE id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
