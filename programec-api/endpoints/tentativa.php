<?php

// Endpoint antigo mantido para o Flutter atual. Ele chama o controller modular.
require_once __DIR__ . "/../controllers/TentativaController.php";

$controller = new TentativaController();
$controller->criar();
