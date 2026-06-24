<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/UsuarioRepository.php";

// Recebe chamadas relacionadas a usuarios, login e perfil.
class UsuarioController
{
    // Cria o Repository ja conectado ao banco.
    private function repository(): UsuarioRepository
    {
        $database = new Database();
        return new UsuarioRepository($database->getConnection());
    }

    // POST /usuarios
    public function criar(): void
    {
        try {
            // Le nome, email e senha enviados pelo Flutter.
            $dados = Request::body();
            $usuarios = $this->repository();

            // Transforma a senha em hash antes de salvar.
            $senhaHash = password_hash($dados["senha"], PASSWORD_BCRYPT);

            // Envia os dados para o Repository inserir no banco.
            $usuarios->criar(
                $dados["nome"],
                $dados["email"],
                $senhaHash
            );

            // Retorna sucesso de cadastro.
            Response::json(1, "Cadastro realizado com sucesso!", null, 201);
        } catch (Exception $e) {
            // Retorna erro caso o cadastro falhe.
            Response::json(0, "Erro ao cadastrar usuario.", null, 500);
        }
    }

    // POST /sessoes
    public function autenticar(): void
    {
        try {
            // Le email e senha enviados pelo Flutter.
            $dados = Request::body();

            // Busca o usuario pelo email informado.
            $usuario = $this->repository()->buscarPorEmail($dados["email"]);

            // Confere se o usuario existe e se a senha bate com o hash.
            if (!$usuario || !password_verify($dados["senha"], $usuario["senha"])) {
                Response::json(0, "E-mail ou senha incorretos.", null, 401);
                return;
            }

            // Remove a senha para nunca enviar esse dado ao Flutter.
            unset($usuario["senha"]);

            // Retorna os dados do usuario logado.
            Response::json(1, "Login realizado com sucesso!", $usuario);
        } catch (Exception $e) {
            // Retorna erro caso o login falhe.
            Response::json(0, "Erro ao realizar login.", null, 500);
        }
    }

    // GET /usuarios/{id}
    public function buscar(string $id): void
    {
        try {
            // Busca o perfil pelo id recebido na URL.
            $usuario = $this->repository()->buscarPorId((int) $id);

            // Retorna os dados do perfil.
            Response::json(1, "Usuario encontrado.", $usuario);
        } catch (Exception $e) {
            // Retorna erro caso a busca falhe.
            Response::json(0, "Erro ao buscar usuario.", null, 500);
        }
    }

    // PATCH /usuarios/{id}
    public function atualizar(string $id): void
    {
        try {
            // Le o avatar escolhido pelo usuario.
            $dados = Request::body();
            $usuarios = $this->repository();

            // Atualiza somente o avatar no banco.
            $usuarios->atualizarAvatar((int) $id, $dados["avatar"]);

            // Busca o usuario atualizado para devolver ao Flutter.
            $usuario = $usuarios->buscarPorId((int) $id);
            Response::json(1, "Perfil atualizado com sucesso!", $usuario);
        } catch (Exception $e) {
            // Retorna erro caso a atualizacao falhe.
            Response::json(0, "Erro ao atualizar usuario.", null, 500);
        }
    }

    // DELETE /usuarios/{id}
    public function deletar(string $id): void
    {
        try {
            // Remove a conta do usuario pelo id.
            $this->repository()->deletar((int) $id);

            // Confirma a exclusao para o Flutter.
            Response::json(1, "Usuario removido com sucesso.");
        } catch (Exception $e) {
            // Retorna erro caso a exclusao falhe.
            Response::json(0, "Erro ao remover usuario.", null, 500);
        }
    }
}
