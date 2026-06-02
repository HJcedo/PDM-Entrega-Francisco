<?php
// ============================================================
// Banco.php — Classe de conexão com o PostgreSQL
// Baseada no exemplo do professor (exemploPDM-I/Model/Banco.php)
// Adaptações: banco franciscozanela, headers CORS, sem echo direto
// ============================================================

class Banco
{
    private $Driver;
    private $Host;
    private $Porta;
    private $User;
    private $Password;
    private $Database;
    public  $conexao;      // conexão PDO, usada pelos models

    private $NumMensagem;
    private $Mensagem;
    private $NumRegistros;
    private $Dados;

    // ----------------------------------------------------------
    // Construtor: chama Abre_Banco com os valores recebidos.
    // Passe NULL em qualquer parâmetro para usar o valor padrão.
    // ----------------------------------------------------------
    function __construct($p_Driver = null, $p_Host = null, $p_Porta = null,
                         $p_User  = null, $p_Password = null, $p_Database = null)
    {
        $this->Abre_Banco($p_Driver, $p_Host, $p_Porta,
                          $p_User,  $p_Password, $p_Database);
    }

    // ----------------------------------------------------------
    // Abre_Banco: define as credenciais e cria a conexão PDO.
    // ----------------------------------------------------------
    function Abre_Banco($p_Driver, $p_Host, $p_Porta,
                        $p_User,  $p_Password, $p_Database)
    {
        $this->User     = is_null($p_User)     ? "franciscozanela" : $p_User;
        $this->Password = is_null($p_Password) ? "123456"           : $p_Password;
        $this->Database = is_null($p_Database) ? "franciscozanela" : $p_Database;
        $this->Driver   = is_null($p_Driver)   ? "pgsql"            : $p_Driver;
        $this->Porta    = is_null($p_Porta)    ? "5432"             : $p_Porta;
        $this->Host     = is_null($p_Host)     ? "192.168.20.18"    : $p_Host;

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

    // ----------------------------------------------------------
    // criaConexao: monta a string DSN do PDO e abre a conexão.
    // PDO (PHP Data Objects) é a forma segura de conectar ao banco.
    // ----------------------------------------------------------
    private function criaConexao()
    {
        $dsn = "{$this->Driver}:host={$this->Host};port={$this->Porta};dbname={$this->Database}";
        try
        {
            $this->conexao = new PDO($dsn, $this->User, $this->Password);
            // Faz o PDO lançar exceções em caso de erro (mais fácil de depurar)
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            throw new Exception("Erro ao conectar ao banco: " . $e->getMessage());
        }
    }

    // ----------------------------------------------------------
    // Métodos para gravar o resultado de uma operação
    // ----------------------------------------------------------
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

    // ----------------------------------------------------------
    // getRetorno: monta e retorna o JSON padrão da API.
    //
    // Estrutura de resposta:
    // {
    //   "NumMens"  : 1,           → 1 = sucesso, 0 = erro
    //   "Mensagem" : "...",       → texto descritivo
    //   "registros": 0,           → quantidade de registros retornados
    //   "dados"    : null         → array de dados ou null
    // }
    // ----------------------------------------------------------
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

// ----------------------------------------------------------
// Headers enviados em TODA resposta da API.
// Content-Type: informa que o retorno é JSON.
// Access-Control-Allow-Origin: permite que o app Flutter
//   (rodando em qualquer host) consiga chamar esta API.
// ----------------------------------------------------------
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
