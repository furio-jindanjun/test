<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $title = 'customer'; 
private $titledb = 'customermodel';
private $editmode = false;

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
            $this->dheader['bodyId'] = 'body-'.$this->title;
            $this->dheader['selMenu'] = 'inventory';
        }else{
            $this->session->set_flashdata('results', array('message'=>'You do not have access level to the previous page, please login using appropriate credentials', 'messageClass'=>'error'));
            redirect('login/logout');
        }
        
    }else{
        $this->session->set_flashdata('adminfrom', '/customer');
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

    $data['userId']  = $this->tank_auth->get_user_id();
    $data['userName']  = $this->tank_auth->get_username();
    $data['jabatan']  = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id());
    
    $this->dheader['bodyId'] = 'body-'.$this->title;
    $this->dheader['selMenu'] = 'inventory';
    $this->dheader['subMenu'] = 'item';
    $this->dheader['jsFiles'] = array('utilities.js');
    $this->dheader['pageTitle'] = 'Managemen '.ucfirst(strtolower($this->title));
  	
  	$titledb = $this->titledb;
    
    $data['keyword'] = null;
    $data['curpage'] = $page;
    $data['urlFilter'] = $this->title."/filter";
    $data['url_browse'] = $this->title."/browse/";
	
	$data['isAddAble'] = true;
	$data['urlNew'] =  $this->title."/editor/add";
	$data['addTitle'] = 'Tambah '.ucfirst(strtolower($this->title)). ' Baru';
		
	$data['columnHeaders'] = array(
							array('header_title'=>'Kode', 'field_name'=> 'kode', 'class'=>'rowhead', 'width'=>'7%', 'rowinfo' => false),
							array('header_title'=>'Gelar', 'field_name'=> 'gelar', 'width'=>'5%', 'rowinfo' => false),
							array('header_title'=>'Nama', 'field_name'=> 'nama', 'width'=>'13%', 'rowinfo' => false),
							array('header_title'=>'Alamat', 'field_name'=> 'alamat', 'width'=>'16%', 'rowinfo' => false),
							array('header_title'=>'Kota', 'field_name'=> 'kota', 'width'=>'8%', 'rowinfo' => false),
							array('header_title'=>'Telepon', 'field_name'=> 'tlp', 'width'=>'10%', 'rowinfo' => false),
							array('header_title'=>'Email', 'field_name'=> 'email', 'width'=>'15%', 'rowinfo' => false),
							array('header_title'=>'No Rek.', 'field_name'=> 'norek1', 'width'=>'13%', 'rowinfo' => false),
							array('header_title'=>'Nama Bank', 'field_name'=> 'namabank1', 'width'=>'13%', 'rowinfo' => false)
	);
	
	$data['rowInfoBtns'] = array(
							array('html'=> 'edit/lihat detil', 'title'=>'Ubah data/lihat detil '.$this->title, 'url'=> $this->title.'/editor/edit/', 'class'=> 'btnedit aw'),
							array('html'=> 'lihat histori resep obat pasien', 'title'=>'lihat histori resep '.$this->title, 'url'=> 'historiresep/browse/', 'class'=> 'btnedit aw'),
							array('html'=> 'lihat histori tindakan pasien', 'title'=>'lihat tindakan '.$this->title, 'url'=> 'historitindakan/browse/', 'class'=> 'btnedit aw'),
							array('html'=> 'hapus', 'title'=>'hapus '.$this->title.' ',  'field_name'=>'nama', 'url'=> $this->title.'/delete/', 'class'=> 'btndel')
	);
	$data['rowInfoBtnsAct'] = array(
		array('html'=> 'edit/lihat detil', 'title'=>'Ubah data/lihat detil '.$this->title, 'url'=> $this->title.'/editor/edit/', 'class'=> 'btnedit aw'),
		array('html'=> 'aktifkan', 'title'=>'Aktifkan kembali ', 'url'=> $this->title.'/activate/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	$data['rowInfoBtnsNon'] = array(
		array('html'=> 'edit/lihat detil', 'title'=>'Ubah data/lihat detil '.$this->title, 'url'=> $this->title.'/editor/edit/', 'class'=> 'btnedit aw'),
		array('html'=> 'non aktifkan', 'title'=>'Non aktifkan ', 'url'=> $this->title.'/delete/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	
	$titfunc1 = 'search_'.$this->title;
	$data['nonaktif'] = $this->$titledb->$titfunc1();
	
	$data['editdelid'] = 'id';
	$data['delete_str'] = 'Hapus '.$this->title;
	$data['delete_title'] = 'nama';
	
	
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
				
	$data['results_per_page'] = $this->config->item('results_per_page');
	
	$flashData = $this->session->flashdata('results');
	if($flashData){
		$dheader['message'] = $flashData['message'];
		$dheader['messageClass'] = $flashData['messageClass'];
	}

    $this->load->view('header',$this->dheader);
    $this->load->view('browse',$data);
    $this->load->view('footer');
}
  
function editor($action = 'add', $id = null){
    $dheader['userId']  = $this->tank_auth->get_user_id();
    $dheader['userName']  = $this->tank_auth->get_username();
    $dheader['jabatan']  = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id());
    $dheader['bodyId'] = $this->title;
    $dheader['selMenu'] = 'inventory';
	$dheader['subMenu'] = 'item';
    $dheader['pageTitle'] = 'Managemen '.ucfirst(strtolower($this->title));
	
	$titledb = $this->titledb;
    
    $dheader['cssFiles'] = array('mavsuggest.css','datepicker.css');
    $dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js','Picker.js','Picker.Attach.js','Picker.Date.js');
    
	$data['form_action'] = $this->title."/ajax_save_add";
	$data['page_title'] = ucfirst(strtolower($this->title)). ' Baru';

	$data['item_id'] = "";
	$data['input_list'] = array(
		'nama' => array('type' => 'text', 'title' => 'Nama', 'value'=> ''),
		'gelar' => array('type' => 'text', 'title' => 'Gelar', 'value'=> ''),
		'alamat' => array('type' => 'text', 'title' => 'Alamat', 'value'=> ''),
		'kota' => array('type' => 'text', 'title' => 'Kota', 'value'=> ''),
		'kodepos' => array('type' => 'text', 'title' => 'Kodepos', 'value'=> ''),
		'tlp' => array('type' => 'text', 'title' => 'Telepon', 'value'=> ''),
		'fax' => array('type' => 'text', 'title' => 'Fax', 'value'=> ''),
		'email' => array('type' => 'text', 'title' => 'Email', 'value'=> '')
	);
	
	$data['input_list_right'] = array(
		'norek1' => array('type' => 'text', 'title' => 'No. Rekening (1)', 'value'=> ''),
		'namabank1' => array('type' => 'text', 'title' => 'Nama Bank (1)', 'value'=> ''),
		'norek2' => array('type' => 'text', 'title' => 'No. Rekening (2)', 'value'=> ''),
		'namabank2' => array('type' => 'text', 'title' => 'Nama Bank (2)', 'value'=> ''),
		'uploadlink' => array('type' => 'html', 'title' => 'Upload Foto', 'value'=> ''),
		'historiobat' => array('type' => 'html', 'title' => 'Histori Obat', 'value'=> ''),
		'historitindakan' => array('type' => 'html', 'title' => 'Histori Tindakan', 'value'=> '')

	);
	
	$data['input_list_hidden'] = array(
		'id' => array('value' => '')
	);
	
	$data['saveable'] = true;
	
    
	if($action == 'edit'){
			
		$data['form_action'] = $this->title."/ajax_save_edit/";
		$data['page_title'] = 'Ubah '. ucfirst(strtolower($this->title));
		
		$rs_edit = $this->$titledb->get_data_by_id($id);
		if(!$rs_edit){
			$flashData['message'] = 'Data '.$this->title.' tidak valid.';
	        $flashData['messageClass'] = "error";
	        $this->session->set_flashdata('results', $flashData);
	        redirect($this->title.'/browse');
		}else{
		
			$data['item_id'] = $id;
			$data['input_list']['nama']['value'] = $rs_edit['nama'];
			$data['input_list']['gelar']['value'] = $rs_edit['gelar'];
			$data['input_list']['alamat']['value'] = $rs_edit['alamat'];
			$data['input_list']['kota']['value'] = $rs_edit['kota'];
			$data['input_list']['kodepos']['value'] = $rs_edit['kodepos'];
			$data['input_list']['tlp']['value'] = $rs_edit['tlp'];
			$data['input_list']['fax']['value'] = $rs_edit['fax'];
			$data['input_list']['email']['value'] = $rs_edit['email'];
			
			$data['input_list_right']['norek1']['value'] = $rs_edit['norek1'];
			$data['input_list_right']['namabank1']['value'] = $rs_edit['namabank1'];
			$data['input_list_right']['norek2']['value'] = $rs_edit['norek2'];
			$data['input_list_right']['namabank2']['value'] = $rs_edit['namabank2'];
			$data['input_list_right']['uploadlink']['value'] = '<a href="'.base_url().'customer/foto/'.$id.'" title="foto Pelanggan">UPLOAD FOTO</a>';
			$data['input_list_right']['historiobat']['value'] = '<a href="'.base_url().'historiresep/browse/'.$id.'" title="Histori resep obat">HISTORI OBAT</a>';
			$data['input_list_right']['historitindakan']['value'] = '<a href="'.base_url().'historitindakan/browse/'.$id.'" title="Histori tindakan">HISTORI TINDAKAN</a>';
			
			$data['input_list_hidden']['id']['value'] = $rs_edit['id'];
			
			$data['saveable'] = true;
		}
			
	}
			
	$this->load->view('header',$dheader);
	$this->load->view('editor_2col',$data);	
	$this->load->view('footer');
	
}
  
function save($action = 'add'){
  $this->load->library('form_validation');
  $this->load->helper('ozl');
  
  $titledb = $this->titledb;
      
  if($action == 'add'){}
  else{
  	$this->editmode = true;
	$this->editid = $this->input->post('id');
  	$flashData['posts']['id'] = $this->input->post('id');
	$this->form_validation->set_rules('id', 'ID', 'required|callback_id_check|xss_clean');
  }    
  
  $this->rek1 = $this->input->post('norek1');
  $this->rek2 = $this->input->post('norek2');
  //$this->form_validation->set_rules('quantity', 'Quantity', 'strip_html_comment|test_null_tags|required|numeric|xss_clean');
  $this->form_validation->set_rules('nama', 'Nama', 'strip_html_comment|test_null_tags|required|min_length[3]|callback_nama_check|xss_clean');
  $this->form_validation->set_rules('gelar', 'Gelar', 'strip_html_comment|test_null_tags|xss_clean');
  $this->form_validation->set_rules('alamat', 'Alamat', 'strip_html_comment|test_null_tags|required|min_length[3]|xss_clean');
  $this->form_validation->set_rules('kota', 'Kota', 'strip_html_comment|test_null_tags|required|xss_clean');	
  $this->form_validation->set_rules('kodepos', 'Kodepos', 'strip_html_comment|test_null_tags|min_length[3]|numeric|xss_clean');
  $this->form_validation->set_rules('tlp', 'Telepon', 'strip_html_comment|test_null_tags|min_length[3]|numeric|xss_clean');
  $this->form_validation->set_rules('fax', 'Fax', 'strip_html_comment|test_null_tags|min_length[3]|numeric|xss_clean');
  $this->form_validation->set_rules('email', 'Email', 'strip_html_comment|test_null_tags|valid_email|xss_clean');
  $this->form_validation->set_rules('norek1', 'No. Rekening (1)', 'strip_html_comment|test_null_tags|min_length[3]|numeric|xss_clean');
  $this->form_validation->set_rules('namabank1', 'Nama Bank (1)', 'strip_html_comment|test_null_tags|callback_namabank1_check|xss_clean');
  $this->form_validation->set_rules('norek2', 'No. Rekening (2)', 'strip_html_comment|test_null_tags|min_length[3]|numeric|xss_clean');
  $this->form_validation->set_rules('namabank2', 'Nama Bank (2)', 'strip_html_comment|test_null_tags|callback_namabank2_check|xss_clean');
  
  $is_passed =  $this->form_validation->run();

  $flashData['message'] = 'Gagal menambah '. $this->title .' baru.';
  $flashData['messageClass'] = "error";
  $flashData['posts']['nama'] = $this->input->post('nama');
  $flashData['posts']['gelar'] = $this->input->post('gelar');
  $flashData['posts']['alamat'] = $this->input->post('alamat');
  $flashData['posts']['kota'] = $this->input->post('kota');
  $flashData['posts']['kodepos'] = $this->input->post('kodepos');
  $flashData['posts']['tlp'] = $this->input->post('tlp');
  $flashData['posts']['fax'] = $this->input->post('fax');
  $flashData['posts']['email'] = $this->input->post('email');
  $flashData['posts']['norek1'] = $this->input->post('norek1');
  $flashData['posts']['namabank1'] = $this->input->post('namabank1');
  $flashData['posts']['norek2'] = $this->input->post('norek2');
  $flashData['posts']['namabank2'] = $this->input->post('namabank2');
	   
  if ($is_passed === FALSE){
    	$this->form_validation->set_error_delimiters('', '');
		$flashData['errors'] = array(
      		'nama'=>form_error('nama'),
      		'gelar'=>form_error('gelar'),
      		'alamat'=>form_error('alamat'),
      		'kota'=>form_error('kota'),
      		'kodepos'=>form_error('kodepos'),
      		'tlp'=>form_error('tlp'),
      		'fax'=>form_error('fax'),
      		'email'=>form_error('email'),
      		'norek1'=>form_error('norek1'),
      		'namabank1'=>form_error('namabank1'),
      		'norek2'=>form_error('norek2'),
      		'namabank2'=>form_error('namabank2'),
      		'idbarang'=>form_error('idbarang')
		);   
  }
  else{
    	//passed validation
		if($action == 'add'){
						
			$this->$titledb->add(array(
			 'nama'=>$flashData['posts']['nama'],
			 'gelar'=>$flashData['posts']['gelar'],
			 'alamat'=>$flashData['posts']['alamat'],
	         'kota'=>$flashData['posts']['kota'],
	         'kodepos'=>$flashData['posts']['kodepos'],
	         'tlp'=>$flashData['posts']['tlp'],
	         'fax'=>$flashData['posts']['fax'],
	         'email'=>$flashData['posts']['email'],
	         'norek1'=>$flashData['posts']['norek1'],
	         'namabank1'=>$flashData['posts']['namabank1'],
	         'norek2'=>$flashData['posts']['norek2'],
	         'namabank2'=>$flashData['posts']['namabank2'])
	    	);
	    }else{
	    	
			//EDIT
			$chg_code = FALSE;
			$temp_id = $this->$titledb->get_data_by_id($flashData['posts']['id']);
			if(substr($flashData['posts']['nama'],0,1)!=substr($temp_id['nama'],0,1)){
				$chg_code = TRUE;
			}
			$this->$titledb->edit(array(
			'nama'=>$flashData['posts']['nama'],
			'gelar'=>$flashData['posts']['gelar'],
			'alamat'=>$flashData['posts']['alamat'],
	        'kota'=>$flashData['posts']['kota'],
	        'kodepos'=>$flashData['posts']['kodepos'],
	        'tlp'=>$flashData['posts']['tlp'],
	        'fax'=>$flashData['posts']['fax'],
	        'email'=>$flashData['posts']['email'],
	        'norek1'=>$flashData['posts']['norek1'],
	        'namabank1'=>$flashData['posts']['namabank1'],
	        'norek2'=>$flashData['posts']['norek2'],
	        'namabank2'=>$flashData['posts']['namabank2'],
	        'id'=>$flashData['posts']['id']),$chg_code);
		     
	    }
			$flashData['posts'] = NULL;
	        $flashData['message'] = ucfirst(strtolower($this->title)).': <b>'.$flashData['posts']['nama'].'</b> berhasil disimpan.';
	        $flashData['messageClass'] = "success";
      
    }
    
    return $flashData;
}

function ajax_save_add(){
  //if($this->tank_auth->is_logged_in()){
    
    $flashData = $this->save('add');
	
    //$result = array('message' => $flashData['message'], 'messageClass' => $flashData['messageClass']);
    
    echo json_encode($flashData);

/*  }else{
    $flashData['redirect'] = base_url().'login';
    echo json_encode($flashData);
  }*/
}

function ajax_save_edit(){
 // if($this->tank_auth->is_logged_in()){
    
    $flashData = $this->save('edit');
	
    //$result = array('message' => $flashData['message'], 'messageClass' => $flashData['messageClass']);
    
    echo json_encode($flashData);

  /*}else{
    $flashData['redirect'] = base_url().'login';
    echo json_encode($flashData);
  }*/
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

function namabank1_check($str){
	
	if((is_null($this->rek1))||($this->rek1 == '')){
		return TRUE;
	}
	else{
		if((is_null($str))||($str == '')){
			$this->form_validation->set_message('namabank1_check', '%s harus diisi');
			return FALSE;
		}
	}
}

function namabank2_check($str){
	
	if((is_null($this->rek2))||($this->rek2 == '')){
		return TRUE;
	}
	else{
		if((is_null($str))||($str == '')){
			$this->form_validation->set_message('namabank2_check', '%s harus diisi');
			return FALSE;
		}
	}
}
  
function delete($id){
    //if($this->tank_auth->is_admin_logged_in()){
      
    $titledb = $this->titledb;
	if($this->$titledb->get_data_by_id($id)){
        
      $titfunc1 = 'search_'.$this->title;
	  $datadel = $this->$titledb->$titfunc1($id);
	   //var_dump($datadel);
	   if($datadel){	
    		$this->$titledb->revoke($id);
			$flashData['message'] = ucfirst(strtolower($this->title)).' berhasil dinon-aktifkan';
	   }else{
	   		$this->$titledb->delete($id);
			$flashData['message'] = ucfirst(strtolower($this->title)).' berhasil dihapus';
	   }
	   
       $flashData['messageClass'] = "success";
      
    }else{
      $flashData['message'] = 'Gagal menghapus '.$this->title.'. '.$this->title.' tidak valid';
	  $flashData['messageClass'] = "error";
    }
      
      
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


function foto($id_cust = 1){
	
	$action = 'edit';
	    
  	$this->dheader['pageTitle'] = 'Pengaturan Foto';
	$this->dheader['bodyId'] = 'editor';
    $this->dheader['cssFiles'] = array('mavsuggest.css','datepicker.css','uploadfoto.css');
    $this->dheader['jsFiles'] = array('utilities.js','fu/Fx.ProgressBar.js','fu/Swiff.Uploader.js','imgupload.js');
    $this->dheader['jsTextx'] = 'window.addEvent("domready", function(){
                  
          stopEnter("frmeditor");
                
          });';
    
	$data['id_cust'] = $id_cust;
	$data['session_id'] = $this->session->userdata('session_id');
	$data['form_action'] = "backend/portfolio/ajax_save_add";
	$data['page_title'] = '&#91New&#93 Portfolio';	
	
	$this->load->helper('date');
	$data['item_id'] = "";
	$data['input_list'] = array(
		'nama' => array('type' => 'label', 'title' => 'Nama', 'value'=> '','class'=>'name')		
	);
	
	$data['input_list_right'] = array();
	
	$data['input_list_hidden'] = array(
		'id_cust' => array('value' => $id_cust,'class'=> 'id_cust')
	);
	
	$data['saveable'] = true;
	
	$this->load->helper('file');
	$data['allrows'] = array();
	if(is_dir('./photos/'.$id_cust.'/')){
		if($action == 'add'){
			delete_files('./photos/'.$id_cust.'/', TRUE);
			rmdir('./photos/'.$id_cust.'/');
		}else{
			$data['allrows'] = get_filenames('./photos/'.$id_cust.'/');
			//log_message('error','data allrow: '.var_export($data, true));
			//show only the thumbnails
			foreach($data['allrows'] as $idx =>$img){
				if(!strstr($img, 'thumb_')){
				    $data['fulljpg'] = $data['allrows'][$idx];
					unset($data['allrows'][$idx]);
				}
			}
			//log_message('error','data unset: '.var_export($data, true));
		}
	}
		
	$data['ritecol_editable'] = true;
	$data['rowinfo_allways_visible'] = true;
	
	$data['rite_columns'] = array();
	$input_list_name = array();
	
    
	if($action == 'edit'){
			
		$data['form_action'] = "backend/portfolio/ajax_save_edit/";
		$data['page_title'] = '&#91Pengaturan&#93 Foto';
		
		$titledb = $this->titledb;
		$rs_edit = $this->$titledb->get_data_by_id($id_cust);
		//$rs_edit = $this->$titledb->get_data_by_id($id);
		if(!$rs_edit){
			$flashData['message'] = 'Invalid Portfolio Data';
	        $flashData['messageClass'] = "error";
	        $this->session->set_flashdata('results', $flashData);
	        redirect('backend/portfolio/browse');
		}else{
			
			$data['input_list']['nama']['value'] = $rs_edit['nama'];
			
			$data['input_list_hidden']['id_cust']['value'] = $rs_edit['id'];
			
			$data['saveable'] = true;
		}
			
	}
			
	$this->load->view('header',$this->dheader);
	$this->load->view('editor_list_upload',$data);	
	$this->load->view('footer');
}

function del_img($id_portfolio, $hash){
	$dir = './photos/'.$id_portfolio.'/';
	
	if(is_dir($dir)){
		
		$this->load->helper('file');
		$data['allrows'] = get_filenames($dir);
		
		foreach($data['allrows'] as $idx =>$img){
			if(strstr($img, $hash)){
				unlink($dir.$img);
			}
		}
	}
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

}