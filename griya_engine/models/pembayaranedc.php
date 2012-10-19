<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class Pembayaranedc extends CI_Model {

private $table_name = 'pembayaranedc';

function __construct()
{
   parent::__construct();
}

function _query_cache_browse($keyword){
    if($keyword){
        $this->db->like("customer.nama", $keyword);
        $this->db->or_like("pegawai.nama", $keyword);
    }
    
    //$this->db->select("mastertransaksi.id,mastertransaksi.kode,tgl,customer.nama as namapasien,pegawai.nama as namapegawai,pegawai.id as idpegawai,sisa,total");
    $this->db->from($this->table_name);
    $this->db->join("mastertransaksi","mastertransaksi.id = $this->table_name.idmaster");
    $this->db->join("customer","customer.id = mastertransaksi.idpasien");
}

function get_browse($keyword = null, $curdate,$page = 1){
    
    $result['rows'] = null;
    $result['rowcount'] = 0;
    $result['curpage'] = $page;
    $result['maxpage'] = $page;
    
    //START CACHE
    $this->db->start_cache();
    
    //$this->db->like("tgl", $curdate, 'after');
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
    $this->db->order_by("$this->table_name.tgl", "DESC");
    
    $query = $this->db->get();
    
    if ($query->num_rows() > 0){
        $result['rows'] = $query->result_array();   
    }
    
    $this->db->flush_cache();
    
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

function add($arrin){
	$arrin['id'] = $this->get_next_id();
	$this->db->insert($this->table_name, $arrin);
}

}
?>