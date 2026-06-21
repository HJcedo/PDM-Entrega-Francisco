<?php

// Teste da API local, usando XAMPP/Apache na sua maquina.
// Antes de rodar, a pasta programec-api precisa estar em C:\xampp\htdocs\programec-api.
// Rode no terminal com:
// C:\xampp\php\php.exe programec-api\testes\teste-fora-do-campus.php

$baseUrl = "http://localhost/programec-api/endpoints";

function chamarApi(string $metodo, string $url, array $dados = []): array
{
    $curl = curl_init();

    if ($metodo === "GET" && !empty($dados)) {
        $url .= "?" . http_build_query($dados);
    }

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
    ]);

    if ($metodo === "POST") {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($dados));
    }

    $resposta = curl_exec($curl);
    $erro = curl_error($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return [
        "status" => $status,
        "erro" => $erro,
        "json" => json_decode($resposta ?: "", true),
        "raw" => $resposta,
    ];
}

function testar(string $nome, callable $callback): void
{
    try {
        $callback();
        echo "[OK] {$nome}\n";
    } catch (Exception $e) {
        echo "[ERRO] {$nome}: {$e->getMessage()}\n";
    }
}

function validarResposta(array $resposta): array
{
    if (!empty($resposta["erro"])) {
        throw new Exception($resposta["erro"]);
    }

    if ($resposta["status"] < 200 || $resposta["status"] >= 300) {
        throw new Exception("HTTP " . $resposta["status"]);
    }

    if (!is_array($resposta["json"])) {
        throw new Exception("Resposta nao e JSON valido: " . ($resposta["raw"] ?? ""));
    }

    return $resposta["json"];
}

echo "Testando API local: {$baseUrl}\n\n";

testar("Listar materias", function () use ($baseUrl) {
    $json = validarResposta(chamarApi("GET", "{$baseUrl}/materias.php"));

    if (($json["NumMens"] ?? 0) != 1 || empty($json["dados"])) {
        throw new Exception($json["Mensagem"] ?? "Materias nao retornaram dados.");
    }
});

testar("Listar exercicios da materia 1", function () use ($baseUrl) {
    $json = validarResposta(chamarApi("GET", "{$baseUrl}/exercicios.php", [
        "materia_id" => 1,
    ]));

    if (($json["NumMens"] ?? 0) != 1 || empty($json["dados"])) {
        throw new Exception($json["Mensagem"] ?? "Exercicios nao retornaram dados.");
    }
});

testar("Login do usuario de teste", function () use ($baseUrl) {
    $json = validarResposta(chamarApi("POST", "{$baseUrl}/login.php", [
        "email" => "joao@email.com",
        "senha" => "123456",
    ]));

    if (($json["NumMens"] ?? 0) != 1) {
        throw new Exception($json["Mensagem"] ?? "Login falhou.");
    }
});
