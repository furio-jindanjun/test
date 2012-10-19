<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class kirimklinikdanreturmodel extends CI_Model {
function __construct()
{
   parent::__construct();
}

function get_kirimklinikdanretur($keyword = null, $page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	//$this->db->start_cache();
	
	if($keyword){
		$this->db->like("ket", $keyword);
	}
	
	$this->db->join('obat','obat.id = kirimklinikdanretur.idobat');
	$query = $this->db->get('kirimklinikdanretur');
	$result['rowcount'] = $query->num_rows();
	
	//STOP CACHE
	//$this->db->stop_cache();
  	//log_message('error', $result['rowcount'].'---'.$this->db->last_query());
  	
	if($keyword){
        $this->db->like("ket", $keyword);
    }
  	//$this->config->load("aat",true);
	//$aat = $this->config->item('aat');
	$limit = $this->config->item('results_per_page');

	//log_message('error', '---'.$limit);
	if($result['rowcount']>0){
		$result['maxpage'] = intval(ceil($result['rowcount']/$limit));
	}else{
		$result['maxpage'] = 0;
	}
	if($page > $result['maxpage']){
		$result['curpage'] = $result['maxpage'];
	}
	if($result['curpage']<1){
		$result['curpage'] = 1;
	}
	
	$this->db->join('obat','obat.id = kirimklinikdanretur.idobat');
	$this->db->select('*, obat.id as idobat, kirimklinikdanretur.id as id');
	$this->db->limit($limit, $limit * ($result['curpage'] - 1));
	$this->db->order_by('kirimklinikdanretur.tgl','ASC');
	$query = $this->db->get('kirimklinikdanretur');
	
	if ($query->num_rows() > 0){
		$result['rows'] = $query->result_array();
	}
	
	//log_message('error', '--kirimklinikdanretur-'.$this->db->last_query());
	//log_message('error', var_export($result,true).'--kirimklinikdanretur-'.$this->db->last_query());
	return $result;
}

function get_kirimklinikdanretur1($keyword = null, $page = 1){
    
    $result['rows'] = null;
    $result['rowcount'] = 0;
    $result['curpage'] = $page;
    $result['maxpage'] = $page;
    
    //START CACHE
    //$this->db->start_cache();
    
    if($keyword){
        $this->db->where("obat.id", $keyword);
    }
    
    $this->db->join('obat','obat.id = kirimklinikdanretur.idobat');
    $query = $this->db->get('kirimklinikdanretur');
    $result['rowcount'] = $query->num_rows();
    
    //STOP CACHE
    //$this->db->stop_cache();
    //log_message('error', $result['rowcount'].'---'.$this->db->last_query());
    
    if($keyword){
        $this->db->where("obat.id", $keyword);
    }
    //$this->config->load("aat",true);
    //$aat = $this->config->item('aat');
    $limit = $this->config->item('results_per_page');

    //log_message('error', '---'.$limit);
    if($result['rowcount']>0){
        $result['maxpage'] = intval(ceil($result['rowcount']/$limit));
    }else{
        $result['maxpage'] = 0;
    }
    if($page > $result['maxpage']){
        $result['curpage'] = $result['maxpage'];
    }
    if($result['curpage']<1){
        $result['curpage'] = 1;
    }
    
    $this->db->join('obat','obat.id = kirimklinikdanretur.idobat');
    $this->db->select('*, obat.id as idobat, kirimklinikdanretur.id as id');
    $this->db->limit($limit, $limit * ($result['curpage'] - 1));
    $this->db->order_by('kirimklinikdanretur.tgl','ASC');
    $query = $this->db->get('kirimklinikdanretur');
    
    if ($query->num_rows() > 0){
        $result['rows'] = $query->result_array();
    }
    
    //log_message('error', '--kirimklinikdanretur-'.$this->db->last_query());
    //log_message('error', var_export($result,true).'--kirimklinikdanretur-'.$this->db->last_query());
    return $result;
}

function get_id_kirimklinikdanretur($keyword = NUll, $page = 1){
      $result = array();
      $result['rows'] = null;
      $result['rowcount'] = 0;
      $result['curpage'] = $page;
      $result['maxpage'] = $page;
    
      if($keyword){
        $this->db->where('kirimklinikdanretur.id', $keyword);
      }
      
      $this->db->distinct();
      $this->db->select('obat.id as idobat, nama, kode');
      $this->db->join('obat','obat.id = kirimklinikdanretur.idobat');
      $query = $this->db->get('kirimklinikdanretur');
      $result['rowcount'] = $query->num_rows();
      
      if($keyword){
        $this->db->where('kirimklinikdanretur.id', $keyword);
      }
      
      $limit = $this->config->item('results_per_page');

        //log_message('error', '---'.$limit);
        if($result['rowcount']>0){
            $result['maxpage'] = intval(ceil($result['rowcount']/$limit));
        }else{
            $result['maxpage'] = 0;
        }
        if($page > $result['maxpage']){
            $result['curpage'] = $result['maxpage'];
        }
        if($result['curpage']<1){
            $result['curpage'] = 1;
        }
      
      $this->db->distinct();
      $this->db->select('obat.id as idobat, nama, kode');
      $this->db->limit($limit, $limit * ($result['curpage'] - 1));
      $this->db->join('obat','obat.id = kirimklinikdanretur.idobat');
      $query = $this->db->get('kirimklinikdanretur');
      
      if($query->num_rows() > 0){
        $result['rows'] = $query->result_array();
      }
      //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
      return $result;
}

function get_code($idcode){
  $result = null;
  $this->db->where('idhistoridepo',$idcode);
  $query = $this->db->get('kirimklinikdanretur');
  $this->db->join('pegawai','pegawai.id = kirimklinikdanretur.pemesan');
  //$this->db->join('pegawai','pegawai.id = kirimklinikdanretur.pengurus');
  //$this->db->join('pegawai','pegawai.id = kirimklinikdanretur.pengirim');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function get_nama($keyword){
  $result = null;
  if($keyword){
        $this->db->like("obat.nama", $keyword);
        $this->db->or_like("obat.kode", $keyword);
    }
  $this->db->join('obat','obat.id = kirimklinikdanretur.idobat');
  //$this->db->select('*, obat.id as idobat, kirimklinikdanretur.id as id');
  $query = $this->db->get('kirimklinikdanretur');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
  return $result;
}

function search_kirimklinikdanretur($keyword = NULL){
  $result = array();
  if($keyword){
    $this->db->where('kirimklinikdanretur.id', $keyword);
  }
  $query = $this->db->get('kirimklinikdanretur');
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
  return $result;
}

function get_next_id(){
	$result = 1;
	$this->db->select_max('id');
	
	$query1 = $this->db->get('kirimklinikdanretur');
	if ($query1->num_rows() > 0){
		$result = ($query1->row()->id) + 1;
	}	
	return $result;
}

function get_next_code($char){
	$result = 0;
	//$string = 'a101';
	//echo preg_replace("/[^0-9]/", '', $string);
	$this->db->select('kode');
	$this->db->where('left(kode,1)',$char);
	$this->db->order_by('kode', 'desc'); 
	$query1 = $this->db->get('kirimklinikdanretur');
	if ($query1->num_rows() > 0){
		$result = (preg_replace("/[^0-9]/", '', $query1->row()->kode));
	}	
	//log_message('error', $query1->row()->kode.'---'.$this->db->last_query());
	return intval($result)+1;
}

function add($arrin){
	
	$arrin['id'] = $this->get_next_id();
	
	$this->db->insert('kirimklinikdanretur', $arrin);
	//log_message('error', 'arrkirimklinikdanretur: '. var_export($arrin,true));
	return $arrin['id'];
}

function get_data_by_id($idkat){
  $result = null;
  $this->db->where('kirimklinikdanretur.id',$idkat);
  $query = $this->db->get('kirimklinikdanretur');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  //log_message('error', var_export($result, true).'---'.$this->db->last_query());

  return $result;
}

}
?>