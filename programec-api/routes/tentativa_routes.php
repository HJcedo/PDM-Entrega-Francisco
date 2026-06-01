<?php

require_once __DIR__ . "/../controllers/TentativaController.php";

return [
    "POST /tentativa" => [TentativaController::class, "criar"],
];
