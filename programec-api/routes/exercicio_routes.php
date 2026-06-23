<?php

// Carrega o Controller responsável pelos exercícios.
require_once __DIR__ . "/../app/Controllers/ExercicioController.php";

// Exercícios aparecem como um recurso pertencente a uma matéria.
return [
    "GET /materias/{materiaId}/exercicios" => [
        ExercicioController::class,
        "listarPorMateria",
    ],
];
