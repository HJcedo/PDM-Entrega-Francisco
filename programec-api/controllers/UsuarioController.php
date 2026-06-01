<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../helpers/Response.php";
require_once __DIR__ . "/../repositories/UsuarioRepository.php";

class UsuarioController
{
    private function repository(): UsuarioRepository
    {
        $database = new Database();
        return new UsuarioRepository($database->getConnection());
    }

    public function cadastro(): void
    {
        try {
            $usuarios = $this->repository();
            $nome = $_POST["nome"] ?? "";
            $email = $_POST["email"] ?? "";
            $senha = $_POST["senha"] ?? "";

            if (empty($nome) || empty($email) || empty($senha)) {
                Response::json(0, "Preencha todos os campos: nome, email e senha.");
                return;
            }

            if ($usuarios->emailExiste($email)) {
                Response::json(0, "Este e-mail ja esta cadastrado.");
                return;
            }

            $usuarios->criar($nome, $email, password_hash($senha, PASSWORD_BCRYPT));
            Response::json(1, "Cadastro realizado com sucesso!");
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }

    public function login(): void
    {
        try {
            $usuarios = $this->repository();
            $email = $_POST["email"] ?? "";
            $senha = $_POST["senha"] ?? "";

            if (empty($email) || empty($senha)) {
                Response::json(0, "Informe e-mail e senha.");
                return;
            }

            $usuario = $usuarios->buscarPorEmail($email);
            if (!$usuario || !password_verify($senha, $usuario["senha"])) {
                Response::json(0, "E-mail ou senha incorretos.");
                return;
            }

            unset($usuario["senha"]);
            Response::json(1, "Login realizado com sucesso!", $usuario);
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }

    public function perfil(): void
    {
        try {
            $usuarios = $this->repository();
            $id = $_GET["id"] ?? "";

            if (empty($id) || !is_numeric($id)) {
                Response::json(0, "Informe um id valido.");
                return;
            }

            $usuario = $usuarios->buscarPorId((int) $id);
            if (!$usuario) {
                Response::json(0, "Usuario nao encontrado.");
                return;
            }

            Response::json(1, "Usuario encontrado.", $usuario);
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }

    public function atualizar(): void
    {
        try {
            $usuarios = $this->repository();
            $id = $_POST["id"] ?? "";
            $nome = $_POST["nome"] ?? null;
            $avatar = $_POST["avatar"] ?? null;

            if (empty($id) || !is_numeric($id)) {
                Response::json(0, "Informe um id valido.");
                return;
            }

            if (empty($nome) && empty($avatar)) {
                Response::json(0, "Informe ao menos nome ou avatar para atualizar.");
                return;
            }

            $usuarios->atualizar((int) $id, $nome, $avatar);
            Response::json(1, "Perfil atualizado com sucesso!");
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }

    public function deletar(): void
    {
        try {
            $usuarios = $this->repository();
            $id = $_POST["id"] ?? "";

            if (empty($id) || !is_numeric($id)) {
                Response::json(0, "Informe um id valido.");
                return;
            }

            if (!$usuarios->buscarPorId((int) $id)) {
                Response::json(0, "Usuario nao encontrado.");
                return;
            }

            $usuarios->deletar((int) $id);
            Response::json(1, "Usuario removido com sucesso.");
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }
}
