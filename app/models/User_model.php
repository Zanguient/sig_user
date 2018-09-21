<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    
    /**************************************************/
    
      public function getAllEquipeByUser($user) {
        
         $this->db->select("projetos.id as id_projeto, projetos.projeto as projeto")
            ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')
            ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner')
             ->join('projetos', 'equipes.projeto = projetos.id', 'inner');
         
        $q = $this->db->get_where('membros_equipe', array('users_setores.usuario' => $user, 'projetos.status' => 'ATIVO'));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     public function getAllEquipeByUserDistinct($user) {
        
          $this->db->select("projetos.id as id_projeto, projetos.projeto as projeto")
            ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')
            ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner')
             ->join('projetos', 'equipes.projeto = projetos.id', 'inner')
            //->group_by('projetos.id')     
            ->distinct();
         
        $q = $this->db->get_where('membros_equipe', array('users_setores.usuario' => $user, 'projetos.status' => 'ATIVO'));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    public function getAgendaProjetos($projeto)
    {
        $date_hoje = date('Y-m-d H:i:s');
        $prazo =  date('Y-m-d', strtotime("0 days",strtotime($date_hoje))); 
        
        $prazo_max =  date('Y-m-d', strtotime("+10 days",strtotime($date_hoje))); 
        
        $ordem = 'asc';
        $campo = 'start';
        
         $this->db->select('*')
         ->order_by($campo, $ordem);
      
      
         $q = $this->db->get_where('calendar', array('projeto' => $projeto, 'start >=' => $prazo, 'start <=' => $prazo_max));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * PEGA AS PERÍODO DE COMPETENCIA  DO USUÁRIO
     */
     public function getPeriodoHEByUsuario($usuario)
    {
       
       $this->db->select("*")
       ->distinct();
       $q = $this->db->get_where('periodo_he', array('user_id' => $usuario));  
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     public function getPeriodoHEById($id)
    {
       
      
       $q = $this->db->get_where('periodo_he', array('id' => $id));  
       
      if ($q->num_rows() > 0) {
            
            return $q->row();
        }
        return FALSE;
    }
    
    
      public function getPeriodo_detalhesHEById($id)
    {
       
      
       $q = $this->db->get_where('periodo_he_registros', array('id' => $id));  
       
      if ($q->num_rows() > 0) {
            
            return $q->row();
        }
        return FALSE;
    }
    
     public function getDetalhesPeriodoHEByIdPeriodo($id_periodo)
    {
       $this->db->select("*")
      // 
        ->order_by('mes', 'ASC')
        ->order_by('dia', 'ASC');        
       $q = $this->db->get_where('periodo_he_registros', array('id_periodo' => $id_periodo));  
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function get_total_registro_data($periodo, $mes, $dia) {
        
         $this->db->select('count(*) as quantidade')
         ->where('dia', $dia)
                ->where('mes', $mes);
         $q = $this->db->get_where('periodo_he_registros', array('id_periodo' => $periodo), 1);
        
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function updateRegistrosHE($id, $data  = array())
    {  
     if ($this->db->update('periodo_he_registros', $data, array('id' => $id))) {
           
         return true;
        }
        return false;
    }
    
         public function deleteRegistrosHE($id)
    {  
             
            if($id){
                   $this->db->delete('periodo_he_registros', array('id' => $id));
            
                  return true;
            }
        return false;
    }
    
    
    public function addRegistrosHE($data  = array())
    {  
     if ($this->db->insert('periodo_he_registros', $data)) {
           
         return true;
        }
        return false;
    }

}
