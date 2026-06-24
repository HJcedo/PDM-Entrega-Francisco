<?php

// Carrega o Controller que executa as acoes de usuario.
require_once __DIR__ . "/../app/Controllers/UsuarioController.php";

// Cada item liga uma URL a um Controller e a um metodo.
return [
    // Cria usuario: POST /usuarios.
    "POST /usuarios" => [UsuarioController::class, "criar"],

    // Busca perfil pelo id: GET /usuarios/4.
    "GET /usuarios/{id}" => [UsuarioController::class, "buscar"],

    // Atualiza avatar pelo id: PATCH /usuarios/4.
    "PATCH /usuarios/{id}" => [UsuarioController::class, "atualizar"],

    // Remove usuario pelo id: DELETE /usuarios/4.
    "DELETE /usuarios/{id}" => [UsuarioController::class, "deletar"],

    // Login cria uma sessao: POST /sessoes.
    "POST /sessoes" => [UsuarioController::class, "autenticar"],
];
