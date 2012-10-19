<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php class historistokobatkamarobatmodel extends CI_Model {
function __construct()
{
   parent::__construct();
}

function get_historistokobatkamarobat($keyword = null, $page = 1){
	
	$result['rows'] = null;
	$result['rowcount'] = 0;
	$result['curpage'] = $page;
	$result['maxpage'] = $page;
	
	//START CACHE
	//$this->db->start_cache();
	
	if($keyword){
		$this->db->like("ket", $keyword);
	}
	
	$this->db->join('obat','obat.id = historistokobatkamarobat.idobat');
	$query = $this->db->get('historistokobatkamarobat');
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
	
	$this->db->join('obat','obat.id = historistokobatkamarobat.idobat');
	$this->db->select('*, obat.id as idobat, historistokobatkamarobat.id as id');
	$this->db->limit($limit, $limit * ($result['curpage'] - 1));
	$this->db->order_by('historistokobatkamarobat.tgl','ASC');
	$query = $this->db->get('historistokobatkamarobat');
	
	if ($query->num_rows() > 0){
		$result['rows'] = $query->result_array();
	}
	
	//log_message('error', '--historistokobatkamarobat-'.$this->db->last_query());
	//log_message('error', var_export($result,true).'--historistokobatkamarobat-'.$this->db->last_query());
	return $result;
}

function get_historistokobatkamarobat1($keyword = null, $page = 1){
    
    $result['rows'] = null;
    $result['rowcount'] = 0;
    $result['curpage'] = $page;
    $result['maxpage'] = $page;
    
    //START CACHE
    //$this->db->start_cache();
    
    if($keyword){
        $this->db->where("obat.id", $keyword);
    }
    
    $this->db->join('obat','obat.id = historistokobatkamarobat.idobat');
    $query = $this->db->get('historistokobatkamarobat');
    $result['rowcount'] = $query->num_rows();
    
    //STOP CACHE
    //$this->db->stop_cache();
    //log_message('error', $result['rowcount'].'---'.$this->db->last_query());
    
    if($keyword){
        $this->db->where("obat.id", $keyword);
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
    
    $this->db->join('obat','obat.id = historistokobatkamarobat.idobat');
    $this->db->select('*, obat.id as idobat, historistokobatkamarobat.id as id');
    $this->db->limit($limit, $limit * ($result['curpage'] - 1));
    $this->db->order_by('historistokobatkamarobat.tgl','ASC');
    $query = $this->db->get('historistokobatkamarobat');
    
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
                if($rslt){
                    $result['rows'][$item]['rowinfo'] = TRUE;
                    $result['rows'][$item]['pemesan'] = $this->get_name_pegawai($rslt['pemesan']);
                    $result['rows'][$item]['pengirim'] = $this->get_name_pegawai($rslt['pengirim']);
                    $result['rows'][$item]['pengurus'] = $this->get_name_pegawai($rslt['pengurus']);
                    $result['rows'][$item]['ket_tujuan'] = $rslt['ket'];
                }
            }
        }
    }
    
    //log_message('error', '--historistokobatkamarobat-'.$this->db->last_query());
    //log_message('error', var_export($result,true).'--historistokobatkamarobat-'.$this->db->last_query());
    return $result;
}

function query_obat_tok($keyword = NULL){
  $result = null;
  if($keyword){
        $this->db->like("obat.nama", $keyword);
        $this->db->or_like("obat.kode", $keyword);
  }
  
  $this->db->distinct();
  $this->db->join('obat','obat.id = historistokobatkamarobat.idobat');
  $this->db->join('supplier','supplier.id = obat.idsupplier');
  $this->db->select('supplier.nama as namasupplier, obat.nama as nama, obat.id as id, obat.kode as kode, obat.hargajual');
  $this->db->where('obat.activated','1');
  $query = $this->db->get('historistokobatkamarobat');
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  
  //log_message('error', '--result histstokkm: '.var_export($result,true).'----'.$this->db->last_query());
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

function get_id_historistokobatkamarobat($keyword = NUll, $page = 1){
      $result = array();
      $result['rows'] = null;
      $result['rowcount'] = 0;
      $result['curpage'] = $page;
      $result['maxpage'] = $page;
    
      if($keyword){
        $this->db->where('historistokobatkamarobat.id', $keyword);
      }
      
      $this->db->distinct();
      $this->db->select('obat.id as idobat, nama, kode');
      $this->db->join('obat','obat.id = historistokobatkamarobat.idobat');
      $query = $this->db->get('historistokobatkamarobat');
      $result['rowcount'] = $query->num_rows();
      
      if($keyword){
        $this->db->where('historistokobatkamarobat.id', $keyword);
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
      $this->db->limit($limit, $limit * ($result['curpage'] - 1));
      $this->db->join('obat','obat.id = historistokobatkamarobat.idobat');
      $query = $this->db->get('historistokobatkamarobat');
      
      if($query->num_rows() > 0){
        $result['rows'] = $query->result_array();
      }
      //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
      return $result;
}

function get_code($idcode){
  $result = null;
  $this->db->where('kode',$idcode);
  $query = $this->db->get('historistokobatkamarobat');
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
  $this->db->join('obat','obat.id = historistokobatkamarobat.idobat');
  //$this->db->select('*, obat.id as idobat, historistokobatkamarobat.id as id');
  $query = $this->db->get('historistokobatkamarobat');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
  return $result;
}

function search_historistokobatkamarobat($keyword = NULL){
  $result = array();
  if($keyword){
    $this->db->where('historistokobatkamarobat.id', $keyword);
  }
  $query = $this->db->get('historistokobatkamarobat');
  if($query->num_rows() > 0){
    $result = $query->result_array();
  }
  //log_message('error','---name: '. var_export($result,true) .'--resultna: '.$this->db->last_query());
  return $result;
}

function get_next_id(){
	$result = 1;
	$this->db->select_max('id');
	
	$query1 = $this->db->get('historistokobatkamarobat');
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
	$query1 = $this->db->get('historistokobatkamarobat');
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
           // log_message('error', 'ONOK: '.$saldonya);
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
            //log_message('error', ' $arrin[saldo]: '. $arrin['saldo'].' |$arrin[kredit]: '.$arrin['kredit'].' |$arrin[debet]: '.$arrin['debet']);
    }else{
        $arrin['saldo'] = $arrin['kredit'];
        //log_message('error', 'ORA ONOK');
    }
	$this->db->insert('historistokobatkamarobat', $arrin);
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

function get_data_by_id($idkat){
  $result = null;
  $this->db->where('historistokobatkamarobat.id',$idkat);
  $query = $this->db->get('historistokobatkamarobat');
  if($query->num_rows() > 0){
    $result = $query->row_array();
  }
  //log_message('error', var_export($result, true).'---'.$this->db->last_query());

  return $result;
}

function subtract_saldo($idobat, $jumlah, $ket){
	
	$saldo = 0;
	$this->db->select('saldo');
	$this->db->where('idobat', $idobat);
	$this->db->order_by('historistokobatkamarobat.tgl','DESC');
	$this->db->limit(1);
  	$query = $this->db->get('historistokobatkamarobat');
  	if($query->num_rows() > 0){
    	$row = $query->row_array();
    	$saldo = $row['saldo'];
  	}
  	$saldo = $saldo - $jumlah;
  	
  	/*
  	$this->load->helper('date');
  	$timestamp = time();
	$timezone = 'UP7';
	$daylight_saving = TRUE;
	
	$tgl = unix_to_human(gmt_to_local($timestamp, $timezone, $daylight_saving), TRUE, 'us');
	*/
  	$tgl = date('Y-m-d H:i:s');
  	
  	$arrin = array(
  		'idobat' => $idobat,
  		'tgl' => $tgl,
  		'debet' => $jumlah,
  		'kredit' => 0,
  		'saldo' => $saldo,
  		'ket' => $ket
  	);
  	return $this->add($arrin);
}

function add_saldo($idobat, $jumlah, $ket){
	
	$saldo = 0;
	$this->db->select('saldo');
	$this->db->where('idobat', $idobat);
	$this->db->order_by('historistokobatkamarobat.tgl','DESC');
	$this->db->limit(1);
  	$query = $this->db->get('historistokobatkamarobat');
  	if($query->num_rows() > 0){
    	$row = $query->row_array();
    	$saldo = $row['saldo'];
  	}
  	$saldo = $saldo + $jumlah;
  	
  	$tgl = date('Y-m-d H:i:s');
  	
  	$arrin = array(
  		'idobat' => $idobat,
  		'tgl' => $tgl,
  		'debet' => 0,
  		'kredit' => $jumlah,
  		'saldo' => $saldo,
  		'ket' => $ket
  	);
  	return $this->add($arrin);
}

function return_trans_stock($idtrans){
	
	$this->db->select('idobat,debet');
	$this->db->like("ket", 'Keluar dari Transaksi');
	$this->db->like("ket", $idtrans);
	$query = $this->db->get('historistokobatkamarobat');
	$arrq = $query->result_array(); 
	foreach ($arrq as $row){
	   	$this->add_saldo($row['idobat'], $row['debet'], '[Retur dari Transaksi #'.$idtrans.']');
	}
}

function lastStock($idobat){
      $result = null;
      $this->db->select('idobat, (sum( kredit ) - sum( debet )) AS saldonya');
      $this->db->where('idobat', $idobat);
      //$this->db->limit($limit, $limit * ($result['curpage'] - 1));
      $query = $this->db->get('historistokobatkamarobat');
      
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
    $this->db->order_by('historistokobatkamarobat.tgl','DESC');
    $this->db->limit(1);
    $query = $this->db->get('historistokobatkamarobat');
    
    if($query->num_rows() > 0){
        $result = $query->row_array();
    }
    //log_message('error', 'viewLastStock------'.var_export($result, true).'---'.$this->db->last_query());
    return $result['saldo'];
}

}
?>