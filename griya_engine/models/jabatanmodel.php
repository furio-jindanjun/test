<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class Jabatanmodel extends CI_Model {
function __construct()
{
   parent::__construct();
}

function get_jabatan($keyword = null, $page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	//$this->db->start_cache();
	
	if($keyword){
		$this->db->like("nama", $keyword);
		$this->db->or_like("ket", $keyword);
	}
	
	$query = $this->db->get('jabatan');
	$result['rowcount'] = $query->num_rows();
	
	//STOP CACHE
	//$this->db->stop_cache();
  	//log_message('error', $result['rowcount'].'---'.$this->db->last_query());
  	
	if($keyword){
	    $this->db->like("nama", $keyword);
	    $this->db->or_like("ket", $keyword);
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
	$query = $this->db->get('jabatan');
	if ($query->num_rows() > 0){
		$result['rows'] = $query->result_array();	
	}
	
	//log_message('error', $result.'---'.$this->db->last_query());
	return $result;
}

function get_code($idcode){
  $result = null;
  $this->db->where('ket',$idcode);
  $query = $this->db->get('jabatan');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function get_nama($idname){
  $result = null;
  $this->db->where('nama',$idname);
  $query = $this->db->get('jabatan');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function search_jabatan($keyword = NULL){
  $result = array();
  if($keyword){
  	$this->db->where('jabatan.id', $keyword);
  }
  $this->db->select('jabatan.id');
  $this->db->join('pegawai','jabatan.id = pegawai.idjabatan');
  $query = $this->db->get('jabatan');
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  //log_message('error','name: '. $name .'resultna: '.$this->db->last_query());
  return $result;
}


function get_next_id(){
	$result = 1;
	$this->db->select_max('id');
	
	$query1 = $this->db->get('jabatan');
	if ($query1->num_rows() > 0){
		$result = ($query1->row()->id) + 1;
	}	
	return $result;
}

function add($arrin){
	
	$arrin['id'] = $this->get_next_id();
	$arrin['activated'] = 1;
  //$arrin['tgl'] = date('y-m-d');
	//log_message('error', $idsubject.'---'.$this->db->last_query());
	$this->db->insert('jabatan', $arrin);
  return $arrin['id'];
}

function edit($data){	
	$this->db->where('id', $data['id']);
	$this->db->update('jabatan', $data); 
	//log_message('error', $data['id'].'---'.$this->db->last_query());
}

function list_jabatan(){
	$result = array();
	$this->db->select('id, nama');
	$this->db->from('jabatan');
	$query = $this->db->get();
	if ($query->num_rows() > 0){
		foreach($query->result_array() as $jabatan){
			$result[$jabatan['id']] = $jabatan['nama'];
		}
	}	
	return $result;
}

function reactivate($idsubject){	
	$this->db->where('id', $idsubject);
	$this->db->update('jabatan', array('activated'=>'1')); 
}

function get_data_by_id($idkat){
  $result = null;
  $this->db->where('id',$idkat);
  $query = $this->db->get('jabatan');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}


function get_jabatan_by_pegawai_id($id){
	
	$result = null;
	$this->db->join('jabatan','jabatan.id = pegawai.idjabatan');
	$this->db->where('pegawai.id',$id);
	$this->db->select('jabatan.nama');
	$query = $this->db->get('pegawai');
	if($query->num_rows() > 0){
		$resultar = $query->row_array();
		$result = $resultar['nama']; 
	}
	  
	return $result;
}

function revoke($idsubject){	
	$this->db->where('id', $idsubject);
	$this->db->update('jabatan', array('activated'=>'0')); 
	//log_message('error', $idsubject.'---'.$this->db->last_query());
}
function delete($idsubject){	
	$this->db->delete('jabatan', array('id' => $idsubject)); 
}

}
?>