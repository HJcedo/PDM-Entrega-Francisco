<?php

require_once __DIR__ . "/../helpers/Response.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

$rotas = array_merge(
    require __DIR__ . "/../routes/usuario_routes.php",
    require __DIR__ . "/../routes/materia_routes.php",
    require __DIR__ . "/../routes/exercicio_routes.php",
    require __DIR__ . "/../routes/tentativa_routes.php",
);

$metodo = $_SERVER["REQUEST_METHOD"];
$caminho = $_SERVER["PATH_INFO"] ?? "";

if ($caminho === "") {
    $caminho = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $script = $_SERVER["SCRIPT_NAME"];
    $base = rtrim(str_replace("\\", "/", dirname($script)), "/");

    $caminho = str_replace($script, "", $caminho);
    $caminho = str_replace($base, "", $caminho);
}

$caminho = "/" . trim($caminho, "/");

$chave = $metodo . " " . $caminho;

if (!isset($rotas[$chave])) {
    http_response_code(404);
    Response::json(0, "Rota nao encontrada.");
    exit;
}

[$classe, $acao] = $rotas[$chave];
$controller = new $classe();
$controller->$acao();
