<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class Barangmodel extends CI_Model {
function __construct(){
   parent::__construct();
}

function get_barang($keyword = null, $page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	//$this->db->start_cache();
	
	if($keyword){
	    $this->db->like("namabarang", $keyword);
	    $this->db->or_like("kondisi", $keyword);
	    $this->db->or_like("serialnum", $keyword);
		  $this->db->or_like("namaprovider", $keyword);
	    $this->db->or_like("namatipe", $keyword);
	    $this->db->or_like("namaprovider", $keyword);
	    $this->db->or_like("namamanufaktur", $keyword);
	    $this->db->or_like("namakategori", $keyword);
	    $this->db->or_like("kodekategori", $keyword);
	}
	
	//$this->db->join('as_produsen','as_produsen.idprodusen = as_barang.iddetilprodusen');
	$this->db->join('as_tipebarang','as_tipebarang.idtipe = as_barang.idtipe');
	$this->db->join('as_provider','as_provider.idprovider = as_barang.idprovider');
	$this->db->join('as_manufaktur','as_manufaktur.idmanufaktur = as_barang.idmanufaktur');
	$this->db->join('as_kategori','as_kategori.idkategori = as_barang.idkategori');
	$query = $this->db->get('as_barang');
	$result['rowcount'] = $query->num_rows();
	
	//STOP CACHE
	//$this->db->stop_cache();
  	//log_message('error', $result['rowcount'].'---'.$this->db->last_query());
  	
	if($keyword){
    $this->db->like("namabarang", $keyword);
    $this->db->or_like("kondisi", $keyword);
    $this->db->or_like("serialnum", $keyword);
    $this->db->or_like("namaprovider", $keyword);
    $this->db->or_like("namatipe", $keyword);
    $this->db->or_like("namaprovider", $keyword);
    $this->db->or_like("namamanufaktur", $keyword);
    $this->db->or_like("namakategori", $keyword);
    $this->db->or_like("kodekategori", $keyword);
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
	
	//$this->db->join('as_produsen','as_produsen.idprodusen = as_barang.iddetilprodusen');
  $this->db->join('as_tipebarang','as_tipebarang.idtipe = as_barang.idtipe');
  $this->db->join('as_provider','as_provider.idprovider = as_barang.idprovider');
  $this->db->join('as_manufaktur','as_manufaktur.idmanufaktur = as_barang.idmanufaktur');
  $this->db->join('as_kategori','as_kategori.idkategori = as_barang.idkategori');
  
	$this->db->limit($limit, $limit * ($result['curpage'] - 1));
	$this->db->order_by('idbarang','DESC');
	$query = $this->db->get('as_barang');
	if ($query->num_rows() > 0){
		$result['rows'] = $query->result_array();	
	}
	
	//log_message('error', $result.'---'.$this->db->last_query());
	return $result;
}

function get_name($idcode){
  $result = null;
  $this->db->where('namabarang',$idcode);
  $query = $this->db->get('as_barang');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function get_serial($serial){
  $result = null;
  $this->db->where('serialnum',$serial);
  $query = $this->db->get('as_barang');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function search_barang($key){
  $result = array();
  $this->db->select('as_barang.*,namatipe');
  $this->db->like('serialnum',$key);
  $this->db->or_like('namabarang',$key);
  $this->db->from('as_barang');
  $this->db->join("as_tipebarang","as_tipebarang.idtipe = as_barang.idtipe");
  $query = $this->db->get();
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  
  return $result;
}

function get_next_id(){
	$result = 1;
	$this->db->select_max('idbarang');
	
	$query1 = $this->db->get('as_barang');
	if ($query1->num_rows() > 0){
		$result = ($query1->row()->idbarang) + 1;
	}	
	return $result;
}

function get_next_id_inventori(){
  $result = 1;
  $this->db->select_max('idinventori');
  
  $query1 = $this->db->get('as_invetorikantor');
  if ($query1->num_rows() > 0){
    $result = ($query1->row()->idinventori) + 1;
  } 
  return $result;
}

function add($arrin){
	
	$arrin['idbarang'] = $this->get_next_id();
  //$arrin['tgl'] = date('y-m-d');
	//log_message('error', $idsubject.'---'.$this->db->last_query());
	$this->db->insert('as_barang', array(
	         'namabarang'=>$arrin['namabarang'],
           'quantity'=>$arrin['quantity'],
           'serialnum'=>$arrin['serialnum'],
           'status'=>$arrin['status'],
           'tglpembelian'=>$arrin['tglpembelian'],
           'masagaransi'=>$arrin['masagaransi'],
           'kondisi'=>$arrin['kondisi'],
           'idkategori'=>$arrin['idkategori'],
           'idmanufaktur'=>$arrin['idmanufaktur'],
           'idprovider'=>$arrin['idprovider'],
           'iddetilprodusen'=>NULL,
           'idtipe'=>$arrin['idtipe'],
           'idbarang'=>$arrin['idbarang']
	));
	
	$arrin['idinventori'] = $this->get_next_id_inventori();
  //$arrin['tgl'] = date('y-m-d');
  //log_message('error', $idsubject.'---'.$this->db->last_query());
  $this->db->insert('as_invetorikantor', array(
    'idbarang'=>$arrin['idbarang'],
    'iatacode'=>$arrin['iatacode'],
    'idinventori'=>$arrin['idinventori']
  ));
	
  return $arrin['idbarang'];
}

function edit($arrin){	
	$this->db->where('idbarang', $arrin['idbarang']);
	$this->db->update('as_barang', array(
           'namabarang'=>$arrin['namabarang'],
           'quantity'=>$arrin['quantity'],
           'serialnum'=>$arrin['serialnum'],
           'status'=>$arrin['status'],
           'tglpembelian'=>$arrin['tglpembelian'],
           'masagaransi'=>$arrin['masagaransi'],
           'kondisi'=>$arrin['kondisi'],
           'idkategori'=>$arrin['idkategori'],
           'idmanufaktur'=>$arrin['idmanufaktur'],
           'idprovider'=>$arrin['idprovider'],
           'iddetilprodusen'=>NULL,
           'idtipe'=>$arrin['idtipe']
  )); 
	$this->db->where('idbarang', $arrin['idbarang']);
  $this->db->update('as_invetorikantor', array(
  'iatacode'=>$arrin['iatacode'])); 
}

function edit_quantity($data){
  $this->db->where('idbarang', $data['idbarang']);
  $this->db->update('as_barang', $data); 
}

function get_barang_by_id($idkat){
  $result = null;
  $this->db->join('as_tipebarang','as_tipebarang.idtipe = as_barang.idtipe');
  $this->db->join('as_provider','as_provider.idprovider = as_barang.idprovider');
  $this->db->join('as_manufaktur','as_manufaktur.idmanufaktur = as_barang.idmanufaktur');
  $this->db->join('as_kategori','as_kategori.idkategori = as_barang.idkategori');
  $this->db->where('idbarang',$idkat);
  $query = $this->db->get('as_barang');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function delete($idsubject){	
	$this->db->delete('as_barang', array('idbarang' => $idsubject));
	$this->db->delete('as_invetorikantor', array('idbarang' => $idsubject)); 
  $this->db->delete('as_statasset', array('idbarang' => $idsubject));
}

}
?>