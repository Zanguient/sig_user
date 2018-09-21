<?php
 ini_set('default_charset','UTF-8');
date_default_timezone_set("Brazil/East"); // Brasil

require_once 'conexao.php';
//    require_once 'conexao.class.php';
//    $con = new conexao(); // instancia classe de conxao
//    $con->connect(); // abre conexao com o banco

//funções úteis
//****************************
function limita_caracteres($texto, $limite, $quebra = true){
   $tamanho = strlen($texto);
   if($tamanho <= $limite){ //Verifica se o tamanho do texto é menor ou igual ao limite
      $novo_texto = $texto;
   }else{ // Se o tamanho do texto for maior que o limite
      if($quebra == true){ // Verifica a opção de quebrar o texto
         $novo_texto = trim(substr($texto, 0, $limite))."...";
      }else{ // Se não, corta $texto na última palavra antes do limite
         $ultimo_espaco = strrpos(substr($texto, 0, $limite), " "); // Localiza o útlimo espaço antes de $limite
         $novo_texto = trim(substr($texto, 0, $ultimo_espaco))."..."; // Corta o $texto até a posição localizada
      }
   }
   return $novo_texto; // Retorna o valor formatado
}

function tirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}

function nfce($string)
{
  $string = str_replace('á','a',$string);
  $string = str_replace('Á','A',$string);
  $string = str_replace('à','a',$string);
  $string = str_replace('À','A',$string);
  $string = str_replace('â','a',$string);
  $string = str_replace('Â','A',$string);
  $string = str_replace('ã','a',$string);
  $string = str_replace('Ã','A',$string);
  $string = str_replace('ç','c',$string);
  $string = str_replace('Ç','C',$string);
  $string = str_replace('é','e',$string);
  $string = str_replace('É','E',$string);
  $string = str_replace('ê','e',$string);
  $string = str_replace('Ê','E',$string);
  $string = str_replace('è','e',$string);
  $string = str_replace('È','E',$string);
  $string = str_replace('í','i',$string);
  $string = str_replace('Í','I',$string);
  $string = str_replace('ó','o',$string);
  $string = str_replace('Ó','O',$string);
  $string = str_replace('ô','o',$string);
  $string = str_replace('Ô','O',$string);
  $string = str_replace('õ','o',$string);
  $string = str_replace('Õ','O',$string);
  $string = str_replace('ú','u',$string);
  $string = str_replace('Ú','U',$string);
  $string = str_replace('~','',$string);
  $string = str_replace('&','e',$string);
  $string = str_replace('.','',$string);
  $string = str_replace('-','',$string);
  $string = str_replace(',','',$string);
  $string = str_replace(';','',$string);
  $string = str_replace(':','',$string);
  $string = str_replace('(','',$string);
  $string = str_replace(')','',$string);
  $string = str_replace('/','',$string);
  
  
  
  return $string;
  } 

    function utf8_strtr($str) {
    $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
    $to = "aaaaeeiooouucAAAAEEIOOOUUC";
    
    $keys = array();
    $values = array();
    preg_match_all('/./u', $from, $keys);
    preg_match_all('/./u', $to, $values);
    $mapping = array_combine($keys[0], $values[0]);
    return strtr($str, $mapping);
    }
   
  
  function icms($preco, $icms){
    $valor = $preco; // valor original
    $percentual = $icms / 100.0; // 8%
    $valor_final = $valor - ($percentual * $valor);
    $creditoicms = $valor - $valor_final;
    return $creditoicms;
  }

  function geraCN($length=8){
      $numero = '';    
      for ($x=0;$x<$length;$x++){
          $numero .= rand(0,9);
      }
      return $numero;
  }

  function calculaDV($chave43) {
      $multiplicadores = array(2,3,4,5,6,7,8,9);
      $i = 42;
      $soma_ponderada = 0;
      while ($i >= 0) {
          for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
              $soma_ponderada+= $chave43[$i] * $multiplicadores[$m];
              $i--;
          }
      }
      $resto = $soma_ponderada % 11;
      if ($resto == '0' || $resto == '1') {
          return 0;
      } else {
          return (11 - $resto);
     }
  }
  
     function clear_tags($str)
    {
        return htmlentities(
            strip_tags($str,
                '<p>'
            ),
            ENT_QUOTES | ENT_XHTML | ENT_HTML5,
            'UTF-8'
        );
    }
    
    function decode_html($str)
    {
        return html_entity_decode($str, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }
//*************************
  
//recebe o id do usuario, para pegar o id da empresa dele
$usuario_id = $_GET['usuario'];
  
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


define('EMPRESA', utf8_encode($empresa_nome));	
define('CNPJ',  $empresa_cnpj);
define('INSCRICAOESTADUAL',  $empresa_ie);
define('EMPRESA_ENDERECO', utf8_encode($empresa_endereco));	
define('EMPRESA_NUMERO', $empresa_numero);	
define('EMPRESA_BAIRRO', utf8_encode($empresa_bairro));	
define('EMPRESA_COMPLEMENTO', utf8_encode($empresa_complemento));	
define('EMPRESA_CIDADE',  utf8_encode($empresa_cidade));
define('EMPRESA_UF',  $empresa_uf);
define('EMPRESA_CEP',  $empresa_cep);
define('EMPRESA_TEL',  $empresa_telefone);
define('EMPRESA_IBGE',  $empresa_ibge);
  
  
  
/* NFe Agilcontabil.net Integração ;-) */
$pedido = $_GET['pedido'];

$query_sale = mysql_query("SELECT * FROM sma_sales WHERE id = '$pedido' ") or die(mysql_error());
$sales = mysql_fetch_array($query_sale);

if(!empty($sales["xml"])){
    //já emitido
    
    
    $xml = $sales["xml"];
    
    //mostrar pdf pelo xml
    //conectar com agilcontabil pra mostrar pdf
    
    $urlIntegracao    = 'http://www.agilcontabil.net/sistemaInstalado/ajax';
    $dados['usuario'] = $loginAfil;
    $dados['senha']   = $senhgaAgil;    
    $dados['acao']    = 'emitirDanfe';
    $dados['xml']     = $xml;
   
  //Inicia comunicação com servidor agilcontabil.net
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $urlIntegracao);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));
  //recebe a resposta
  $resposta = curl_exec($ch);
  //finaliza comunicação
  curl_close($ch);


$arrayResposta = json_decode($resposta,true); //transforma resposta json em array do PHP

//salva o pdf em arquivo (se preferir)
$pdf = hex2bin($arrayResposta["pdf"]); //transforma PDF de hexadecimal (texto) para binário (00010101010101)
header("Content-type:application/pdf");
echo $pdf;
    
//file_put_contents('teste.pdf',$pdf); //salva conteudo binário do pdf em arquivo
?>




<?php
    
   
    
}else{//não tem xml emitido ainda
    $row = mysql_num_rows($query_sale);

    $clienteid = $sales['customer_id'];

    $query_customer = mysql_query("SELECT * FROM sma_companies WHERE id = '$clienteid'");
    $cliente = mysql_fetch_array($query_customer);  

    $query_psale = mysql_query("SELECT * FROM sma_payments WHERE sale_id = '$pedido'");
    $paymentsale = mysql_fetch_array($query_psale);  

    $idpedido = $sales['id'];

    $nomecliente = utf8_encode($cliente['name']);
    $nomecliente = utf8_strtr($nomecliente);
    $ieCliente = nfce(utf8_encode($cliente['cf1']));
    $indicacaoIeCliente = nfce(utf8_encode($cliente['indicacaoCliente']));
    $cpfcliente = nfce($cliente['vat_no']);
    
    $enderecocliente = utf8_encode($cliente['address']);
    $enderecocliente = utf8_strtr($enderecocliente);
    
    $numerocliente = utf8_strtr(utf8_encode($cliente['cf2']));
    $complementocliente = utf8_decode($cliente['cf3']);
    $complementocliente = utf8_strtr($complementocliente);
    $bairrocliente = utf8_encode($cliente['cf5']);
    $bairrocliente = utf8_strtr($bairrocliente);
    $codigoMunicipioCliente = $cliente['codigoMunicipio'];
    $cidadecliente = utf8_encode($cliente['city']);
    $cidadecliente = utf8_strtr($cidadecliente);
    $estadocliente = nfce(utf8_encode($cliente['state']));
    $cepcliente = nfce($cliente['postal_code']);
    $fonecliente = nfce($cliente['phone']);

    /**DADOS DA EMPRESA**/
    $idEmpresa = $empresa_id;
    $razao = EMPRESA;
    $razao = utf8_strtr($razao);
    $cnpj = nfce(CNPJ);
    $ie = nfce(INSCRICAOESTADUAL);
    $endereco = utf8_strtr(EMPRESA_ENDERECO);
    $numero = nfce(EMPRESA_NUMERO);
    $bairro = utf8_encode(nfce(EMPRESA_BAIRRO));
    $complemento = utf8_strtr(nfce(EMPRESA_COMPLEMENTO));
    $cidade = utf8_strtr(EMPRESA_CIDADE);
    $estado = EMPRESA_UF;
    $cep = nfce(EMPRESA_CEP);
    $fone = nfce(EMPRESA_TEL);
    $codmunicipio = EMPRESA_IBGE;
    $ambiente = $ambienteNfe; //1-produção, 2-homologação(teste)
    $num2 = $nfeAtual + 1; //numeroNfeAtual+1 

    


    $totalvendas = $sales['total'];
    //Monta DV 65
    $cUF = $ufIbge;    
    $aamm = nfce(date('y/m'));     
    $cnpj = nfce(CNPJ);     
    $mod='55';      
    $serie='001';           
    $tpEmis='1';    
    $cn='';       
    $dv='';     
    $num = str_pad($num2, 9, '0',STR_PAD_LEFT);
    
    $cn = geraCN(8);
    $chave = "$cUF$aamm$cnpj$mod$serie$num$tpEmis$cn";
   
    $dv = calculaDV($chave);
    $chave .= $dv;
    $n = strlen($chave);
    /*
    echo 'cUF = '.$cUF.'<BR>';
    echo 'AAMM = '.$aamm.'<BR>';
    echo 'CNPJ = '.$cnpj.'<BR>';
    echo 'MOD = '.$mod.'<BR>';
    echo 'SERIE = '.$serie.'<BR>';
    echo 'NUM = '.$num.'<BR>';
    echo 'tpEmis = '.$tpEmis.'<BR>';
    echo 'CODIGO = '.$cn.'<BR>';
    echo 'DV = '.$dv.'<BR>';

    */
    $datanfe = date('Y-m-d');
    $horanfe = date('H:i:s');
    $formatadata = $datanfe.'T'.$horanfe;

    /*
    $query_taxas_pis = mysql_query("SELECT * FROM sma_tax_rates where code = 'PIS'");
    $taxas_pis = mysql_fetch_array($query_taxas_pis);  
    $pis_taxa = $taxas_pis['rate'];
    
    $query_taxas_icms = mysql_query("SELECT * FROM sma_tax_rates where code = 'ICMS'");
    $taxas_icms = mysql_fetch_array($query_taxas_icms);  
    $icms_taxa = $taxas_icms['rate'];
    
    $query_taxas_confins = mysql_query("SELECT * FROM sma_tax_rates where code = 'CONFINS'");
    $taxas_confins = mysql_fetch_array($query_taxas_confins);  
    $icms_confins = $taxas_confins['rate'];
    
     * 
     */
 


    // Verifica se Existe
    if($row>0) {

    $arquivo = "xml/nfce$chave.xml";

    // Abre se n�o cria
    $ponteiro = fopen($arquivo, "w");
    //fclose($ponteiro);

    // NFCE
    if($paymentsale['paid_by'] = 'cash') {
    $formapgto = "Dinheiro";
    } else {
    $formapgto = "Outros";
    }

    $digVal = base64_encode($idpedido);

    // Escrevendo XML    manausbo_pdv   pdv2016
   
    fwrite($ponteiro, "<?xml version='1.0' encoding='utf-8'?>");
    //fwrite($ponteiro, '<nfeProc xmlns="http://www.portalfiscal.inf.br/nfe" versao="3.10">');
    fwrite($ponteiro, '<NFe xmlns="http://www.portalfiscal.inf.br/nfe">');
    fwrite($ponteiro, '<infNFe Id="'.$chave.'" versao="3.10">');
    fwrite($ponteiro, "<ide>");
    fwrite($ponteiro, "<cUF>13</cUF>");
    fwrite($ponteiro, "<cNF>$idpedido</cNF>");
    fwrite($ponteiro, "<natOp>VENDA DE MERCADORIA</natOp>");
    fwrite($ponteiro, "<indPag>1</indPag>");
    fwrite($ponteiro, "<mod>55</mod>");
    fwrite($ponteiro, "<serie>1</serie>");
    fwrite($ponteiro, "<nNF>$num</nNF>");
    fwrite($ponteiro, "<dhEmi>$formatadata</dhEmi>");
    fwrite($ponteiro, "<tpNF>1</tpNF>");
    fwrite($ponteiro, "<idDest>1</idDest>");
    fwrite($ponteiro, "<cMunFG>$codmunicipio</cMunFG>"); 
    fwrite($ponteiro, "<tpImp>1</tpImp>");
    fwrite($ponteiro, "<tpEmis>1</tpEmis>");
    fwrite($ponteiro, "<cDV>8</cDV>");
    fwrite($ponteiro, "<tpAmb>$ambiente</tpAmb>");
    fwrite($ponteiro, "<finNFe>1</finNFe>");
    fwrite($ponteiro, "<indFinal>1</indFinal>");
    fwrite($ponteiro, "<indPres>1</indPres>");
    fwrite($ponteiro, "<procEmi>0</procEmi>");
    fwrite($ponteiro, "<verProc>V2.245</verProc>");
    fwrite($ponteiro, "</ide>");
    fwrite($ponteiro, "<emit>");
    fwrite($ponteiro, "<CNPJ>$cnpj</CNPJ>");
    fwrite($ponteiro, "<xNome>$razao</xNome>");
    fwrite($ponteiro, "<enderEmit>");
    fwrite($ponteiro, "<xLgr>$endereco</xLgr>");
    fwrite($ponteiro, "<nro>$numero</nro>");
    fwrite($ponteiro, "<xCpl>$complemento</xCpl>");
    fwrite($ponteiro, "<xBairro>$bairro</xBairro>");
    fwrite($ponteiro, "<cMun>$codmunicipio</cMun>");
    fwrite($ponteiro, "<xMun>$cidade</xMun>");
    fwrite($ponteiro, "<UF>$estado</UF>");
    fwrite($ponteiro, "<CEP>$cep</CEP>");
    fwrite($ponteiro, "<cPais>1058</cPais>");
    fwrite($ponteiro, "<xPais>Brasil</xPais>");
    fwrite($ponteiro, "<fone>$fone</fone>");
    fwrite($ponteiro, "</enderEmit>");
    fwrite($ponteiro, "<IE>$ie</IE>");
    fwrite($ponteiro, "<CRT>3</CRT>");
    fwrite($ponteiro, "</emit>");
    fwrite($ponteiro, "<dest>");
    fwrite($ponteiro, "<CPF>$cpfcliente</CPF>");
    fwrite($ponteiro, "<xNome>$nomecliente</xNome>");
    fwrite($ponteiro, "<enderDest>");
    fwrite($ponteiro, "<xLgr>$enderecocliente</xLgr>");
    fwrite($ponteiro, "<nro>$numerocliente</nro>");
    fwrite($ponteiro, "<xCpl>$complementocliente</xCpl>");
    fwrite($ponteiro, "<xBairro>$bairrocliente</xBairro>");
    fwrite($ponteiro, "<cMun>$codigoMunicipioCliente</cMun>");
    fwrite($ponteiro, "<xMun>$cidadecliente</xMun>");
    fwrite($ponteiro, "<UF>$estadocliente</UF>");
    fwrite($ponteiro, "<CEP>$cepcliente</CEP>");
    fwrite($ponteiro, "<cPais>1058</cPais>");
    fwrite($ponteiro, "<xPais>BRASIL</xPais>");
    fwrite($ponteiro, "<fone>$fonecliente</fone>");
    fwrite($ponteiro, "</enderDest>");
    fwrite($ponteiro, "<indIEDest>$indicacaoIeCliente</indIEDest>");
    if(!empty($ieCliente)) fwrite($ponteiro, "<IE>$ieCliente</IE>");
    fwrite($ponteiro, "</dest>");

    $z = 0;
    $query_bling = mysql_query("SELECT * FROM sma_sales WHERE id = '$pedido'");
    $bling = mysql_fetch_array($query_bling);
 
   $nota = decode_html($bling['note']);
   $nota = strip_tags($nota);
   $nota = limita_caracteres(nfce($nota),5000,false);
   echo $nota; exit;
   // echo $nota; exit;
   
   $total = $bling['total'];  // VALOR TOTAL DA COMPRA
   
   $soma_icms = 0;
   $soma_pis = 0;
   $soma_confins = 0;
   
    $prdbling = mysql_query("SELECT * FROM sma_sale_items WHERE sale_id = '".$bling['id']."'");
    while($produtobling = mysql_fetch_array($prdbling)){

    $prdsbb = mysql_query("SELECT * FROM sma_products WHERE id = '".$produtobling['product_id']."'");
    $prds = mysql_fetch_array($prdsbb);

    $z++;
    $ncm = $prds['cf3'];  //NCM
    $ean = $prds['code']; 
    $cfop = $prds['cf4'];  //CFOP
    $pis = $prds['cf2'];   //PIS
    $icms = $prds['cf5'];  //ICMS
    $cofins = $prds['cf6']; //CONFINS
  
    
    
    
    $nomeproduto = utf8_strtr(utf8_encode($produtobling['product_name']));
    $detalhes_produto = strip_tags($prds['product_details']);
    $detalhes_produto = decode_html($detalhes_produto);
    $detalhesproduto = limita_caracteres(utf8_encode(nfce($detalhes_produto)),500,false);

    
    $preco = $produtobling['unit_price'];
    $quantity = $produtobling['quantity'];
    $subtotal = $produtobling['subtotal'];

    $uTrib = nfce($prds['unit']);
    $pisproduto = icms($preco, $pis);
    $icmsproduto = icms($preco,$icms);
    $confinsproduto = icms($preco,$cofins);
    
    $soma_icms += icms($subtotal,$icms);
    $soma_pis += icms($subtotal,$pis);
    $soma_confins += icms($subtotal,$cofins);
    
    fwrite($ponteiro, '<det nItem="'.$z.'">');
    fwrite($ponteiro, "<prod>");
    fwrite($ponteiro, "<cProd>$ean</cProd>");
    
    fwrite($ponteiro, "<cEAN/>");
    fwrite($ponteiro, "<xProd>$nomeproduto</xProd>");
    fwrite($ponteiro, "<NCM>$ncm</NCM>");
    fwrite($ponteiro, "<CFOP>$cfop</CFOP>");
    fwrite($ponteiro, "<uCom>$uTrib</uCom>");
    fwrite($ponteiro, "<qCom>$quantity</qCom>");
    fwrite($ponteiro, "<vUnCom>$preco</vUnCom>");
    fwrite($ponteiro, "<vProd>$subtotal</vProd>");
    fwrite($ponteiro, "<cEANTrib/>");
    
    fwrite($ponteiro, "<uTrib>$uTrib</uTrib>");
    fwrite($ponteiro, "<qTrib>$preco</qTrib>");
    fwrite($ponteiro, "<vUnTrib>$quantity</vUnTrib>");
    fwrite($ponteiro, "<indTot>1</indTot>");
    fwrite($ponteiro, "</prod>");
    
    fwrite($ponteiro, "<imposto>");
    
    fwrite($ponteiro, "<ICMS>");
    fwrite($ponteiro, "<ICMS00>");
    fwrite($ponteiro, "<orig>0</orig>");
    fwrite($ponteiro, "<CST>00</CST>");
    fwrite($ponteiro, "<vBC>$subtotal</vBC>");
    fwrite($ponteiro, "<pICMS>$icms</pICMS>");
    fwrite($ponteiro, "<vICMS>$soma_icms</vICMS>");
    fwrite($ponteiro, "</ICMS00>");
    fwrite($ponteiro, "</ICMS>");
    
    fwrite($ponteiro, "<PIS>");
    fwrite($ponteiro, "<PISAliq>");
    fwrite($ponteiro, "<CST>01</CST>");
    fwrite($ponteiro, "<vBC>$preco</vBC>");
    fwrite($ponteiro, "<pPIS>$pis</pPIS>");
    fwrite($ponteiro, "<vPIS>$pisproduto</vPIS>");
    fwrite($ponteiro, "</PISAliq>");
    fwrite($ponteiro, "</PIS>");
    
    fwrite($ponteiro, "<COFINS>");
    fwrite($ponteiro, "<COFINSAliq>");
    fwrite($ponteiro, "<CST>01</CST>");
    fwrite($ponteiro, "<vBC>$preco</vBC>");
    fwrite($ponteiro, "<pCOFINS>$cofins</pCOFINS>");
    fwrite($ponteiro, "<vCOFINS>$confinsproduto</vCOFINS>");
    fwrite($ponteiro, "</COFINSAliq>");
    fwrite($ponteiro, "</COFINS>");
    fwrite($ponteiro, "</imposto>");
    
    fwrite($ponteiro, "<infAdProd>$detalhesproduto</infAdProd>");
    fwrite($ponteiro, "</det>");
    }
    
    $claculoicmsnota = $soma_icms;//icms($totalvendas,$soma_icms);
    $claculopisnota = $soma_pis;//icms($totalvendas, $soma_pis);
    $claculoConfinsNota = $soma_confins;//icms($totalvendas, $soma_confins);
    
    fwrite($ponteiro, "<total>");
    fwrite($ponteiro, "<ICMSTot>");
    fwrite($ponteiro, "<vBC>$total</vBC>");
    fwrite($ponteiro, "<vICMS>$claculoicmsnota</vICMS>");
    fwrite($ponteiro, "<vBCST>0.00</vBCST>");
    fwrite($ponteiro, "<vST>0.00</vST>");
    fwrite($ponteiro, "<vProd>$totalvendas</vProd>");
    fwrite($ponteiro, "<vFrete>0.00</vFrete>");
    fwrite($ponteiro, "<vSeg>0.00</vSeg>");
    fwrite($ponteiro, "<vDesc>0.00</vDesc>");
    fwrite($ponteiro, "<vII>0.00</vII>");
    fwrite($ponteiro, "<vIPI>0.00</vIPI>");
    fwrite($ponteiro, "<vPIS>$claculopisnota</vPIS>");
    fwrite($ponteiro, "<vCOFINS>$claculoConfinsNota</vCOFINS>");
    fwrite($ponteiro, "<vOutro>0.00</vOutro>");
    fwrite($ponteiro, "<vNF>$totalvendas</vNF>");
    fwrite($ponteiro, "</ICMSTot>");
    fwrite($ponteiro, "</total>");
    
    fwrite($ponteiro, "<transp>");
    fwrite($ponteiro, "<modFrete>9</modFrete>");
    fwrite($ponteiro, "</transp>");
    
    fwrite($ponteiro, "<infAdic>");
    fwrite($ponteiro, "<infCpl>$nota</infCpl>");
    fwrite($ponteiro, "<infAdFisco></infAdFisco>");
    fwrite($ponteiro, "</infAdic>");
    
    fwrite($ponteiro, "<pag>");
    fwrite($ponteiro, "<tPag>$formapgto</tPag>");
    fwrite($ponteiro, "<vPag>$totalvendas</vPag>");
    fwrite($ponteiro, "</pag>");
    fwrite($ponteiro, "</infNFe>");
 

    /*
    fwrite($ponteiro, '<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
                <SignedInfo>
                    <CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
                    <SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                    <Reference URI="#NFe'.$chave.'">
                        <Transforms>
                            <Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/>
                            <Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
                        </Transforms>
                        <DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <DigestValue>'.$digVal.'</DigestValue>
                    </Reference>
                </SignedInfo>
                <SignatureValue>
                        ZYZo8lq1RLEo/q6TewsRamDweKn0jEXV29rirKm63b7o6qrSNmSreUKpZOGzwPiZz8xNeLjDdS6g
                        U1K1FyBup2NhNuqE4X2oE9MahEJEdektHaF/GZDfff9A48GmaNwQo3LQ7Im+KmZLO8kHCX3eZqFz
                        vqaaPrZjwHtr6ZUj/LMMMbClxaR0M4VfbEtL74YPUVqfKIZC/gcPp2WFlnGtm1rRI//LK3KDEc2v
                        pJXDWj5sNJaE0z9FjzLZp2lXWAJdyAVHijCpSE4uZeMQrwTj4F8hcO1WwRYd96MwmG1VDfNGM8a6
                        B/7zD19uU/TE4Ep4Bp9dx3gkOoNtLP5Fyo0fdw==
                </SignatureValue>
                <KeyInfo><X509Data><X509Certificate>
                    MIIHxzCCBa+gAwIBAgIISGin1tcEtYswDQYJKoZIhvcNAQELBQAwTDELMAkGA1UEBhMCQlIxEzAR
                    BgNVBAoTCklDUC1CcmFzaWwxKDAmBgNVBAMTH1NFUkFTQSBDZXJ0aWZpY2Fkb3JhIERpZ2l0YWwg
                    djIwHhcNMTIxMDI1MTk1MzAwWhcNMTMxMDI1MTk1MzAwWjCB4DELMAkGA1UEBhMCQlIxEzARBgNV
                    BAoTCklDUC1CcmFzaWwxFDASBgNVBAsTCyhFTSBCUkFOQ08pMRgwFgYDVQQLEw8wMDAwMDEwMDM2
                    NjE3MzUxFDASBgNVBAsTCyhFTSBCUkFOQ08pMRQwEgYDVQQLEwsoRU0gQlJBTkNPKTEUMBIGA1UE
                    CxMLKEVNIEJSQU5DTykxFDASBgNVBAsTCyhFTSBCUkFOQ08pMRQwEgYDVQQLEwsoRU0gQlJBTkNP
                    KTEeMBwGA1UEAxMVTCBKIEdVRVJSQSBFIENJQSBMVERBMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A
                    MIIBCgKCAQEAxVJUqBH+hsxaNkihIR9SwLOJ/MaME288DQD7AUw/Dyincl1EOEx77rWrRoVw3+bL
                    0+fzHEAreEioA/MmLb6N9hHchs9OgICchvdQSXU7AdAuHjJpRhC8WNgdu3G8RoQK0C7NkKgvHDT+
                    q4RVmae5nmzG+Gq+mfu5cZqHKoMJNjzgMoaUSX0mqv9ST3yT8oVE04Y64CW0vWHG8B+Lt2IUr/B6
                    eyTsJps5NhqC6Eo5kJPjcD18ZmM3SmoLWGhfbEU7/NjGCH8rrx6KpxToJvt7gV9m4tZmZo/uShQH
                    nhrsittXXqsZMIkA/AVK413GC6nq2w+sCOm19IM9fQ9H509glwIDAQABo4IDFjCCAxIwgZcGCCsG
                    AQUFBwEBBIGKMIGHMEcGCCsGAQUFBzAChjtodHRwOi8vd3d3LmNlcnRpZmljYWRvZGlnaXRhbC5j
                    b20uYnIvY2FkZWlhcy9zZXJhc2FjZHYyLnA3YjA8BggrBgEFBQcwAYYwaHR0cDovL29jc3AuY2Vy
                    dGlmaWNhZG9kaWdpdGFsLmNvbS5ici9zZXJhc2FjZHYyMB8GA1UdIwQYMBaAFJrggxDXJpvputqC
                    soHOORrTh3CGMHEGA1UdIARqMGgwZgYGYEwBAgEGMFwwWgYIKwYBBQUHAgEWTmh0dHA6Ly9wdWJs
                    aWNhY2FvLmNlcnRpZmljYWRvZGlnaXRhbC5jb20uYnIvcmVwb3NpdG9yaW8vZHBjL2RlY2xhcmFj
                    YW8tc2NkLnBkZjCB8AYDVR0fBIHoMIHlMEmgR6BFhkNodHRwOi8vd3d3LmNlcnRpZmljYWRvZGln
                    aXRhbC5jb20uYnIvcmVwb3NpdG9yaW8vbGNyL3NlcmFzYWNkdjIuY3JsMEOgQaA/hj1odHRwOi8v
                    bGNyLmNlcnRpZmljYWRvcy5jb20uYnIvcmVwb3NpdG9yaW8vbGNyL3NlcmFzYWNkdjIuY3JsMFOg
                    UaBPhk1odHRwOi8vcmVwb3NpdG9yaW8uaWNwYnJhc2lsLmdvdi5ici9sY3IvU2VyYXNhL3JlcG9z
                    aXRvcmlvL2xjci9zZXJhc2FjZHYyLmNybDAOBgNVHQ8BAf8EBAMCBeAwHQYDVR0lBBYwFAYIKwYB
                    BQUHAwIGCCsGAQUFBwMEMIG/BgNVHREEgbcwgbSBF0NQREBDQVNBREFTQ09SUkVJQVMuQ09NoD4G
                    BWBMAQMEoDUTMzAyMDExOTYzMDIyMzI2NjQ4NTYwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAw
                    MDAwMKAlBgVgTAEDAqAcExpKT0RJQkVSVE8gTEVNQVIgREFMTCBPR0xJT6AZBgVgTAEDA6AQEw4w
                    NDUwMTEzNjAwMDEzNqAXBgVgTAEDB6AOEwwwMDAwMDAwMDAwMDAwDQYJKoZIhvcNAQELBQADggIB
                    ADxraixJl/YJK8pKDIv1iR4UuPBx4KjpDSSJj48T12T0jxFK1woTQQqGzBFBpv6ShqIRmlux/V8P
                    xqhF30LMObq4mNjLkn9l2Fk7vbYCFrLU+f5ag+0y5e4YbNUtVlfc2Rva9IodaOp8MOzNIEhBcvaw
                    ODL826iTkJkMNWYytygDZaJWLGjXI7JN/XvXCg0thrQ5s2MlGIkMYdV7MxvilN5Qsk5Prt4P59hx
                    NZJ3fvAJ9zCE/tUH0QqpOFIVWrpEOelUad6ZOVZFIbRIGXykfRFM6Rj/aqnH/me7Kmyb7jPewQnz
                    TtSs1GNQqQHknJRtQn4LsrJV/qo2ZyxTOnqtvuXu8tzbrnXwuTmlaUYkcK7do4w6QjIrKr8qp2Zy
                    dJ63seLxiixrEioXwZ/m68Qyx6abxLndPTPDHDjvdi8JvM/ckzaQiF9+uFR63mlc0omkE9PhElLA
                    6wGhvTs0co8QRvLYvOUt8HbF1hqnSWmroivq/WwKgvSf2b3kuMBYGhKmC0vqwzGU6s/Ml9LZky4j
                    0K1xxRMV0+pG/R3huuS0WRz3uJ2k7d1WK3mymQ1+gBlcNFBqCtCjo5wGLMLmL9P9ETLQwm+5yCxf
                    MzVDDMRQpJddtajP16cvGjZQe0CSnHuDELN3AQJRntdRqs1uMKGUcUzu3Oa5nJSLx6HygqU4z6Ve
                </X509Certificate>
                </X509Data>
                </KeyInfo>
                </Signature>');
    fwrite($ponteiro, '<protNFe versao="3.00">');
    fwrite($ponteiro, "<infProt>");
    fwrite($ponteiro, "<tpAmb>2</tpAmb>");//1 - produção; 2 - homologação
    fwrite($ponteiro, "<verAplic>XXXX</verAplic>");
    fwrite($ponteiro, "<chNFe>$chave</chNFe>");
    fwrite($ponteiro, "<dhRecbto>$formatadata</dhRecbto>");
    fwrite($ponteiro, "<nProt>$idpedido</nProt>");
    fwrite($ponteiro, "<digVal>$digVal</digVal>");
    fwrite($ponteiro, "<cStat>100</cStat>");
    fwrite($ponteiro, "<xMotivo>Autorizado o uso da NF-e</xMotivo>");
    fwrite($ponteiro, "</infProt>");
    fwrite($ponteiro, "</protNFe>");*/
    fwrite($ponteiro, "</NFe>");
    //fwrite($ponteiro, "</nfeProc>");

    
    // Fecha XML
    fclose($ponteiro);

   
    } // Fecha IF($row)

    ?>
    <script>
    document.location.href = ("integracaoAgil.php?nfe=nfce<?php echo $chave; ?>.xml&idEmpresa=<?php echo $idEmpresa;?>&usuario=<?php echo $usuario_id;?>&chave=<?php echo $chave;?>&pedido=<?php echo $pedido;?>&idVenda=<?php echo $sales["id"];?>&num=<?php echo $num2; ?>");
    </script>
    <?php
}//if empty(xml)
?>