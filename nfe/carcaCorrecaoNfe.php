<?php
set_time_limit(0);
 ini_set('default_charset','UTF-8');
date_default_timezone_set("Brazil/East"); // Brasil

require_once 'conexao.php';


$usuario_id = $_GET['usuario'];

$numNFe = $_GET['numNFe'];
$chave = $_GET['chave'];
$protocolo = $_GET['protocolo'];
$cliente = str_replace('%', '', $_GET['cliente']);
$valor_id = $_GET['valor'];
$nota = str_replace('%', '', $_GET['nota']);

$query_user = mysql_query("SELECT * FROM sma_users WHERE id = '$usuario_id' ") or die(mysql_error());
$user = mysql_fetch_array($query_user);
$usuario_nome =  $user["username"];
$empresa_id =  $user["empresa_id"];

$query_empresa = mysql_query("SELECT * FROM sma_empresa WHERE id = '$empresa_id' ") or die(mysql_error());
$empresa = mysql_fetch_array($query_empresa);


$empresa_id =  $empresa["id"];
$empresa_nome =  $empresa["razaoSocial"];
$empresa_cnpj =  $empresa["cnpj"];
$empresa_ie   =  $empresa["inscricaoEstadual"];
$empresa_endereco =  $empresa["endereco"];
$empresa_complemento =  $empresa["complementoEndereco"];
$empresa_numero =  $empresa["numero"];
$empresa_bairro =  $empresa["bairro"];
$empresa_cidade =  $empresa["cidade"];
$empresa_uf =  $empresa["uf"];
$empresa_cep =  $empresa["cep"];
$empresa_telefone =  $empresa["telefone"];
$empresa_ibge =  $empresa["cidadeIBGE"];
$nfeAtual =  $empresa["numeroNfeAtual"];
$ambienteNfe =  $empresa["ambiente"];
$ufIbge =  $empresa["ufIbge"];
$loginAfil =  $empresa["login_agil"];
$senhgaAgil =  $empresa["senha_agil"];



$urlIntegracao      = 'http://www.agilcontabil.net/sistemaInstalado/ajax';
$dados['usuario']   = $loginAfil;//'iaraujo.israel@gmail.com';
$dados['senha']     = $senhgaAgil;//'iaraujo.2016';
$dados['acao']      = 'cancelarNfeA3';
  //enviar cÃ³digo de seguranÃ§a do contribuinte quando for NFC-e
  //$dados['idCsc'] = '000001';
  //$dados['csc'] = 'FFABDA2E-1A3E-48B7-A964-A9D6782AD664';
$dados["ambiente"]  = $ambienteNfe; //1 = produÃ§Ã£o, 2 = homologaÃ§Ã£o 
$dados["modelo"]    = '55'; //55 = Nfe, 65 = Nfce
$dados["uf"]        = 'AM'; //Sigla do estado do seu cliente (emitente da Nfe)
$dados["chave"]     = $chave; //chave da Nfe a ser cancelada
$dados["numeroCorrecao"] = '1'; //número da correção enviada, 1 para a primeira, 2 para a segunda...
$dados["textoCorrecao"]  = 'Texto da carta de correção';



   


  //Inicia comunicação com servidor agilcontabil.net
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $urlIntegracao);
  curl_setopt($ch,CURLOPT_POST, 1);
  curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($dados));
  //recebe a resposta
  $resposta = curl_exec($ch);
  //finaliza comunicação
  curl_close($ch);


$arrayResposta = json_decode($resposta,true); //transforma resposta json em array do PHP

//

 if(!empty($arrayResposta["cStat"])){
        if($arrayResposta["cStat"]=='100'){//sucesso
           
            //mostra a resposta do cancelamento da nota
            print_r($arrayResposta);

            //echo "UPDATE sma_sales SET xml = '".$arrayResposta["xml"]."' WHERE id = '".$_REQUEST["idVenda"]."' ";
            mysql_query("UPDATE sma_nfe SET status = 'CANCELADO' WHERE num_nfe = '".$numNFe."' ");
            
            $data = date('Y-m-d');
            mysql_query("INSERT INTO sma_nfe_canceladas (data,usuario,numNFe,chave,protocolo,cliente,valor,motivo) values "
                    . " ('".$data."', '".$usuario_id."', '".$numNFe."', '".$chave."', '".$protocolo."','".$cliente."','".$valor_id."','".$nota."' )");
            
           
        }else{//rejeição
            //mostrar mensagem de rejeição da sefaz
           
            print_r($arrayResposta);
        }
   
        }else{
        print_r($arrayResposta);
        }
   



?>