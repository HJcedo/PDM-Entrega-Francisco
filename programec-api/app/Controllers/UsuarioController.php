<?php

require_once __DIR__ . "/../../core/bootstrap.php";
require_once __DIR__ . "/../Repositories/UsuarioRepository.php";

class UsuarioController
{
    // Cria o repository com uma conexao ao banco.
    private function repository(): UsuarioRepository
    {
        $database = new Database();
        return new UsuarioRepository($database->getConnection());
    }

    // Atende a rota POST /cadastro.
    public function cadastro(): void
    {
        try {
            $usuarios = $this->repository();

            // Le os campos enviados pelo formulario/app.
            $nome = $_POST["nome"] ?? "";
            $email = $_POST["email"] ?? "";
            $senha = $_POST["senha"] ?? "";

            if (empty($nome) || empty($email) || empty($senha)) {
                Response::json(0, "Preencha todos os campos: nome, email e senha.");
                return;
            }

            // Nao permite dois usuarios com o mesmo e-mail.
            if ($usuarios->emailExiste($email)) {
                Response::json(0, "Este e-mail ja esta cadastrado.");
                return;
            }

            // Salva a senha usando hash, nunca em texto puro.
            $usuarios->criar($nome, $email, password_hash($senha, PASSWORD_BCRYPT));
            Response::json(1, "Cadastro realizado com sucesso!");
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }

    // Atende a rota POST /login.
    public function login(): void
    {
        try {
            $usuarios = $this->repository();

            // Le e-mail e senha enviados pelo app.
            $email = $_POST["email"] ?? "";
            $senha = $_POST["senha"] ?? "";

            if (empty($email) || empty($senha)) {
                Response::json(0, "Informe e-mail e senha.");
                return;
            }

            // Busca o usuario e compara a senha digitada com o hash salvo.
            $usuario = $usuarios->buscarPorEmail($email);
            if (!$usuario || !password_verify($senha, $usuario["senha"])) {
                Response::json(0, "E-mail ou senha incorretos.");
                return;
            }

            // Remove a senha do retorno antes de enviar ao app.
            unset($usuario["senha"]);
            Response::json(1, "Login realizado com sucesso!", $usuario);
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }

    // Atende a rota GET /perfil?id=X.
    public function perfil(): void
    {
        try {
            $usuarios = $this->repository();

            // Le o id enviado na URL.
            $id = $_GET["id"] ?? "";

            if (empty($id) || !is_numeric($id)) {
                Response::json(0, "Informe um id valido.");
                return;
            }

            // Busca os dados publicos do usuario.
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

    // Atende a rota POST /atualizar-usuario.
    public function atualizar(): void
    {
        try {
            $usuarios = $this->repository();

            // Nome e avatar sao opcionais, mas pelo menos um deve vir preenchido.
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

            // Atualiza somente os campos enviados.
            $usuarios->atualizar((int) $id, $nome, $avatar);
            Response::json(1, "Perfil atualizado com sucesso!");
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }

    // Atende a rota POST /deletar-usuario.
    public function deletar(): void
    {
        try {
            $usuarios = $this->repository();

            // Le o id enviado pelo app.
            $id = $_POST["id"] ?? "";

            if (empty($id) || !is_numeric($id)) {
                Response::json(0, "Informe um id valido.");
                return;
            }

            // Confere se o usuario existe antes de tentar excluir.
            if (!$usuarios->buscarPorId((int) $id)) {
                Response::json(0, "Usuario nao encontrado.");
                return;
            }

            // Remove o usuario e suas tentativas.
            $usuarios->deletar((int) $id);
            Response::json(1, "Usuario removido com sucesso.");
        } catch (Exception $e) {
            Response::json(0, "Erro interno: " . $e->getMessage());
        }
    }
}
