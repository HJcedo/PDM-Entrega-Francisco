<?php

class Response
{
    // Static permite chamar Response::json() sem criar um objeto Response.
    public static function json(
        int $numMens,
        string $mensagem,
        $dados = null,
        int $status = 200
    ): void {
        // Define o status HTTP. Se não for informado, usa 200.
        http_response_code($status);

        // Começa com zero porque $dados pode ser nulo ou uma lista vazia.
        $registros = 0;

        if (is_array($dados)) {
            // Distingue uma lista [0, 1] de um registro ["id" => 1].
            $isList = empty($dados)
                || array_keys($dados) === range(0, count($dados) - 1);

            // Uma lista conta seus itens; um objeto associativo conta como um.
            $registros = $isList ? count($dados) : 1;
        } elseif ($dados !== null) {
            // Qualquer outro valor não nulo representa um registro.
            $registros = 1;
        }

        // Monta o formato padronizado usado por toda a aplicação.
        echo json_encode([
            "NumMens" => $numMens,
            "Mensagem" => $mensagem,
            "registros" => $registros,
            "dados" => $dados,
        ], JSON_UNESCAPED_UNICODE); // Mantém acentos e emojis legíveis.
    }
}
