<?php

// Endpoint antigo mantido para o Flutter atual. Ele chama o controller modular.
require_once __DIR__ . "/../controllers/UsuarioController.php";

$controller = new UsuarioController();
$controller->login();
