<?php

// Teste da API REST local usando XAMPP/Apache.
$baseUrl = "http://localhost/programec-api/public";

function chamarApi(string $metodo, string $url, array $dados = []): array
{
    $curl = curl_init();
    $headers = ["Content-Type: application/json; charset=UTF-8"];

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => $metodo,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    if (!empty($dados)) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dados, JSON_UNESCAPED_UNICODE));
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

function validarResposta(array $resposta): array
{
    if (!empty($resposta["erro"])) {
        throw new Exception($resposta["erro"]);
    }

    if ($resposta["status"] < 200 || $resposta["status"] >= 300) {
        $mensagem = $resposta["json"]["Mensagem"] ?? "Erro HTTP";
        throw new Exception("HTTP " . $resposta["status"] . ": " . $mensagem);
    }

    if (!is_array($resposta["json"])) {
        throw new Exception("Resposta não é JSON válido: " . ($resposta["raw"] ?? ""));
    }

    return $resposta["json"];
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

echo "Testando API REST local: {$baseUrl}\n\n";

testar("Listar matérias", function () use ($baseUrl) {
    validarResposta(chamarApi("GET", "{$baseUrl}/materias"));
});

testar("Listar exercícios da matéria 1", function () use ($baseUrl) {
    validarResposta(chamarApi("GET", "{$baseUrl}/materias/1/exercicios"));
});

testar("Login do usuário de teste", function () use ($baseUrl) {
    validarResposta(chamarApi("POST", "{$baseUrl}/sessoes", [
        "email" => "joao@email.com",
        "senha" => "123456",
    ]));
});
