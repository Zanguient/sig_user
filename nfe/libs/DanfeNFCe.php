<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('libs/NFe/DanfeNFCeNFePHP.class.php');


$saida = $_REQUEST['o'];

if (!isset($_REQUEST['o'])) {
    $saida = 'pdf';
}
$getnfe = $_GET['nfe'];
$arq = "../xml/$getnfe";
if (is_file($arq)) {
    $docxml = file_get_contents($arq);
    $danfe = new DanfeNFCeNFePHP($docxml, 'images/logo.jpg', 0);
    $id = $danfe->montaDANFE(true);
    $teste = $danfe->printDANFE($saida, $id.'.pdf', 'I');
}
exit();
