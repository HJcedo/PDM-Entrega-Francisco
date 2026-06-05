<?php

require_once __DIR__ . "/../controllers/TentativaController.php";

// Salva a nota final do quiz respondido pelo usuario.
return [
    "POST /tentativa" => [TentativaController::class, "criar"],
];
