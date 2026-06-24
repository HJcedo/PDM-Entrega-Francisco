<?php

// Carrega o Controller das tentativas.
require_once __DIR__ . "/../app/Controllers/TentativaController.php";

return [
    // Salva o resultado final do quiz: POST /tentativas.
    "POST /tentativas" => [TentativaController::class, "criar"],
];
