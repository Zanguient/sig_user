<?php
     $link_glpi = mysqli_connect(
    '40.40.0.26',
    'sig',
    'Unimed.2018',
    'glpiweb');

if (!$link_glpi) {
    printf("Problemas na conexão: %s\n", mysqli_connect_error());
    exit;
}
?>