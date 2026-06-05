<?php

// Endpoint antigo mantido para o Flutter atual. Ele chama o controller modular.
require_once __DIR__ . "/../controllers/ExercicioController.php";

$controller = new ExercicioController();
$controller->listarPorMateria();
