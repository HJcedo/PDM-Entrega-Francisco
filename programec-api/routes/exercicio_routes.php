<?php

require_once __DIR__ . "/../controllers/ExercicioController.php";

return [
    "GET /exercicios" => [ExercicioController::class, "listarPorMateria"],
];
