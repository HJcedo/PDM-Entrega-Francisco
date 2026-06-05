<?php

require_once __DIR__ . "/../app/Controllers/UsuarioController.php";

// Rotas relacionadas ao usuario: cadastro, login, perfil e exclusao.
return [
    "POST /cadastro" => [UsuarioController::class, "cadastro"],
    "POST /login" => [UsuarioController::class, "login"],
    "GET /perfil" => [UsuarioController::class, "perfil"],
    "POST /atualizar-usuario" => [UsuarioController::class, "atualizar"],
    "POST /deletar-usuario" => [UsuarioController::class, "deletar"],
];
