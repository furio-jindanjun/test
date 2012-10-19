<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pegawai extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $curid = 0;
private $title = 'pegawai';

function __construct(){
    parent::__construct();
	//$this->tank_auth->create_user('griyaadmin2', '2admin@griyaamana.com', 'abc123', false);
	if($this->tank_auth->is_logged_in() ){

		$this->load->model('pegawaimodel');
	
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
		$this->session->set_flashdata('adminfrom', '/pegawai');
		$this->session->set_flashdata('results', array('message'=>'Your session is expired, you need to login again.', 'messageClass'=>'updated'));
		redirect('login');
	}
         
}

function _admin_check(){
    if(!in_array($this->dheader['jabatan'],$this->allowed_level_admin)){
      $this->session->set_flashdata('results', array('message'=>'You do not have access level to the previous page, please login using appropriate credentials', 'messageClass'=>'error'));
      redirect('login/logout');
    }
}

function index(){
	$this->browse();
} 
 
function browse($page = 1){
	
	$this->dheader['bodyId'] = 'body-crew';
	$this->dheader['selMenu'] = 'managemen';
	$this->dheader['pageTitle'] = 'Crew Management';
	
	$this->dheader['cssFiles'] = array('mavsuggest.css','iata.css');
  	$this->dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js');
	$this->dheader['jsText'] = 'window.addEvent("domready", function(){
																
							});';
   
	
	//log_message('error',var_export($data['rsIata'],true));
	$data['keyword'] = null;
	$data['curpage'] = $page;
	$data['addnama'] = null;
	$data['addiduser'] = null;
	$data['defaultnamacrew'] = "Cari Pegawai";
	$data['addiatacode'] = null;
	$data['editnama'] = null;
	$data['editiduser'] = null;
	$data['editiatacode'] = null;
	$data['results_per_page'] = $this->config->item('results_per_page');
	
	$data['url_add'] = "pegawai/save_add";
	$data['add_saveable'] = true;
	$data['url_edit'] = "pegawai/save_edit";
	$data['edit_saveable'] = true;
	$data['url_browse'] = 'pegawai/browse/';
	$data['url_filter'] = "pegawai/filter";
	
	
	$rsJabatan = $this->jabatanmodel->list_jabatan();
	$data['input_list_add'] = array(
				'kode' => array('type' => 'text', 'title' => 'Kode', 'value'=>'' ),
				'gelar' => array('type' => 'text', 'title' => 'Gelar', 'value'=>'' ),
				'nama' => array('type' => 'text', 'title' => 'Nama', 'value'=>'' ),
				'idjabatan' => array('type' => 'select', 'title' => 'Jabatan', 'value'=>'', 'select_list'=> $rsJabatan),
				'namabank1' => array('type' => 'text', 'title' => 'Bank 1', 'value'=>'' ),
				'norek1' => array('type' => 'text', 'title' => 'No Rek 1', 'value'=>'' ),
				'namabank2' => array('type' => 'text', 'title' => 'Bank 2', 'value'=>'' ),
				'norek2' => array('type' => 'text', 'title' => 'No Rek 2', 'value'=>'' ),
				'alamat' => array('type' => 'text', 'title' => 'Alamat', 'value'=>'' ),
				'kota' => array('type' => 'text', 'title' => 'Kota', 'value'=>'' ),
				'tlp' => array('type' => 'text', 'title' => 'tlp', 'value'=>'' ),
				'hp' => array('type' => 'text', 'title' => 'HP', 'value'=>'' ),
				'email' => array('type' => 'text', 'title' => 'Email', 'value'=>'' ),
				'password' => array('type' => 'password', 'title' => 'Password', 'value'=>'' ),
				'password2' => array('type' => 'password', 'title' => 'Confirm Pass', 'value'=>'' )
	);
	$data['input_list_hidden_add'] = array();
	
	$data['input_list_edit'] = array(
				'editkode' => array('type' => 'text', 'title' => 'Kode', 'value'=>'', 'class'=>'editkode' ),
				'editgelar' => array('type' => 'text', 'title' => 'Gelar', 'value'=>'', 'class'=>'editgelar' ),
				'editnama' => array('type' => 'text', 'title' => 'Nama', 'value'=>'', 'class'=>'editnama' ),
				'editidjabatan' => array('type' => 'select', 'title' => 'Jabatan', 'value'=>'', 'select_list'=> $rsJabatan, 'class'=>'editidjabatan'),
				'editnamabank1' => array('type' => 'text', 'title' => 'Bank 1', 'value'=>'', 'class'=>'editnamabank1' ),
				'editnorek1' => array('type' => 'text', 'title' => 'No Rek 1', 'value'=>'', 'class'=>'editnorek1' ),
				'editnamabank2' => array('type' => 'text', 'title' => 'Bank 2', 'value'=>'', 'class'=>'editnamabank2' ),
				'editnorek2' => array('type' => 'text', 'title' => 'No Rek 2', 'value'=>'', 'class'=>'editnorek2' ),
				'editalamat' => array('type' => 'text', 'title' => 'Alamat', 'value'=>'', 'class'=>'editalamat' ),
				'editkota' => array('type' => 'text', 'title' => 'Kota', 'value'=>'', 'class'=>'editkota' ),
				'edittlp' => array('type' => 'text', 'title' => 'Tlp', 'value'=>'', 'class'=>'edittlp' ),
				'edithp' => array('type' => 'text', 'title' => 'HP', 'value'=>'', 'class'=>'edithp' ),
				'editemail' => array('type' => 'text', 'title' => 'Email', 'value'=>'', 'class'=>'editemail' ),
				'editpassword' => array('type' => 'password', 'title' => 'Password', 'value'=>'', 'class'=>'editpassword' ),
				'editpassword2' => array('type' => 'password', 'title' => 'Confirm Pass', 'value'=>'', 'class'=>'editpassword2' ),
				'editactivated' => array('type' => 'select', 'title' => 'Aktif', 'value'=>'', 'class'=>'editactivated', 'select_list'=> array("n"=>'Tidak',"y"=>'Ya'))
	);
	
	$data['input_list_hidden_edit'] = array(
				'editid' => array('value'=>'','class'=>'editid')
	);
	
	$data['columnHeaders'] = array(
		array('header_title'=>'Kode', 'field_name'=> 'kode', 'class'=>'rowhead', 'width'=>'10%', 'rowinfo' => false),
		array('header_title'=>'Gelar', 'field_name'=> 'gelar', 'width'=>'10%', 'rowinfo' => false),
		array('header_title'=>'Nama', 'field_name'=> 'nama', 'width'=>'30%', 'rowinfo' => false),
		array('header_title'=>'Email', 'field_name'=> 'email', 'width'=>'20%', 'rowinfo' => false),
		array('header_title'=>'Jabatan', 'field_name'=> 'namajabatan', 'width'=>'20%', 'rowinfo' => false),
		array('header_title'=>'Aktif', 'field_name'=> 'activated', 'width'=>'10%', 'rowinfo' => false, 'format' => 'if($row["activated"] == "y"){echo "Ya";}else{echo "Tidak";}'),
	);
	
	$data['editdelid'] = 'id'; 
	
	$data['rowInfoBtns'] = array(
		//array('html'=> 'delete', 'title'=>'delete ', 'url'=> 'pegawai/delete/',  'field_name'=>'nama', 'class'=> 'btndel aw delbtn')
    );
    //$data['rowInfoBtnsAct'] = array();
    //$data['rowInfoBtnsNon'] = array();
	
	if($this->session->userdata('keyword_pegawai')){
		$keyword = $this->session->userdata('keyword_pegawai');
		$data['keyword'] = $keyword;
	}
	
	$allData = $this->pegawaimodel->get_pegawai($data['keyword'], $data['curpage']);
	$data['rowcount'] = $allData['rowcount'];
	$data['curpage'] = $allData['curpage'];
	$data['maxpage'] = $allData['maxpage'];
	$data['allrows'] = $allData['rows'];
	//log_message('error',var_export($data['allrows'],true));
	$data['addtitle'] = 'Pegawai Baru';
	$data['edittitle'] = 'Edit Pegawai';
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
  
      	if(isset($flashData['addResult'])){
      		$data['input_list_add']['kode']['value'] = $flashData['kode'];
        	$data['input_list_add']['gelar']['value'] = $flashData['gelar'];
        	$data['input_list_add']['nama']['value'] = $flashData['nama'];
        	$data['input_list_add']['idjabatan']['value'] = $flashData['idjabatan'];
        	$data['input_list_add']['namabank1']['value'] = $flashData['namabank1'];
        	$data['input_list_add']['norek1']['value'] = $flashData['norek1'];
        	$data['input_list_add']['namabank2']['value'] = $flashData['namabank2'];
        	$data['input_list_add']['norek2']['value'] = $flashData['norek2'];
        	$data['input_list_add']['alamat']['value'] = $flashData['alamat'];
        	$data['input_list_add']['kota']['value'] = $flashData['kota'];
        	$data['input_list_add']['tlp']['value'] = $flashData['tlp'];
        	$data['input_list_add']['hp']['value'] = $flashData['hp'];
        	$data['input_list_add']['email']['value'] = $flashData['email'];
        	$data['input_list_add']['password']['value'] = $flashData['password'];
        	$data['input_list_add']['password2']['value'] = $flashData['password2'];
      	}
      	
		if(isset($flashData['editid'])){
	        $data['input_list_hidden_edit']['editid'] = $flashData['editid'];
        	$data['input_list_edit']['editkode']['value'] = $flashData['editkode'];
        	$data['input_list_edit']['editgelar']['value'] = $flashData['editgelar'];
        	$data['input_list_edit']['editnama']['value'] = $flashData['editnama'];
        	$data['input_list_edit']['editidjabatan']['value'] = $flashData['editidjabatan'];
        	$data['input_list_edit']['editnamabank1']['value'] = $flashData['editnamabank1'];
        	$data['input_list_edit']['editnorek1']['value'] = $flashData['editnorek1'];
        	$data['input_list_edit']['editnamabank2']['value'] = $flashData['editnamabank2'];
        	$data['input_list_edit']['editnorek2']['value'] = $flashData['editnorek2'];
        	$data['input_list_edit']['editalamat']['value'] = $flashData['editalamat'];
        	$data['input_list_edit']['editkota']['value'] = $flashData['editkota'];
        	$data['input_list_edit']['edittlp']['value'] = $flashData['edittlp'];
        	$data['input_list_edit']['edithp']['value'] = $flashData['edithp'];
        	$data['input_list_edit']['editemail']['value'] = $flashData['editemail'];
        	$data['input_list_edit']['editpassword']['value'] = $flashData['editpassword'];
        	$data['input_list_edit']['editpassword2']['value'] = $flashData['editpassword2'];
        	$data['input_list_edit']['editactivated']['value'] = $flashData['editactivated'];
	    }
        
	}
	
	
	$this->load->view('header',$this->dheader);
	$this->load->view('editor_browse',$data);	
	$this->load->view('footer');
	
}


function save_add(){  
    $this->load->library('form_validation');
    $this->load->helper('ozl');

   	$this->curid = 0;
  	$this->form_validation->set_rules('kode', 'Kode', 'trim|required|xss_clean|callback_kode_check');
  	$this->form_validation->set_rules('gelar', 'Gelar', 'trim|xss_clean');
	$this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
	$this->form_validation->set_rules('idjabatan', 'Jabatan', 'trim|required|xss_clean|callback_jabatan_check');		
	$this->form_validation->set_rules('namabank1', 'Bank 1', 'trim|xss_clean');
	$this->form_validation->set_rules('norek1', 'rek 1', 'trim|xss_clean');
	$this->form_validation->set_rules('namabank2', 'Bank 2', 'trim|xss_clean');
	$this->form_validation->set_rules('norek2', 'rek 2', 'trim|xss_clean');
	$this->form_validation->set_rules('alamat', 'Alamat', 'trim|xss_clean');
	$this->form_validation->set_rules('kota', 'Kota', 'trim|xss_clean');
	$this->form_validation->set_rules('tlp', 'Tlp', 'trim|xss_clean');
	$this->form_validation->set_rules('hp', 'Hp', 'trim|xss_clean');
	$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean|callback_email_check');
	$this->form_validation->set_rules('password', ' ', 'required|min_length[5]|max_length[12]|alpha_dash');
	$this->form_validation->set_rules('password2', 'Password', 'required|matches[password]');
    
    
    $is_passed = $this->form_validation->run();
    
    $flashData['message'] = 'Gagal menambah pegawai baru';
    $flashData['messageClass'] = "error";
    $flashData['addResult'] = true;
    $flashData['kode'] = $this->input->post('kode');
    $flashData['gelar'] = $this->input->post('gelar');
    $flashData['nama'] = $this->input->post('nama');
    $flashData['idjabatan'] = $this->input->post('idjabatan');
    $flashData['namabank1'] = $this->input->post('namabank1');
    $flashData['norek1'] = $this->input->post('norek1');
    $flashData['namabank2'] = $this->input->post('namabank2');
    $flashData['norek2'] = $this->input->post('norek2');
    $flashData['alamat'] = $this->input->post('alamat');
    $flashData['kota'] = $this->input->post('kota');
    $flashData['tlp'] = $this->input->post('tlp');
    $flashData['hp'] = $this->input->post('hp');
    $flashData['email'] = $this->input->post('email');
    $flashData['password'] = $this->input->post('password');
    $flashData['password2'] = $this->input->post('password2');
	$this->form_validation->set_error_delimiters('', '');
    
  	if ($is_passed == FALSE){
  		
    	$flashData['errors'] = array(
    		'kode'=>form_error('kode'),
    		'gelar'=>form_error('gelar'),
    		'nama'=>form_error('nama'),
    		'idjabatan'=>form_error('idjabatan'),
    		'namabank1'=>form_error('namabank1'),
    		'norek1'=>form_error('norek1'),
    		'namabank2'=>form_error('namabank2'),
    		'norek2'=>form_error('norek2'),
    		'alamat'=>form_error('alamat'),
    		'kota'=>form_error('kota'),
    		'hp'=>form_error('hp'),
    		'tlp'=>form_error('tlp'),
    		'email'=>form_error('email'),
    		'password'=>form_error('password'),
    		'password2'=>form_error('password2')
    	);
	  	
  	}
  	else{
		$iduser = $this->tank_auth->create_user(
			$this->form_validation->set_value('kode'),
			$this->form_validation->set_value('email'),
			$this->form_validation->set_value('password'),
			false
		);
		if($iduser){
			$this->pegawaimodel->add(array(
				'id'=>	$iduser['user_id'],
				'kode'=> $flashData['kode'],
				'gelar'=> $flashData['gelar'],
				'nama'=> $flashData['nama'],
				'idjabatan'=> $flashData['idjabatan'],
				'namabank1'=> $flashData['namabank1'],
				'norek1'=> $flashData['norek1'],
				'namabank2'=> $flashData['namabank2'],
				'norek2'=> $flashData['norek2'],
				'alamat'=> $flashData['alamat'],
				'kota'=> $flashData['kota'],
				'tlp'=> $flashData['tlp'],
				'hp'=> $flashData['hp'],
				'email'=> $flashData['email'],
				'activated'=> 'y'
			));
			
			$flashData['message'] = 'Berhasil menambah pegawai baru';
    		$flashData['messageClass'] = "success";
			
		}
		
  		      
  	}
    
  	$flashData['frmaction'] = 'add';
    $this->session->set_flashdata('results', $flashData);
    redirect('pegawai');
}


function save_edit(){
      
    $this->load->library('form_validation');
    $this->load->helper('ozl');
    
    $flashData['frmaction'] = 'edit';
    
    $this->curid = $this->input->post('editid');
    $this->form_validation->set_rules('editkode', 'Kode', 'trim|required|xss_clean|callback_kode_check');
	$this->form_validation->set_rules('editgelar', 'Gelar', 'trim|xss_clean');
	$this->form_validation->set_rules('editnama', 'Nama', 'trim|required|xss_clean');
	$this->form_validation->set_rules('editidjabatan', 'Jabatan', 'trim|required|xss_clean|callback_jabatan_check');		
	$this->form_validation->set_rules('editnamabank1', 'Bank 1', 'trim|xss_clean');
	$this->form_validation->set_rules('editnorek1', 'rek 1', 'trim|xss_clean');
	$this->form_validation->set_rules('editnamabank2', 'Bank 2', 'trim|xss_clean');
	$this->form_validation->set_rules('editnorek2', 'rek 2', 'trim|xss_clean');
	$this->form_validation->set_rules('editalamat', 'Alamat', 'trim|xss_clean');
	$this->form_validation->set_rules('editkota', 'Kota', 'trim|xss_clean');
	$this->form_validation->set_rules('edittlp', 'Tlp', 'trim|xss_clean');
	$this->form_validation->set_rules('edithp', 'Hp', 'trim|xss_clean');
	$this->form_validation->set_rules('editemail', 'Email', 'required|valid_email|xss_clean|callback_email_check');
	$this->form_validation->set_rules('editpassword', 'pwd ', 'min_length[5]|max_length[12]|alpha_dash');
	$this->form_validation->set_rules('editpassword2', 'Password', 'min_length[5]|max_length[12]|matches[editpassword]');
	$this->form_validation->set_rules('editactivated', 'Activation', 'trim|xss_clean|required');
  	
    $is_passed = $this->form_validation->run();
    
    $flashData['message'] = 'Gagal mengedit pegawai.';
    $flashData['messageClass'] = "error";
    $flashData['editid'] = $this->input->post('editid');
    $flashData['editkode'] = $this->input->post('editkode');
    $flashData['editgelar'] = $this->input->post('editgelar');
    $flashData['editnama'] = $this->input->post('editnama');
    $flashData['editidjabatan'] = $this->input->post('editidjabatan');
    $flashData['editnamabank1'] = $this->input->post('editnamabank1');
    $flashData['editnorek1'] = $this->input->post('editnorek1');
    $flashData['editnamabank2'] = $this->input->post('editnamabank2');
    $flashData['editnorek2'] = $this->input->post('editnorek2');
    $flashData['editalamat'] = $this->input->post('editalamat');
    $flashData['editkota'] = $this->input->post('editkota');
    $flashData['edittlp'] = $this->input->post('edittlp');
    $flashData['edithp'] = $this->input->post('edithp');
    $flashData['editemail'] = $this->input->post('editemail');
    $flashData['editpassword'] = $this->input->post('editpassword');
    $flashData['editpassword2'] = $this->input->post('editpassword2');
    $flashData['editactivated'] = $this->input->post('editactivated');
    if($flashData['editactivated'] != "y" && $flashData['editactivated'] != "n"){
    	$flashData['editactivated'] = 'n';
    }
	$this->form_validation->set_error_delimiters('', '');
    
	if($flashData['editpassword'] != '' && $flashData['editpassword'] != $flashData['editpassword2']){
		$is_passed = FALSE;
		//GAK NGEFEK..$flashData['errors']['editpassword2'] = 'password tidak sama';
	}
	
  	if ($is_passed == FALSE){
  		$flashData['errors'] = array(
    		'editkode'=>form_error('editkode'),
    		'editgelar'=>form_error('editgelar'),
    		'editnama'=>form_error('editnama'),
    		'editidjabatan'=>form_error('editidjabatan'),
    		'editnamabank1'=>form_error('editnamabank1'),
    		'editnorek1'=>form_error('editnorek1'),
    		'editnamabank2'=>form_error('editnamabank2'),
    		'editnorek2'=>form_error('editnorek2'),
    		'editalamat'=>form_error('editalamat'),
    		'editkota'=>form_error('editkota'),
    		'edithp'=>form_error('edithp'),
    		'edittlp'=>form_error('edittlp'),
    		'editemail'=>form_error('editemail'),
    		'editpassword'=>form_error('editpassword'),
    		'editpassword2'=>form_error('editpassword2'),
  			'editactivated'=>form_error('editactivated')
    	);
    	if($flashData['errors']['editpassword2']!= ''){$flashData['errors']['editpassword2'] = 'password tidak sama';}
  	}
  	else{
  		//log_message('error', 'lulus sensor');
		$validCrew = $this->pegawaimodel->get_pegawai_by_id($flashData['editid']);
		if($validCrew){
			$this->pegawaimodel->edit(array(
				'id'=>	$flashData['editid'],
				'kode'=> $flashData['editkode'],
				'gelar'=> $flashData['editgelar'],
				'nama'=> $flashData['editnama'],
				'idjabatan'=> $flashData['editidjabatan'],
				'namabank1'=> $flashData['editnamabank1'],
				'norek1'=> $flashData['editnorek1'],
				'namabank2'=> $flashData['editnamabank2'],
				'norek2'=> $flashData['editnorek2'],
				'alamat'=> $flashData['editalamat'],
				'kota'=> $flashData['editkota'],
				'tlp'=> $flashData['edittlp'],
				'hp'=> $flashData['edithp'],
				'email'=> $flashData['editemail'],
				'activated'=> $flashData['editactivated']
			));
			
			if (strlen($flashData['editpassword']) > 3 ){
				$hasher = new PasswordHash(
					$this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
				$hashed_password = $hasher->HashPassword($flashData['editpassword']);
				// Replace old password with new one
				$this->users->change_password($flashData['editid'], $hashed_password);
				//return TRUE;
			}
			//$flashData = array();
			$flashData['message'] = 'Berhasil mengubah pegawai <b>'.$flashData['editkode'].'</b>';
    		$flashData['messageClass'] = "success";
    		$flashData['frmaction'] = 'add';
			
				
		}else{
			
			$flashData['message'] = 'Invalid Pegawai!!';
			
		}
  		
  	}
    
    $this->session->set_flashdata('results', $flashData);
    redirect('pegawai');
}


function delete($iduser){
  	/*
	if($this->branch->get_crew_iata($iduser)){
	    
	
		$this->branch->delete($iduser);
	  	$flashData['message'] = 'Assignment successfully deleted';
	  	$flashData['messageClass'] = "success";
	  
	}else{
	  $flashData['message'] = 'Failed to delete the assignment. invalid crew';
	  $flashData['messageClass'] = "error";
	}
	
	*/
	$flashData['frmaction'] = 'add';
	//$this->session->set_flashdata('results', $flashData);
	redirect('pegawai/browse');
    
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
			$flashData['message'] = 'Error filtering results with <b>'.$this->input->post('filter').'</b><br/>'.validation_errors();
			$flashData['messageClass'] = "error";
			$this->session->set_flashdata('results', $flashData);

		}else{
			$this->session->set_userdata('keyword_pegawai', $this->input->post('filter'));
			
		}
	//}
	redirect('pegawai/browse');
	
}

function email_check($str){
    if($this->pegawaimodel->check_email($str, $this->curid)){
      return TRUE;
    }else{
      $this->form_validation->set_message('email_check', '%s terpakai');
      return FALSE;
    }
}


function jabatan_check($str){
	$result = FALSE;
	foreach($this->jabatanmodel->list_jabatan() as $idjabatan => $namajabatan){
		if($idjabatan == $str){
			$result = TRUE;
		}
	}

    if(!$result){
      $this->form_validation->set_message('jabatan_check', 'invalid %s');
      return FALSE;
    }else{
      return TRUE;
    }
}


function kode_check($str){
    if($this->pegawaimodel->is_kode_exists($str,  $this->curid)){
      $this->form_validation->set_message('kode_check', '%s telah terpakai');
      return FALSE;
    }else{
      return TRUE;
    }
}

}
?>