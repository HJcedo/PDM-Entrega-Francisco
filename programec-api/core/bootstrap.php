<?php

// Carrega as classes usadas em toda a API.
require_once __DIR__ . "/Response.php";
require_once __DIR__ . "/Request.php";
require_once __DIR__ . "/../config/Database.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
