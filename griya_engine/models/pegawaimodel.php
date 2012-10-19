<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class pegawaimodel extends CI_Model {
function __construct(){
   parent::__construct();
}

function get_pegawai($keyword = null, $page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	//$this->db->start_cache();
	
	if($keyword){
	    $this->db->like("jabatan.nama", $keyword);
	    $this->db->or_like("kode", $keyword);
	    $this->db->or_like("pegawai.nama", $keyword);
		$this->db->or_like("alamat", $keyword);
	}
	
	//$this->db->join('as_produsen','as_produsen.idprodusen = pegawai.iddetilprodusen');
	$this->db->join('jabatan','jabatan.id = pegawai.idjabatan');
	
	$query = $this->db->get('pegawai');
	$result['rowcount'] = $query->num_rows();
	
	//STOP CACHE
	//$this->db->stop_cache();
  	//log_message('error', $result['rowcount'].'---'.$this->db->last_query());
  	
	if($keyword){
	    $this->db->like("jabatan.nama", $keyword);
	    $this->db->or_like("kode", $keyword);
	    $this->db->or_like("pegawai.nama", $keyword);
		$this->db->or_like("alamat", $keyword);
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
	
	//$this->db->join('as_produsen','as_produsen.idprodusen = pegawai.iddetilprodusen');
  	$this->db->join('jabatan','jabatan.id = pegawai.idjabatan');
  
	$this->db->limit($limit, $limit * ($result['curpage'] - 1));
	$this->db->order_by('pegawai.id','DESC');
	
	$this->db->select('pegawai.*, jabatan.nama as namajabatan');
	
	$query = $this->db->get('pegawai');
	if ($query->num_rows() > 0){
		$result['rows'] = $query->result_array();	
		
	}
	
	//log_message('error', $result.'---'.$this->db->last_query());
	return $result;
}

function get_name($idcode){
  $result = null;
  $this->db->where('nama',$idcode);
  $query = $this->db->get('pegawai');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}


function add($arrin){
	//$arrin['id'] = $this->get_next_id();
  	//$arrin['tgl'] = date('y-m-d');
	//log_message('error', $idsubject.'---'.$this->db->last_query());
	//$arrin['kode'] = $arrin['id'].substr($arrin['namapegawai'],0,1);
	$this->db->insert('pegawai', $arrin);
	
	//$arrin['idlogin'] = $this->get_next_id_login();
  	//log_message('error', $idsubject.'---'.$this->db->last_query());
  	/*
  	$this->db->insert('login', array(
	    'id'=>$arrin['idlogin'],
	    'idpegawai'=>$arrin['id'],
	    'uname'=>$arrin['uname'],
	    'pass'=>$arrin['pass']
	  ));
	 */
	
	return $arrin['id'];
}

function edit($arrin){	
	$this->db->where('id', $arrin['id']);
	$this->db->update('pegawai', array(
	       'kode'=>$arrin['kode'],
           'gelar'=>$arrin['gelar'],
           'nama'=>$arrin['nama'],
           'idjabatan'=>$arrin['idjabatan'],
           'alamat'=>$arrin['alamat'],
           'kota'=>$arrin['kota'],
           'email'=>$arrin['email'],
           'hp'=>$arrin['hp'],
           'tlp'=>$arrin['tlp'],
           'norek1'=>$arrin['norek1'],
           'namabank1'=>$arrin['namabank1'],
           'norek2'=>$arrin['norek2'],
		   'namabank1'=>$arrin['namabank2'],
           'activated'=>$arrin['activated']           
	));
	$aktif = 0;
	if(strtolower($arrin['activated']) == 'y'){
		$aktif = 1;
	}
	$this->db->where('id', $arrin['id']);
    $this->db->update('users', array(
      'activated'=>$aktif
    ));
}

function get_pegawai_by_id($idkat){
  $result = null;
  $this->db->join('jabatan','jabatan.id = pegawai.idjabatan');
  $this->db->select('*, pegawai.nama as namapegawai');
  $this->db->where('pegawai.id',$idkat);
  $query = $this->db->get('pegawai');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  //log_message('error', var_export($result,true));
  return $result;
}

function is_kode_exists($kode, $id){
	
	$result = false;
  	$this->db->where('LOWER(kode)',strtolower($kode));
  	if($id>0){
		$this->db->where('id !=', $id);
  	}
	$query = $this->db->get('pegawai');
	//log_message('error',$this->db->last_query());
	if($query->num_rows() > 0){
		$result = true;
	}
	  
	return $result;
}

function check_email($email,$id){
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		if($id>0){
			$this->db->where('id !=', $id);
		}
		$query = $this->db->get('users');
		return $query->num_rows() == 0;
}

function delete($idsubject){	
	$this->db->where('id', $idsubject);
	$this->db->update('pegawai', array('activated'=>0));
}

}
?>