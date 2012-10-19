<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
class Tindakan extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $editmode = false;
private $editiduser = null;
private $title = 'tindakan'; 
private $titledb = 'tindakanmodel';

function __construct(){
	parent::__construct();
	if($this->tank_auth->is_logged_in() ){
        $this->load->model($this->titledb);
        $jabatan = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id()); 
        $this->allowed_level = $this->config->item('allowed_level_pegawai');
        $this->allowed_level_admin = $this->config->item('allowed_level_pegawai_admin');
        
        if(in_array($jabatan,$this->allowed_level)){
            
            $this->dheader['userId']  = $this->tank_auth->get_user_id();
            $this->dheader['userName']  = $this->tank_auth->get_username();
            $this->dheader['jabatan']  = $jabatan;
            $this->dheader['bodyId'] = 'body-request';
            $this->dheader['selMenu'] = 'request';  
        }else{
            $this->session->set_flashdata('results', array('message'=>'You do not have access level to the previous page, please login using appropriate credentials', 'messageClass'=>'error'));
            redirect('login/logout');
        }
        
    }else{
        $this->session->set_flashdata('adminfrom', '/tindakan');
        $this->session->set_flashdata('results', array('message'=>'Your session is expired, you need to login again.', 'messageClass'=>'updated'));
        redirect('login');
    }
}
  
function index(){
	$this->browse();
} 
 
function browse($page = 1){
	
	$data['userId']  = $this->tank_auth->get_user_id();
    $data['userName']  = $this->tank_auth->get_username();
    $data['jabatan']  = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id());
    
	$this->dheader['bodyId'] = 'body-'.$this->title;
	$this->dheader['selMenu'] = 'inventory';
	$this->dheader['pageTitle'] = 'Managemen '.ucfirst(strtolower($this->title));
	
	$this->dheader['cssFiles'] = array('mavsuggest.css',$this->title.'.css');
  	$this->dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js','Meio.Mask.js','Meio.Mask.Fixed.js','Meio.Mask.Extras.js');
	$this->dheader['jsText'] = 'window.addEvent("domready", function(){
											init_numonly();					
							});';
	//$rsIata = null;

	//log_message('error',var_export($data['rsIata'],true));
	$data['keyword'] = null;
	$data['curpage'] = $page;
	$data['results_per_page'] = $this->config->item('results_per_page');
	
	$data['url_add'] = $this->title."/save/add";
	$data['add_saveable'] = true;
	$data['url_edit'] = $this->title."/save/edit";
	$data['edit_saveable'] = true;
	$data['url_browse'] = $this->title.'/browse/';
	$data['url_filter'] = $this->title.'/filter';
	
	$titledb = $this->titledb;
	//$titfunc = 'get_'.$this->title;
	//$rsJabatan = $this->$titledb->$titfunc();
	$data['input_list_add'] = array(
				'nama' => array('type' => 'text', 'title' => 'Nama', 'value'=>'' ),
				'harga' => array('type' => 'text', 'title' => 'Harga', 'value'=>'', 'class'=>'numonly'),
				'ket' => array('type' => 'textarea', 'title' => 'Ket', 'value'=> '')
	);
	$data['input_list_hidden_add'] = array();
	
	$data['input_list_edit'] = array(
				'editnama' => array('type' => 'text', 'title' => 'Nama', 'value'=>'', 'class'=>'editnama'),
				'editharga' => array('type' => 'text', 'title' => 'Harga', 'value'=>'', 'class'=>'editharga numonly'),
				'editket' => array('type' => 'textarea', 'title' => 'Ket', 'value'=> '', 'class'=>'editket')
	);
	
	$data['input_list_hidden_edit'] = array(
				'editid' => array('value'=>'','class'=>'editid')
	);
	
	$data['columnHeaders'] = array(
		array('header_title'=>'Kode', 'field_name'=> 'kode', 'class'=>'rowhead', 'width'=>'25%', 'rowinfo' => false),
		array('header_title'=>'Nama', 'field_name'=> 'nama', 'width'=>'25%', 'rowinfo' => false),
		array('header_title'=>'Harga', 'field_name'=> 'harga', 'width'=>'20%', 'rowinfo' => false),
		array('header_title'=>'Keterangan', 'field_name'=> 'ket', 'width'=>'30%', 'rowinfo' => false) 
	);
	
	$data['editdelid'] = 'id'; 
	
	$data['rowInfoBtns'] = array(
		array('html'=> 'hapus', 'title'=>'Hapus ', 'url'=> $this->title.'/delete/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	$data['rowInfoBtnsAct'] = array(
		array('html'=> 'aktifkan', 'title'=>'Aktifkan kembali ', 'url'=> $this->title.'/activate/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	$data['rowInfoBtnsNon'] = array(
		array('html'=> 'non aktifkan', 'title'=>'Non aktifkan ', 'url'=> $this->title.'/delete/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	
	if($this->session->userdata('keyword_'.$this->title)){
		$keyword = $this->session->userdata('keyword_'.$this->title);
		$data['keyword'] = $keyword;
	}
	
	$titfunc = 'get_'.$this->title;
	$allData = $this->$titledb->$titfunc($data['keyword'], $data['curpage']);
	$data['rowcount'] = $allData['rowcount'];
	$data['curpage'] = $allData['curpage'];
	$data['maxpage'] = $allData['maxpage'];
	$data['allrows'] = $allData['rows'];
	$data['addtitle'] = ucfirst(strtolower($this->title)). ' Baru';
	$data['edittitle'] = 'Ubah '. ucfirst(strtolower($this->title));
	
	$titfunc1 = 'search_'.$this->title;
	$data['nonaktif'] = $this->$titledb->$titfunc1();
	//log_message('error', 'browseeditmode: '.$this->editmode.' - editid: '.$this->editid);
	$data['frmaction'] = 'add';
	
	$flashData = $this->session->flashdata('results');
	if($flashData){
		
		$this->dheader['message'] = $flashData['message'];
		$this->dheader['messageClass'] = $flashData['messageClass'];
		
		$data['frmaction'] = $flashData['frmaction'];
		
		if(isset($flashData['errors'])){    
       	$data['errors'] = $flashData['errors'];
       	foreach($data['errors'] as $key => $value) {
        	if($value == "") {
           		unset($data['errors'][$key]);
	         }
	       }
	    }
  		
      	if($data['frmaction'] == 'add' && isset($flashData['nama'])){
        	$data['input_list_add']['nama']['value'] = $flashData['nama'];
			$data['input_list_add']['harga']['value'] = $flashData['harga'];
        	$data['input_list_add']['ket']['value'] = $flashData['ket'];
      	}elseif($data['frmaction'] == 'edit' && isset($flashData['editid'])){
	        $data['input_list_hidden_edit']['editid']['value'] = $flashData['editid'];
            $data['input_list_edit']['editnama']['value'] = $flashData['nama'];
			$data['input_list_edit']['editharga']['value'] = $flashData['harga'];
            $data['input_list_edit']['editket']['value'] = $flashData['ket'];
	    }
        
	}
	
	$this->load->view('header',$this->dheader);
	$this->load->view('editor_browse',$data);	
	$this->load->view('footer');
	
}


function save($action = 'add'){
	$titledb = $this->titledb;
    $action = trim(strtolower($action));  
    $this->load->library('form_validation');
    $this->load->helper('ozl');
  	
  	if($action == 'add') {
    	$this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean|callback_nama_check');
		$this->form_validation->set_rules('harga', 'Harga', 'trim|required|xss_clean|numeric');
    	$this->form_validation->set_rules('ket', 'Keterangan', 'trim|xss_clean');	
    }else{
        $this->editmode = true;
        $this->editid = $this->input->post('editid');
        $this->form_validation->set_rules('editnama', 'Nama', 'trim|required|xss_clean|callback_nama_check');
		$this->form_validation->set_rules('editharga', 'Harga', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('editket', 'Keterangan', 'trim|xss_clean');       
        $this->form_validation->set_rules('editid', ' ', 'required|xss_clean|callback_id_check');
    }
    $is_passed = $this->form_validation->run();
    
    if($action == 'add') {
        $flashData['message'] = 'Gagal menambah '. $this->title .' baru.';
        $flashData['messageClass'] = "error";
        $flashData['nama'] = $this->input->post('nama');
		$flashData['harga'] = $this->input->post('harga');
        $flashData['ket'] = $this->input->post('ket');
    }else{
        $flashData['message'] = 'Gagal menambah '. $this->title;
        $flashData['messageClass'] = "error";
        $flashData['nama'] = $this->input->post('editnama');
        $flashData['ket'] = $this->input->post('editket');
        $flashData['harga'] = $this->input->post('editharga');
        $flashData['editid'] = $this->input->post('editid');
    }
	$this->form_validation->set_error_delimiters('', '');
    
  	if ($is_passed == FALSE){
  		
    	if($action == 'add') {

        	$flashData['errors'] = array(
        		'nama'=>form_error('nama'),
        		'harga'=>form_error('harga'),
        		'ket'=>form_error('ket')
        	);
        }else{
            $flashData['errors'] = array(
                'editnama'=>form_error('editnama'),
                'editharga'=>form_error('editharga'),
                'editket'=>form_error('editket')
            );
        }
	  	
  	}
  	else{
  		
  		if($action == 'add') {
    			$this->$titledb->add(array(
    				'nama'=> $flashData['nama'],
    				'harga'=> $flashData['harga'],
    				'ket'=> $flashData['ket']
    			));
    			
    			$flashData = NULL;
    			$flashData['message'] = 'Berhasil menambah '.$this->title.' baru.';
        		$flashData['messageClass'] = "success";
    		
        }else{
			if($temp_id = $this->$titledb->get_data_by_id($flashData['editid'])){
				$chg_code = FALSE;
				if(substr($flashData['nama'],0,1)!=substr($temp_id['nama'],0,1)){
					$chg_code = TRUE;
				}
	            $this->$titledb->edit(array(
	                'id'=>  $flashData['editid'],
	                'nama'=> $flashData['nama'],
	                'harga'=> $flashData['harga'],
	                'ket'=> $flashData['ket']
	            ),$chg_code);
			
	            $nama = $flashData['nama'];
	            $flashData = NULL;
	            $flashData['message'] = 'Berhasil merubah '.$nama;
	            $flashData['messageClass'] = "success";
	        	$action = 'add';
			}
        }
  	}
    
    if($action == 'add') {
  	     $flashData['frmaction'] = 'add';
  	}else{
  	     $flashData['frmaction'] = 'edit';
  	}
	//log_message('error','disave: '.var_export($flashData,true));
    $this->session->set_flashdata('results', $flashData);
    redirect('tindakan');
}

function delete($iduser){
  	$titledb = $this->titledb;
	if($this->$titledb->get_data_by_id($iduser)){
	   $titfunc1 = 'search_'.$this->title;
	   $datadel = $this->$titledb->$titfunc1($iduser);
	   //var_dump($datadel);
	   if($datadel){	
    		$this->$titledb->revoke($iduser);
			$flashData['message'] = ucfirst(strtolower($this->title)).' berhasil dinon-aktifkan';
	   }else{
	   		$this->$titledb->delete($iduser);
			$flashData['message'] = ucfirst(strtolower($this->title)).' berhasil dihapus';
	   }
	   
       $flashData['messageClass'] = "success";
		 
	}else{
	  $flashData['message'] = 'Gagal menghapus '.$this->title.'. '.$this->title.' tidak valid';
	  $flashData['messageClass'] = "error";
	}
	
	
	$flashData['frmaction'] = 'add';
	$this->session->set_flashdata('results', $flashData);
	redirect($this->title);
    
}


function activate($id){
	$titledb = $this->titledb;
	$rsJab = $this->$titledb->get_data_by_id($id);
	if($rsJab){
		if($rsJab['activated']==0){
			$this->$titledb->reactivate($id);
			$flashData['message'] = ucfirst(strtolower($this->title)).' berhasil diaktifkan kembali';
	        $flashData['messageClass'] = "success";
		}
		else{
			$flashData['message'] = ucfirst(strtolower($this->title)).' gagal diaktifkan';
	        $flashData['messageClass'] = "error";
		}
	}
	$flashData['frmaction'] = 'add';
	$this->session->set_flashdata('results', $flashData);
	redirect($this->title);
}


function filter(){
	
	//set cookies
	//if($this->input->post('keyword')){
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('filter', 'Keyword', 'xss_clean');
		$is_passed = $this->form_validation->run();
		//log_message('error', $this->input->post('pagep').'-------'.$this->input->post('pagej').'-------'.$this->input->post('pagef'));
			
		if ($is_passed == FALSE)
		{
			$flashData['message'] = 'Gagal menyaring dengan <b>'.$this->input->post('filter').'</b><br/>'.validation_errors();
			$flashData['messageClass'] = "error";
			$this->session->set_flashdata('results', $flashData);

		}else{
			$this->session->set_userdata('keyword_'.$this->title, $this->input->post('filter'));
			
		}
	//}
	redirect($this->title.'/browse');
	
}



function empid_check($str){
	$titledb = $this->titledb;
    //log_message('error', 'emp check editmode: '.$this->editmode.' - editid: '.$this->editid);
    if($this->editmode){
        if($tindakan = $this->$titledb->get_data_by_id($str)){
            if($tindakan['id'] != $this->editid){
                $this->form_validation->set_message('empid_check', '%s sudah digunakan');
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
          return TRUE;
        }
    }else{
    	
		
        if($this->$titledb->get_data_by_id($str)){
          $this->form_validation->set_message('empid_check', '%s sudah digunakan');
          if($this->editmode)
          return FALSE;
        }else{
          return TRUE;
        }
    }
}


function nama_check($str){
	$titledb = $this->titledb;
    if($this->editmode){
        if($tindakan = $this->$titledb->get_nama($str)){
            if($tindakan['id'] != $this->editid){
                $this->form_validation->set_message('nama_check', '%s sudah digunakan');
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
          return TRUE;
        }
    }else{
        if($this->$titledb->get_nama($str)){
          $this->form_validation->set_message('nama_check', '%s sudah ada');
          return FALSE;
        }else{
          return TRUE;
        }
    }
}


function id_check($str){
    $titledb = $this->titledb;
    if($this->$titledb->get_data_by_id($str)){
        return TRUE;
    }else{
        $this->form_validation->set_message('id_check', '%s tidak valid');
        return FALSE;
    }
}


}
?>