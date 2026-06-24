<?php

// Carrega as configuracoes iniciais da API.
// O bootstrap importa classes importantes e define cabecalhos de resposta.
require_once __DIR__ . "/../core/bootstrap.php";

// O navegador pode enviar uma requisicao OPTIONS antes de POST, PATCH ou DELETE.
// Essa consulta serve para verificar se a API aceita a chamada real.
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204); // 204 significa: aceito, mas sem conteudo para devolver.
    exit;
}

// Junta todas as rotas da aplicacao em um unico array.
// Cada arquivo de routes cuida de uma parte da API.
$rotas = array_merge(
    require __DIR__ . "/../routes/usuario_routes.php",
    require __DIR__ . "/../routes/materia_routes.php",
    require __DIR__ . "/../routes/exercicio_routes.php",
    require __DIR__ . "/../routes/tentativa_routes.php",
);

// Metodo HTTP usado na chamada: GET, POST, PATCH ou DELETE.
$metodo = $_SERVER["REQUEST_METHOD"];

// Caminho chamado depois da pasta public.
// Exemplo: /materias ou /usuarios/4.
$caminho = $_SERVER["PATH_INFO"] ?? "/";

// Quebra o caminho em partes para facilitar rotas com id.
// Exemplo: /usuarios/4 vira ["usuarios", "4"].
$partesUrl = explode("/", trim($caminho, "/"));

// Percorre todas as rotas cadastradas ate encontrar uma compativel.
foreach ($rotas as $rota => [$classe, $acao]) {
    // A chave da rota vem no formato "METODO /caminho".
    // Exemplo: "GET /usuarios/{id}".
    [$metodoRota, $caminhoRota] = explode(" ", $rota, 2);

    // Se o metodo da chamada nao for igual ao metodo da rota,
    // essa rota nao serve e o codigo passa para a proxima.
    if ($metodo !== $metodoRota) {
        continue;
    }

    // Guarda valores que serao enviados ao controller, como o id do usuario.
    $parametros = [];

    // Comeca como falso. So vira true quando a URL combinar com uma rota.
    $rotaEncontrada = false;

    // Caso mais simples: a URL chamada e exatamente igual a rota cadastrada.
    // Exemplo: GET /materias ou POST /usuarios.
    if ($caminho === $caminhoRota) {
        $rotaEncontrada = true;
    }

    // Caso de rotas de usuario com id.
    // A rota cadastrada e /usuarios/{id}, mas a URL real chega como /usuarios/4.
    if (
        !$rotaEncontrada &&
        $caminhoRota === "/usuarios/{id}" &&
        count($partesUrl) === 2 &&
        $partesUrl[0] === "usuarios"
    ) {
        // Guarda o id encontrado na URL para passar ao controller.
        $parametros[] = $partesUrl[1];
        $rotaEncontrada = true;
    }

    // Caso da rota de exercicios de uma materia.
    // A rota cadastrada e /materias/{materiaId}/exercicios,
    // mas a URL real chega como /materias/1/exercicios.
    if (
        !$rotaEncontrada &&
        $caminhoRota === "/materias/{materiaId}/exercicios" &&
        count($partesUrl) === 3 &&
        $partesUrl[0] === "materias" &&
        $partesUrl[2] === "exercicios"
    ) {
        // Guarda o id da materia para o controller buscar os exercicios dela.
        $parametros[] = $partesUrl[1];
        $rotaEncontrada = true;
    }

    // Quando encontra uma rota valida, chama o controller indicado no arquivo routes.
    if ($rotaEncontrada) {
        // Exemplo: cria um MateriaController ou UsuarioController.
        $controller = new $classe();

        // Chama a acao definida na rota.
        // O ... espalha os parametros: buscar(...[4]) vira buscar(4).
        $controller->$acao(...$parametros);
        exit;
    }
}

// Se chegou ate aqui, nenhuma rota cadastrada combinou com a URL chamada.
Response::json(0, "Rota nao encontrada.", null, 404);
