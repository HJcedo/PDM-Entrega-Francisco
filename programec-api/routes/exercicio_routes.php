<?php

// Carrega o Controller dos exercicios.
require_once __DIR__ . "/../app/Controllers/ExercicioController.php";

return [
    // Lista exercicios de uma materia: GET /materias/1/exercicios.
    "GET /materias/{materiaId}/exercicios" => [
        ExercicioController::class,
        "listarPorMateria",
    ],
];
