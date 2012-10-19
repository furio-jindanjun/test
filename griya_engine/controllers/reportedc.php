<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportedc extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $title = 'reportedc'; 
private $titledb = 'pembayaranedc';
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
    
    $this->dheader['pageTitle'] = 'Report EDC';
  	
  	$titledb = $this->titledb;
    
    $data['keyword'] = null;
    $data['curpage'] = $page;
    $data['urlFilter'] = $this->title."/filter";
    $data['url_browse'] = $this->title."/browse/";
	
	$data['isAddAble'] = false;
	$data['urlNew'] =  "customer/editor/edit/";
	$data['addTitle'] = 'Kembali ke detil pasien';
	
	$data['edc'] = true;
		
	$data['columnHeaders'] = array(
							array('header_title'=>'Tanggal', 'field_name'=> 'tgl', 'class'=>'rowhead', 'width'=>'20%', 'rowinfo' => false),
							array('header_title'=>'Nama Pasien', 'field_name'=> 'nama', 'width'=>'20%', 'rowinfo' => false, 'bold' => 'ya'),
							array('header_title'=>'Jumlah', 'field_name'=> 'jumlah', 'width'=>'20%', 'rowinfo' => false),
							array('header_title'=>'Jumlah+Tax', 'field_name'=> 'jumlahplustax', 'width'=>'20%', 'rowinfo' => false),
							array('header_title'=>'Metode', 'field_name'=> 'metode', 'width'=>'20%', 'rowinfo' => false)
	);
	
	$data['rowInfoBtns'] = false;
	$data['rowInfoBtnsAct'] = array(
		array('html'=> 'edit/lihat detil', 'title'=>'Ubah data/lihat detil '.$this->title, 'url'=> $this->title.'/editor/edit/', 'class'=> 'btnedit aw'),
		array('html'=> 'aktifkan', 'title'=>'Aktifkan kembali ', 'url'=> $this->title.'/activate/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	$data['rowInfoBtnsNon'] = array(
		array('html'=> 'edit/lihat detil', 'title'=>'Ubah data/lihat detil '.$this->title, 'url'=> $this->title.'/editor/edit/', 'class'=> 'btnedit aw'),
		array('html'=> 'non aktifkan', 'title'=>'Non aktifkan ', 'url'=> $this->title.'/delete/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	
	
	$data['editdelid'] = 'id';
	$data['delete_str'] = 'Hapus '.$this->title;
	$data['delete_title'] = 'nama';
	
	
	if($this->session->userdata('keyword_'.$this->title)){
		$keyword = $this->session->userdata('keyword_'.$this->title);
		$data['keyword'] = $keyword;
	}
	
	$titfunc = 'get_browse';
	$allData = $this->$titledb->$titfunc($data['keyword'], $data['curpage']);
    
    $data['rowcount'] = $allData['rowcount'];
    $data['curpage'] = $allData['curpage'];
    $data['maxpage'] = $allData['maxpage'];
    $data['allrows'] = $allData['rows'];
    
    //log_message('error','rows: '.var_export($allData['rows'],true));
    //log_message('error','$data[allrows]: '.var_export($data['allrows'],true));
    
				
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
	redirect('/historitindakan/browse/'.$this->input->post('idnyapasien'));
	
}

}