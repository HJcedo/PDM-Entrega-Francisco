<?php

require_once __DIR__ . "/Banco.php";

// Entrega aos Repositories a conexão criada pela classe Banco.
class Database
{
    private Banco $banco;

    public function __construct()
    {
        $this->banco = new Banco();
    }

    public function getConnection(): PDO
    {
        return $this->banco->conexao;
    }
}
