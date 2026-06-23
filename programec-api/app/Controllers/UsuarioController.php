<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/UsuarioRepository.php";

class UsuarioController
{
    // Cria o Repository conectado ao banco.
    private function repository(): UsuarioRepository
    {
        $database = new Database();
        return new UsuarioRepository($database->getConnection());
    }

    // POST /usuarios
    public function criar(): void
    {
        try {
            $dados = Request::body();
            $usuarios = $this->repository();

            // A senha é armazenada como hash.
            $senhaHash = password_hash($dados["senha"], PASSWORD_BCRYPT);

            $usuarios->criar(
                $dados["nome"],
                $dados["email"],
                $senhaHash
            );

            Response::json(1, "Cadastro realizado com sucesso!", null, 201);
        } catch (Exception $e) {
            Response::json(0, "Erro ao cadastrar usuário.", null, 500);
        }
    }

    // POST /sessoes
    public function autenticar(): void
    {
        try {
            $dados = Request::body();
            $usuario = $this->repository()->buscarPorEmail($dados["email"]);

            // Esta verificação é necessária para confirmar o login.
            if (!$usuario || !password_verify($dados["senha"], $usuario["senha"])) {
                Response::json(0, "E-mail ou senha incorretos.", null, 401);
                return;
            }

            // A senha nunca deve ser enviada para o Flutter.
            unset($usuario["senha"]);
            Response::json(1, "Login realizado com sucesso!", $usuario);
        } catch (Exception $e) {
            Response::json(0, "Erro ao realizar login.", null, 500);
        }
    }

    // GET /usuarios/{id}
    public function buscar(string $id): void
    {
        try {
            $usuario = $this->repository()->buscarPorId((int) $id);
            Response::json(1, "Usuário encontrado.", $usuario);
        } catch (Exception $e) {
            Response::json(0, "Erro ao buscar usuário.", null, 500);
        }
    }

    // PATCH /usuarios/{id}
    public function atualizar(string $id): void
    {
        try {
            $dados = Request::body();
            $usuarios = $this->repository();
            $usuarios->atualizarAvatar((int) $id, $dados["avatar"]);

            $usuario = $usuarios->buscarPorId((int) $id);
            Response::json(1, "Perfil atualizado com sucesso!", $usuario);
        } catch (Exception $e) {
            Response::json(0, "Erro ao atualizar usuário.", null, 500);
        }
    }

    // DELETE /usuarios/{id}
    public function deletar(string $id): void
    {
        try {
            $this->repository()->deletar((int) $id);
            Response::json(1, "Usuário removido com sucesso.");
        } catch (Exception $e) {
            Response::json(0, "Erro ao remover usuário.", null, 500);
        }
    }
}
