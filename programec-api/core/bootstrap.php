<?php

// Arquivo global da API: carrega dependencias comuns e configura headers.
require_once __DIR__ . "/Response.php";
require_once __DIR__ . "/../config/Database.php";

// Headers basicos usados por todas as respostas da API.
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
