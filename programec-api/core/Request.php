<?php

class Request
{
    // Lê o JSON enviado pelo Flutter e devolve um array PHP.
    public static function body(): array
    {
        $json = file_get_contents("php://input");
        return json_decode($json, true) ?? [];
    }
}
