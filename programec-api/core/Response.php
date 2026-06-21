<?php

class Response
{
    // Padroniza todas as respostas da API em JSON.
    public static function json(int $numMens, string $mensagem, $dados = null, int $status = 200): void
    {
        http_response_code($status);

        $registros = 0;
        if (is_array($dados)) {
            $isList = empty($dados) || array_keys($dados) === range(0, count($dados) - 1);
            $registros = $isList ? count($dados) : 1;
        } elseif ($dados !== null) {
            $registros = 1;
        }

        echo json_encode([
            "NumMens" => $numMens,
            "Mensagem" => $mensagem,
            // Lista conta varios registros; objeto associativo conta como 1.
            "registros" => $registros,
            "dados" => $dados,
        ], JSON_UNESCAPED_UNICODE);
    }
}
