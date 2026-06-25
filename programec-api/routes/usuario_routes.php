<?php

// Carrega a classe que executará as ações destas rotas.
require_once __DIR__ . "/../app/Controllers/UsuarioController.php";

// A chave contém método e caminho; o valor indica Controller e ação.
return [
    // Cria um novo recurso usuário.
    "POST /usuarios" => [UsuarioController::class, "criar"],

    // {id} é um parâmetro dinâmico extraído pelo roteador.
    "GET /usuarios/{id}" => [UsuarioController::class, "buscar"],

    // PATCH altera apenas os campos enviados.
    "PATCH /usuarios/{id}" => [UsuarioController::class, "atualizar"],

    // DELETE remove o recurso identificado pelo id.
    "DELETE /usuarios/{id}" => [UsuarioController::class, "deletar"],

    // Criar uma sessão representa realizar o login.
    "POST /sessoes" => [UsuarioController::class, "autenticar"],
];
