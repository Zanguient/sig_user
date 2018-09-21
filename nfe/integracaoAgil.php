<?php
require_once 'conexao.php';

//$getnfe = base64_decode($_GET['nfe']);

$num = $_GET['num'];
$idEmpresa = $_GET['idEmpresa'];
$usuario_id = $_GET['usuario'];
$pedido = $_GET['pedido'];

$query_empresa = mysql_query("SELECT * FROM sma_empresa WHERE id = '$idEmpresa' ") or die(mysql_error());
$empresa = mysql_fetch_array($query_empresa);
$loginAfil =  $empresa["login_agil"];
$senhgaAgil =  $empresa["senha_agil"];

$empresa_id =  $empresa["id"];
/*
 * $num = base64_decode($_GET['num']);
$idEmpresa = base64_decode($_GET['idEmpresa']);
 */
$getnfe = $_GET['nfe'];
$arq = "xml/$getnfe";

$chave = $_GET['chave'];

//echo 'Numero'. $num;
//echo '<br> Chave :'.$chave;
//echo '<br> Empresa : '.$idEmpresa;
//echo '<br> Pedido : '.$pedido;
//echo '<br> Usuario : '.$usuario_id;
$datanfe = date('Y-m-d');
$horanfe = date('H:i:s');

if (is_file($arq)) {
    $urlIntegracao    = 'http://www.agilcontabil.net/sistemaInstalado/ajax';
    $dados['usuario'] = $loginAfil;
    $dados['senha']   = $senhgaAgil;
    $dados['acao']    = 'emitirNfeA3';
    $dados['xml']     = base64_encode(file_get_contents($arq)); //o xml deve ser enviado em formato base64

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
  
    //mostra a resposta da emissão da nota
    //o xml da nota fiscal emitida está dentro da variavel $resposta["xml"] e deve ser gravado em sua base de dados
    //o pdf da nota fiscal emitida está dentro da variavel $resposta["pdf"] em formato texto hexadecimal
    //print_r($arrayResposta);
    if(!empty($arrayResposta["cStat"])){
        if($arrayResposta["cStat"]=='100'){//sucesso
            $protocolo = $arrayResposta["protocolo"];
            $recibo = $arrayResposta["recibo"];
           $chave_resposta = $arrayResposta["chave"];
           
//sucesso de emissão
            //$xml = base64_decode($arrayResposta["xml"]);
            $pdf = hex2bin($arrayResposta["pdf"]);
            //gravar xml no banco de dados na venda
            //echo "UPDATE sma_sales SET xml = '".$arrayResposta["xml"]."' WHERE id = '".$_REQUEST["idVenda"]."' ";
            mysql_query("UPDATE sma_sales SET xml = '".$arrayResposta["xml"]."' WHERE id = '".$_REQUEST["idVenda"]."' ");
            
            mysql_query("UPDATE sma_empresa  SET numeroNfeAtual = '".$num."' WHERE id = '".$idEmpresa."' ");
            //acrescentar +1 no número da ultima nota emitida
            //mysql_query("UPDATE sma_empresa set numeroNfeAtual = '".$_REQUEST["numeroNfe"]."' WHERE id = '".$_REQUEST["idEmpresa"]."' ");
            
            /*
             * SALVA NA NFE
             */
            mysql_query("INSERT INTO sma_nfe (num_nfe,chave,xml,venda_id,empresa_id,usuario_id,data_nfe,hora,status,protocolo,recibo) values ('".$num."', '".$arrayResposta["chave"]."', '".$arrayResposta["xml"]."', '".$pedido."', '".$idEmpresa."','".$usuario_id."','".$datanfe."','".$horanfe."', 'EMITIDO','".$protocolo."','".$recibo."' )");
            
           // echo  $arrayResposta["chave"];
          // exit;
//$exec = query($insert, $bd);
            //mostrar pdf na tela
            header("Content-type:application/pdf");
            echo $pdf;
        }else{//rejeição
            //mostrar mensagem de rejeição da sefaz
           
            echo "Rejeição: ".$arrayResposta["cStat"].' - '.$arrayResposta["xMotivo"];
        }
    }
    
}else{
    echo "arquivo xml não encontrado";
}
