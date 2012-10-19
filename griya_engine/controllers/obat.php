<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class obat extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $title = 'obat'; 
private $titledb = 'obatmodel';
private $editmode = false;

function __construct(){
    parent::__construct();
	
     if($this->tank_auth->is_logged_in() ){
        $this->load->model($this->titledb);
        $jabatan = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id()); 
        $this->allowed_level = $this->config->item('allowed_level_obat');
        $this->allowed_level_admin = $this->config->item('allowed_level_pegawai_admin');
        log_message('error','jab:'.$jabatan);
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
        $this->session->set_flashdata('adminfrom', '/obat');
        $this->session->set_flashdata('results', array('message'=>'Your session is expired, you need to login again.', 'messageClass'=>'updated'));
        redirect('login');
    }
         
}


function index(){
    $this->browse();  
}
  
function browse($page = 1){
    
    $dheader['userId']  = $this->tank_auth->get_user_id();
    $dheader['userName']  = $this->tank_auth->get_username();
    $dheader['jabatan']  = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id());
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
							array('header_title'=>'Kode', 'field_name'=> 'kodeobat', 'class'=>'rowhead', 'width'=>'7%', 'rowinfo' => false),
							array('header_title'=>'Nama', 'field_name'=> 'namaobat', 'width'=>'15%', 'rowinfo' => false),
							array('header_title'=>'Supplier', 'field_name'=> 'namasupplier', 'width'=>'19%', 'rowinfo' => false),
							array('header_title'=>'Satuan', 'field_name'=> 'satuan', 'width'=>'11%', 'rowinfo' => false),
							array('header_title'=>'Harga Beli', 'field_name'=> 'hargabeli', 'width'=>'11%', 'rowinfo' => false),
							array('header_title'=>'Harga Jual', 'field_name'=> 'hargajual', 'width'=>'11%', 'rowinfo' => false),
							array('header_title'=>'Expired', 'field_name'=> 'expired', 'width'=>'13%', 'rowinfo' => false),
							array('header_title'=>'Jasa Apoteker', 'field_name'=> 'jasaapt', 'width'=>'13%', 'rowinfo' => false)
	);
	
	$data['rowInfoBtns'] = array(
							array('html'=> 'edit/lihat detil', 'title'=>'Ubah data/lihat detil '.$this->title, 'url'=> $this->title.'/editor/edit/', 'class'=> 'btnedit aw'),
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
	
	$data['editdelid'] = 'idobat';
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
				log_message('error','--aaaaa:'. var_export($data['allrows'],true));
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
    $dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js','Picker.js','Picker.Attach.js','Picker.Date.js','Meio.Mask.js','Meio.Mask.Fixed.js','Meio.Mask.Extras.js');
    $dheader['jsText'] = 'window.addEvent("domready", function(){
                  //init_block();
                 init_numonly();
                 new DatePicker($("expired"), {
                    pickerClass: "datepicker",
                    allowEmpty: false,
                    format: "%d-%b-%Y",onSelect: function(date){
                                $("expired_tmp").set("value", date.format("%Y-%m-%d"));
                              }
                  });

                 var supplierSuggest = onSuggest.pass(["supplier",["idsupplier"], ["id"]]);
                 predict_supplier = new MavSuggest.Request.JSON({
                    "elem": "supplier",
                    "url":"'.base_url().'ajaxquery/supplier",
                    "requestVar": "supplier", 
                    "singleMode": true,
                    "onSelect": supplierSuggest
                  });
                 
                  chgBlur("supplier","Cari Disini", ["idsupplier"], [""]);
                  stopEnter("frmeditor");
                
                });';
    
    $this->load->helper('date');
    
	$data['form_action'] = $this->title."/ajax_save_add";
	$data['page_title'] = ucfirst(strtolower($this->title)). ' Baru';

	$data['item_id'] = "";
	$data['input_list'] = array(
		'supplier' => array('type' => 'text', 'title' => 'Supplier', 'value'=> 'Cari Disini'),
		'nama' => array('type' => 'text', 'title' => 'Nama', 'value'=> ''),
		'satuan' => array('type' => 'select', 'title' => 'Satuan', 'select_list'=> array ('Tablet' => 'Tablet', 'Pot' => 'Pot', 'Box' => 'Box', 'Tube' => 'Tube', 'Box' => 'Box', 'Botol' => 'Botol', 'Kapsul' => 'Kapsul', 'Pcs' => 'Pcs')),
		'hargabeli' => array('type' => 'text', 'title' => 'Harga Beli', 'value'=> '', 'class'=>'numonly'),
		'hargajual' => array('type' => 'text', 'title' => 'Harga Jual', 'value'=> '', 'class'=>'numonly')
	);
	
	$data['input_list_right'] = array(
		'merek' => array('type' => 'text', 'title' => 'Merek', 'value'=> ''),
		'nobatch' => array('type' => 'text', 'title' => 'No Batch', 'value'=> ''),
		'jasaapt' => array('type' => 'text', 'title' => 'Jasa Apoteker', 'value'=> '', 'class'=>'numonly'),
		'expired' => array('type' => 'text', 'title' => 'Expired', 'value'=> '', 'class'=> 'buttoncal')
	);
	
	$data['input_list_hidden'] = array(
		'id' => array('value' => ''),
		'idsupplier' => array('value' => ''),
		'expired_tmp' => array('value' => '')
	);
	
	$data['saveable'] = true;
	
    
	if($action == 'edit'){
			
		$data['form_action'] = $this->title."/ajax_save_edit";
		$data['page_title'] = 'Ubah '. ucfirst(strtolower($this->title));
		
		$rs_edit = $this->$titledb->get_data_by_id($id);
		if(!$rs_edit){
			$flashData['message'] = 'Data '.$this->title.' tidak valid.';
	        $flashData['messageClass'] = "error";
	        $this->session->set_flashdata('results', $flashData);
	        redirect($this->title.'/browse');
		}else{
		
			$data['item_id'] = $id;
			$data['input_list']['supplier']['value'] = $rs_edit['namasupplier'];
			$data['input_list']['nama']['value'] = $rs_edit['namaobat'];
			$data['input_list']['satuan']['value'] = $rs_edit['satuan'];
			$data['input_list']['hargabeli']['value'] = $rs_edit['hargabeli'];
			$data['input_list']['hargajual']['value'] = $rs_edit['hargajual'];
			
			$data['input_list_right']['merek']['value'] = $rs_edit['merek'];
			$data['input_list_right']['expired']['value'] = mdate('%d-%M-%Y', strtotime($rs_edit['expired']));
			$data['input_list_right']['nobatch']['value'] = $rs_edit['nobatch'];
			$data['input_list_right']['jasaapt']['value'] = $rs_edit['jasaapt'];
			
			$data['input_list_hidden']['id']['value'] = $rs_edit['idobat'];
			$data['input_list_hidden']['idsupplier']['value'] = $rs_edit['idsupplier'];
			$data['input_list_hidden']['expired_tmp']['value'] = $rs_edit['expired'];
			
			
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
  
  //$this->form_validation->set_rules('quantity', 'Quantity', 'strip_html_comment|test_null_tags|required|numeric|xss_clean');
  $this->form_validation->set_rules('idsupplier', 'Supplier', 'strip_html_comment|test_null_tags|required|xss_clean');
  $this->form_validation->set_rules('supplier', 'Supplier', 'strip_html_comment|test_null_tags|required|min_length[3]|callback_search_check|xss_clean');
  $this->form_validation->set_rules('nama', 'Nama Obat', 'strip_html_comment|test_null_tags|required|callback_nama_check|xss_clean');	
  $this->form_validation->set_rules('satuan', 'Satuan', 'strip_html_comment|test_null_tags|xss_clean');
  $this->form_validation->set_rules('hargabeli', 'Harga Beli', 'strip_html_comment|test_null_tags|required|min_length[3]|numeric|xss_clean');
  $this->form_validation->set_rules('hargajual', 'Harga Jual', 'strip_html_comment|test_null_tags|required|min_length[3]|numeric|xss_clean');
  $this->form_validation->set_rules('expired', 'Expired Date', 'strip_html_comment|test_null_tags|xss_clean');
  $this->form_validation->set_rules('merek', 'Merek', 'strip_html_comment|test_null_tags|min_length[3]|xss_clean');
  $this->form_validation->set_rules('nobatch', 'No. Batch', 'strip_html_comment|test_null_tags|xss_clean');
  $this->form_validation->set_rules('jasaapt', 'Jasa Apoteker', 'strip_html_comment|test_null_tags|min_length[3]|numeric|xss_clean');
  
  $is_passed =  $this->form_validation->run();

  $flashData['message'] = 'Gagal menambah '. $this->title .' baru.';
  $flashData['messageClass'] = "error";
  $flashData['posts']['nama'] = $this->input->post('nama');
  $flashData['posts']['idsupplier'] = $this->input->post('idsupplier');
  $flashData['posts']['supplier'] = $this->input->post('supplier');
  $flashData['posts']['satuan'] = $this->input->post('satuan');
  $flashData['posts']['hargabeli'] = $this->input->post('hargabeli');
  $flashData['posts']['hargajual'] = $this->input->post('hargajual');
  $flashData['posts']['expired'] = $this->input->post('expired_tmp');
  $flashData['posts']['merek'] = $this->input->post('merek');
  $flashData['posts']['nobatch'] = $this->input->post('nobatch');
  $flashData['posts']['jasaapt'] = $this->input->post('jasaapt');
  
  if($flashData['posts']['jasaapt'] == ''){
    $flashData['posts']['jasaapt'] = NULL;
  }
  if($flashData['posts']['expired'] == ''){
    $flashData['posts']['expired'] = NULL;
  }
	   
  if ($is_passed === FALSE){
    	$this->form_validation->set_error_delimiters('', '');
		$flashData['errors'] = array(
      		'nama'=>form_error('nama'),
      		'supplier'=>form_error('supplier'),
      		'satuan'=>form_error('satuan'),
      		'hargabeli'=>form_error('hargabeli'),
      		'hargajual'=>form_error('hargajual'),
      		'expired'=>form_error('expired'),
      		'merek'=>form_error('merek'),
      		'nobatch'=>form_error('nobatch'),
      		'jasaapt'=>form_error('jasaapt')
		);   
  }
  else{
    	//passed validation
		if($action == 'add'){
						
			$this->$titledb->add(array(
			 'nama'=>$flashData['posts']['nama'],
			 'idsupplier'=>$flashData['posts']['idsupplier'],
	         'satuan'=>$flashData['posts']['satuan'],
	         'hargabeli'=>$flashData['posts']['hargabeli'],
	         'hargajual'=>$flashData['posts']['hargajual'],
	         'expired'=>$flashData['posts']['expired'],
	         'merek'=>$flashData['posts']['merek'],
	         'nobatch'=>$flashData['posts']['nobatch'],
	         'jasaapt'=>$flashData['posts']['jasaapt'])
	    	);
	    }else{
	    	
			//EDIT
			$chg_code = FALSE;
			$temp_id = $this->$titledb->get_data_by_id($flashData['posts']['id']);
			if(substr($flashData['posts']['nama'],0,1)!=substr($temp_id['namaobat'],0,1)){
				$chg_code = TRUE;
			}
			$this->$titledb->edit(array(
			 'nama'=>$flashData['posts']['nama'],
             'idsupplier'=>$flashData['posts']['idsupplier'],
             'satuan'=>$flashData['posts']['satuan'],
             'hargabeli'=>$flashData['posts']['hargabeli'],
             'hargajual'=>$flashData['posts']['hargajual'],
             'expired'=>$flashData['posts']['expired'],
             'merek'=>$flashData['posts']['merek'],
             'nobatch'=>$flashData['posts']['nobatch'],
             'jasaapt'=>$flashData['posts']['jasaapt'],
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

function search_check($str){
    if((strtolower($str) == 'cari disini')||($str == '')){
        $this->form_validation->set_message('search_check', '%s harus diisi');
        return FALSE;
    }else{
        return TRUE;
    }
}

function idsup_check($str){
    $this->load->model('suppliermodel');
    if($this->suppliermodel->get_data_by_id($str)){
        return TRUE;
    }else{
        $this->form_validation->set_message('idsup_check', '%s tidak valid');
        return FALSE;
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
  
function delete($id){
    //if($this->tank_auth->is_admin_logged_in()){
      
    $titledb = $this->titledb;
	if($this->$titledb->get_data_by_id($id)){
        
      $titfunc1 = 'search_'.$this->title;
	  $datadel = $this->$titledb->$titfunc1($id);
	  //log_message('error','---datadel: '. var_export($datadel,true));
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
	//$this->browse();
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
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */