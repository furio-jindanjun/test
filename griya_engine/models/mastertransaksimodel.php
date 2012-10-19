<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class Mastertransaksimodel extends CI_Model {

private $table_name = "mastertransaksi";
private $primary_key = "id";

function __construct(){
	// Call the Model constructor
    parent::__construct();
}

function _query_cache_browse($keyword){
	if($keyword){
		$this->db->like("customer.nama", $keyword);
		$this->db->or_like("pegawai.nama", $keyword);
		$this->db->or_like("mastertransaksi.kode", $keyword);
		$this->db->or_like("mastertransaksi.retur_dari", $keyword);
	}
	
	$this->db->select("mastertransaksi.id,mastertransaksi.kode,tgl,customer.nama as namapasien,pegawai.nama as namapegawai,pegawai.id as idpegawai,sisa,total, batal_oleh, retur_dari");
	$this->db->from($this->table_name);
	$this->db->join("pegawai","pegawai.id = $this->table_name.idpegawai");
	$this->db->join("customer","customer.id = $this->table_name.idpasien");
}

function _query_cache_browse_resep($keyword = null){
    if($keyword){
        $this->db->or_like("mastertransaksi.tgl", $keyword, 'after');
        $this->db->or_like("mastertransaksi.kode", $keyword);
        $this->db->or_like("obat.nama", $keyword);
        $this->db->select("mastertransaksi.id,mastertransaksi.kode,tgl");
        $this->db->join("obat","obat.id = detiltransaksi.idobat");
    }
    
}

function _query_cache_browse_tindakan($keyword = null){
    if($keyword){
        $this->db->or_like("mastertransaksi.tgl", $keyword, 'after');
        $this->db->or_like("mastertransaksi.kode", $keyword);
        $this->db->or_like("tindakan.nama", $keyword);
        $this->db->select("mastertransaksi.id,mastertransaksi.kode,tgl");
        $this->db->join("tindakan","tindakan.id = historiresep.idtindakan");
    }
    
}

function _query_order_browse(){
	$this->db->order_by("tgl", "DESC");
	$this->db->order_by("kode", "DESC");
}

function _query_by_id(){
  $this->db->select("$this->table_name.*, cracc.nama as namaacc, cr.nama");
  $this->db->join("as_crew as cr","cr.id = $this->table_name.idbranch");
  $this->db->join("as_crew as cracc","cracc.id = $this->table_name.acc");
  //$this->db->join("as_barang","as_barang.idbarang=as_requestlog.idbarang", 'left');
}

function get_browse($keyword = null, $curdate,$page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	$this->db->start_cache();
	
	$this->db->like("tgl", $curdate, 'after');
	$this->_query_cache_browse($keyword);
	
	//STOP CACHE
	$this->db->stop_cache();
  	
  	$query = $this->db->get();
  	//log_message('error', $query->num_rows().'---'.$this->db->last_query());
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

function get_browse_resep($idpasien,$keyword = null,$page = 1){
    
    $result['rows'] = null;
    $result['rowcount'] = 0;
    $result['curpage'] = $page;
    $result['maxpage'] = $page;
    
    //START CACHE
    $this->db->start_cache();
    $this->db->where('idpasien', $idpasien);
    $this->db->join("customer","customer.id = $this->table_name.idpasien");
    $this->db->distinct();
    $this->db->select('tgl, mastertransaksi.id, customer.nama as namapasien');
    $this->db->from($this->table_name);
    $this->db->join("detiltransaksi","detiltransaksi.idmaster = $this->table_name.id");
    
    $this->_query_cache_browse_resep($keyword);
    //STOP CACHE
    $this->db->stop_cache();
    
    $query = $this->db->get();
    //log_message('error', $query->num_rows().'---'.$this->db->last_query());
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
    $this->db->order_by("tgl", "DESC");
    
    if($keyword){
        $this->db->select('tgl, mastertransaksi.id, customer.nama as namapasien, obat.nama, obat.kode, detiltransaksi.jumlah');
    }
    else{
        $this->db->select('tgl, mastertransaksi.id, customer.nama as namapasien');
    }
    $this->db->distinct();
    $query = $this->db->get();
    
    if ($query->num_rows() > 0){
        $result['rows'] = $query->result_array();   
        $this->load->helper('date');
    
        foreach($result['rows'] as $item => $value){
            $result['rows'][$item]['tgl'] = '<strong>'.mdate('%d-%M-%Y',strtotime($result['rows'][$item]['tgl'])).'</strong> '.mdate('%h:%i:%s',strtotime($result['rows'][$item]['tgl']));
        }
    }
    
    $this->db->flush_cache();
    //log_message('error', 'hoho: '.var_export($result,true));
    //log_message('error', '---'.$this->db->last_query());
    return $result;
}

function get_browse_tindakan($idpasien,$keyword = null,$page = 1){
    
    $result['rows'] = null;
    $result['rowcount'] = 0;
    $result['curpage'] = $page;
    $result['maxpage'] = $page;
    
    //START CACHE
    $this->db->start_cache();
  
    $this->db->where('idpasien', $idpasien);
    $this->db->join("customer","customer.id = $this->table_name.idpasien");
    $this->db->select('tgl, mastertransaksi.id, customer.nama as namapasien');
    $this->db->from($this->table_name);
    $this->db->join("historiresep","historiresep.idmaster = $this->table_name.id");
    $this->_query_cache_browse_tindakan($keyword);
    //STOP CACHE
    $this->db->stop_cache();
    
    $this->db->distinct();
    $query = $this->db->get();
    //log_message('error', $query->num_rows().'---'.$this->db->last_query());
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
    $this->db->order_by("tgl", "DESC");
    
    if($keyword){
        $this->db->select('tgl, mastertransaksi.id, customer.nama as namapasien, tindakan.nama, tindakan.kode');
    }
    else{
        $this->db->select('tgl, mastertransaksi.id, customer.nama as namapasien');
    }
    
    $this->db->distinct();
    $query = $this->db->get();
    
    if ($query->num_rows() > 0){
        $result['rows'] = $query->result_array();   
        $this->load->helper('date');
    
        foreach($result['rows'] as $item => $value){
            $result['rows'][$item]['tgl'] = '<strong>'.mdate('%d-%M-%Y',strtotime($result['rows'][$item]['tgl'])).'</strong> '.mdate('%h:%i:%s',strtotime($result['rows'][$item]['tgl']));
        }
    }
    
    $this->db->flush_cache();
    log_message('error', 'hoho: '.var_export($result,true));
    log_message('error', '---'.$this->db->last_query());
    return $result;
}

function get_details_tindakan($id){
    $result = null;
    $this->db->select("mastertransaksi.id,  customer.nama as namapasien, pegawai.nama as namapegawai, pegawai.id as idpegawai");
    $this->db->from($this->table_name);
    $this->db->join("pegawai","pegawai.id = $this->table_name.idpegawai");
    $this->db->join("customer","customer.id = $this->table_name.idpasien");
    $this->db->where('mastertransaksi.id',$id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
        $result['masterdata'] = $query->row_array();
        
        $this->db->select("historiresep.*, tindakan.nama, tindakan.kode ,tindakan.id");
        $this->db->from('historiresep');
        $this->db->join("tindakan","tindakan.id = historiresep.idtindakan");
        $this->db->where('idmaster',$id);
        $queryhr = $this->db->get();
        if($queryhr->num_rows() > 0){
            $result['tindakandata'] = $queryhr->result_array();
        }else{
            $result['tindakandata'] = null;
        }
        
    }
    log_message('error','tindakan: '.var_export($result,true));
    return $result;
}

function get_details_obat($id){
    $result = null;
    $this->db->select("mastertransaksi.id,  customer.nama as namapasien, pegawai.nama as namapegawai, pegawai.id as idpegawai");
    $this->db->from($this->table_name);
    $this->db->join("pegawai","pegawai.id = $this->table_name.idpegawai");
    $this->db->join("customer","customer.id = $this->table_name.idpasien");
    $this->db->where('mastertransaksi.id',$id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
        $result['masterdata'] = $query->row_array();
        
        //detiltransaksi
        $this->db->select("detiltransaksi.jumlah, obat.nama, obat.kode, obat.id");
        $this->db->from('detiltransaksi');
        $this->db->join("obat","obat.id = detiltransaksi.idobat");
        $this->db->where('idmaster',$id);
        $querydt = $this->db->get();
        if($querydt->num_rows() > 0){
            $result['obatdata'] = $querydt->result_array();
        }else{
            $result['obatdata'] = null;
        }
        
    }
    return $result;
}


function get_next_id(){
	$result = 1;
	$this->db->select_max('id');
	
	$query1 = $this->db->get($this->table_name);
	if ($query1->num_rows() > 0){
		$result = ($query1->row()->id) + 1;
	}	
	return $result;
}

function get_next_kode(){
	$result = 1;
	$header = 'GA'.date('ymd');
	$this->db->select_max('kode');
	$this->db->like('kode', $header, 'after');
	
	$query1 = $this->db->get($this->table_name);
	if ($query1->num_rows() > 0){
		$result = ($query1->row()->kode);
		$result = intval(substr($result,8))+1;
	}
	
	$result = str_pad($result, 3, "0", STR_PAD_LEFT);
	
	return $header.$result;
	
}


function is_returned($id){
	$result = null;
	$this->db->select("id");
	$this->db->from($this->table_name);
	$this->db->where('retur_dari',$id);
	$query = $this->db->get();
	if ($query->num_rows() > 0){
		$result = $query->result_array();
		$result = $result[0]['id'];	
	}
	return $result;
}

function get_details($id){
	$result = null;
	$this->db->select("mastertransaksi.*,  customer.nama as namapasien, customer.alamat as alamat, customer.kodepos as kodepos, customer.kota as kota, customer.tlp as tlp, customer.hp as hp, pegawai.nama as namapegawai, pegawai.id as idpegawai");
	$this->db->from($this->table_name);
	$this->db->join("pegawai","pegawai.id = $this->table_name.idpegawai");
	$this->db->join("customer","customer.id = $this->table_name.idpasien");
	$this->db->where('mastertransaksi.id',$id);
	$query = $this->db->get();
	if ($query->num_rows() > 0){
		$result['masterdata'] = $query->row_array();
		
		//detiltransaksi
		$this->db->select("detiltransaksi.*, obat.nama, obat.kode, obat.id");
		$this->db->from('detiltransaksi');
		$this->db->join("obat","obat.id = detiltransaksi.idobat");
		$this->db->where('idmaster',$id);
		$querydt = $this->db->get();
		if($querydt->num_rows() > 0){
			$result['obatdata'] = $querydt->result_array();
		}else{
			$result['obatdata'] = null;
		}
		
		//historiresep
		$this->db->select("historiresep.*, tindakan.nama, tindakan.kode ,tindakan.id");
		$this->db->from('historiresep');
		$this->db->join("tindakan","tindakan.id = historiresep.idtindakan");
		$this->db->where('idmaster',$id);
		$queryhr = $this->db->get();
		if($queryhr->num_rows() > 0){
			$result['tindakandata'] = $queryhr->result_array();
		}else{
			$result['tindakandata'] = null;
		}
		
		//pembayaranedc
		$this->db->select("tgl, jumlah, metode, ket, jumlahplustax");
		$this->db->from('pembayaranedc');
		$this->db->where('idmaster',$id);
		$querype = $this->db->get();
		if($querype->num_rows() > 0){
			$result['edcdata'] = $querype->result_array();
		}else{
			$result['edcdata'] = null;
		}
		
	}
	return $result;
}

function get_by_id($id){
	$result = null;
	$this->db->select("mastertransaksi.id, mastertransaksi.kode, tgl, customer.nama as namapasien, 
		pegawai.nama as namapegawai, pegawai.id as idpegawai, sisa, total, batal_oleh, retur_dari");
	$this->db->from($this->table_name);
	$this->db->join("pegawai","pegawai.id = $this->table_name.idpegawai");
	$this->db->join("customer","customer.id = $this->table_name.idpasien");
	$this->db->where('mastertransaksi.id',$id);
	$query = $this->db->get();
	if ($query->num_rows() > 0){
		$result = $query->result_array();	
	}
	return $result;
}

function add($arrin){
	
	$arrin['id'] = $this->get_next_id();
  //$arrin['tgl'] = date('y-m-d');
	//log_message('error', $idsubject.'---'.$this->db->last_query());
	$this->db->insert($this->table_name, $arrin);
	
  	return $arrin['id'];
}

function edit($arrin){	
	$this->db->where('id', $arrin['id']);
	$this->db->update($this->table_name, $arrin); 
}

}
?>