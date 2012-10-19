<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
class Jabatan extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $editmode = false;
private $editiduser = null;

function __construct(){
	parent::__construct();
	$this->load->model('jabatanmodel');
}
  
function index(){
	$this->browse();
} 
 
function browse($page = 1){
	
	$this->dheader['bodyId'] = 'body-jabatan';
	$this->dheader['selMenu'] = 'office';
	$this->dheader['pageTitle'] = 'Managemen Jabatan';
	
	$this->dheader['cssFiles'] = array('mavsuggest.css','jabatan.css');
  	$this->dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js');
	$this->dheader['jsText'] = 'window.addEvent("domready", function(){
																
							});';
	//$rsIata = null;

	//log_message('error',var_export($data['rsIata'],true));
	$data['keyword'] = null;
	$data['curpage'] = $page;
	$data['results_per_page'] = $this->config->item('results_per_page');
	
	$data['url_add'] = "jabatan/save/add";
	$data['add_saveable'] = true;
	$data['url_edit'] = "jabatan/save/edit";
	$data['edit_saveable'] = true;
	$data['url_browse'] = 'jabatan/browse/';
	$data['url_filter'] = "jabatan/filter";
	
	
	$rsJabatan = $this->jabatanmodel->get_jabatan();
	$data['input_list_add'] = array(
				'nama' => array('type' => 'text', 'title' => 'Nama', 'value'=>'' ),
				'ket' => array('type' => 'textarea', 'title' => 'Ket', 'value'=> '')
	);
	$data['input_list_hidden_add'] = array();
	
	$data['input_list_edit'] = array(
				'editnama' => array('type' => 'text', 'title' => 'Nama', 'value'=>'', 'class'=>'editnama'),
				'editket' => array('type' => 'textarea', 'title' => 'Ket', 'value'=> '', 'class'=>'editket')
	);
	
	$data['input_list_hidden_edit'] = array(
				'editid' => array('value'=>'','class'=>'editid')
	);
	
	$data['columnHeaders'] = array(
		array('header_title'=>'Nama', 'field_name'=> 'nama', 'class'=>'rowhead', 'width'=>'30%', 'rowinfo' => false),
		array('header_title'=>'Keterangan', 'field_name'=> 'ket', 'width'=>'50%', 'rowinfo' => false)		
	);
	
	$data['editdelid'] = 'id'; 
	
	$data['rowInfoBtns'] = array(
		array('html'=> 'hapus', 'title'=>'Hapus ', 'url'=> 'jabatan/delete/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	$data['rowInfoBtnsAct'] = array(
		array('html'=> 'aktifkan', 'title'=>'Aktifkan kembali ', 'url'=> 'jabatan/activate/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	$data['rowInfoBtnsNon'] = array(
		array('html'=> 'non aktifkan', 'title'=>'Non aktifkan ', 'url'=> 'jabatan/delete/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	
	if($this->session->userdata('keyword_jabatan')){
		$keyword = $this->session->userdata('keyword_jabatan');
		$data['keyword'] = $keyword;
	}
	
	$allData = $this->jabatanmodel->get_jabatan($data['keyword'], $data['curpage']);
	$data['rowcount'] = $allData['rowcount'];
	$data['curpage'] = $allData['curpage'];
	$data['maxpage'] = $allData['maxpage'];
	$data['allrows'] = $allData['rows'];
	$data['addtitle'] = 'Jabatan Baru';
	$data['edittitle'] = 'Edit Jabatan';
	$data['nonaktif'] = $this->jabatanmodel->search_jabatan();
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
        	$data['input_list_add']['ket']['value'] = $flashData['ket'];
      	}elseif($data['frmaction'] == 'edit' && $flashData['editid']){
	        $data['input_list_hidden_edit']['editid']['value'] = $flashData['editid'];
            $data['input_list_edit']['editnama']['value'] = $flashData['nama'];
            $data['input_list_edit']['editket']['value'] = $flashData['ket'];
	    }
        
	}
	
	
	$this->load->view('header',$this->dheader);
	$this->load->view('editor_browse',$data);	
	$this->load->view('footer');
	
}


function save($action = 'add'){
    $action = trim(strtolower($action));  
    $this->load->library('form_validation');
    $this->load->helper('ozl');
  	
  	if($action == 'add') {
    	$this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean|callback_nama_check');
    	$this->form_validation->set_rules('ket', 'Keterangan', 'trim|xss_clean');	
    }else{
        $this->editmode = true;
        $this->editid = $this->input->post('editid');
        $this->form_validation->set_rules('editnama', 'Nama', 'trim|required|xss_clean|callback_nama_check');
        $this->form_validation->set_rules('editket', 'Keterangan', 'trim|xss_clean');       
        $this->form_validation->set_rules('editid', ' ', 'required|xss_clean|callback_id_check');
    }
    $is_passed = $this->form_validation->run();
    
    if($action == 'add') {
        $flashData['message'] = 'Gagal menambah jabatan baru.';
        $flashData['messageClass'] = "error";
        $flashData['nama'] = $this->input->post('nama');
        $flashData['ket'] = $this->input->post('ket');
    }else{
        $flashData['message'] = 'Gagal menambah jabatan.';
        $flashData['messageClass'] = "error";
        $flashData['nama'] = $this->input->post('editnama');
        $flashData['ket'] = $this->input->post('editket');
        $flashData['rek'] = $this->input->post('editrek');
        $flashData['editid'] = $this->input->post('editid');
    }
	$this->form_validation->set_error_delimiters('', '');
    
  	if ($is_passed == FALSE){
  		
    	if($action == 'add') {

        	$flashData['errors'] = array(
        		'nama'=>form_error('nama'),
        		'ket'=>form_error('ket')
        	);
        }else{
            $flashData['errors'] = array(
                'editnama'=>form_error('editnama'),
                'editket'=>form_error('editket')
            );
        }
	  	
  	}
  	else{
  		
  		if($action == 'add') {
    			$this->jabatanmodel->add(array(
    				'nama'=> $flashData['nama'],
    				'ket'=> $flashData['ket']
    			));
				
    			$flashData = null;
    			$flashData['message'] = 'Berhasil menambah jabatan baru.'.substr('abcd',0,1);
        		$flashData['messageClass'] = "success";
    		
        }else{
		
			if ($flashData['editid'] == 1){
				$this->jabatanmodel->edit(array(
                'id'=>  $flashData['editid'],
                'ket'=> $flashData['ket']
            	));	
				$msg = 'keterangan jabatan default';
			}
			else{
				if($this->jabatanmodel->get_jabatan_by_id($flashData['editid'])){
					$this->jabatanmodel->edit(array(
	                'id'=>  $flashData['editid'],
	                'nama'=> $flashData['nama'],
	                'ket'=> $flashData['ket']
	            	));
					$msg = $flashData['nama'];
				}
			}
            
            
            $flashData = null;
            $flashData['message'] = 'Berhasil merubah '.$msg;
            $flashData['messageClass'] = "success";
        	$action = 'add';
        }
		
  	}
    
    if($action == 'add') {
  	     $flashData['frmaction'] = 'add';
  	}else{
  	     $flashData['frmaction'] = 'edit';
  	}
    $this->session->set_flashdata('results', $flashData);
    redirect('jabatan');
}




function delete($iduser){
	
	if($iduser == 1){
		$flashData['message'] = 'Tidak bisa menghapus jabatan superadmin';
	  	$flashData['messageClass'] = "error";
	}
	else{
		if($this->jabatanmodel->get_jabatan_by_id($iduser)){
			$datadel = $this->jabatanmodel->search_jabatan($iduser);
			//var_dump($datadel);
			if($datadel){	
	    		$this->jabatanmodel->revoke($iduser);
				$flashData['message'] = 'Jabatan berhasil dinon-aktifkan';
		   }else{
		   		$this->jabatanmodel->delete($iduser);
				$flashData['message'] = 'Jabatan berhasil dihapus';
		   }
		   
	       $flashData['messageClass'] = "success";
		}else{
		  $flashData['message'] = 'Gagal menghapus jabatan. Jabatan tidak valid';
		  $flashData['messageClass'] = "error";
		}
	}
	
	$flashData['frmaction'] = 'add';
	$this->session->set_flashdata('results', $flashData);
	redirect('jabatan');
    
}

function activate($id){
	$rsJab = $this->jabatanmodel->get_jabatan_by_id($id);
	if($rsJab){
		if($rsJab['activated']==0){
			$this->jabatanmodel->reactivate($id);
			$flashData['message'] = 'Jabatan berhasil diaktifkan kembali';
	        $flashData['messageClass'] = "success";
		}
		else{
			$flashData['message'] = 'Jabatan gagal diaktifkan';
	        $flashData['messageClass'] = "error";
		}
	}
	$flashData['frmaction'] = 'add';
	$this->session->set_flashdata('results', $flashData);
	redirect('jabatan');
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
			$this->session->set_userdata('keyword_jabatan', $this->input->post('filter'));
			
		}
	//}
	redirect('jabatan/browse');
	
}


function empid_check($str){
    //log_message('error', 'emp check editmode: '.$this->editmode.' - editid: '.$this->editid);
    if($this->editmode){
        if($jabatan = $this->jabatanmodel->get_jabatan_by_id($str)){
            if($jabatan['id'] != $this->editid){
                $this->form_validation->set_message('empid_check', '%s sudah digunakan');
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
          return TRUE;
        }
    }else{
    	
		
        if($this->jabatanmodel->get_jabatans_by_id($str)){
          $this->form_validation->set_message('empid_check', '%s sudah digunakan');
          if($this->editmode)
          return FALSE;
        }else{
          return TRUE;
        }
    }
}


function nama_check($str){
    if($this->editmode){
        if($jabatan = $this->jabatanmodel->get_nama($str)){
            if($jabatan['id'] != $this->editid){
                $this->form_validation->set_message('nama_check', '%s sudah digunakan');
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
          return TRUE;
        }
    }else{
        if($this->jabatanmodel->get_nama($str)){
          $this->form_validation->set_message('nama_check', '%s sudah ada');
          return FALSE;
        }else{
          return TRUE;
        }
    }
}


function id_check($str){
    
    if($this->jabatanmodel->get_jabatan_by_id($str)){
        return TRUE;
    }else{
        $this->form_validation->set_message('id_check', '%s tidak vaid');
        return FALSE;
    }
}


}
?>