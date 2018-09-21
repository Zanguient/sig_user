<?php
date_default_timezone_set("Brazil/East");
require_once('libs/NFe/DanfeNFePHP.class.php');

$getnfe = $_GET['nfe'];
$arq = "../xml/$getnfe";

if (is_file($arq)) {
    $docxml = file_get_contents($arq);
    $danfe = new DanfeNFePHP($docxml, 'P', 'A4', 'images/logo.jpg', 'I', '');
    $id = $danfe->montaDANFE();
    $teste = $danfe->printDANFE($id.'.pdf', 'I');
}
