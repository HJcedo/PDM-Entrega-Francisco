<?php

// Endpoint antigo mantido para o Flutter atual. Ele chama o controller modular.
require_once __DIR__ . "/../app/Controllers/ExercicioController.php";

$controller = new ExercicioController();
$controller->listarPorMateria();
