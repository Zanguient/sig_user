<?php 
    $usuario = $this->session->userdata('user_id');
    $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
?>


    <div class="content-wrapper">
        
    <section class="content-header">
      <h1>
        Requisição de Horas
        <small>Lançamento de horas</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= site_url('welcome/home'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
     
    <section class="content">
      
        
        <div class="row">


                <?php
                                
                $meses = array(
                    '1'=>'Janeiro',
                    '2'=>'Fevereiro',
                    '3'=>'Março',
                    '4'=>'Abril',
                    '5'=>'Maio',
                    '6'=>'Junho',
                    '7'=>'Julho',
                    '8'=>'Agosto',
                    '9'=>'Setembro',
                    '10'=>'Outubro',
                    '11'=>'Novembro',
                    '12'=>'Dezembro'
                );
                //competencia
                ?>
        
        <div class="col-md-12">
        <h3>Competência: <?php echo $meses[$competencia->mes].'/'.$competencia->ano; ?></h3>

        <div class="col-lg-12">

                   <div style="text-align: right" class="col-lg-12">
                  
              </div>
                        <?php
                        $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                        echo form_open_multipart("Projetos/Eventos_index_form", $attrib);

                        ?>
                        <div class="portlet-body">
                            <div class="table-responsive">
                                <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                    <thead>
                                     <tr style="background-color: orange; width: 100%;">

                                        <th style="width: 10%;">DATA</th>
                                        <th style="width: 35%;">Descrição</th>
                                        <th style="width: 5%;">Tipo</th>
                                        <th style="width: 5%;"> Início</th>
                                        <th style="width: 5%;"> Fim </th>
                                        <th style="width: 5%;">Saldo</th>
                                        <th style="width: 10%;">Ação</th>
                                        <th style="width: 5%;">H.Extra</th>
                                        <th style="width: 20%;">Opções</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                         <?php
                    $wu4[''] = '';
                    $cont = 1;
                    foreach ($lacamentos as $periodo) {
                        $id_cript = encrypt($periodo->id,'UNIMED');
                        $meses = array(
                            '1'=>'Janeiro',
                            '2'=>'Fevereiro',
                            '3'=>'Março',
                            '4'=>'Abril',
                            '5'=>'Maio',
                            '6'=>'Junho',
                            '7'=>'Julho',
                            '8'=>'Agosto',
                            '9'=>'Setembro',
                            '10'=>'Outubro',
                            '11'=>'Novembro',
                            '12'=>'Dezembro'
                        );

                        $data_registro = $competencia->ano.'-'.$periodo->mes.'-'.$periodo->dia;
                        $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
                        $diasemana_numero = date('w', strtotime($data_registro));
                        $dia_semana = $diasemana[$diasemana_numero];
                        
                        
                        $qtde_registros =  $this->user_model->get_total_registro_data($periodo->id_periodo, $periodo->mes, $periodo->dia );
                        $qtde_reg = $qtde_registros->quantidade;
                        $qtde_colspan = $qtde_reg + 1;
                       ?>   
                            <?php 
                            $tipo = $periodo->tipo_registro;
                            $saldo = calculaTempo($periodo->hora_inicio, $periodo->hora_fim_confirmado);
                            
                            if( $tipo == 'Crédito'){
                                $desc_saldo = $saldo;
                                $cor = "blue";
                            }else if( $tipo == 'Débito'){
                                $desc_saldo = "-".$saldo;
                                $cor = "red";
                            }
                            
                            ?>               

                        <tr  >

                            <td style="width: 10%;"><?php echo $periodo->dia.'/'.substr($meses[$periodo->mes], 0, 3); ?> <small class="label pull-right <?php if(($dia_semana == "Sabado")||($dia_semana == "Domingo")){ ?> bg-orange <?php }else{ ?> bg-gray <?php } ?> "><?php echo '('.$dia_semana.')'; ?></small></td> 
                            <td style="width: 35%;"><?php echo $periodo->descricao; ?> </td>
                            <td style="width: 5%;"><?php echo $tipo; ?> </td>
                            <td style="width: 5%;"><?php echo $periodo->hora_inicio; ?></td> 
                            <td style="width: 5%;"><?php echo $periodo->hora_fim_confirmado; ?> </td>
                            <td style="width: 5%;"><font style="color: <?php echo $cor; ?>"><?php if($periodo->descricao){ echo $desc_saldo; } ?></font></td>    
                            <td style="width: 10%;"><?php if($periodo->id_acao != 0){ echo $periodo->id_acao; }else{ if($periodo->descricao){ echo 'N/A'; } } ?> </td>
                            <td style="width: 5%;"><?php echo $periodo->hora_extra; ?> </td>
                            <td style="width: 20%;" class="center">
                                <?php //if ($qtde_reg == 1){
                                    
                                    if($periodo->descricao){ ?>
                                        <a style="background-color: chocolate; color: #ffffff;" title="Editar Registro" class="btn fa fa-edit" href="<?= site_url('welcome/editarRequisicaoHorasDetalhes/'.$periodo->id); ?>" data-toggle="modal" data-target="#myModal">  </a>
                                        <a style="background-color: green; color: #ffffff;" title="Novo Registro" class="btn fa fa-plus" href="<?= site_url('welcome/novaRequisicaoHorasDetalhes/'.$periodo->id); ?>" data-toggle="modal" data-target="#myModal">  </a>
                                        <a style="background-color: orange; color: #ffffff;" title="Limpar Registro" class="btn fa fa-eraser" href="<?= site_url('welcome/cancelarRequisicaoHorasDetalhes/'.$periodo->id); ?>" >  </a>
                                        
                                <?php 
                                    }else{
                                    ?>
                                        <a style="background-color: green; color: #ffffff;" class="btn fa fa-plus" href="<?= site_url('welcome/editarRequisicaoHorasDetalhes_nova/'.$periodo->id); ?>" data-toggle="modal" data-target="#myModal">  </a>
                                  
                                <?php      
                                    }
                                    
                                ?>
                                        
                                        <?php
                                        if($qtde_reg > 1){
                                        ?>
                                        <a style="background-color: red; color: #ffffff;" title="Apagar Registro" class="btn fa fa-trash" href="<?= site_url('welcome/apagarRequisicaoHorasDetalhes/'.$periodo->id); ?>" >  </a>
                                        
                                        <?php
                                        }
                                        ?>
                            </td>

                        </tr>
                        <?php
                    }
                    ?>
                                    </tbody>
                                </table>


                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.portlet-body -->


                </div>


    </div>
        </div>
    </section>       
    </div>
    

