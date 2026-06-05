<?php

class Response
{
    // Padroniza todas as respostas da API em JSON.
    public static function json(int $numMens, string $mensagem, $dados = null): void
    {
        echo json_encode([
            "NumMens" => $numMens,
            "Mensagem" => $mensagem,
            // Se dados for uma lista, conta itens. Se for um objeto, conta 1.
            "registros" => is_array($dados) ? count($dados) : ($dados === null ? 0 : 1),
            "dados" => $dados,
        ], JSON_UNESCAPED_UNICODE);
    }
}
