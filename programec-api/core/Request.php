<?php

// Ajuda os Controllers a lerem dados enviados pelo Flutter.
class Request
{
    // Static permite chamar Request::body() sem criar um objeto Request.
    public static function body(): array
    {
        // Le o corpo bruto da requisicao HTTP.
        // Exemplo: o JSON enviado no cadastro ou login.
        $json = file_get_contents("php://input");

        // Converte JSON em array PHP.
        // Se vier vazio ou invalido, devolve array vazio.
        return json_decode($json, true) ?? [];
    }
}
