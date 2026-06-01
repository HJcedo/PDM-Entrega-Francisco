<?php

require_once __DIR__ . "/../controllers/MateriaController.php";

return [
    "GET /materias" => [MateriaController::class, "listar"],
];
