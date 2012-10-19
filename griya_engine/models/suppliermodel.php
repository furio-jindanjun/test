<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class Suppliermodel extends CI_Model {
function __construct()
{
   parent::__construct();
}

function get_supplier($keyword = null, $page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	//$this->db->start_cache();
	
	if($keyword){
		$this->db->like("nama", $keyword);
		$this->db->or_like("kode", $keyword);
	}
	
	$query = $this->db->get('supplier');
	$result['rowcount'] = $query->num_rows();
	
	//STOP CACHE
	//$this->db->stop_cache();
  	//log_message('error', $result['rowcount'].'---'.$this->db->last_query());
  	
	if($keyword){
    $this->db->like("nama", $keyword);
    $this->db->or_like("kode", $keyword);
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
	
	$this->db->limit($limit, $limit * ($result['curpage'] - 1));
	$this->db->order_by('id','DESC');
	$query = $this->db->get('supplier');
	if ($query->num_rows() > 0){
		$result['rows'] = $query->result_array();	
	}
	
	//log_message('error', $result.'---'.$this->db->last_query());
	return $result;
}

function get_supplier_by_name($keyword){
  $result = array();
  if($keyword){
    $this->db->like('supplier.nama', $keyword);
    $this->db->or_like("kode", $keyword);
  }
  $this->db->select('id,nama,kode');
  $query = $this->db->get('supplier');
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  //log_message('error','name: '. $name .'resultna: '.$this->db->last_query());
  return $result;
}

function get_nama($idname){
  $result = null;
  $this->db->where('nama',$idname);
  $query = $this->db->get('supplier');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function search_supplier($keyword = NULL){
  $result = array();
  if($keyword){
    $this->db->where('supplier.id', $keyword);
  }
  $this->db->select('supplier.id');
  $this->db->join('obat','supplier.id = obat.idsupplier');
  $query = $this->db->get('supplier');
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  //log_message('error','name: '. $name .'resultna: '.$this->db->last_query());
  return $result;
}

function get_next_id(){
	$result = 1;
	$this->db->select_max('id');
	
	$query1 = $this->db->get('supplier');
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
	$query1 = $this->db->get('supplier');
	if ($query1->num_rows() > 0){
		$result = (preg_replace("/[^0-9]/", '', $query1->row()->kode));
	}	
	//log_message('error', $query1->row()->kode.'---'.$this->db->last_query());
	return intval($result)+1;
}

function add($arrin){
	
	$arrin['id'] = $this->get_next_id();
	$arrin['activated'] = 1;
	$arrin['kode'] = substr($arrin['nama'],0,1).$this->get_next_code(substr($arrin['nama'],0,1));
	//substr($arrin['nama'],0,1).$arrin['id'];
  //$arrin['tgl'] = date('y-m-d');
	
	$this->db->insert('supplier', $arrin);
	//log_message('error', $arrin['nama'].'---'.$this->db->last_query());
	return $arrin['id'];
}

function edit($data,$nextid = FALSE){
	
	if($nextid){
		$data['kode'] = substr($data['nama'],0,1).$this->get_next_code(substr($data['nama'],0,1));	
	}
	
	$this->db->where('id', $data['id']);
	$this->db->update('supplier', $data); 
}

function get_data_by_id($idkat){
  $result = null;
  $this->db->where('id',$idkat);
  $query = $this->db->get('supplier');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function delete($idsubject){	
	$this->db->delete('supplier', array('id' => $idsubject)); 
}

function reactivate($idsubject){	
	$this->db->where('id', $idsubject);
	$this->db->update('supplier', array('activated'=>'1')); 
}

function revoke($idsubject){	
	$this->db->where('id', $idsubject);
	$this->db->update('supplier', array('activated'=>'0')); 
	//log_message('error', $idsubject.'---'.$this->db->last_query());
}

}
?>