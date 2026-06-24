<?php

// Carrega o Controller das materias.
require_once __DIR__ . "/../app/Controllers/MateriaController.php";

return [
    // Lista todas as materias: GET /materias.
    "GET /materias" => [MateriaController::class, "listar"],
];
