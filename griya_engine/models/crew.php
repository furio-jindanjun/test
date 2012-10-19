<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class Crew extends CI_Model {

private $table_name = "as_crew";
private $primary_key = "id";

function __construct(){
	// Call the Model constructor
    parent::__construct();
}

function _query_cache_browse($keyword){
	if($keyword){
		$this->db->like("employeeid", $keyword);
		$this->db->or_like("nama", $keyword);
		$this->db->or_like("iatacode", $keyword);
		$this->db->or_like("user_email", $keyword);
		$this->db->or_like("namajabatan", $keyword);
	}
	
	$this->db->select("as_crew.*, user_email as email, namajabatan, iatacode, activated");
	$this->db->from($this->table_name);
	$this->db->join("as_jabatan","as_jabatan.idjabatan = $this->table_name.idjabatan");
	$this->db->join("as_users","as_users.user_id = $this->table_name.$this->primary_key");
	$this->db->join("as_branch","as_branch.iduser = $this->table_name.$this->primary_key", 'left');
}


function _query_order_browse(){
	$this->db->order_by("nama", "ASC");
}


function get_browse($keyword = null, $page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	$this->db->start_cache();
	
	$this->_query_cache_browse($keyword);
	
	//STOP CACHE
	$this->db->stop_cache();
  	
  	//log_message('error', $query->num_rows().'---'.$this->db->last_query());
  	$query = $this->db->get();
	$result['rowcount'] = $query->num_rows();
		
	$limit = $this->config->item('results_per_page');
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
	$this->_query_order_browse();
	
	$query = $this->db->get();
	
	if ($query->num_rows() > 0){
		$result['rows'] = $query->result_array();	
	}
	
	$this->db->flush_cache();
	
	return $result;
}


function get_crews_by_name($keyword = null){
	$result = array();
	//$this->db->order_by("nama", "ASC");
	
	$strquery ='SELECT as_crew.id,nama,namajabatan FROM as_crew,as_jabatan
WHERE as_crew.idjabatan = as_jabatan.idjabatan';
	if($keyword){
		$strquery .=' AND nama LIKE "%'.$this->db->escape_like_str($keyword).'%"';
	}
	$query = $this->db->query($strquery);
	if ($query->num_rows() > 0){
		$result = $query->result_array();	
	}	
	//log_message('error', '----as_crew---------'.$this->db->last_query());
	return $result;

}

function get_crew_by_nama($keyword = null){
	$keyword = trim(strtoupper($keyword));
	$result = array();
	//$this->db->order_by("nama", "ASC");
	
	$strquery ='SELECT as_crew.id,nama FROM as_crew
WHERE UPPER(nama) = "'.$this->db->escape_like_str($keyword).'"';
	$query = $this->db->query($strquery);
	if ($query->num_rows() > 0){
		$result = $query->row_array();	
	}	
	//log_message('error', '----as_crew---------'.$this->db->last_query());
	return $result;

}


function get_crews_by_name_branch($keyword = null){
  $result = array();
  //$this->db->order_by("nama", "ASC");
  
  $strquery ='SELECT as_crew.id,namajabatan,nama,nama_bandara,iata_code FROM as_crew,as_jabatan,as_branch,as_iata
WHERE as_crew.id = as_branch.iduser and as_crew.idjabatan = as_jabatan.idjabatan and as_branch.iatacode = as_iata.iata_code';
  if($keyword){
    $strquery .=' AND nama LIKE "%'.$this->db->escape_like_str($keyword).'%"';
  }
  $query = $this->db->query($strquery);
  if ($query->num_rows() > 0){
    $result = $query->result_array(); 
  } 
  //log_message('error', '----as_crew---------'.$this->db->last_query());
  return $result;

}


function get_crews_by_empid($idsched){
	$result = null;
	$this->db->where('employeeid',$idsched);
	$query1 = $this->db->get('as_crew');
	if ($query1->num_rows() > 0){
		$result = $query1->row_array();
	}	
	//log_message('error','---'.$this->db->last_query());
	return $result;
}


function get_crews_by_email($email){
	$result = null;
	$this->db->where('user_email',$email);
	$query1 = $this->db->get('as_users');
	if ($query1->num_rows() > 0){
		$result = $query1->row_array();
	}	
	return $result;
}


function get_crew_by_id($as_crewid){
	$result = null;
	$this->db->where('as_crew.id', $as_crewid);
	$query1 = $this->db->get('as_crew');
	if ($query1->num_rows() > 0){
		$result = $query1->row_array();
	}	
	return $result;
}


function get_jabatan(){
	$result = array();
	$this->db->select('idjabatan, namajabatan');
	$this->db->from('as_jabatan');
	$query = $this->db->get();
	if ($query->num_rows() > 0){
		foreach($query->result_array() as $jabatan){
			$result[$jabatan['idjabatan']] = $jabatan['namajabatan'];
		}
	}	
	return $result;
}


function get_jabatan_by_id($as_crewid){
	$result = '--';
	$this->db->select('namajabatan');
	$this->db->where('as_crew.id', $as_crewid);
	$this->db->from('as_crew');
	$this->db->join('as_jabatan', 'as_crew.idjabatan = as_jabatan.idjabatan');
	$query1 = $this->db->get();
	if ($query1->num_rows() > 0){
		$result = $query1->row()->namajabatan;
	}	
	return $result;
}


function is_crew_connected($id){
    $result = false;
    
    $query1 = $this->db->get_where('as_maintenance', array('idbranch' => $id));
    if ($query1->num_rows() > 0){
        $result = true;
    }
    $this->db->flush_cache();
    
    $this->db->where('idbranch', $id);
    $this->db->or_where('acc', $id);
    $query2 = $this->db->get('as_requestlog');
    if ($query2->num_rows() > 0){
        $result = true;
    }
    $this->db->flush_cache();
    
    $query3 = $this->db->get_where('as_requestmain', array('pekerja' => $id));
    if ($query3->num_rows() > 0){
        $result = true;
    }
    $this->db->flush_cache();
    
    $query4 = $this->db->get_where('as_statasset', array('idbranch' => $id));
    if ($query4->num_rows() > 0){
        $result = true;
    }
    $this->db->flush_cache();
    
    return $result;
}


function add($arrin){
	
	//$arrin[$this->primary_key] = $nid = $this->get_next_id();
	$this->db->insert($this->table_name, $arrin);
  	//return $nid;
}


function edit_crew($data){   
    $this->db->where($this->primary_key, $data[$this->primary_key]);
    $this->db->update($this->table_name, $data); 
}

function edit_user($data){   
    $this->db->where('user_id', $data['user_id']);
    $this->db->update('as_users', $data); 
}

function delete($idcrew){   
    $this->db->delete('as_branch', array('iduser' => $idcrew));
    $this->db->delete('as_users', array('user_id' => $idcrew));
    $this->db->delete('as_crew', array('id' => $idcrew));
    $this->db->delete('as_user_autologin', array('user_id' => $idcrew));
    $this->db->delete('as_user_profiles', array('user_id' => $idcrew)); 
}

}
?>