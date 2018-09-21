<?php
// CONEXAO PADR�O
require_once("conexao.php"); 
date_default_timezone_set("Brazil/East"); // Brasil
$host = $host; // servidor
$database = $banco; // nome do banco
$login_db = $usuario; // usuario do banco 
$senha_db = $senha; // senha do usuario do banco

define('HOST', $host);	
define('BANCO',  $database);
define('LOGIN',  $login_db);
define('SENHA',  $senha_db);		

@$cn=mysql_connect($host, $login_db, $senha_db);
mysql_select_db($database);

class conexao
{

    /*
    CONEX�O CRUDS
    */

    private $db_host = HOST; // servidor
    private $db_user = LOGIN; // usuario do banco
    private $db_pass = SENHA; // senha do usuario do banco
    private $db_name = BANCO; // nome do banco

    private $con = false;

   
    public function connect() // Estabelece conexao
    {
        if(!$this->con)
        {
            $myconn = @mysql_connect($this->db_host,$this->db_user,$this->db_pass);
            if($myconn)
            {
                $seldb = @mysql_select_db($this->db_name,$myconn);
                if($seldb)
                {
                    $this->con = true;
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }

   
    public function disconnect() // Fecha conexao
    {
    if($this->con)
    {
        if(@mysql_close())
        {
                        $this->con = false;
            return true;
        }
        else
        {
            return false;
        }
    }
    }
      
}

?>
