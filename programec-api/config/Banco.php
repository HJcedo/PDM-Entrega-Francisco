<?php

// Classe responsavel apenas por abrir a conexao com o PostgreSQL.
class Banco
{
    private $Driver;
    private $Host;
    private $Porta;
    private $User;
    private $Password;
    private $Database;

    // Conexao PDO usada pelos repositories.
    public $conexao;

    public function __construct(
        $p_Driver = null,
        $p_Host = null,
        $p_Porta = null,
        $p_User = null,
        $p_Password = null,
        $p_Database = null
    ) {
        $this->Abre_Banco($p_Driver, $p_Host, $p_Porta, $p_User, $p_Password, $p_Database);
    }

    // Define as credenciais e abre a conexao.
    public function Abre_Banco($p_Driver, $p_Host, $p_Porta, $p_User, $p_Password, $p_Database)
    {
        $this->User     = is_null($p_User)     ? "postgres.mqnvhhojnjersfxeqfyu"         : $p_User;
        $this->Password = is_null($p_Password) ? "!?Chuleta123"                          : $p_Password;
        $this->Database = is_null($p_Database) ? "postgres"                              : $p_Database;
        $this->Driver   = is_null($p_Driver)   ? "pgsql"                                 : $p_Driver;
        $this->Porta    = is_null($p_Porta)    ? "5432"                                  : $p_Porta;
        $this->Host     = is_null($p_Host)     ? "aws-1-sa-east-1.pooler.supabase.com"   : $p_Host;

        $this->criaConexao();
    }

    private function criaConexao()
    {
        $dsn = "{$this->Driver}:host={$this->Host};port={$this->Porta};dbname={$this->Database}";

        try {
            $this->conexao = new PDO($dsn, $this->User, $this->Password);
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao conectar ao banco: " . $e->getMessage());
        }
    }
}
