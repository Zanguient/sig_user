<?php

    date_default_timezone_set("Brazil/East"); // Brasil

    require_once 'conexao.class.php';
    $con = new conexao(); // instancia classe de conxao
    $con->connect(); // abre conexao com o banco

/* NFe Bling Integração API v1 */

if($_GET['pedido'] == '') { 
header("Location: ../"); 

} else { 
$pedido = $_GET['pedido'];

$query_sale = mysql_query("SELECT * FROM sma_sales WHERE id = '$pedido'");
$sales = mysql_fetch_array($query_sale);

$cliente = $sales['customer_id'];

$query_customer = mysql_query("SELECT * FROM sma_companies WHERE id = '$cliente'");
$customer = mysql_fetch_array($query_customer);

// CF4 Tipo de Cliente J ou F

$url = 'http://www.bling.com.br/recepcao.nfe.php';
$xml .= '<?xml version="1.0" encoding="UTF-8"?>
<pedido>
    <cliente>
        <nome>'.$customer[name].'</nome>
        <tipoPessoa>'.$customer[cf4].'</tipoPessoa>
        <cpf_cnpj>'.$customer[vat_no].'</cpf_cnpj>
        <ie_rg>'.$customer[cf1].'</ie_rg>
        <endereco>'.$customer[address].'</endereco>
        <numero>'.$customer[cf2].'</numero>
        <complemento>'.$customer[cf3].'</complemento>
        <bairro>'.$customer[cf5].'</bairro>
        <cep>'.$customer[postal_code].'</cep>
        <cidade>'.$customer[city].'</cidade>
        <uf>'.$customer[state].'</uf>
        <fone>'.$customer[phone].'</fone>
        <email>'.$customer[email].'</email>
    </cliente>
    <transporte>
        <transportadora>EMPRESA BRASILEIRA DE CORREIOS E TELÉGRAFOS - ECT</transportadora>
        <cpf_cnpj>34.028.316/0001-03</cpf_cnpj>
        <ie_rg></ie_rg>
        <endereco></endereco>
        <cidade></cidade>
        <uf></uf>
        <placa></placa>
        <uf_veiculo></uf_veiculo>
        <tipo_frete>D</tipo_frete>
        <qtde_volumes></qtde_volumes>
        <especie>Volumes</especie>
        <numero></numero>
        <peso_bruto>1</peso_bruto>
        <peso_liquido>1</peso_liquido>
        <servico_correios>SEDEX</servico_correios>
    </transporte>
    <dados_etiqueta>
        <nome>'.$customer[name].'</nome>
        <endereco>'.$customer[address].'</endereco>
        <numero>'.$customer[cf2].'</numero>
        <complemento>'.$complemento.'</complemento>
        <bairro>'.$customer[cf5].'</bairro>
        <municipio>'.$customer[city].'</municipio>
        <uf>'.$customer[state].'</uf>
        <cep>'.$customer[postal_code].'</cep>
    </dados_etiqueta>
    <itens>';

$query_bling = mysql_query("SELECT * FROM sma_sales WHERE id = '$pedido'");
while($bling = mysql_fetch_array($query_bling)){

$prdbling = mysql_query("SELECT * FROM sma_sale_items WHERE sale_id = '".$bling['id']."'");
$produtobling = mysql_fetch_array($prdbling);

$prdsbb = mysql_query("SELECT * FROM sma_products WHERE id = '".$produtobling['product_id']."'");
$prds = mysql_fetch_array($prdsbb);

$xml .='<item>
            <codigo>'.$produtobling[product_code].'</codigo>
            <descricao>'.$produtobling[product_name].'</descricao>
            <un>'.$prds[unit].'</un>
            <qtde>'.(int)$produtobling[quantity].'</qtde>
            <vlr_unit>'.$produtobling[unit_price].'</vlr_unit>
            <tipo>P</tipo>
            <peso_bruto>'.($prds[cf2] * (int)$produtobling[quantity]).'</peso_bruto>
            <peso_liq>'.($prds[cf2] * (int)$produtobling[quantity]).'</peso_liq>
            <class_fiscal>'.$prds[cf3].'</class_fiscal>
            <origem>0</origem>
        </item>';
}

$datapagamento = date('d/m/Y');
$xml .= '</itens>
    <vlr_frete>'.$sales[shipping].'</vlr_frete>
    <numero_loja>'.$sales[reference_no].'</numero_loja>
    <obs>Pedido: '.$sales[reference_no].' EXPOS</obs>
</pedido>';

$apiblingkey = API_BLING; 

$data = "apiKey=$apiblingkey&retornaNumeroNota=S&pedidoXML=" . urlencode($xml);

$nferetorna = enviarPedidoREST($url, $data);

 
function enviarPedidoREST($url, $data){
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_POST, 2);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($curl_handle);
    curl_close($curl_handle);
 
    return $response;
}

/* FIM API BLING */ 

$sql_nfe = mysql_query("UPDATE sma_sales SET note='$nferetorna' WHERE id= '$pedido'");
$rs_alterar = mysql_query($sql_nfe);

header("Location: ../sales/view/$pedido"); // redireciona para a listagem 

}


?>