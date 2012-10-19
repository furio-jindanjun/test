<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
class permintaanobat extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $editmode = false;
private $editiduser = null;
private $title = 'permintaanobat';
private $title_ = 'Permintaan Obat';  
private $titledb = 'permintaanobatmodel';

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
        $this->session->set_flashdata('adminfrom', '/permintaanobat');
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
	$this->dheader['selMenu'] = 'office';
	$this->dheader['pageTitle'] = 'Managemen '.ucfirst(strtolower($this->title_));
	
	$this->dheader['cssFiles'] = array('mavsuggest.css',$this->title.'.css','datepicker.css');
  	$this->dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js','Picker.js','Picker.Attach.js','Picker.Date.js','Meio.Mask.js','Meio.Mask.Fixed.js','Meio.Mask.Extras.js');
	$this->dheader['jsText'] = 'window.addEvent("domready", function(){
	                                       init_numonly();
											new DatePicker($("tgl"), {
                                                pickerClass: "datepicker",
                                                allowEmpty: false,
                                                format: "%d-%b-%Y",onSelect: function(date){
                                                            $("tgl_tmp").set("value", date.format("%Y-%m-%d"));
                                                          }
                                            });		
                                            
                                            new DatePicker($("edittgl"), {
                                                pickerClass: "datepicker",
                                                allowEmpty: false,
                                                format: "%d-%b-%Y",onSelect: function(date){
                                                            $("edittgl_tmp").set("value", date.format("%Y-%m-%d"));
                                                          }
                                            });    
                                            
                                             var obatSuggest = onSuggest.pass(["obat",["idobat"], ["id"]]);
                                            predict_obat = new MavSuggest.Request.JSON({
                                                "elem": "obat",
                                                "url":"'.base_url().'ajaxquery/obat",
                                                "requestVar": "obat", 
                                                "singleMode": true,
                                                "onSelect": obatSuggest
                                            });
                  
                                            var editObatSuggest = onSuggest.pass(["editobat",["editidobat"], ["editid"]]);
                                            predict_editObat = new MavSuggest.Request.JSON({
                                                "elem": "editobat",
                                                "url":"'.base_url().'ajaxquery/obat/edit",
                                                "requestVar": "editobat", 
                                                "singleMode": true,
                                                "onSelect": editObatSuggest
                                            });
                                            
                                            chgBlur("obat","Cari Disini", ["idobat"], [""]); 	
                                            stopEnter("frmadd");		
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
	
	$this->load->helper('date');
	
	$titledb = $this->titledb;
	//$titfunc = 'get_'.$this->title;
	//$rsJabatan = $this->$titledb->$titfunc();
	$data['input_list_add'] = array(
				'tgl' => array('type' => 'text', 'title' => 'Tanggal', 'value'=> mdate('%d-%M-%Y'), 'class'=>'buttoncal'),
				'obat' => array('type' => 'text', 'title' => 'Obat', 'value'=> 'Cari Disini'),
				'jumlah' => array('type' => 'text', 'title' => 'Jumlah', 'value'=>'', 'class'=>'numonly'),
				'ket' => array('type' => 'textarea', 'title' => 'Keterangan', 'value'=> '')
	);
	$data['input_list_hidden_add'] = array(
	            'tgl_tmp' => array('value'=> mdate('%Y-%m-%d')),
	            'idobat' => array('value'=> '')
	);
	
	$data['input_list_edit'] = array(
				'edittgl' => array('type' => 'text', 'title' => 'Tanggal', 'value'=> '', 'class'=>'edittgl buttoncal'),
				'editobat' => array('type' => 'text', 'title' => 'Obat', 'value'=> 'Cari Disini', 'class'=>'editobat'),
				'editjumlah' => array('type' => 'text', 'title' => 'Jumlah', 'value'=>'', 'class'=>'editjumlah numonly'),
				'editket' => array('type' => 'textarea', 'title' => 'Keterangan', 'value'=> '', 'class'=>'editket')
	);
	
	$data['input_list_hidden_edit'] = array(
				'editid' => array('value'=>'','class'=>'editid'),
				'editidobat' => array('value'=> '', 'class'=>'editidobat'),
				'edittgl_tmp' => array('value'=>'','class'=>'edittgl')
	);
	
	$data['columnHeaders'] = array(
		array('header_title'=>'Tanggal', 'field_name'=> 'tgl', 'class'=>'rowhead', 'width'=>'25%', 'rowinfo' => false),
		array('header_title'=>'Nama Obat', 'field_name'=> 'nama', 'width'=>'25%', 'rowinfo' => false),
		array('header_title'=>'Jumlah', 'field_name'=> 'jumlah', 'width'=>'25%', 'rowinfo' => false),
		array('header_title'=>'Keterangan', 'field_name'=> 'ket', 'width'=>'20%', 'rowinfo' => false)
	);
	
	$data['editdelid'] = 'id'; 
	
	$data['rowInfoBtns'] = NULL;
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
	$data['addtitle'] = ucfirst(strtolower($this->title_)). ' Baru';
	$data['edittitle'] = 'Ubah '. ucfirst(strtolower($this->title_));
	
	//$titfunc1 = 'search_'.$this->title;
	//$data['nonaktif'] = $this->$titledb->$titfunc1();
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
  		
      	if($data['frmaction'] == 'add' && isset($flashData['tgl'])){
        	$data['input_list_add']['tgl']['value'] = $flashData['tgl'];
        	$data['input_list_add']['obat']['value'] = $flashData['obat'];
			$data['input_list_add']['jumlah']['value'] = $flashData['jumlah'];
        	$data['input_list_add']['ket']['value'] = $flashData['ket'];
      	}elseif($data['frmaction'] == 'edit' && isset($flashData['editid'])){
	        $data['input_list_hidden_edit']['editid']['value'] = $flashData['editid'];
            $data['input_list_edit']['edittgl']['value'] = $flashData['edittgl'];
            $data['input_list_hidden_edit']['editidobat']['value'] = $flashData['editidobat'];
			$data['input_list_edit']['editjumlah']['value'] = $flashData['editjumlah'];
            $data['input_list_edit']['editket']['value'] = $flashData['editket'];
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
    	$this->form_validation->set_rules('tgl', 'Tanggal', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('obat', 'Obat', 'trim|required|callback_search_check|xss_clean');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required|numeric|xss_clean');
    	$this->form_validation->set_rules('ket', 'Ketetangan', 'trim|required|xss_clean');	
    }else{
        $this->editmode = true;
        $this->editid = $this->input->post('editid');
        $this->form_validation->set_rules('edittgl', 'Tanggal', 'trim|required|xss_clean');
        $this->form_validation->set_rules('editobat', 'Obat', 'trim|required|callback_search_check|xss_clean');
		$this->form_validation->set_rules('editjumlah', 'Jumlah', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('editket', 'Ketetangan', 'trim|required|xss_clean');
    }
    $is_passed = $this->form_validation->run();
    
    if($action == 'add') {
        $flashData['message'] = 'Gagal menambah '. $this->title .' baru.';
        $flashData['messageClass'] = "error";
        $flashData['tgl'] = $this->input->post('tgl_tmp');
        $flashData['obat'] = $this->input->post('obat');
        $flashData['idobat'] = $this->input->post('idobat');
		$flashData['jumlah'] = $this->input->post('jumlah');
        $flashData['ket'] = $this->input->post('ket');
    }else{
        $flashData['message'] = 'Gagal menambah '. $this->title;
        $flashData['messageClass'] = "error";
        $flashData['edittgl'] = $this->input->post('edittgl_tmp');
        $flashData['editobat'] = $this->input->post('editobat');
        $flashData['editidobat'] = $this->input->post('editidobat');
        $flashData['editjumlah'] = $this->input->post('editjumlah');
        $flashData['editket'] = $this->input->post('editket');
        $flashData['editid'] = $this->input->post('editid');
    }
	$this->form_validation->set_error_delimiters('', '');
    
  	if ($is_passed == FALSE){
  		
    	if($action == 'add') {

        	$flashData['errors'] = array(
        		'tgl'=>form_error('tgl'),
        		'obat'=>form_error('obat'),
        		'jumlah'=>form_error('jumlah'),
        		'ket'=>form_error('ket')
        	);
        }else{
            $flashData['errors'] = array(
                'edittgl'=>form_error('edittgl'),
                'editobat'=>form_error('editobat'),
                'editjumlah'=>form_error('editjumlah'),
                'editket'=>form_error('editket')
            );
        }
	  	
  	}
  	else{
  		
  		if($action == 'add') {
    			$this->$titledb->add(array(
    				'tgl'=> $flashData['tgl'],
    				'idobat'=> $flashData['idobat'],
    				'jumlah'=> $flashData['jumlah'],
    				'ket'=> $flashData['ket']
    			));
    			
    			$flashData = NULL;
    			$flashData['message'] = 'Berhasil menambah '.$this->title.' baru.';
        		$flashData['messageClass'] = "success";
    		
        }else{
			if($temp_id = $this->$titledb->get_data_by_id($flashData['editid'])){
				
	            $this->$titledb->edit(array(
	                'id'=>  $flashData['editid'],
	                'idobat'=> $flashData['editidobat'],
	                'tgl'=> $flashData['edittgl'],
	                'jumlah'=> $flashData['editjumlah'],
	                'ket'=> $flashData['editket']
	            ));
			
	            
	            $flashData = NULL;
	            $flashData['message'] = 'Berhasil merubah data';
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
    redirect('permintaanobat');
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
        if($pengeluaranklinik = $this->$titledb->get_data_by_id($str)){
            if($pengeluaranklinik['id'] != $this->editid){
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


function search_check($str){
    if((strtolower($str) == 'cari disini')||($str == '')){
        $this->form_validation->set_message('search_check', '%s harus diisi');
        return FALSE;
    }else{
        return TRUE;
    }
}



}
?>