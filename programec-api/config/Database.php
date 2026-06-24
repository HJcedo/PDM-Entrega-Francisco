<?php

// Usa a classe Banco, que abre a conexao com o PostgreSQL do IFSUL.
require_once __DIR__ . "/Banco.php";

// Adaptador simples entre o projeto e a classe Banco.
class Database
{
    // Guarda o objeto Banco criado pelo arquivo do professor.
    private Banco $banco;

    public function __construct()
    {
        // Ao criar Database, ja abre a conexao pelo Banco.
        $this->banco = new Banco();
    }

    public function getConnection(): PDO
    {
        // Entrega a conexao PDO para os Repositories.
        return $this->banco->conexao;
    }
}
