<?php

require_once __DIR__ . "/../core/bootstrap.php";

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
$caminho = $_SERVER["PATH_INFO"] ?? "/";
$partesUrl = explode("/", trim($caminho, "/"));

foreach ($rotas as $rota => [$classe, $acao]) {
    [$metodoRota, $caminhoRota] = explode(" ", $rota, 2);

    if ($metodo !== $metodoRota) {
        continue;
    }

    $parametros = [];
    $rotaEncontrada = false;

    if ($caminho === $caminhoRota) {
        $rotaEncontrada = true;
    }

    if (
        !$rotaEncontrada &&
        $caminhoRota === "/usuarios/{id}" &&
        count($partesUrl) === 2 &&
        $partesUrl[0] === "usuarios"
    ) {
        $parametros[] = $partesUrl[1];
        $rotaEncontrada = true;
    }

    if (
        !$rotaEncontrada &&
        $caminhoRota === "/materias/{materiaId}/exercicios" &&
        count($partesUrl) === 3 &&
        $partesUrl[0] === "materias" &&
        $partesUrl[2] === "exercicios"
    ) {
        $parametros[] = $partesUrl[1];
        $rotaEncontrada = true;
    }

    if ($rotaEncontrada) {
        $controller = new $classe();
        $controller->$acao(...$parametros);
        exit;
    }
}

Response::json(0, "Rota nao encontrada.", null, 404);
