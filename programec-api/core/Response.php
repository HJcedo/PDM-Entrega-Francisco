<?php

// Padroniza todas as respostas JSON da API.
class Response
{
    // Static permite chamar Response::json() sem criar um objeto Response.
    public static function json(
        int $numMens,
        string $mensagem,
        $dados = null,
        int $status = 200
    ): void {
        // Define o status HTTP. Se nao for informado, usa 200 OK.
        http_response_code($status);

        // Conta quantos registros voltaram em "dados".
        $registros = 0;

        if (is_array($dados)) {
            // Verifica se $dados e uma lista ou um unico registro associativo.
            $isList = empty($dados)
                || array_keys($dados) === range(0, count($dados) - 1);

            // Lista conta itens; registro unico conta como 1.
            $registros = $isList ? count($dados) : 1;
        } elseif ($dados !== null) {
            // Qualquer valor nao nulo conta como 1 registro.
            $registros = 1;
        }

        // Envia o JSON final para o Flutter.
        echo json_encode([
            "NumMens" => $numMens,
            "Mensagem" => $mensagem,
            "registros" => $registros,
            "dados" => $dados,
        ], JSON_UNESCAPED_UNICODE); // Mantem acentos e emojis no JSON.
    }
}
