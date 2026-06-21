<?php

require_once __DIR__ . "/Banco.php";

// Camada simples para entregar a conexao PDO para os repositories.
class Database
{
    private Banco $banco;

    public function __construct()
    {
        // Banco.php contem os dados de conexao e abre o PDO.
        $this->banco = new Banco();
    }

    public function getConnection(): PDO
    {
        // Retorna a conexao aberta para quem precisa executar SQL.
        return $this->banco->conexao;
    }
}
