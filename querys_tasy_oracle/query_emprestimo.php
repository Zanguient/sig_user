<?php
/*
 * SALDO DEVEDOR HMU
 */

    $saldo_devedor_hmu = oci_parse($ora_conexao,$query_saldo_devedor_hmu);
    oci_execute($saldo_devedor_hmu, OCI_NO_AUTO_COMMIT);

    // $emprestimo_entrada = array();
    $soma_qt_material = 0;
    $soma_custo_medio = 0;
    
    while (($row_sd_hmu = oci_fetch_array($saldo_devedor_hmu, OCI_BOTH)) != false)
    {
    $qt_material = $row_sd_hmu[0];  
    $custo_medio = $row_sd_hmu[1];
 
   
    }
        
    /*
    * SALDO DEVEDOR HUPL
    */

    $saldo_devedor_hupl = oci_parse($ora_conexao,$query_saldo_devedor_hupl);
    oci_execute($saldo_devedor_hupl, OCI_NO_AUTO_COMMIT);

    // $emprestimo_entrada = array();
    $soma_qt_material_sd_hupl = 0;
    $soma_custo_medio_sd_hupl = 0;
    while (($row_sd_hupl = oci_fetch_array($saldo_devedor_hupl, OCI_BOTH)) != false)
    {
    $qt_material_sd_hupl = $row_sd_hupl[0];  
    $custo_medio_sd_hupl = $row_sd_hupl[1];

    $soma_custo_medio_sd_hupl += $custo_medio_sd_hupl;
    $soma_qt_material_sd_hupl += $qt_material_sd_hupl;
     
    }
    
    /*
    * SALDO RECEBER HMU
    */

    $saldo_receber_hmu = oci_parse($ora_conexao,$query_saldo_receber_hmu);
    oci_execute($saldo_receber_hmu, OCI_NO_AUTO_COMMIT);

    // $emprestimo_entrada = array();
    $soma_qt_material_sr = 0;
    $soma_custo_medio_sr = 0;
    
    while (($row_sr_hmu = oci_fetch_array($saldo_receber_hmu, OCI_BOTH)) != false)
    {
    $qt_material_sr = $row_sr_hmu[0];
    $custo_medio_sr = $row_sr_hmu[1];
    }
    
    
    /*
    * SALDO RECEBER HUPL
    */

    $saldo_receber_hupl = oci_parse($ora_conexao,$query_saldo_receber_hupl);
    oci_execute($saldo_receber_hupl, OCI_NO_AUTO_COMMIT);

    // $emprestimo_entrada = array();
    $soma_qt_material_sr_hupl = 0;
    $soma_custo_medio_sr_hupl = 0;
    
    while (($row_sr_hupl = oci_fetch_array($saldo_receber_hupl, OCI_BOTH)) != false)
    {
     $qt_material_sr_hupl = $row_sr_hupl[0];
    $custo_medio_sr_hupl = $row_sr_hupl[1] ;
  
    
   
    }
   

/*
 * FORNECEDORES A PAGAR
 */

    $stid = oci_parse($ora_conexao,$query_entrada_fornecedor_hmu);
    oci_execute($stid, OCI_NO_AUTO_COMMIT);

    // $emprestimo_entrada = array();
    $total_emprestimo = 0;
    while (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
    {
    $cd_pj = $row[0];  
    $pj_desc = $row[1];
    $numero_emprestimo = $row[2];
    $qtde_itens = $row[3];

    $total_emprestimo = $numero_emprestimo;

    $emprestimo_entrada[] = array("empresa" => $pj_desc, "quantidade" => $qtde_itens );


    }

        ?>