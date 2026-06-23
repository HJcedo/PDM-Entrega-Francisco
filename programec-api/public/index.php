<?php

require_once __DIR__ . "/../core/bootstrap.php";

// Responde à consulta prévia feita pelo navegador.
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

// Reúne todas as rotas da aplicação.
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
    // Separa "GET /usuarios/{id}" em método e caminho.
    [$metodoRota, $caminhoRota] = explode(" ", $rota, 2);

    if ($metodo !== $metodoRota) {
        continue;
    }

    $partesRota = explode("/", trim($caminhoRota, "/"));

    if (count($partesUrl) !== count($partesRota)) {
        continue;
    }

    $parametros = [];
    $rotaEncontrada = true;

    foreach ($partesRota as $indice => $parteRota) {
        // Uma parte entre chaves é um parâmetro, como {id}.
        if ($parteRota[0] === "{") {
            $parametros[] = $partesUrl[$indice];
            continue;
        }

        if ($parteRota !== $partesUrl[$indice]) {
            $rotaEncontrada = false;
            break;
        }
    }

    if ($rotaEncontrada) {
        $controller = new $classe();
        $controller->$acao(...$parametros);
        exit;
    }
}

Response::json(0, "Rota não encontrada.", null, 404);
