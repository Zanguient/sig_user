<?php
date_default_timezone_set('America/Manaus'); //configura horário local



$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'pdv';

@$bd = mysql_connect($host,$usuario,$senha);
mysql_select_db($banco) or die(mysql_error());



  /*      
define('EMPRESA', utf8_decode("MAELY INDUSTRIA  E COMERCIO DE CONFECÇÕES LTDA"));	
define('CNPJ',  "04.141.849/0001-36");
define('INSCRICAOESTADUAL',  "04.151.080-1");
define('EMPRESA_ENDERECO', utf8_decode("RUA MARIA TEREZA"));	
define('EMPRESA_NUMERO', utf8_decode("264"));	
define('EMPRESA_BAIRRO', utf8_decode("JAPIIM"));	
define('EMPRESA_COMPLEMENTO', utf8_decode("PRÓX. FORRÓ DOS 3"));	
define('EMPRESA_CIDADE',  utf8_decode("MANAUS"));
define('EMPRESA_UF',  "AM");
define('EMPRESA_CEP',  "64540-000");
define('EMPRESA_TEL',  "92 9 9430-2106");
define('EMPRESA_IBGE',  "1302603");
define('EMPRESA_CERTIFICADO',  "cert_maely.pfx");
*/
//define('API_BLING',  "2061af2d2fbfb2dd29fdbd122c680aa8830fb4e8");
	
	
	
?>
