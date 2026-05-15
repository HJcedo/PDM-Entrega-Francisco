<?php
// ============================================================
// Banco.php — Classe de conexão com o PostgreSQL
// Baseada no exemplo do professor (exemploPDM-I/Model/Banco.php)
// ============================================================

class Banco
{
    private $Driver;
    private $Host;
    private $Porta;
    private $User;
    private $Password;
    private $Database;
    public  $conexao;

    private $NumMensagem;
    private $Mensagem;
    private $NumRegistros;
    private $Dados;

    function __construct($p_Driver = null, $p_Host = null, $p_Porta = null,
                         $p_User  = null, $p_Password = null, $p_Database = null)
    {
        $this->Abre_Banco($p_Driver, $p_Host, $p_Porta,
                          $p_User,  $p_Password, $p_Database);
    }

    function Abre_Banco($p_Driver, $p_Host, $p_Porta,
                        $p_User,  $p_Password, $p_Database)
    {
        // Substitua os valores abaixo pelas credenciais do seu banco
        $this->User     = is_null($p_User)     ? "SEU_USUARIO"     : $p_User;
        $this->Password = is_null($p_Password) ? "SUA_SENHA"       : $p_Password;
        $this->Database = is_null($p_Database) ? "postgres"        : $p_Database;
        $this->Driver   = is_null($p_Driver)   ? "pgsql"           : $p_Driver;
        $this->Porta    = is_null($p_Porta)    ? "5432"            : $p_Porta;
        $this->Host     = is_null($p_Host)     ? "SEU_HOST"        : $p_Host;

        $this->conexao = null;
        try
        {
            $this->criaConexao();
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    private function criaConexao()
    {
        $dsn = "{$this->Driver}:host={$this->Host};port={$this->Porta};dbname={$this->Database}";
        try
        {
            $this->conexao = new PDO($dsn, $this->User, $this->Password);
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            throw new Exception("Erro ao conectar ao banco: " . $e->getMessage());
        }
    }

    public function setMensagem($p_NumMensagem, $p_Mensagem)
    {
        $this->NumMensagem  = $p_NumMensagem;
        $this->Mensagem     = $p_Mensagem;
    }

    public function setDados($p_Dados)
    {
        $this->Dados        = $p_Dados;
        $this->NumRegistros = is_array($p_Dados) ? count($p_Dados) : 1;
    }

    public function getRetorno()
    {
        return json_encode([
            "NumMens"   => $this->NumMensagem,
            "Mensagem"  => $this->Mensagem,
            "registros" => $this->NumRegistros ?? 0,
            "dados"     => $this->Dados ?? null
        ], JSON_UNESCAPED_UNICODE);
    }
}

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
