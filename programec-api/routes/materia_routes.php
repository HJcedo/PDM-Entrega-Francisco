<?php

// Carrega o Controller responsável pelas matérias.
require_once __DIR__ . "/../app/Controllers/MateriaController.php";

// Liga a listagem do recurso matérias ao método listar().
return [
    "GET /materias" => [MateriaController::class, "listar"],
];
