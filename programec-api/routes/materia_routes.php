<?php

require_once __DIR__ . "/../controllers/MateriaController.php";

// Cada item liga uma URL a um metodo do controller.
return [
    "GET /materias" => [MateriaController::class, "listar"],
];
