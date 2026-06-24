<?php

// Arquivos usados em praticamente toda a API.
require_once __DIR__ . "/Response.php";
require_once __DIR__ . "/Request.php";
require_once __DIR__ . "/../config/Database.php";

// Toda resposta da API sera JSON em UTF-8.
header("Content-Type: application/json; charset=UTF-8");

// Libera chamadas vindas do Flutter ou navegador.
header("Access-Control-Allow-Origin: *");

// Metodos HTTP aceitos pela API REST.
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");

// Permite que o Flutter envie JSON no corpo da requisicao.
header("Access-Control-Allow-Headers: Content-Type");
