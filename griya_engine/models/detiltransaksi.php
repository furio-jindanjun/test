<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class Detiltransaksi extends CI_Model {

private $table_name = 'detiltransaksi';

function __construct()
{
   parent::__construct();
}

function get_jumlah($idmaster, $idobat){
	$result = null;
	$this->db->select("jumlah");
	$this->db->where("idobat",$idobat);
	$this->db->where("idmaster",$idmaster);
	$query = $this->db->get($this->table_name);
	if($query->num_rows() > 0){
    		$row = $query->row();
    		$result = $row->jumlah;
	}
	return $result;
}

function add($arrin){
	
	$this->db->insert($this->table_name, $arrin);
}

function edit_jumlah($data){
	
	$this->db->where('idobat', $data['idobat']);
	$this->db->where('idmaster', $data['idmaster']);
	$this->db->update($this->table_name, $data);
}

}
?>