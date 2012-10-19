<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class Historiresep extends CI_Model {

private $table_name = 'historiresep';

function __construct()
{
   parent::__construct();
}

function add($arrin){
	
	$this->db->insert($this->table_name, $arrin);
}

}
?>