<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class historiklinikmodel extends CI_Model {
function __construct()
{
   parent::__construct();
}

function get_historiklinik($keyword = null, $page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	//$this->db->start_cache();
	
	if($keyword){
		$this->db->like("ket", $keyword);
	}
	
	$this->db->join('obat','obat.id = historiklinik.idobat');
	$query = $this->db->get('historiklinik');
	$result['rowcount'] = $query->num_rows();
	
	//STOP CACHE
	//$this->db->stop_cache();
  	//log_message('error', $result['rowcount'].'---'.$this->db->last_query());
  	
	if($keyword){
        $this->db->like("ket", $keyword);
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
	
	$this->db->join('obat','obat.id = historiklinik.idobat');
	$this->db->select('*, obat.id as idobat, historiklinik.id as id');
	$this->db->limit($limit, $limit * ($result['curpage'] - 1));
	$this->db->order_by('historiklinik.tgl','ASC');
	$query = $this->db->get('historiklinik');
	
	if ($query->num_rows() > 0){
		$result['rows'] = $query->result_array();
	}
	
	//log_message('error', '--historiklinik-'.$this->db->last_query());
	//log_message('error', var_export($result,true).'--historiklinik-'.$this->db->last_query());
	return $result;
}

function get_historiklinik1($keyword = null, $page = 1){
    
    $result['rows'] = null;
    $result['rowcount'] = 0;
    $result['curpage'] = $page;
    $result['maxpage'] = $page;
    
    //START CACHE
    //$this->db->start_cache();
    
    if($keyword){
        $this->db->where("obat.id", $keyword);
    }
    
    $this->db->join('obat','obat.id = historiklinik.idobat');
    $query = $this->db->get('historiklinik');
    $result['rowcount'] = $query->num_rows();
    
    //STOP CACHE
    //$this->db->stop_cache();
    //log_message('error', $result['rowcount'].'---'.$this->db->last_query());
    
    if($keyword){
        $this->db->where("obat.id", $keyword);
    }
    //$this->config->load("aat",true);
    //$aat = $this->config->item('aat');
    
    
    $this->db->join('obat','obat.id = historiklinik.idobat');
    $this->db->select('*, obat.id as idobat, historiklinik.id as id');
    //$this->db->limit($limit, $limit * ($result['curpage'] - 1));
    $this->db->order_by('historiklinik.tgl','ASC');
    $query = $this->db->get('historiklinik');
    
    if ($query->num_rows() > 0){
        $result['rows'] = $query->result_array();
        $this->load->helper('date');
    
        foreach($result['rows'] as $item => $value){
            $result['rows'][$item]['tgl'] = mdate('%d-%M-%Y %h:%i:%s',strtotime($result['rows'][$item]['tgl']));
            if($result['rows'][$item]['debet']){
                //log_message('error', '---debet: '. $result['rows'][$item]['debet']);
                //log_message('error', '---id: '. $result['rows'][$item]['id']);
                $rslt = $this->get_code_kirim($result['rows'][$item]['id']);
                //log_message('error', '---rowskirim: '. var_export($rslt,true));
                $result['rows'][$item]['rowinfo'] = TRUE;
                $result['rows'][$item]['pemesan'] = $this->get_name_pegawai($rslt['pemesan']);
                $result['rows'][$item]['pengirim'] = $this->get_name_pegawai($rslt['pengirim']);
                $result['rows'][$item]['pengurus'] = $this->get_name_pegawai($rslt['pengurus']);
                $result['rows'][$item]['ket_tujuan'] = $rslt['ket'];
            }
        }
        //log_message('error', '---rows: '. var_export($result['rows'],true));
    }
    
    //log_message('error', '--historiklinik-'.$this->db->last_query());
    //log_message('error', var_export($result,true).'--historiklinik-'.$this->db->last_query());
    return $result;
}

function get_name_pegawai($idcode){
  $result = null;
  $this->db->select('nama');
  $this->db->where('id',$idcode);
  $query = $this->db->get('pegawai');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result['nama'];
}

function get_code_kirim($idcode){
  $result = null;
  $this->db->where('idhistoriklinik',$idcode);
  $query = $this->db->get('kirimklinikdanretur');
  //$this->db->join('pegawai','pegawai.id = kirimkamarobat.pemesan');
  //$this->db->join('pegawai','pegawai.id = kirimkamarobat.pengurus');
  //$this->db->join('pegawai','pegawai.id = kirimkamarobat.pengirim');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function query_obat_tok($keyword = NULL){
  $result = null;
  if($keyword){
        $this->db->like("obat.nama", $keyword);
        $this->db->or_like("obat.kode", $keyword);
  }
  
  $this->db->distinct();
  $this->db->join('obat','obat.id = historiklinik.idobat');
  $this->db->join('supplier','supplier.id = obat.idsupplier');
  $this->db->select('supplier.nama as namasupplier, obat.nama as nama, obat.id as id, obat.kode as kode, obat.hargajual');
  $this->db->where('obat.activated','1');
  $query = $this->db->get('historiklinik');
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  
  //log_message('error', '--result histstokkm: '.var_export($result,true).'----'.$this->db->last_query());
  return $result;
}

function get_id_historiklinik($keyword = NUll, $page = 1){
      $result = array();
      $result['rows'] = null;
      $result['rowcount'] = 0;
      $result['curpage'] = $page;
      $result['maxpage'] = $page;
    
      if($keyword){
        $this->db->where('historiklinik.id', $keyword);
      }
      
      $this->db->distinct();
      $this->db->select('obat.id as idobat, nama, kode');
      $this->db->join('obat','obat.id = historiklinik.idobat');
      $query = $this->db->get('historiklinik');
      $result['rowcount'] = $query->num_rows();
      
      if($keyword){
        $this->db->where('historiklinik.id', $keyword);
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
      
      $this->db->distinct();
      $this->db->select('obat.id as idobat, nama, kode');
      //$this->db->limit($limit, $limit * ($result['curpage'] - 1));
      $this->db->join('obat','obat.id = historiklinik.idobat');
      $query = $this->db->get('historiklinik');
      
      if($query->num_rows() > 0){
        $result['rows'] = $query->result_array();
      }
      //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
      return $result;
}

function get_code($idcode){
  $result = null;
  $this->db->where('kode',$idcode);
  $query = $this->db->get('historiklinik');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  
  return $result;
}

function get_nama($keyword){
  $result = null;
  if($keyword){
        $this->db->like("obat.nama", $keyword);
        $this->db->or_like("obat.kode", $keyword);
    }
  $this->db->join('obat','obat.id = historiklinik.idobat');
  //$this->db->select('*, obat.id as idobat, historiklinik.id as id');
  $query = $this->db->get('historiklinik');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
  return $result;
}

function search_historiklinik($keyword = NULL){
  $result = array();
  if($keyword){
    $this->db->where('historiklinik.id', $keyword);
  }
  $query = $this->db->get('historiklinik');
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
  return $result;
}

function get_next_id(){
	$result = 1;
	$this->db->select_max('id');
	
	$query1 = $this->db->get('historiklinik');
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
	$query1 = $this->db->get('historiklinik');
	if ($query1->num_rows() > 0){
		$result = (preg_replace("/[^0-9]/", '', $query1->row()->kode));
	}	
	//log_message('error', $query1->row()->kode.'---'.$this->db->last_query());
	return intval($result)+1;
}

function add($arrin, $arrKirim = NULL){
	
	$arrin['id'] = $this->get_next_id();
	//$arrin['kode'] = substr($arrin['nama'],0,1).$this->get_next_code(substr($arrin['nama'],0,1));
	//substr($arrin['nama'],0,1).$arrin['id'];
    //$arrin['tgl'] = date('y-m-d');
	if($saldonya = $this->viewLastStock($arrin['idobat'])){
            //$arrin['saldo'] = $saldonya['saldonya'];
            //log_message('error', 'ONOK: '.$arrin['saldo']);
            if(!$saldonya){
                 $arrin['saldo'] = $arrin['kredit'];
                 
            }
            else{
                if(!$arrin['kredit']==0){
                    $arrin['saldo'] = $saldonya + $arrin['kredit'];
                }
                elseif(!$arrin['debet']==0){
                    $arrin['saldo'] = $saldonya - $arrin['debet'];
                }
            }
            log_message('error', ' $arrin[saldo]: '. $arrin['saldo']);
    }else{
        $arrin['saldo'] = $arrin['kredit'];
        //log_message('error', 'ORA ONOK');
    }
    
	$this->db->insert('historiklinik', $arrin);
	if($arrKirim){
       $arrKirim['idhistoriklinik'] = $arrin['id'];
       $this->add_kirim($arrKirim);
    }
	return $arrin['id'];
}

function add_kirim($arrin){
    
    $arrin['id'] = $this->get_next_id_kirim();
    
    $this->db->insert('kirimklinikdanretur', $arrin);
    //log_message('error', 'arrkirimklinikdanretur: '. var_export($arrin,true));
    return $arrin['id'];
}

function get_next_id_kirim(){
    $result = 1;
    $this->db->select_max('id');
    
    $query1 = $this->db->get('kirimklinikdanretur');
    if ($query1->num_rows() > 0){
        $result = ($query1->row()->id) + 1;
    }   
    return $result;
}

function edit($data){
	$this->db->where('id', $data['id']);
	$this->db->update('historiklinik', $data); 
}

function get_data_by_id($idkat){
  $result = null;
  $this->db->where('historiklinik.id',$idkat);
  $query = $this->db->get('historiklinik');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  //log_message('error', var_export($result, true).'---'.$this->db->last_query());

  return $result;
}

function delete($idsubject){	
	$this->db->delete('historiklinik', array('id' => $idsubject)); 
}

function reactivate($idsubject){	
	$this->db->where('id', $idsubject);
	$this->db->update('historiklinik', array('activated'=>'1')); 
}

function revoke($idsubject){	
	$this->db->where('id', $idsubject);
	$this->db->update('historiklinik', array('activated'=>'0')); 
	//log_message('error', $idsubject.'---'.$this->db->last_query());
}

function subtract_saldo($idobat, $jumlah, $ket){
	
	$saldo = 0;
	$this->db->select('saldo');
	$this->db->where('idobat', $idobat);
	$this->db->order_by('historiklinik.tgl','DESC');
	$this->db->limit(1);
  	$query = $this->db->get('historiklinik');
  	if($query->num_rows() > 0){
    	$row = $query->row_array();
    	$saldo = $row['saldo'];
  	}
  	$saldo = $saldo - $jumlah;
  	/*
  	$this->load->helper('date');
  	$timestamp = time();
	$timezone = 'UTC';
	$daylight_saving = TRUE;
	
	$tgl = unix_to_human(gmt_to_local($timestamp, $timezone, $daylight_saving), TRUE, 'us');
	*/
	$tgl = date('Y-m-d H:i:s');
  	
  	$arrin = array(
  		'idobat' => $idobat,
  		'tgl' => $tgl,
  		'debet' => $jumlah,
  		'kredit' => NULL,
  		'saldo' => $saldo,
  		'ket' => $ket
  	);
  	return $this->add($arrin);
}


function lastStock($idobat){
      $result = null;
      $this->db->select('idobat, (sum( kredit ) - sum( debet )) AS saldonya');
      $this->db->where('idobat', $idobat);
      //$this->db->limit($limit, $limit * ($result['curpage'] - 1));
      $query = $this->db->get('historiklinik');
      
      if($query->num_rows() > 0){
        $result = $query->result_array();
      }
      
      log_message('error', 'SALdooo------'.var_export($result, true).'---'.$this->db->last_query());
      return $result[0];
      
      
}

function viewLastStock($idobat){
    $result = null;
    $this->db->select('saldo');
    $this->db->where('idobat', $idobat);
    $this->db->where('idobat', $idobat);
    $this->db->order_by('historiklinik.tgl','DESC');
    $this->db->limit(1);
    $query = $this->db->get('historiklinik');
    
    if($query->num_rows() > 0){
        $result = $query->row_array();
    }
    //log_message('error', 'viewLastStock------'.var_export($result, true).'---'.$this->db->last_query());
    return $result['saldo'];
}

}
?>