<?php

// Carrega o Controller responsável pelas tentativas.
require_once __DIR__ . "/../app/Controllers/TentativaController.php";

// POST cria um novo registro de tentativa.
return [
    "POST /tentativas" => [TentativaController::class, "criar"],
];
