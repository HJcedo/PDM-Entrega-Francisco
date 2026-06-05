<?php

// Carrega o helper responsavel por devolver JSON no mesmo formato em toda API.
require_once __DIR__ . "/../helpers/Response.php";

// Headers basicos da API: resposta em JSON e liberacao para chamadas do Flutter.
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// O navegador pode enviar OPTIONS antes do POST. Aqui respondemos e encerramos.
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

// Junta todos os arquivos de rotas em um unico array.
$rotas = array_merge(
    require __DIR__ . "/../routes/usuario_routes.php",
    require __DIR__ . "/../routes/materia_routes.php",
    require __DIR__ . "/../routes/exercicio_routes.php",
    require __DIR__ . "/../routes/tentativa_routes.php",
);

// Guarda o metodo usado na chamada: GET, POST etc.
$metodo = $_SERVER["REQUEST_METHOD"];

// PATH_INFO pega o caminho depois de index.php, por exemplo: /materias.
$caminho = $_SERVER["PATH_INFO"] ?? "";

// Caso o servidor nao preencha PATH_INFO, tenta descobrir o caminho pela URL.
if ($caminho === "") {
    $caminho = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $script = $_SERVER["SCRIPT_NAME"];
    $base = rtrim(str_replace("\\", "/", dirname($script)), "/");

    $caminho = str_replace($script, "", $caminho);
    $caminho = str_replace($base, "", $caminho);
}

// Normaliza o caminho para sempre ficar no formato /rota.
$caminho = "/" . trim($caminho, "/");

// A chave junta metodo e caminho. Exemplo: GET /materias.
$chave = $metodo . " " . $caminho;

// Se a chave nao existir no array de rotas, retorna erro 404 em JSON.
if (!isset($rotas[$chave])) {
    http_response_code(404);                                                                                                                                                                                                                                                                                                                                                                                                       
    Response::json(0, "Rota nao encontrada.");
    exit;
}

// Cada rota informa qual controller e qual metodo devem ser chamados.
[$classe, $acao] = $rotas[$chave];

$chave = GET /usuarios

rota = "/../routes/usuario_routes.php" 



// Cria o controller e executa a acao da rota.
$controller = new $classe();
$controller->$acao();
