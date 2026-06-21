<?php

require_once __DIR__ . "/../app/Controllers/ExercicioController.php";

// Lista os exercicios de uma materia recebendo materia_id por GET.
return [
    "GET /exercicios" => [ExercicioController::class, "listarPorMateria"],
];
