<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class obatmodel extends CI_Model {
function __construct()
{
   parent::__construct();
}

function get_obat($keyword = null, $page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	//$this->db->start_cache();
	
	if($keyword){
		$this->db->like("obat.nama", $keyword);
		$this->db->or_like("obat.kode", $keyword);
	}
	
	$this->db->join('supplier','supplier.id = obat.idsupplier');
	
	$query = $this->db->get('obat');
	$result['rowcount'] = $query->num_rows();
	
	//STOP CACHE
	//$this->db->stop_cache();
  	//log_message('error', $result['rowcount'].'---'.$this->db->last_query());
  	
	if($keyword){
        $this->db->like("obat.nama", $keyword);
        $this->db->or_like("obat.kode", $keyword);
    }
  	//$this->config->load("aat",true);
	//$aat = $this->config->item('aat');
	$this->db->join('supplier','supplier.id = obat.idsupplier');
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
	
	$this->db->select('*, supplier.nama as namasupplier, obat.nama as namaobat, obat.id as idobat, supplier.id as idsupplier, obat.kode as kodeobat, obat.activated as activated');
	$this->db->limit($limit, $limit * ($result['curpage'] - 1));
	$this->db->order_by('obat.id','DESC');
	$query = $this->db->get('obat');
	if ($query->num_rows() > 0){
		$result['rows'] = $query->result_array();	
	}
	
	//log_message('error', var_export($result,true).'--obat-'.$this->db->last_query());
	return $result;
}

function get_code($idcode){
  $result = null;
  $this->db->where('kode',$idcode);
  $query = $this->db->get('obat');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function get_nama($idname){
  $result = null;
  $this->db->where('nama',$idname);
  $query = $this->db->get('obat');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function search_obat($keyword = NULL){
  $result = array();
  if($keyword){
    $this->db->where('obat.id', $keyword);
  }
  $this->db->select('obat.id');
  $this->db->join('detiltransaksi','detiltransaksi.idobat = obat.id');
  $query = $this->db->get('obat');
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
  return $result;
}

function get_price($id){
	$result = 0;
    $this->db->where('id', $id);
  	$this->db->select('hargajual');
	$query = $this->db->get('obat');
  	if($query->num_rows() > 0){
    	$result = $query->row()->hargajual;
  	}
  	return $result;
}

function query_obat($keyword = NULL){
  $result = null;
  if($keyword){
        $this->db->like("obat.nama", $keyword);
        $this->db->or_like("obat.kode", $keyword);
  }
  
  $this->db->join('supplier','supplier.id = obat.idsupplier');
  $this->db->select('supplier.nama as namasupplier, obat.nama as nama, obat.id as id, obat.kode as kode, obat.hargajual');
  $this->db->where('obat.activated','1');
  $query = $this->db->get('obat');
  if($query->num_rows() > 0){
    $tmpres = $query->result_array();
        
    $newArr = array(); 
    
    foreach($tmpres as $idx => $res){
    
        $this->db->select('saldo');
        $this->db->where('idobat',$res['id']);
        $this->db->order_by('tgl','desc');
        $this->db->limit(1);
        $queryo = $this->db->get('historistokobatkamarobat');
        
        $is_stok_in_kamarobat = false;
        
        if($queryo->num_rows() > 0){
            $row = $queryo->row();
            $is_stok_in_kamarobat = true;
            $res['stok'] = $row->saldo;
            $res['lokasi'] = 'KM';
            $tmpres[$idx] = $res; 
        }
        
        $this->db->select('saldo');
        $this->db->where('idobat',$res['id']);
        $this->db->order_by('tgl','desc');
        $this->db->limit(1);
        $queryp = $this->db->get('historiklinik');
        
        if($queryp->num_rows() > 0){
            $row = $queryp->row();
            if($is_stok_in_kamarobat){
                $newRow = $res;
                $newRow['stok'] = $row->saldo;
                $newRow['lokasi'] = 'A';
                $newArr[] = $newRow;
            }else{
                $res['stok'] = $row->saldo;
                $res['lokasi'] = 'A';
                $tmpres[$idx] = $res;
            } 
        }
        
    }
    $result = array_merge($tmpres, $newArr);
    foreach($result as $c=>$key) {
        $sort_numcie[] = $key['nama'];
    }

    array_multisort($sort_numcie, SORT_STRING, $result);
    
  }
  
  //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
  return $result;
}

function get_next_id(){
	$result = 1;
	$this->db->select_max('id');
	
	$query1 = $this->db->get('obat');
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
	$query1 = $this->db->get('obat');
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
	
	$this->db->insert('obat', $arrin);
	//log_message('error', $arrin['nama']);
	return $arrin['id'];
}

function edit($data,$nextid = FALSE){
	
	if($nextid){
		$data['kode'] = substr($data['nama'],0,1).$this->get_next_code(substr($data['nama'],0,1));	
	}
	
	$this->db->where('id', $data['id']);
	$this->db->update('obat', $data); 
}

function get_data_by_id($idkat){
  $result = null;
  $this->db->join('supplier','supplier.id = obat.idsupplier');
  $this->db->select('*, supplier.nama as namasupplier, obat.nama as namaobat, obat.id as idobat, supplier.id as idsupplier, obat.activated as activated');
  $this->db->where('obat.id',$idkat);
  $query = $this->db->get('obat');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  //log_message('error', var_export($result, true).'---'.$this->db->last_query());

  return $result;
}

function delete($idsubject){	
	$this->db->delete('obat', array('id' => $idsubject)); 
}

function reactivate($idsubject){	
	$this->db->where('id', $idsubject);
	$this->db->update('obat', array('activated'=>'1')); 
}

function revoke($idsubject){	
	$this->db->where('id', $idsubject);
	$this->db->update('obat', array('activated'=>'0')); 
	//log_message('error', $idsubject.'---'.$this->db->last_query());
}

}
?>