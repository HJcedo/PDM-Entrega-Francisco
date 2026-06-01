<?php

class Response
{
    public static function json(int $numMens, string $mensagem, $dados = null): void
    {
        echo json_encode([
            "NumMens" => $numMens,
            "Mensagem" => $mensagem,
            "registros" => is_array($dados) ? count($dados) : ($dados === null ? 0 : 1),
            "dados" => $dados,
        ], JSON_UNESCAPED_UNICODE);
    }
}
