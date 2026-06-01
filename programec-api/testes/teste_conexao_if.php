<?php

require_once __DIR__ . "/../config/Banco.php";

try {
    $inicio = microtime(true);
    $banco = new Banco();
    $pdo = $banco->conexao;

    $info = $pdo->query("
        SELECT
            current_database() AS banco,
            current_user AS usuario,
            inet_server_addr() AS servidor,
            inet_server_port() AS porta
    ")->fetch(PDO::FETCH_ASSOC);

    $tabelas = $pdo->query("
        SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = 'public'
        ORDER BY table_name
    ")->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        "NumMens" => 1,
        "Mensagem" => "Conexao com o banco do IF realizada com sucesso.",
        "tempo_ms" => round((microtime(true) - $inicio) * 1000, 2),
        "dados" => [
            "conexao" => $info,
            "tabelas_publicas" => $tabelas,
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "NumMens" => 0,
        "Mensagem" => "Erro ao conectar com o banco do IF.",
        "erro" => $e->getMessage(),
        "codigo" => $e->getCode(),
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
