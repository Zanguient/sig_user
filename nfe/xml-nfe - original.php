<?php

date_default_timezone_set("Brazil/East"); // Brasil

    require_once 'conexao.class.php';
    $con = new conexao(); // instancia classe de conxao
    $con->connect(); // abre conexao com o banco

function nfce($string)
{
  $string = str_replace('б','a',$string);
  $string = str_replace('Б','A',$string);
  $string = str_replace('а','a',$string);
  $string = str_replace('А','A',$string);
  $string = str_replace('в','a',$string);
  $string = str_replace('В','A',$string);
  $string = str_replace('г','a',$string);
  $string = str_replace('Г','A',$string);
  $string = str_replace('з','c',$string);
  $string = str_replace('З','C',$string);
  $string = str_replace('й','e',$string);
  $string = str_replace('Й','E',$string);
  $string = str_replace('к','e',$string);
  $string = str_replace('К','E',$string);
  $string = str_replace('и','e',$string);
  $string = str_replace('И','E',$string);
  $string = str_replace('н','i',$string);
  $string = str_replace('Н','I',$string);
  $string = str_replace('у','o',$string);
  $string = str_replace('У','O',$string);
  $string = str_replace('ф','o',$string);
  $string = str_replace('Ф','O',$string);
  $string = str_replace('х','o',$string);
  $string = str_replace('Х','O',$string);
  $string = str_replace('ъ','u',$string);
  $string = str_replace('Ъ','U',$string);
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
  
  
/* NFe Bling Integraзгo API v1 */

$pedido = $_GET['pedido'];

$query_sale = mysql_query("SELECT * FROM sma_sales WHERE id = '$pedido'");
$sales = mysql_fetch_array($query_sale);
$row = mysql_num_rows($query_sale);
$clienteid = $sales['customer_id'];

$query_customer = mysql_query("SELECT * FROM sma_companies WHERE id = '$clienteid'");
$cliente = mysql_fetch_array($query_customer);  
  
$query_psale = mysql_query("SELECT * FROM sma_payments WHERE sale_id = '$pedido'");
$paymentsale = mysql_fetch_array($query_psale);  

$idpedido = $sales['pedido'];

$nomecliente = nfce($cliente['name']);
$cpfcliente = nfce($cliente['vat_no']);
$enderecocliente = nfce($cliente['address']);
$numerocliente = nfce($cliente['cf2']);
$complementocliente = nfce($cliente['cf3']);
$bairrocliente = nfce($cliente['cf5']);
$cidadecliente = nfce($cliente['city']);
$estadocliente = nfce($cliente['state']);
$cepcliente = nfce($cliente['postal_code']);
$fonecliente = nfce($cliente['phone']);

$razao = nfce(EMPRESA);
$cnpj = nfce(CNPJ);
$ie = INSCRICAOESTADUAL;
$endereco = nfce(EMPRESA_ENDERECO);
$cidade = nfce(EMPRESA_CIDADE);
$estado = EMPRESA_UF;
$cep = nfce(EMPRESA_CEP);
$fone = nfce(EMPRESA_TEL);
$codmunicipio = EMPRESA_IBGE;


$totalvendas = $sales['total'];
//Monta DV 65
$cUF = '35';    
$aamm = nfce(date('y/m'));     
$cnpj = nfce(CNPJ);     
$mod='55';      
$serie='001';     
$num = $sales['id'];       
$tpEmis='1';    
$cn='';       
$dv='';     
$num = str_pad($num, 9, '0',STR_PAD_LEFT);
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

$datanfe = date('Y-m-d');
$horanfe = date('H:i:s');
$formatadata = $datanfe.'T'.$horanfe;

$claculoicmsnota = icms($totalvendas,7.60);
$claculopisnota = icms($totalvendas,1.65);

// Verifica se Existe
if($row >0) {

$arquivo = "xml/nfce$chave.xml";

// Abre se nгo cria
$ponteiro = fopen($arquivo, "w");

// NFCE
if($paymentsale['paid_by'] = 'cash') {
$formapgto = "Dinheiro";
} else {
$formapgto = "Outros";
}

$digVal = base64_encode($idpedido);

// Escrevendo XML
fwrite($ponteiro, "<?xml version='1.0' encoding='utf-8'?>");
fwrite($ponteiro, '<nfeProc xmlns="http://www.portalfiscal.inf.br/nfe" versao="3.10">');
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
fwrite($ponteiro, "<tpImp>4</tpImp>");
fwrite($ponteiro, "<tpEmis>1</tpEmis>");
fwrite($ponteiro, "<cDV>8</cDV>");
fwrite($ponteiro, "<tpAmb>2</tpAmb>");
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
fwrite($ponteiro, "<nro></nro>");
fwrite($ponteiro, "<xCpl></xCpl>");
fwrite($ponteiro, "<xBairro></xBairro>");
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
fwrite($ponteiro, "<cMun>1302603</cMun>");
fwrite($ponteiro, "<xMun>$cidadecliente</xMun>");
fwrite($ponteiro, "<UF>$estadocliente</UF>");
fwrite($ponteiro, "<CEP>$cepcliente</CEP>");
fwrite($ponteiro, "<cPais>1058</cPais>");
fwrite($ponteiro, "<xPais>BRASIL</xPais>");
fwrite($ponteiro, "<fone>$fonecliente</fone>");
fwrite($ponteiro, "</enderDest>");
fwrite($ponteiro, "</dest>");

$z = 0;
$query_bling = mysql_query("SELECT * FROM sma_sales WHERE id = '$pedido'");
while($bling = mysql_fetch_array($query_bling)){

$prdbling = mysql_query("SELECT * FROM sma_sale_items WHERE sale_id = '".$bling['id']."'");
$produtobling = mysql_fetch_array($prdbling);

$prdsbb = mysql_query("SELECT * FROM sma_products WHERE id = '".$produtobling['product_id']."'");
$prds = mysql_fetch_array($prdsbb);

$z++;
$ncm = $prds['cf3'];
$ean = $prds['code'];
$nomeproduto = nfce($produtobling['product_name']);
$preco = $produtobling['unit_price'];
$uTrib = nfce($prds['unit']);
$pisproduto = icms($preco,1.65);
$icmsproduto = icms($preco,7.60);
fwrite($ponteiro, "<det nItem='$z'>");
fwrite($ponteiro, "<prod>");
fwrite($ponteiro, "<cProd>$ean</cProd>");
fwrite($ponteiro, "<cEAN/>");
fwrite($ponteiro, "<xProd>$nomeproduto</xProd>");
fwrite($ponteiro, "<NCM>$ncm</NCM>");
fwrite($ponteiro, "<CFOP>5405</CFOP>");
fwrite($ponteiro, "<uCom>$uTrib</uCom>");
fwrite($ponteiro, "<qCom>1.0000</qCom>");
fwrite($ponteiro, "<vUnCom>$preco</vUnCom>");
fwrite($ponteiro, "<vProd>$preco</vProd>");
fwrite($ponteiro, "<cEANTrib/>");
fwrite($ponteiro, "<uTrib>$uTrib</uTrib>");
fwrite($ponteiro, "<qTrib>1.0000</qTrib>");
fwrite($ponteiro, "<vUnTrib>$preco</vUnTrib>");
fwrite($ponteiro, "<indTot>1</indTot>");
fwrite($ponteiro, "</prod>");
fwrite($ponteiro, "<imposto>");
fwrite($ponteiro, "<ICMS>");
fwrite($ponteiro, "<ICMS60>");
fwrite($ponteiro, "<orig>0</orig>");
fwrite($ponteiro, "<CST>60</CST>");
fwrite($ponteiro, "<vBCSTRet>0.00</vBCSTRet>");
fwrite($ponteiro, "<vICMSSTRet>0.00</vICMSSTRet>");
fwrite($ponteiro, "</ICMS60>");
fwrite($ponteiro, "</ICMS>");
fwrite($ponteiro, "<PIS>");
fwrite($ponteiro, "<PISAliq>");
fwrite($ponteiro, "<CST>01</CST>");
fwrite($ponteiro, "<vBC>$preco</vBC>");
fwrite($ponteiro, "<pPIS>0.65</pPIS>");
fwrite($ponteiro, "<vPIS>$pisproduto</vPIS>");
fwrite($ponteiro, "</PISAliq>");
fwrite($ponteiro, "</PIS>");
fwrite($ponteiro, "<COFINS>");
fwrite($ponteiro, "<COFINSAliq>");
fwrite($ponteiro, "<CST>01</CST>");
fwrite($ponteiro, "<vBC>$preco</vBC>");
fwrite($ponteiro, "<pCOFINS>7.60</pCOFINS>");
fwrite($ponteiro, "<vCOFINS>$icmsproduto</vCOFINS>");
fwrite($ponteiro, "</COFINSAliq>");
fwrite($ponteiro, "</COFINS>");
fwrite($ponteiro, "</imposto>");
fwrite($ponteiro, "</det>");
}
fwrite($ponteiro, "<total>");
fwrite($ponteiro, "<ICMSTot>");
fwrite($ponteiro, "<vBC>0.00</vBC>");
fwrite($ponteiro, "<vICMS>0.00</vICMS>");
fwrite($ponteiro, "<vBCST>0.00</vBCST>");
fwrite($ponteiro, "<vST>0.00</vST>");
fwrite($ponteiro, "<vProd>$totalvendas</vProd>");
fwrite($ponteiro, "<vFrete>0.00</vFrete>");
fwrite($ponteiro, "<vSeg>0.00</vSeg>");
fwrite($ponteiro, "<vDesc>0.00</vDesc>");
fwrite($ponteiro, "<vII>0.00</vII>");
fwrite($ponteiro, "<vIPI>0.00</vIPI>");
fwrite($ponteiro, "<vPIS>$claculopisnota</vPIS>");
fwrite($ponteiro, "<vCOFINS>$claculoicmsnota</vCOFINS>");
fwrite($ponteiro, "<vOutro>0.00</vOutro>");
fwrite($ponteiro, "<vNF>$totalvendas</vNF>");
fwrite($ponteiro, "</ICMSTot>");
fwrite($ponteiro, "</total>");
fwrite($ponteiro, "<transp>");
fwrite($ponteiro, "<modFrete>9</modFrete>");
fwrite($ponteiro, "</transp>");
fwrite($ponteiro, "<pag>");
fwrite($ponteiro, "<tPag>$formapgto</tPag>");
fwrite($ponteiro, "<vPag>$totalvendas</vPag>");
fwrite($ponteiro, "</pag>");
fwrite($ponteiro, "</infNFe>");
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
            <SignatureValue>ZYZo8lq1RLEo/q6TewsRamDweKn0jEXV29rirKm63b7o6qrSNmSreUKpZOGzwPiZz8xNeLjDdS6g
U1K1FyBup2NhNuqE4X2oE9MahEJEdektHaF/GZDfff9A48GmaNwQo3LQ7Im+KmZLO8kHCX3eZqFz
vqaaPrZjwHtr6ZUj/LMMMbClxaR0M4VfbEtL74YPUVqfKIZC/gcPp2WFlnGtm1rRI//LK3KDEc2v
pJXDWj5sNJaE0z9FjzLZp2lXWAJdyAVHijCpSE4uZeMQrwTj4F8hcO1WwRYd96MwmG1VDfNGM8a6
B/7zD19uU/TE4Ep4Bp9dx3gkOoNtLP5Fyo0fdw==</SignatureValue><KeyInfo><X509Data><X509Certificate>MIIHxzCCBa+gAwIBAgIISGin1tcEtYswDQYJKoZIhvcNAQELBQAwTDELMAkGA1UEBhMCQlIxEzAR
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
MzVDDMRQpJddtajP16cvGjZQe0CSnHuDELN3AQJRntdRqs1uMKGUcUzu3Oa5nJSLx6HygqU4z6Ve</X509Certificate></X509Data></KeyInfo></Signature>');
fwrite($ponteiro, '<protNFe versao="3.00">');
fwrite($ponteiro, "<infProt>");
fwrite($ponteiro, "<tpAmb>1</tpAmb>");
fwrite($ponteiro, "<verAplic>XXXX</verAplic>");
fwrite($ponteiro, "<chNFe>$chave</chNFe>");
fwrite($ponteiro, "<dhRecbto>$formatadata</dhRecbto>");
fwrite($ponteiro, "<nProt>$idpedido</nProt>");
fwrite($ponteiro, "<digVal>$digVal</digVal>");
fwrite($ponteiro, "<cStat>100</cStat>");
fwrite($ponteiro, "<xMotivo>Autorizado o uso da NF-e</xMotivo>");
fwrite($ponteiro, "</infProt>");
fwrite($ponteiro, "</protNFe>");
fwrite($ponteiro, "</NFe>");
fwrite($ponteiro, "</nfeProc>");

// Fecha XML
fclose($ponteiro);


} // Fecha IF($row)


?>
<script>
alert('NFe Gerado - Ambiente Teste Sem Certificado Digital');
document.location.href = ("libs/DanfeNFe.php?nfe=nfce<?php echo $chave; ?>.xml");
</script>
