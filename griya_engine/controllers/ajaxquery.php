<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
class Ajaxquery extends CI_Controller {

function __construct(){
	parent::__construct();
	//$this->load->library('form_validation');
}
  
function index(){
	
}

function supplier(){
 
    $keyword = $this->input->post('supplier');
    //$keyword = $this->input->post('request');
    $result = array();
     // Some simple validation
    if (is_string($keyword) && strlen($keyword) > 2 && strlen($keyword) < 64)
    {
        $this->load->model('suppliermodel');                     
        $result2 = $this->suppliermodel->get_supplier_by_name($keyword);
        //log_message('error','aaa---'.var_export($result2,true));
        foreach($result2 as $namerow){
            $tmp['html'] = $namerow['kode'] . '<div>'. $namerow['nama'] .'</div>';
            $tmp['text'] = json_encode($namerow);
            $tmp['label'] = $namerow['nama'];
            $result[] = $tmp;
            
        }
    }
     
    // Finally the JSON, including the correct content-type
    header('Content-type: application/json');
     
    //echo $keyword; // see NOTE!
    echo json_encode($result);
}

function obat($key=NULL){
 
    $keyword = $this->input->post('obat');
    
    if($key){
        $keyword = $this->input->post('editobat');
    }
    //$keyword = $this->input->post('request');
    $result = array();
     // Some simple validation
    if (is_string($keyword) && strlen($keyword) > 1 && strlen($keyword) < 64)
    {
        $this->load->model('obatmodel');                     
        $result2 = $this->obatmodel->query_obat_tok($keyword);
        if($result2){
            foreach($result2 as $namerow){
                //log_message('error','---aaa---'.$namerow);
                $tmp['html'] = $namerow['kode'] . '<div>'. $namerow['nama'] . ' | Supplier: ' . $namerow['namasupplier'] .'</div>';
                $tmp['text'] = json_encode($namerow);
                $tmp['label'] = $namerow['nama'];
                $result[] = $tmp;
                
            }
        }
    }
     
    // Finally the JSON, including the correct content-type
    header('Content-type: application/json');
     
    //echo $keyword; // see NOTE!
    echo json_encode($result);
}

function obat_stok($asal='historistokobatkamarobatmodel',$key=NULL){
 
    $keyword = $this->input->post('obat');
    
    if($key){
        $keyword = $this->input->post('editobat');
    }
    //$keyword = $this->input->post('request');
    $result = array();
     // Some simple validation
    if (is_string($keyword) && strlen($keyword) > 1 && strlen($keyword) < 64)
    {
        $this->load->model($asal);                     
        $result2 = $this->$asal->query_obat_tok($keyword);
        if($result2){
            foreach($result2 as $namerow){
                //log_message('error','---aaa---'.$namerow);
                $tmp['html'] = $namerow['kode'] . '<div><span style="font-wight:800">'. $namerow['nama'] . '</span> <br/> Supplier: ' . $namerow['namasupplier'] .'</div>';
                $tmp['text'] = json_encode($namerow);
                $tmp['label'] = $namerow['nama'];
                $result[] = $tmp;
                
            }
        }
    }
     
    // Finally the JSON, including the correct content-type
    header('Content-type: application/json');
     
    //echo $keyword; // see NOTE!
    echo json_encode($result);
}

function kasir_search($keyword=NULL){
 
    $keyword = $this->input->post('itemsearch');
    
    $result = array();
     // Some simple validation
    if (is_string($keyword) && strlen($keyword) > 1 && strlen($keyword) < 64)
    {
        $this->load->model('obatmodel');                     
        $result2 = $this->obatmodel->query_obat($keyword);
        if($result2){
            foreach($result2 as $namerow){
                //log_message('error','---aaa---'.$namerow);
                if(isset($namerow['lokasi'])){
	                $namerow['ktipe']= 'obat';
	                $tmp['html'] = '<span>Stok '. $namerow['lokasi'] .' : '.$namerow['stok'].'</span>'.$namerow['nama'] . '<div>'. $namerow['kode'] . ' | Supplier: ' . $namerow['namasupplier'].'<br/> Rp ' . number_format($namerow['hargajual'], 0, ',', '.') .'</div>';
	                $tmp['text'] = json_encode($namerow);
	                $tmp['label'] = $namerow['nama'];
	                $result[] = $tmp;
                }
                
            }
        }
		
		$this->load->model('tindakanmodel');                     
        $result2 = $this->tindakanmodel->query_tindakan($keyword);
        if($result2){
            foreach($result2 as $namerow){
                //log_message('error','---aaa---'.$namerow);
                $namerow['ktipe']= 'tindakan';
                $tmp['html'] = $namerow['kode'] .' | '. $namerow['nama'] . '<div> ' . $namerow['ket'] .'<br/> Rp ' . number_format($namerow['harga'], 0, ',', '.') .'</div>';
                $tmp['text'] = json_encode($namerow);
                $tmp['label'] = $namerow['nama'];
                $result[] = $tmp;
                
            }
        }
    }
     
    // Finally the JSON, including the correct content-type
    header('Content-type: application/json');
     
    //echo $keyword; // see NOTE!
    echo json_encode($result);
}

function pasien(){
 
  $keyword = $this->input->post('namapasien');
  //$keyword = $this->input->post('request');
  $result = array();
   // Some simple validation
  if (is_string($keyword) && strlen($keyword) > 2 && strlen($keyword) < 64)
  {
      $this->load->model('customermodel');           
    $result2 = $this->customermodel->get_customer($keyword);
    foreach($result2['rows'] as $namerow){
      $tmp['html'] = $namerow['nama'] . '<div>'. $namerow['alamat'] . ' <br/> ' . $namerow['kota'] . ' <br/> ' . $namerow['tlp'] .'</div>';
      $tmp['text'] = json_encode($namerow);
      $tmp['label'] = $namerow['nama'];
      $result[] = $tmp;
      
    }
  }
   
  // Finally the JSON, including the correct content-type
    header('Content-type: application/json');
     
    //echo $keyword; // see NOTE!
    echo json_encode($result);
}

function pegawai($key = NULL){
 
  if ($key == 'pemesan'){
    $keyword = $this->input->post('pemesan');
  }
  elseif ($key == 'pengirim'){
    $keyword = $this->input->post('pengirim');
  }
  elseif ($key == 'pengurus'){
    $keyword = $this->input->post('pengurus');
  }
  
  //$keyword = $this->input->post('request');
  $result = array();
   // Some simple validation
  if (is_string($keyword) && strlen($keyword) > 1 && strlen($keyword) < 64)
  {
      $this->load->model('pegawaimodel');           
      $result2 = $this->pegawaimodel->get_pegawai($keyword);
      foreach($result2['rows'] as $namerow){
          $tmp['html'] = $namerow['nama'] . '<div>'. $namerow['alamat'] . ' <br/> ' . $namerow['kota'] . ' <br/> ' . $namerow['tlp'] .'</div>';
          $tmp['text'] = json_encode($namerow);
          $tmp['label'] = $namerow['nama'];
          $result[] = $tmp;
      
      }
      log_message('error', 'keyword:'. $keyword. ' |result:'.var_export($result,true));
  }
   
  // Finally the JSON, including the correct content-type
    header('Content-type: application/json');
    
    //echo $keyword; // see NOTE!
    echo json_encode($result);
}

}
?>