<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kasir extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();

function __construct(){
    parent::__construct();
	if($this->tank_auth->is_logged_in() ){
		
		$this->load->model('mastertransaksimodel');
		$jabatan = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id()); 
		$this->allowed_level = $this->config->item('allowed_level_pegawai');
		$this->allowed_level_admin = $this->config->item('allowed_level_pegawai_admin');
		
		if(in_array($jabatan,$this->allowed_level)){
			
			$this->dheader['userId']  = $this->tank_auth->get_user_id();
			$this->dheader['userName']  = $this->tank_auth->get_username();
			$this->dheader['jabatan']  = $jabatan;
			$this->dheader['bodyId'] = 'body-kasir';
			$this->dheader['selMenu'] = 'request';	
		}else{
			$this->session->set_flashdata('results', array('message'=>'You do not have access level to the previous page, please login using appropriate credentials', 'messageClass'=>'error'));
			redirect('login/logout');
		}
		
	}else{
		$this->session->set_flashdata('adminfrom', '/kasir');
		$this->session->set_flashdata('results', array('message'=>'Your session is expired, you need to login again.', 'messageClass'=>'updated'));
		redirect('login');
	}
         
}

function index(){
    $this->browse();  
}
  
function browse($page = 1){
	$this->dheader['pageTitle'] = 'Master Transaksi';
	$this->dheader['cssFiles'] = array('datepicker.css');
    $this->dheader['jsFiles'] = array('utilities.js','Picker.js','Picker.Attach.js','Picker.Date.js');
    $this->dheader['jsText'] = 'window.addEvent("domready", function(){

                  new DatePicker($("tglsearch"), {
                    allowEmpty: false,
                    format: "%d-%b-%Y",onSelect: function(date){
                      $("tglsearch_tmp").set("value", date.format("%Y-%m-%d"));
                      $("browseform").submit();
                    } 
                  });
                                 
                });';
   
	$data['searchtglmode'] = true;
    $data['keyword'] = null;
	$data['curpage'] = $page;
	$data['curdate'] = date('Y-m-d');
	
	//new trans hack
	if($page < 0){
		$this->dheader['message'] = "Sukses menambah transaksi baru";
		$this->dheader['messageClass'] = 'success';
		if($page <-29){
			$this->dheader['message'] = "Transaksi belum dibatalkan";
			$this->dheader['messageClass'] = 'error';
		}
	if($page <-39){
			$this->dheader['message'] = "Transaksi sudah pernah di retur";
			$this->dheader['messageClass'] = 'error';
		}	
	}
	
	$data['urlFilter'] = "kasir/filter";
	$data['url_browse'] = "kasir/browse/";
	
	$data['isAddAble'] = true;
	$data['urlNew'] = "kasir/new_trans";
	$data['addTitle'] = "buat transaksi baru";
		
	$data['columnHeaders'] = array(
							array('header_title'=>'Kode', 'field_name'=> 'kode', 'class'=>'rowhead', 'width'=>'10%', 'rowinfo' => false),
							array('header_title'=>'Tgl', 'field_name'=> 'tgl', 'width'=>'10%', 'rowinfo' => false, 'format'=> 'echo mdate("%d-%M-%Y", strtotime($row["tgl"]));'),
							array('header_title'=>'Pasien', 'field_name'=> 'namapasien', 'width'=>'30%', 'rowinfo' => false,'format' => 'echo character_limiter($row["namapasien"], 20);'),
							array('header_title'=>'Pegawai', 'field_name'=> 'namapegawai', 'width'=>'15%', 'rowinfo' => false, 'format' => 'echo character_limiter($row["namapegawai"], 20);'),
							array('header_title'=>'Status', 'field_name'=> 'batal_oleh', 'width'=>'15%', 'rowinfo' => false, 'format' => 'if($row["batal_oleh"]){echo "batal";}else{echo "aktif";}'),
							array('header_title'=>'Total', 'field_name'=> 'total', 'width'=>'10%', 'rowinfo' => false),
							array('header_title'=>'Retur dari', 'field_name'=> 'retur_dari', 'width'=>'10%', 'rowinfo' => true),
							array('header_title'=>'Sisa', 'field_name'=> 'sisa', 'width'=>'10%', 'rowinfo' => false, 'format' => 'if($row["sisa"] <= 0){echo "LUNAS";}else{echo $row["sisa"];}')	
	);
	
	$urled = 'kasir/editor/';
	if(in_array($this->dheader['jabatan'],$this->allowed_level_admin)){
		$urled = 'kasir/editor/';
	}
	
	$data['rowInfoBtns'] = array(
							array('html'=> 'view/edit', 'title'=>'edit transaksi', 'url'=> $urled, 'class'=> 'btnedit aw')
							
	);
	$data['editdelid'] = 'id';
	
	
	if($this->session->userdata('keyword_kasir')){
		$data['keyword'] = $keyword = $this->session->userdata('keyword_kasir');
	}
	if($this->session->userdata('date_kasir')){
		$data['curdate'] = $this->session->userdata('date_kasir');
	}
	
	$allData = $this->mastertransaksimodel->get_browse($data['keyword'], $data['curdate'], $data['curpage']);
	$data['rowcount'] = $allData['rowcount'];
	$data['curpage'] = $allData['curpage'];
	$data['maxpage'] = $allData['maxpage'];
	$data['allrows'] = $allData['rows'];
				
	$data['results_per_page'] = $this->config->item('results_per_page');
	
	$flashData = $this->session->flashdata('results');
	if($flashData){
		$this->dheader['message'] = $flashData['message'];
		$this->dheader['messageClass'] = $flashData['messageClass'];
	}	
	
	$this->load->view('header',$this->dheader);
	$this->load->view('browse',$data);	
	$this->load->view('footer');
}
  
function new_trans(){
    $data['userId']  = $this->tank_auth->get_user_id();
  	$data['userName']  = $this->tank_auth->get_username();
  	$data['jabatan']  = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id());
  	$data['bodyId'] = 'item';
  	$data['selMenu'] = 'inventory';
  	$data['subMenu'] = 'item';
  	$data['pageTitle'] = 'Transaksi';
  	$data['cssFiles'] = array('kasir.css','mavsuggest.css');
  	$data['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js');
  	$data['jsText'] = 'window.addEvent("domready", function(){
                  var srcin = "namapasien";
                  var PatientSuggest = onSuggest.pass([srcin, [srcin, "idpasien"], ["nama", "id"]]);
                                                      
                  predictPatient = new MavSuggest.Request.JSON({
                    "elem": $(srcin),
                    "url":"'.base_url().'ajaxquery/pasien/",
                    "requestVar": "namapasien",
                    "singleMode": true,
                    "onSelect": PatientSuggest
                  });
                  
                  chgBlur(srcin,"Cari Nama Pasien", [srcin,"idpasien"], ["",""]);
				  
				  predictItem = new MavSuggest.Request.JSON({
				  	"append": $("items"),
                    "elem": $("itemsearch"),
                    "url":"'.base_url().'ajaxquery/kasir_search/",
                    "requestVar": "itemsearch",
                    "singleMode": true,
                    "minLength": 3,
                    "noResults": "No matches found.",
                    "onSelect": onselItem
                  });
                  $("itemsearch").addEvent("focus", function(){
                  	$("itemsearch").value="";
				  });
				  $("itemsearch").addEvent("blur", function(){
					$("itemsearch").value="Tambah Barang";
				  });               
                  
				  stopEnter("frmeditor"); 
                  //alert("sdsf");             
                });';
  
  	$data['form_action'] = "ajax_save_add/";
	$data['url_browse'] = "kasir";
	$data['kode'] = $this->mastertransaksimodel->get_next_kode();
	$data['tgl'] = date('d/m/Y');
	$data['tgltrans'] = date('Y-m-d');
	$data['metodeedc'] = $this->config->item('edc_methods');
	$data['biayaTotal'] = '0';
	$data['biayaKonsul'] = '0';
	$data['biayaAdmin'] = '0';
	$data['ketTrans'] = '';
	$data['sisa'] = '0';
	$data['biayaTunai'] = '0';
	$data['taxes'] = $this->config->item('edc_tax');
	
	$data['isAddAble'] = true;
	$data['addTitle'] = "add new item";
		
	$data['rite_columns'] = array(
							array('header_title'=>'No', 'field_name'=> 'namabarang', 'class'=>'rowhead acenter', 'width'=>'5%', 'rowinfo' => false),
							array('header_title'=>'Kode', 'field_name'=> 'serialnum', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead'),
							array('header_title'=>'Nama', 'field_name'=> 'namakategori', 'width'=>'35%', 'rowinfo' => false, 'class'=>'acenter'),
							array('header_title'=>'Harga', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead'),
							array('header_title'=>'Jumlah', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter'),
							array('header_title'=>'SubTotal', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead')	
	);
	
	$data['rowInfoBtns'] = array(
							array('html'=> 'edit/view', 'title'=>'view/edit this item', 'url'=> 'item/editor/edit/', 'class'=> 'btnedit aw'),
							array('html'=> 'view status', 'title'=>'view status item ',  'field_name'=>'namabarang', 'url'=> 'statusitem/browse/1/', 'class'=> 'btnedit aw'),
							array('html'=> 'delete', 'title'=>'delete item ',  'field_name'=>'namabarang', 'url'=> 'item/delete/', 'class'=> 'btndel')
							
	);
	$data['editdelid'] = 'idbarang';
	$data['delete_str'] = 'Delete Item ';
	$data['delete_title'] = 'namabarang';
	
	
	if($this->session->userdata('keyword_item')){
		$keyword = $this->session->userdata('keyword_item');
		$data['keyword'] = $keyword;
	}
	
	$data['obatdata'] = null;
	$data['tindakandata'] = null;
	
	$flashData = $this->session->flashdata('results');
	if($flashData){
		$data['message'] = $flashData['message'];
		$data['messageClass'] = $flashData['messageClass'];
	}

    //$this->load->view('header',$this->dheader);
    $this->load->view('kasir',$data);
    $this->load->view('footer');
}

function editor($idtransaksi){
	
	$data['userId']  = $this->tank_auth->get_user_id();
  	$data['userName']  = $this->tank_auth->get_username();
  	$data['jabatan']  = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id());
  	$data['bodyId'] = 'item';
  	$data['selMenu'] = 'inventory';
  	$data['subMenu'] = 'item';
  	$data['pageTitle'] = 'Transaksi';
  	$data['cssFiles'] = array('kasir.css','mavsuggest.css');
  	$data['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js');
  	
  	$data['jsText'] = 'window.addEvent("domready", function(){              
                        count_kasir();
				  		stopEnter("frmeditor"); 
                  //alert("sdsf");             
                });';
  
  	$data['form_action'] = base_url()."kasir/ajax_save_edit/";
	$data['url_browse'] = "kasir";
	$data['metodeedc'] = $this->config->item('edc_methods');
	$data['taxes'] = $this->config->item('edc_tax');
	$allData = $this->mastertransaksimodel->get_details($idtransaksi);
	
	if ($allData){
		//log_message('error', var_export($allData,true));
		$this->load->helper('date');
		
		$data['kode'] = $allData['masterdata']['kode'];
		$unix = human_to_unix($allData['masterdata']['tgl']);
		$data['tgl'] = date('d/m/Y', $unix);
		$data['tgltrans'] = $allData['masterdata']['tgl'];
		$data['biayaTotal'] = $allData['masterdata']['total'];
		$data['biayaKonsul'] = $allData['masterdata']['jasadokter'];
		$data['biayaAdmin'] = $allData['masterdata']['biayaadmin'];
		$data['ketTrans'] = $allData['masterdata']['ket'];
		$data['sisa'] = $allData['masterdata']['sisa'];
		$data['biayaTunai'] = $allData['masterdata']['biayatunai'];
		$data['idtrans'] = $idtransaksi;
		
		$data['totaledc'] = 0;
  		if($allData['edcdata'])
  		foreach($allData['edcdata'] as $edcdata){
  			$data['totaledc'] = $data['totaledc']+$edcdata['jumlah'];
  		}
		
		$data['isAddAble'] = false;
		$data['addTitle'] = "add new item";
			
		$data['rite_columns'] = array(
								array('header_title'=>'No', 'field_name'=> 'namabarang', 'class'=>'rowhead acenter', 'width'=>'5%', 'rowinfo' => false),
								array('header_title'=>'Kode', 'field_name'=> 'serialnum', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead'),
								array('header_title'=>'Nama', 'field_name'=> 'namakategori', 'width'=>'35%', 'rowinfo' => false, 'class'=>'acenter'),
								array('header_title'=>'Harga', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead'),
								array('header_title'=>'Jumlah', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter'),
								array('header_title'=>'SubTotal', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead')	
		);
		
		$data['rowInfoBtns'] = array(
								array('html'=> 'edit/view', 'title'=>'view/edit this item', 'url'=> 'item/editor/edit/', 'class'=> 'btnedit aw'),
								array('html'=> 'view status', 'title'=>'view status item ',  'field_name'=>'namabarang', 'url'=> 'statusitem/browse/1/', 'class'=> 'btnedit aw'),
								array('html'=> 'delete', 'title'=>'delete item ',  'field_name'=>'namabarang', 'url'=> 'item/delete/', 'class'=> 'btndel')
								
		);
		$data['editdelid'] = 'idbarang';
		$data['delete_str'] = 'Delete Item ';
		$data['delete_title'] = 'namabarang';
		
		
		if($this->session->userdata('keyword_item')){
			$keyword = $this->session->userdata('keyword_item');
			$data['keyword'] = $keyword;
		}
		
		$data['masterdata'] = $allData['masterdata'];
		$data['obatdata'] = $allData['obatdata'];
		$data['tindakandata'] = $allData['tindakandata'];
		$data['edcdata'] = $allData['edcdata'];
		
		$flashData = $this->session->flashdata('results');
		if($flashData){
			$data['message'] = $flashData['message'];
			$data['messageClass'] = $flashData['messageClass'];
		}
	
	    //$this->load->view('header',$this->dheader);
	    $this->load->view('kasir',$data);
	    $this->load->view('footer');	
	
	}else{
		$flashData['message'] = 'kode transaksi invalid!';
        $flashData['messageClass'] = "error";
        $this->session->set_flashdata('results', $flashData);
        redirect('kasir');
	}
	
}

function retur($idtransaksi){
	
	$data['userId']  = $this->tank_auth->get_user_id();
  	$data['userName']  = $this->tank_auth->get_username();
  	$data['jabatan']  = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id());
  	$data['bodyId'] = 'item';
  	$data['selMenu'] = 'inventory';
  	$data['subMenu'] = 'item';
  	$data['pageTitle'] = 'Transaksi';
  	$data['cssFiles'] = array('kasir.css','mavsuggest.css');
  	$data['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js');
  	
  	$data['jsText'] = 'window.addEvent("domready", function(){              
                                                
                        predictItem = new MavSuggest.Request.JSON({
						  	"append": $("items"),
		                    "elem": $("itemsearch"),
		                    "url":"'.base_url().'ajaxquery/kasir_search/",
		                    "requestVar": "itemsearch",
		                    "singleMode": true,
		                    "minLength": 3,
		                    "noResults": "No matches found.",
		                    "onSelect": onselItem
		                  });
		                  $("itemsearch").addEvent("focus", function(){
		                  	$("itemsearch").value="";
						  });
						  $("itemsearch").addEvent("blur", function(){
							$("itemsearch").value="Tambah Barang";
						  });
                        
						count_kasir();
						
				  		stopEnter("frmeditor"); 
                  //alert("sdsf");             
                });';
  
  	$data['form_action'] = base_url()."kasir/ajax_save_retur/";
	$data['url_browse'] = "kasir";
	
	$data['metodeedc'] = $this->config->item('edc_methods');
	$data['taxes'] = $this->config->item('edc_tax');
	$allData = $this->mastertransaksimodel->get_details($idtransaksi);
	
	if ($allData){
	
		if(false){
			//$this->mastertransaksimodel->is_returned($allData['masterdata']['kode'])
			$flashData['message'] = 'transaksi <b>#'.$allData['masterdata']['kode'].'</b> sudah pernah di retur!';
	        $flashData['messageClass'] = "error";
	        $this->session->set_flashdata('results', $flashData);
	        redirect('kasir');
		}else{
		
			//log_message('error', var_export($allData,true));
			$this->load->helper('date');
			
			$data['kode'] = '[retur] '.$allData['masterdata']['kode'];
			$data['tgl'] = date('d/m/Y');
			$data['tgltrans'] = date('Y-m-d');
			$data['biayaTotal'] = $allData['masterdata']['total'];
			$data['biayaKonsul'] = $allData['masterdata']['jasadokter'];
			$data['biayaAdmin'] = $allData['masterdata']['biayaadmin'];
			$data['ketTrans'] = $allData['masterdata']['ket'];
			$data['sisa'] = $allData['masterdata']['sisa'];
			$data['biayaTunai'] = $allData['masterdata']['biayatunai'];
			$data['idtrans'] = $idtransaksi;
			
			$data['totaledc'] = 0;
	  		if($allData['edcdata'])
	  		foreach($allData['edcdata'] as $edcdata){
	  			$data['totaledc'] = $data['totaledc']+$edcdata['jumlah'];
	  		}
			
			$data['isAddAble'] = false;
			$data['returMode'] = false;
			$data['addTitle'] = "add new item";
				
			$data['rite_columns'] = array(
									array('header_title'=>'No', 'field_name'=> 'namabarang', 'class'=>'rowhead acenter', 'width'=>'5%', 'rowinfo' => false),
									array('header_title'=>'Kode', 'field_name'=> 'serialnum', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead'),
									array('header_title'=>'Nama', 'field_name'=> 'namakategori', 'width'=>'35%', 'rowinfo' => false, 'class'=>'acenter'),
									array('header_title'=>'Harga', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead'),
									array('header_title'=>'Jumlah', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter'),
									array('header_title'=>'SubTotal', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead')	
			);
			
			$data['rowInfoBtns'] = array(
									array('html'=> 'edit/view', 'title'=>'view/edit this item', 'url'=> 'item/editor/edit/', 'class'=> 'btnedit aw'),
									array('html'=> 'view status', 'title'=>'view status item ',  'field_name'=>'namabarang', 'url'=> 'statusitem/browse/1/', 'class'=> 'btnedit aw'),
									array('html'=> 'delete', 'title'=>'delete item ',  'field_name'=>'namabarang', 'url'=> 'item/delete/', 'class'=> 'btndel')
									
			);
			$data['editdelid'] = 'idbarang';
			$data['delete_str'] = 'Delete Item ';
			$data['delete_title'] = 'namabarang';
			
			
			if($this->session->userdata('keyword_item')){
				$keyword = $this->session->userdata('keyword_item');
				$data['keyword'] = $keyword;
			}
			
			$data['masterdata'] = $allData['masterdata'];
			$data['obatdata'] = $allData['obatdata'];
			$data['tindakandata'] = $allData['tindakandata'];
			$data['edcdata'] = $allData['edcdata'];
			$data['message'] = 'proses retur dari transaksi #'.$allData['masterdata']['kode'];
			$data['messageClass'] = 'success';
			
			$flashData = $this->session->flashdata('results');
			if($flashData){
				$data['message'] = $flashData['message'];
				$data['messageClass'] = $flashData['messageClass'];
			}
		
		    //$this->load->view('header',$this->dheader);
		    $this->load->view('kasir',$data);
		    $this->load->view('footer');
		}
	
	}else{
		$flashData['message'] = 'kode transaksi invalid!';
        $flashData['messageClass'] = "error";
        $this->session->set_flashdata('results', $flashData);
        redirect('kasir');
	}
	
}

function _save($action = 'add'){
	
	$this->load->library('form_validation');
	$this->load->helper('ozl');
	$id_pegawai = $this->tank_auth->get_user_id();
      
	if($action == 'add' || $action == 'retur'){
  		$this->form_validation->set_rules('tgltrans', 'Tanggal', 'strip_html_comment|required|xss_clean');
		$this->form_validation->set_rules('iditem', 'Barang', 'required|xss_clean');
		$this->form_validation->set_rules('bkonsul', 'Biaya Konsul', 'numeric|xss_clean');
  		$this->form_validation->set_rules('badmin', 'Biaya Admin', 'numeric|xss_clean');
  		$this->form_validation->set_rules('badmin', 'Biaya Admin', 'numeric|xss_clean|required');	
  	}
  	if($action == 'add'){
	  	$this->form_validation->set_rules('idpasien', 'Nama Pasien', 'required|xss_clean');
	  	$this->form_validation->set_rules('kodetrans', 'Kode Trans', 'strip_html_comment|test_null_tags|xss_clean|required');
  	}		 
  	$this->form_validation->set_rules('ktunai', 'Biaya Tunai', 'numeric|xss_clean');
  	$this->form_validation->set_rules('kedc', 'Biaya EDC', 'numeric|xss_clean');
  	$this->form_validation->set_rules('kbname', 'Ket EDC', 'strip_html_comment|test_null_tags|xss_clean');
  	$this->form_validation->set_rules('keterangan', 'Keterangan', 'strip_html_comment|test_null_tags|xss_clean');
  	
  	$is_passed =  $this->form_validation->run();
  	  	
  	$flashData['message'] = 'Gagal menyimpan transaksi baru';
  	$flashData['messageClass'] = "error";
  	if($action == 'add' || $action == 'retur'){
  		$flashData['posts']['tgltrans'] = $this->input->post('tgltrans');
  		$flashData['posts']['bkonsul'] = $this->input->post('bkonsul');
		$flashData['posts']['badmin'] = $this->input->post('badmin');
		$flashData['posts']['lokasi'] = $this->input->post('lokasi');
  	}
	if($action == 'add'){
  		$flashData['posts']['idpasien'] = $this->input->post('idpasien');
		$flashData['posts']['kodetrans'] = $this->input->post('kodetrans');
  	}	
	$flashData['posts']['ktunai'] = $this->input->post('ktunai');
	$flashData['posts']['kedc'] = $this->input->post('kedc');
	$flashData['posts']['keterangan'] = $this->input->post('keterangan');
	$flashData['posts']['kbname'] = $this->input->post('kbname');
	$totaledc = 0;
	$allData = null;
	//retur check
	if($action == 'retur'){
  		$idmasterprev = $this->input->post('idtrans');
  		$allData = $this->mastertransaksimodel->get_details($idmasterprev);
  		if(!$allData){
  			$flashData['message'] = 'Transaksi Invalid';
        	$flashData['messageClass'] = "error";
  			$flashData['redirect'] = base_url().'kasir/browse';
  			$is_passed == FALSE;
  		}else{
  				if(!$allData['masterdata']['batal_oleh']){
  					//$flashData['message'] = 'Transaksi belum dibatalkan';
		        	//$flashData['messageClass'] = "error";
		  			$flashData['redirect'] = base_url().'kasir/browse/-30';
		  			$is_passed == FALSE;
  				}elseif($this->mastertransaksimodel->is_returned($allData['masterdata']['kode'])){
  					$flashData['redirect'] = base_url().'kasir/browse/-40';
		  			$is_passed == FALSE;
  				}else{
	  				if($allData['edcdata']){
		  				foreach($allData['edcdata'] as $edcdata){
		  					$totaledc = $totaledc+$edcdata['jumlah'];
		  				}
	  				}
  				}
  				$flashData['posts']['idpasien'] = $allData['masterdata']['idpasien'];
  		}
  		
  	}
	
	
	//item count	   
	if ($is_passed === FALSE){
    	$this->form_validation->set_error_delimiters('', '');
    	$flashData['message'] = $flashData['message']." ".validation_errors();
  	}
  	else{
    	//passed validation
    	$total = 0;
    	
  		if($action == 'add' || $action == 'retur'){
  			
	  		$this->load->model('obatmodel');
	  		$this->load->model('tindakanmodel');
	  		
	    	$arrItem = $this->input->post('iditem');
	    	$arrType = $this->input->post('tipeitem');
	    	$arrJumlah = $this->input->post('jumlah');
	    	$arrSubTotal = array();
	    	
	    	foreach($arrItem as $idx => $iditem){
	    		$price = 0; 
	    		if($arrType[$idx] == 'obat'){
	    			//Type = Obat
	    			$price = $this->obatmodel->get_price($iditem);
	    		}else{
	    			//Type = Tindakan
	    			$price = $this->tindakanmodel->get_price($iditem);
	    			$arrJumlah[$idx] = 1;
	    		}
	    		$arrSubTotal[$idx] = $price * $arrJumlah[$idx];
	    		$total = $total + $arrSubTotal[$idx];
	    	}
	    	
	  		//count total
	  		$flashData['posts']['ktotal'] = $total = $total + $flashData['posts']['bkonsul'] + $flashData['posts']['badmin'];
	  		$edcmethod = strtolower($this->input->post('metodeedc'));
	  		$metodeedc = $this->config->item('edc_methods');
	  		//log_message('error',"cek ADD : ".$edcmethod.'---'.$flashData['posts']['kedc']);
	    	if(!isset($metodeedc[$edcmethod])) $flashData['posts']['kedc'] = 0;
	  		$flashData['posts']['sisa'] = $sisa = $total - ($flashData['posts']['ktunai'] + $flashData['posts']['kedc'] + $totaledc);
	  		$tglbayar = '';
	  		if($flashData['posts']['ktunai'] > 0){
	  			/*
	  			$this->load->helper('date');
			  	$timestamp = time();
				$timezone = 'UP7';
				$daylight_saving = TRUE;
				
				$tglbayar = unix_to_human(gmt_to_local($timestamp, $timezone, $daylight_saving), TRUE, 'us');
				*/
				$tglbayar = date('Y-m-d H:i:s');
	  		}
	  		
	  		//'kode'=>$flashData['posts']['kodetrans'],
	  		$flashData['posts']['kodetrans'] = $this->mastertransaksimodel->get_next_kode(); 
	  		$arrad = array(
			    'kode'=>$flashData['posts']['kodetrans'],
			    'tgl'=>date('Y-m-d H:i:s'),
	         	'idpasien'=>$flashData['posts']['idpasien'],
	         	'jasadokter'=>$flashData['posts']['bkonsul'],
	         	'biayaadmin'=>$flashData['posts']['badmin'],
				'biayatunai'=>$flashData['posts']['ktunai'],
				'total'=>$flashData['posts']['ktotal'],
				'sisa'=>$flashData['posts']['sisa'],
				'tglbyr'=>$tglbayar,
				'idpegawai'=> $id_pegawai,
				'ket'=>$flashData['posts']['keterangan']);
	  		
	  		$returstr='';
	  		
	  		if($action == 'retur'){
	  			$arrad['retur_dari'] = $allData['masterdata']['kode'];
	  			$returstr=', Retur dari #'.$arrad['retur_dari'];
	  		}
	  		
	  		//SAVE MASTER DATA
  			$idmaster = $this->mastertransaksimodel->add($arrad);
  			
  			if($action == 'retur'){
		  		//copy previous EDC data
	  			if($allData['edcdata']){
	  				$this->load->model('pembayaranedc');
		  			foreach($allData['edcdata'] as $edcdata){
		  				$this->pembayaranedc->add(array(
				    		'idmaster' => $idmaster,
				    		'metode' => $edcdata['metode'],
				    		'tgl' => $edcdata['tgl'],
				    		'jumlah' => $edcdata['jumlah'],
				    		'jumlahplustax' => $edcdata['jumlahplustax'],
				    		'ket' => $edcdata['ket']
				    	));
		  			}
	  			}
	  		}
	    	
	    	$this->load->model('detiltransaksi');
	  		$this->load->model('historiresep');
	  		$this->load->model('historiklinikmodel');
	  		$this->load->model('kirimklinikdanreturmodel');
	  		$this->load->model('historistokobatkamarobatmodel');
	  		$this->load->model('kirimkamarobatmodel');
	  		
	    	
	    	foreach($arrItem as $idx => $iditem){
	    		if($arrType[$idx] == 'obat'){
	    			//Save obat
	    			$price = $this->obatmodel->get_price($iditem);
	    			$prevJumlah = $this->detiltransaksi->get_jumlah($idmaster, $iditem);
	    			
	    			if(is_null($prevJumlah)){
		    			$this->detiltransaksi->add(array(
		    				'idmaster' => $idmaster,
		    				'idobat' => $iditem,
		    				'jumlah' => $arrJumlah[$idx],
		    				'harga' => $price
		    			));
	    			}else{
	    				$prevJumlah = $prevJumlah + $arrJumlah[$idx];
	    				$this->detiltransaksi->edit_jumlah(array(
		    				'idmaster' => $idmaster,
		    				'idobat' => $iditem,
		    				'jumlah' => $prevJumlah
		    			));
	    			}
	    			
	    			//kurangi saldo
	    			//log_message('error', 'lokasi:'.$flashData['posts']['lokasi'][$idx]);
	    			if ($flashData['posts']['lokasi'][$idx] == 'A'){
	    				$idhistklinik = $this->historiklinikmodel->subtract_saldo($iditem, $arrJumlah[$idx], "[Keluar dari Transaksi <strong>#".$flashData['posts']['kodetrans']."</strong>]'.$returstr.' <br/> Kasir: ".$this->tank_auth->get_username()."<br/> ID Kasir: ".$id_pegawai);
	    				/*$arrkirim = array(
	    					'idhistoriklinik' => $idhistklinik,
	    					'tgl' => date('Y-m-d H:i:s'),
	    					'kondisi' => 'Baik',
	    					'pemesan' => $id_pegawai,
	    					'pengurus' => $id_pegawai,
	    					'pengirim' => $id_pegawai,
	    					'ket' => '[Kasir] id-trans : '.$idmaster
	    				);
	    				$this->kirimklinikdanreturmodel->add($arrkirim);*/
	    			}else{
	    				$idhistkm = $this->historistokobatkamarobatmodel->subtract_saldo($iditem, $arrJumlah[$idx], "[Keluar dari Transaksi #".$flashData['posts']['kodetrans']."]'.$returstr.' <br/> Kasir: ".$this->tank_auth->get_username()."<br/> ID Kasir: ".$id_pegawai);
	    				/*$arrkirim = array(
	    					'idhistoridepo' => $idhistkm,
	    					'tgl' => date('Y-m-d H:i:s'),
	    					'kondisi' => 'Baik',
	    					'pemesan' => $id_pegawai,
	    					'pengurus' => $id_pegawai,
	    					'pengirim' => $id_pegawai,
	    					'ket' => '[Kasir] id-trans : '.$idmaster
	    				);
	    				$this->kirimkamarobatmodel->add($arrkirim);*/
	    			}
	    			
	    			
	    		}else{
	    			//Save Tindakan
	    			$this->historiresep->add(array(
	    				'idmaster' => $idmaster,
	    				'idtindakan' => $iditem,
	    				'harga' => $arrSubTotal[$idx]
	    			));
	    		}
	    	}
	        
	        $flashData['message'] = 'Transaksi <b>'.$flashData['posts']['kodetrans'].'</b> berhasil disimpan.';
	        $flashData['messageClass'] = "success";
	        $flashData['redirect'] = base_url().'kasir/browse/-20';
	  		
  		}//endif($action == 'add')
  		else{
  			// action == edit
  			$idmaster = $this->input->post('idtrans');
  			$allData = $this->mastertransaksimodel->get_details($idmaster);
  			if(!$allData){
  				$flashData['message'] = 'Transaksi Invalid';
	        	$flashData['messageClass'] = "error";
  				$flashData['redirect'] = base_url().'kasir/browse';
  			}else{
  				$newtunai = $flashData['posts']['ktunai'];
  				$tglbayar = $allData['masterdata']['tglbyr'];
  				$biayatunai = $allData['masterdata']['biayatunai'];
  				$sisa = $allData['masterdata']['sisa'];
  				$total = $allData['masterdata']['total'];
  				
  				$totaledc = 0;
  				if($allData['edcdata'])
  				foreach($allData['edcdata'] as $edcdata){
  					$totaledc = $totaledc+$edcdata['jumlah'];
  				}
  				
  				if($newtunai != $biayatunai){
  				
  					$tglbayar = date('Y-m-d H:i:s');  				
  				
  				}
  				$edcmethod = strtolower($this->input->post('metodeedc'));
  				$metodeedc = $this->config->item('edc_methods');
  				//log_message('error',$edcmethod.'---'.$flashData['posts']['kedc']);
				if(!isset($metodeedc[$edcmethod])) $flashData['posts']['kedc'] = 0;
  				$flashData['posts']['sisa'] = $sisa = $total - ($newtunai + $flashData['posts']['kedc'] + $totaledc);
  				//log_message('error',$edcmethod.'---------'.$flashData['posts']['kedc']);
  				
  				$this->mastertransaksimodel->edit(array(
				    'id'=>$idmaster,
					'biayatunai'=>$newtunai,
					'sisa'=>$sisa,
					'tglbyr'=>$tglbayar,
					'idpegawai'=> $id_pegawai,
					'ket'=>$flashData['posts']['keterangan']
	    		));
	    		$flashData['message'] = 'Transaksi <b>'.$allData['masterdata']['kode'].'</b> berhasil diupdate ';
	        	$flashData['messageClass'] = "success";
	        	$flashData['redirect'] = base_url().'kasir/editor/'.$idmaster;
  			}
  			
  		
  		}
    	
  		//EDC PAYMENT
  		$edcmethod = strtolower($this->input->post('metodeedc'));
	    $metodeedc = $this->config->item('edc_methods');
	    $taxedc = $this->config->item('edc_tax');
	    //log_message('error',"nambah: ".$edcmethod.'---'.$flashData['posts']['kedc']);
	    if( $flashData['posts']['kedc'] > 0){
	    	$aftertax = $flashData['posts']['kedc'] + ($flashData['posts']['kedc'] * ($taxedc[$edcmethod]/100));
	    	$this->load->model('pembayaranedc');
	    	$this->pembayaranedc->add(array(
	    		'idmaster' => $idmaster,
	    		'metode' => $edcmethod,
	    		'tgl' => date('Y-m-d H:i:s'),
	    		'jumlah' => $flashData['posts']['kedc'],
	    		'jumlahplustax' => $aftertax,
	    		'ket' => $flashData['posts']['kbname']
	    	));
	    }
	    
	    if($action == 'add'){
	    	foreach($flashData['posts'] as $idx => $el){
    		  	$flashData['posts'][$idx] = '';
    		}
	    }else{
	    	$flashData['posts']['sisa']=number_format($flashData['posts']['sisa'], 0, ',', '.');
	    }
      
    }
    
    return $flashData;
}

function ajax_save_add(){
  if($this->tank_auth->is_logged_in()){
    
    $flashData = $this->_save('add');
	
    //$result = array('message' => $flashData['message'], 'messageClass' => $flashData['messageClass']);
    
    echo json_encode($flashData);

  }else{
    $flashData['redirect'] = base_url().'login';
    echo json_encode($flashData);
  }
}

function ajax_save_edit(){
  if($this->tank_auth->is_logged_in()){
    
    $flashData = $this->_save('edit');
	
    //$result = array('message' => $flashData['message'], 'messageClass' => $flashData['messageClass']);
    
    echo json_encode($flashData);

  }else{
    $flashData['redirect'] = base_url().'login';
    echo json_encode($flashData);
  }
}

function ajax_save_retur(){
  if($this->tank_auth->is_logged_in()){
    
    $flashData = $this->_save('retur');
	
    //$result = array('message' => $flashData['message'], 'messageClass' => $flashData['messageClass']);
    
    echo json_encode($flashData);

  }else{
    $flashData['redirect'] = base_url().'login';
    echo json_encode($flashData);
  }
}
 

function batal($idtransaksi = 0){
	
	$idtransaksi = intval($idtransaksi);
	$this->load->library('form_validation');
	$this->load->helper('ozl');
	$id_pegawai = $this->tank_auth->get_user_id();
      
	
  	$allData = $this->mastertransaksimodel->get_by_id($idtransaksi);
  	  	
  	$flashData['message'] = 'transaksi tidak ditemukan';
  	$flashData['messageClass'] = "error";
  	$redirect_url = 'kasir/browse';
  	
  	$max_days = $this->config->item('max_days_to_cancel_transaction');
  	//$cont_to_retur = true;
  	
  	if($allData){
  		
  		if($this->mastertransaksimodel->is_returned($allData[0]['kode'])){
  		
  			$flashData['message'] = 'transaksi <b>#'.$allData[0]['kode'].'</b> sudah pernah diretur';
  			//echo 'transaksi sudah pernah dibatalkan sebelumnya';
  			
  		}else 
  		
  		
  		if(round(abs(time()-strtotime($allData[0]['tgl']))/60/60/24) > $max_days){
  			
  			$flashData['message'] = 'transaksi yg berumur lebih dari '.$max_days.' hari tidak bisa dibatalkan';
  			//echo 'transaksi yg berumur lebih dari '.$max_days.' hari tidak bisa dibatalkan';
  			
  		}else{
  			
  			$kode_trans = $allData[0]['kode'];
  			$id_trans = $allData[0]['id'];
  			$flashData['message'] = 'proses retur dari transaksi #'.$kode_trans;
  			$flashData['messageClass'] = 'success';
  			$redirect_url = 'kasir/retur/'.$id_trans;
  		
  			if(!($allData[0]['batal_oleh'])){
	  			//PEMBATALAN TRANSAKSI
	  			
	  			$this->load->model('historiklinikmodel');
		  		$this->load->model('historistokobatkamarobatmodel');
		  		
		  		$arrklinik = $this->historiklinikmodel->return_trans_stock($kode_trans);
		  		foreach($arrklinik as $retklinik){
		  			$this->historistokobatkamarobatmodel($retklinik['idobat'], $retklinik['debet'], '[Retur dari Transaksi #'.$kode_trans.']');
		  		}
		  		$this->historistokobatkamarobatmodel->return_trans_stock($kode_trans);
		  		$this->mastertransaksimodel->edit(array('id'=>$id_trans, 'batal_oleh'=>$id_pegawai));
		  		
		  		$flashData['message'] = 'transaksi berhasil dibatalkan. lanjut '.$flashData['message'];
		  		//echo 'batal';
  			}
  			
  			
  		}
  	}
  	
  	$this->session->set_flashdata('results', $flashData);
  	redirect($redirect_url);
}

function print_bill($idtransaksi){
	
	$data['userId']  = $this->tank_auth->get_user_id();
  	$data['userName']  = $this->tank_auth->get_username();
  	$data['jabatan']  = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id());
  	$data['bodyId'] = 'item';
  	$data['selMenu'] = 'inventory';
  	$data['subMenu'] = 'item';
  	$data['pageTitle'] = 'Transaksi';
  	$data['cssFiles'] = array('bill.css');
  	$data['jsFiles'] = array('utilities.js');
  
	$data['url_browse'] = "kasir";
	$data['metodeedc'] = $this->config->item('edc_methods');
	$data['taxes'] = $this->config->item('edc_tax');
	$allData = $this->mastertransaksimodel->get_details($idtransaksi);
	
	if ($allData){
		//log_message('error', var_export($allData,true));
		$this->load->helper('date');
		
		$data['kode'] = $allData['masterdata']['kode'];
		$unix = human_to_unix($allData['masterdata']['tgl']);
		$data['tgl'] = date('d/m/Y', $unix);
		$data['tgltrans'] = $allData['masterdata']['tgl'];
		$data['biayaTotal'] = $allData['masterdata']['total'];
		$data['biayaKonsul'] = $allData['masterdata']['jasadokter'];
		$data['biayaAdmin'] = $allData['masterdata']['biayaadmin'];
		$data['ketTrans'] = $allData['masterdata']['ket'];
		$data['sisa'] = $allData['masterdata']['sisa'];
		$data['biayaTunai'] = $allData['masterdata']['biayatunai'];
		$data['idtrans'] = $idtransaksi;
		
		$data['totaledc'] = 0;
  		if($allData['edcdata'])
  		foreach($allData['edcdata'] as $edcdata){
  			$data['totaledc'] = $data['totaledc']+$edcdata['jumlah'];
  		}
		
		$data['isAddAble'] = false;
		$data['addTitle'] = "add new item";
			
		$data['rite_columns'] = array(
								array('header_title'=>'No', 'field_name'=> 'namabarang', 'class'=>'rowhead acenter', 'width'=>'5%', 'rowinfo' => false),
								array('header_title'=>'Kode', 'field_name'=> 'serialnum', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead'),
								array('header_title'=>'Nama', 'field_name'=> 'namakategori', 'width'=>'35%', 'rowinfo' => false, 'class'=>'acenter'),
								array('header_title'=>'Harga', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead'),
								array('header_title'=>'Jumlah', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter'),
								array('header_title'=>'SubTotal', 'field_name'=> 'namamanufaktur', 'width'=>'15%', 'rowinfo' => false, 'class'=>'acenter zebrahead')	
		);
		
		$data['rowInfoBtns'] = array(
								array('html'=> 'edit/view', 'title'=>'view/edit this item', 'url'=> 'item/editor/edit/', 'class'=> 'btnedit aw'),
								array('html'=> 'view status', 'title'=>'view status item ',  'field_name'=>'namabarang', 'url'=> 'statusitem/browse/1/', 'class'=> 'btnedit aw'),
								array('html'=> 'delete', 'title'=>'delete item ',  'field_name'=>'namabarang', 'url'=> 'item/delete/', 'class'=> 'btndel')
								
		);
		$data['editdelid'] = 'idbarang';
		$data['delete_str'] = 'Delete Item ';
		$data['delete_title'] = 'namabarang';
		
		
		if($this->session->userdata('keyword_item')){
			$keyword = $this->session->userdata('keyword_item');
			$data['keyword'] = $keyword;
		}
		
		$data['masterdata'] = $allData['masterdata'];
		$data['obatdata'] = $allData['obatdata'];
		$data['tindakandata'] = $allData['tindakandata'];
		$data['edcdata'] = $allData['edcdata'];
		
		$flashData = $this->session->flashdata('results');
		if($flashData){
			$data['message'] = $flashData['message'];
			$data['messageClass'] = $flashData['messageClass'];
		}
	
	    //$this->load->view('header',$this->dheader);
	    $this->load->view('bill',$data);
	
	}else{
		$flashData['message'] = 'kode transaksi invalid!';
        $flashData['messageClass'] = "error";
        $this->session->set_flashdata('results', $flashData);
        redirect('kasir');
	}
	
}

function name_check($str){
    //log_message('error','str:'.$str.' %s:');
      
    $is_duplicate = $this->barangmodel->get_name($str);
    
    if($is_duplicate){
      $this->form_validation->set_message('name_check', '%s has already existed');
      return FALSE;
    }
    else
    {
      return TRUE;
    }
}
  
function serial_check($str){
    //log_message('error','str:'.$str.' %s:');
      
    $is_duplicate = $this->barangmodel->get_serial($str);
    
    if($is_duplicate){
      $this->form_validation->set_message('serial_check', '%s has already existed');
      return FALSE;
    }
    else
    {
      return TRUE;
    }
}

function search_check($str){
    //log_message('error','masok str:'.$str.' %s:');
    
    if($str == 'Search here'){
      $this->form_validation->set_message('search_check', '%s cant be empty');
      return FALSE;
    }
    else
    {
      return TRUE;
    }
}
  
function delete($idmanu){
    //if($this->tank_auth->is_admin_logged_in()){
    
      if($idmanu == 1){
        $flashData['message'] = 'Default item cannot be deleted';
        $flashData['messageClass'] = "error";
      }else{
      
        if($this->barangmodel->get_barang_by_id($idmanu)){
            
          /*revert all produks' kategori to default kategori
          $this->load->model('produkmodel');
          $this->produkmodel->switch_to_default_kategori($idkategori);*/
        
          $this->barangmodel->delete($idmanu);
          $flashData['message'] = 'Item is successfully deleted.';
          $flashData['messageClass'] = "success";
          
        }else{
          $flashData['message'] = 'Deletion failed. Invalid item';
          $flashData['messageClass'] = "error";
        }
      }
      
      $this->session->set_flashdata('results', $flashData);
      redirect('item/browse');
      
    /*}else{
      $this->session->set_flashdata('adminfrom', '/backend/kategori');
      redirect('backend/login');
    }*/
  
}
 
function filter(){
		
	$this->load->library('form_validation');
    $this->form_validation->set_rules('filter', 'Keyword', 'xss_clean');
    $this->form_validation->set_rules('tglsearch_tmp', 'Date', 'xss_clean');
    $is_passed = $this->form_validation->run();
    if ($is_passed == FALSE){
    	$flashData['message'] = 'Error filtering results with <b>'.$this->input->post('filter').'</b><br/>'.validation_errors();
        $flashData['messageClass'] = "error";
        $this->session->set_flashdata('results', $flashData);
    }else{
        $this->session->set_userdata('keyword_kasir', $this->input->post('filter'));
        $this->session->set_userdata('date_kasir', $this->input->post('tglsearch_tmp'));
    }
    redirect('kasir/browse');
}

}