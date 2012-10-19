<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Obat extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();

function __construct(){
    parent::__construct();
	
     $this->load->model('obatmodel');
         
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
	$this->_admin_check();
	
  $this->dheader['userId']  = $this->tank_auth->get_user_id();
  $this->dheader['userName']  = $this->tank_auth->get_username();
  $this->dheader['bodyId'] = 'item';
  $this->dheader['selMenu'] = 'inventory';
  $this->dheader['subMenu'] = 'item';
  $this->dheader['jsFiles'] = array('utilities.js');
  $this->dheader['pageTitle'] = 'Item Management';
  
  $data['keyword'] = null;
  $data['curpage'] = $page;
  $data['urlFilter'] = "item/filter";
	$data['url_browse'] = "item/browse/";
	
	$data['isAddAble'] = true;
	$data['urlNew'] = "item/editor/add";
	$data['addTitle'] = "add new item";
		
	$data['columnHeaders'] = array(
							array('header_title'=>'Name', 'field_name'=> 'namabarang', 'class'=>'rowhead', 'width'=>'14%', 'rowinfo' => false),
							array('header_title'=>'Serial Num', 'field_name'=> 'serialnum', 'width'=>'14%', 'rowinfo' => false),
							array('header_title'=>'Category', 'field_name'=> 'namakategori', 'width'=>'13%', 'rowinfo' => false),
							array('header_title'=>'Manufacture', 'field_name'=> 'namamanufaktur', 'width'=>'13%', 'rowinfo' => false),
							array('header_title'=>'Provider', 'field_name'=> 'namaprovider', 'width'=>'13%', 'rowinfo' => false),
							array('header_title'=>'Condition', 'field_name'=> 'kondisi', 'width'=>'12%', 'rowinfo' => false),
							array('header_title'=>'Purchasing', 'field_name'=> 'tglpembelian', 'width'=>'12%', 'rowinfo' => false, 'format'=> 'echo date("d-M-Y",strtotime($row["tglpembelian"]));'),
							array('header_title'=>'Division', 'field_name'=> 'namatipe', 'width'=>'9%', 'rowinfo' => false),
							array('header_title'=>'Warranty', 'field_name'=> 'masagaransi', 'rowinfo' => true),
							array('header_title'=>' || Status', 'field_name'=> 'status', 'rowinfo' => true)	
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
	
	$allData = $this->barangmodel->get_barang($data['keyword'], $data['curpage']);
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
  
function editor($action = 'add', $id_barang = null){
    $this->_admin_check();
  	$dheader['userId']  = $this->tank_auth->get_user_id();
  	$dheader['userName']  = $this->tank_auth->get_username();
  	$dheader['jabatan']  = $this->crew->get_jabatan_by_id($dheader['userId']);
    $dheader['bodyId'] = 'item';
    $dheader['selMenu'] = 'inventory';
	$dheader['subMenu'] = 'item';
    $dheader['pageTitle'] = 'Item Management';
    
    $dheader['cssFiles'] = array('mavsuggest.css','datepicker.css');
    $dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js','Picker.js','Picker.Attach.js','Picker.Date.js');
    
    $dheader['jsText'] = 'window.addEvent("domready", function(){
                  
          new DatePicker($("tglpembelian_tmp"), {
          	pickerClass: "datepicker",
          	allowEmpty: false,
          	format: "%d-%b-%Y",onSelect: function(date){
						$("tglpembelian").set("value", date.format("%Y-%m-%d"));
					  }
          });
                  
				  //["idprodusen", "idprodusen", ["idprodusen", "tmp_kodeprodusen", "src_kodeprodusen"], ["idprodusen", "kodeprodusen", "kodeprodusen"]]
				  //[input_tempat_JSON, field_yg_di_cek, [id_input_1, id_input_2, ..],[field_value_input_1, field_value_input_2, ..]]
				  var produsenSuggest = onSelSuggest.pass(["idprodusen", "idprodusen", ["idprodusen", "tmp_kodeprodusen", "src_kodeprodusen"], ["idprodusen", "kodeprodusen", "kodeprodusen"]]);
				  var divisionSuggest = onSelSuggest.pass(["idtipe", "idtipe", ["idtipe", "tmp_namatipe", "src_namatipe"], ["idtipe", "namatipe", "namatipe"]]);
	    
          var produsenSuggest = onSuggest.pass(["src_namaprovider",["idprovider"], ["idprovider"]]);
          predict_crew = new MavSuggest.Request.JSON({
            "elem": "src_namaprovider",
            "url":"'.base_url().'ajaxquery/provider/src_namaprovider",
            "requestVar": "src_namaprovider", 
            "singleMode": true,
            "onSelect": produsenSuggest
           });
           
          var manufakturSuggest = onSuggest.pass(["src_namamanu",["idmanufaktur"], ["idmanufaktur"]]);
          predict_manufacture = new MavSuggest.Request.JSON({
            "elem": "src_namamanu",
            "url":"'.base_url().'ajaxquery/manufacture/src_namamanu",
            "requestVar": "src_namamanu", 
            "singleMode": true,
            "onSelect": manufakturSuggest
           });
           
           var kategoriSuggest = onSuggest.pass(["src_namakategori",["idkategori"], ["idkategori"]]);
          predict_kategori = new MavSuggest.Request.JSON({
            "elem": "src_namakategori",
            "url":"'.base_url().'ajaxquery/category/src_namakategori",
            "requestVar": "src_namakategori", 
            "singleMode": true,
            "onSelect": kategoriSuggest
           });
          
          var tipeSuggest = onSuggest.pass(["src_namatipe",["idtipe"], ["idtipe"]]);
          predict_tipe = new MavSuggest.Request.JSON({
            "elem": "src_namatipe",
            "url":"'.base_url().'ajaxquery/division",
            "requestVar": "src_namatipe", 
            "singleMode": true,
            "onSelect": divisionSuggest
           });
           
				  chgBlur("src_namaprovider","Search here", ["idprovider"], [""]);
				  chgBlur("src_namakategori","Search here", ["idkategori"], [""]);
				  chgBlur("src_namamanu","Search here", ["idmanufaktur"], [""]);
				  chgBlur("src_namatipe","Search here", ["idtipe"], [""]);
				  /*$("src_namatipe").addEvent("blur", function(){
					if($("tmp_namatipe").value !== $("src_namatipe").value || $("src_namatipe").value.trim() == ""){
						$("tmp_namatipe").value = "";
						$("idtipe").value = "";
						$("src_namatipe").value = "Search Here";
					}
				  });		*/
				  		
				  $("src_namatipe").addEvent("focus", function(){
					if(this.value=="Search Here"){this.value = "";}
				  });
                 
          stopEnter("frmeditor");
          is_logistik("src_namatipe");
                
          });';
    
	$data['form_action'] = "item/ajax_save_add";
	$data['page_title'] = '&#91New&#93 Item';
	
	$data['rsIata'] = $this->iata->get_iata();
  $rsIata = array();
  foreach($data['rsIata'] as $iataval){
    $rsIata[$iataval['iata_code']] = $iataval['iata_code'].' - '.$iataval['nama_bandara'];
  }
  
  $data['rsTipe'] = $this->tipebarangmodel->get_all_tipe();
  $rsTipe = array();
  foreach($data['rsTipe'] as $iataval){
    $rsTipe[$iataval['idtipe']] = $iataval['namatipe'];
  }
	
	$this->load->helper('date');
	$data['item_id'] = "";
	$data['input_list'] = array(
		'tglpembelian_tmp' => array('type' => 'text', 'title' => 'Purchasing Date', 'value'=> mdate('%d-%M-%Y'), 'class'=> 'buttoncal'),
		'namabarang' => array('type' => 'text', 'title' => 'Model Name', 'value'=> ''),
		'serialnum' => array('type' => 'text', 'title' => 'Serial Number', 'value'=> ''),
		'src_namatipe' => array('type' => 'select', 'title' => 'Division src', 'value'=> '1', 'select_list'=>$rsTipe),
		'quantity' => array('type' => 'text', 'title' => 'Quantity', 'value'=> ''),
		'masagaransi' => array('type' => 'text', 'title' => 'Warranty', 'value'=> ''),
		'kondisi' => array('type' => 'select', 'title' => 'Condition', 'value'=> 'Excellent', 'select_list'=> array(
																										'Excellent'=>'Excellent',
																										'Good'=>'Good',
																										'Fine'=>'Fine',
																										'Bad'=>'Bad',
																										'Poor'=>'Poor',
																										'Broken'=>'Broken',
																										'Lost'=>'Lost'))
	);
	
	$data['input_list_right'] = array(
		'src_namakategori' => array('type' => 'text', 'title' => 'Category', 'value'=> 'Search here', 'class'=>'keywordsearch'),
		'src_namamanu' => array('type' => 'text', 'title' => 'Manufacture', 'value'=> 'Search here', 'class'=>'keywordsearch'),
		'src_namaprovider' => array('type' => 'text', 'title' => 'Provider', 'value'=> 'Search here', 'class'=>'keywordsearch'),
		'iatacode' => array('type' => 'select', 'title' => 'Location', 'value'=>'AAS', 'select_list'=> $rsIata),
		'status' => array('type' => 'textarea', 'title' => 'Remark', 'value'=> '')
	);
	
	$data['input_list_hidden'] = array(
		'idkategori' => array('value' => ''),
		'idmanufaktur' => array('value' => ''),
		'idprovider' => array('value' => ''),
		'idtipe' => array('value' => ''),
		'idbarang' => array('value' => ''),
		'tglpembelian' => array('value' => mdate('%Y-%m-%d'))
	);
	
	$data['saveable'] = true;
	
    
	if($action == 'edit'){
			
		$data['form_action'] = "item/ajax_save_edit/";
		$data['page_title'] = '&#91Edit&#93 Item';
		
		$rs_edit = $this->barangmodel->get_barang_by_id($id_barang);
		if(!$rs_edit){
			$flashData['message'] = 'Invalid Item Data';
	        $flashData['messageClass'] = "error";
	        $this->session->set_flashdata('results', $flashData);
	        redirect('item/browse');
		}else{
			
			$this->load->model('inventorikantormodel');
			$inven = $this->inventorikantormodel->get_id($id_barang);
			
			$data['item_id'] = $id_barang;
			$data['input_list']['tglpembelian_tmp']['value'] = mdate('%d-%M-%Y', strtotime($rs_edit['tglpembelian']));
			$data['input_list']['namabarang']['value'] = $rs_edit['namabarang'];
			$data['input_list']['serialnum']['value'] = $rs_edit['serialnum'];
			$data['input_list']['quantity']['value'] = $rs_edit['quantity'];
			$data['input_list']['masagaransi']['value'] = $rs_edit['masagaransi'];
			$data['input_list']['kondisi']['value'] = $rs_edit['kondisi'];
			$data['input_list']['src_namatipe']['value'] = $rs_edit['idtipe'];
			
			$data['input_list_right']['src_namakategori']['value'] = $rs_edit['namakategori'];
			$data['input_list_right']['src_namamanu']['value'] = $rs_edit['namamanufaktur'];
			$data['input_list_right']['src_namaprovider']['value'] = $rs_edit['namaprovider'];
			$data['input_list_right']['iatacode']['value'] = $inven['iatacode'];
			$data['input_list_right']['status']['value'] = $rs_edit['status'];
			
			$data['input_list_hidden']['idkategori']['value'] = $rs_edit['idkategori'];
			$data['input_list_hidden']['idmanufaktur']['value'] = $rs_edit['idmanufaktur'];
			$data['input_list_hidden']['idprovider']['value'] = $rs_edit['idprovider'];
			$data['input_list_hidden']['idtipe']['value'] = $rs_edit['idtipe'];
			$data['input_list_hidden']['idbarang']['value'] = $rs_edit['idbarang'];
			$data['input_list_hidden']['tglpembelian']['value'] = $rs_edit['tglpembelian'];
			
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
      
	if($action == 'add'){
  	$this->form_validation->set_rules('namabarang', 'Model Name', 'strip_html_comment|test_null_tags|required|min_length[3]|callback_name_check|xss_clean');
		$this->form_validation->set_rules('serialnum', 'Serial Number', 'strip_html_comment|test_null_tags|required|min_length[3]|callback_serial_check|xss_clean');
		$this->form_validation->set_rules('idbarang', '', 'xss_clean');	
  }else{
  	$this->form_validation->set_rules('namabarang', 'Item Name', 'strip_html_comment|test_null_tags|required|min_length[3]|xss_clean');
		$this->form_validation->set_rules('serialnum', 'Serial Number', 'strip_html_comment|test_null_tags|required|min_length[3]|xss_clean');
		$this->form_validation->set_rules('idbarang', 'Item ID', 'required|xss_clean');
  }    
    //$this->form_validation->set_rules('quantity', 'Quantity', 'strip_html_comment|test_null_tags|required|numeric|xss_clean');
  $this->form_validation->set_rules('status', 'Remark', 'strip_html_comment|test_null_tags|xss_clean');
  $this->form_validation->set_rules('tglpembelian', 'Purchasing Date', 'strip_html_comment|test_null_tags|required|xss_clean');
  $this->form_validation->set_rules('masagaransi', 'Warranty', 'strip_html_comment|test_null_tags|xss_clean');
  $this->form_validation->set_rules('kondisi', 'Condition', 'strip_html_comment|test_null_tags|xss_clean');
  $this->form_validation->set_rules('src_namamanu', 'Manufacture', 'strip_html_comment|test_null_tags|required|callback_search_check|xss_clean');
  $this->form_validation->set_rules('src_namakategori', 'Category', 'strip_html_comment|test_null_tags|required|callback_search_check|xss_clean');
  $this->form_validation->set_rules('src_namaprovider', 'Provider', 'strip_html_comment|test_null_tags|required|callback_search_check|xss_clean');
  $this->form_validation->set_rules('idkategori', 'Category', 'strip_html_comment|test_null_tags|required|xss_clean');
  $this->form_validation->set_rules('idmanufaktur', 'Manufacture', 'strip_html_comment|test_null_tags|required|xss_clean');
  $this->form_validation->set_rules('idprovider', 'Provider', 'strip_html_comment|test_null_tags|required|xss_clean');
  
  $is_passed =  $this->form_validation->run();

  $flashData['message'] = 'Failed to add new item';
  $flashData['messageClass'] = "error";
	$flashData['posts']['namabarang'] = $this->input->post('namabarang');
	$flashData['posts']['quantity'] = $this->input->post('quantity');
	$flashData['posts']['serialnum'] = $this->input->post('serialnum');
	$flashData['posts']['status'] = $this->input->post('status');
	$flashData['posts']['tglpembelian'] = $this->input->post('tglpembelian');
	$flashData['posts']['masagaransi'] = $this->input->post('masagaransi');
	$flashData['posts']['kondisi'] = $this->input->post('kondisi');
	$flashData['posts']['src_namakategori'] = $this->input->post('src_namakategori');
	$flashData['posts']['src_namamanu'] = $this->input->post('src_namamanu');
	$flashData['posts']['src_namaprovider'] = $this->input->post('src_namaprovider');
	$flashData['posts']['src_namatipe'] = $this->input->post('src_namatipe');
	$flashData['posts']['idprodusen'] = $this->input->post('idprodusen');
	$flashData['posts']['idkategori'] = $this->input->post('idkategori');
	$flashData['posts']['idmanufaktur'] = $this->input->post('idmanufaktur');
	$flashData['posts']['idprovider'] = $this->input->post('idprovider');
	$flashData['posts']['iatacode'] = $this->input->post('iatacode');
	$flashData['posts']['idbarang'] = $this->input->post('idbarang');
	
	//log_message('error','pos: '. $flashData['posts']['iatacode'] );
	$rs_tipe = $this->tipebarangmodel->get_id($flashData['posts']['src_namatipe']);
  if($rs_tipe){
    if(!strtolower($rs_tipe[0]['namatipe'])=='logistic')
    {
      $flashData['posts']['quantity'] = 1;
    }
    //log_message('error','tiii: '.$rs_tipe[0]['namatipe']);
  }
  
	
	$error_name = form_error('namabarang');
  $error_serial = form_error('serialnum');
  $error_kategori = form_error('src_namakategori');
  $error_manu = form_error('src_namamanu');
  $error_provider = form_error('src_namaprovider');
  
  if(!is_null(form_error('idkategori'))){
    $error_kategori = form_error('idkategori');
  }
  
  if(!is_null(form_error('idmanufaktur'))){
    $error_manu = form_error('idmanufaktur');
  }
  
  if(!is_null(form_error('idprovider'))){
    $error_tipe = form_error('idprovider');
  }
    
  $does_exist = $this->barangmodel->get_name($flashData['posts']['namabarang']);
  if($does_exist && ($does_exist['idbarang'] != $flashData['posts']['idbarang'])){
    //log_message('error','flash:'.$flashData['idbarang'].' - deos:'.$does_exist['idbarang']);
      $is_passed = FALSE;
      $error_name = 'Name has already existed';
  }
  
  $serial_exist = $this->barangmodel->get_serial($flashData['posts']['serialnum']);          
  if($serial_exist && ($serial_exist['idbarang'] != $flashData['posts']['idbarang'])){
    //log_message('error','flash:'.$flashData['idprovider'].' - deos:'.$does_exist['idprovider']);
      $is_passed = FALSE;
      $error_serial = 'Serial Number has already existed';
  }
	
	$id_barang = intval($this->input->post('idbarang'));
	if($action == 'edit'){      
      if(!$result_barang = $this->barangmodel->get_barang_by_id($id_barang)){
            //kategori is not valid
            $flashData['message'] = 'Invalid Item data.';
            $flashData['messageClass'] = "error";
      }
   }
	   
  if ($is_passed === FALSE){
    	$this->form_validation->set_error_delimiters('', '');
		  $flashData['errors'] = array(
      		'namabarang'=>form_error('namabarang'),
      		'quantity'=>form_error('quantity'),
      		'serialnum'=>$error_serial,
      		'status'=>form_error('status'),
      		'tglpembelian'=>form_error('tglpembelian'),
      		'masagaransi'=>form_error('masagaransi'),
      		'kondisi'=>form_error('kondisi'),
      		'src_namakategori'=>$error_kategori,
      		'src_namamanu'=>$error_manu,
      		'src_namaprovider'=>$error_provider,
      		'src_namatipe'=>form_error('idtipe'),
      		'idbarang'=>form_error('idbarang')
		  );   
  }
  else{
    	//passed validation
    $this->load->model('inventorikantormodel'); 
    
		if($action == 'add'){
						
			$this->barangmodel->add(array(
			     'namabarang'=>$flashData['posts']['namabarang'],
			     'quantity'=>$flashData['posts']['quantity'],
	         'serialnum'=>$flashData['posts']['serialnum'],
	         'status'=>$flashData['posts']['status'],
	         'tglpembelian'=>$flashData['posts']['tglpembelian'],
	         'masagaransi'=>$flashData['posts']['masagaransi'],
	         'kondisi'=>$flashData['posts']['kondisi'],
	         'idkategori'=>$flashData['posts']['idkategori'],
	         'idmanufaktur'=>$flashData['posts']['idmanufaktur'],
	         'idprovider'=>$flashData['posts']['idprovider'],
	         'iddetilprodusen'=>NULL,
	         'idtipe'=>$flashData['posts']['src_namatipe'],
	         'iatacode'=>$flashData['posts']['iatacode'])
	    );
	        
	        $flashData['message'] = 'Item: <b>'.$flashData['posts']['namabarang'].'</b> is saved successfully ';
	        $flashData['messageClass'] = "success";
	        
    			foreach($flashData['posts'] as $el){
    			  	$el = '';
    			}	
			
	    }else{
	    	
			//EDIT
			$this->barangmodel->edit(array(
			       'namabarang'=>$flashData['posts']['namabarang'], 
			       'quantity'=>$flashData['posts']['quantity'],
				     'serialnum'=>$flashData['posts']['serialnum'], 
				     'status'=>$flashData['posts']['status'],
			       'tglpembelian'=>$flashData['posts']['tglpembelian'], 
			       'masagaransi'=>$flashData['posts']['masagaransi'],
			       'kondisi'=>$flashData['posts']['kondisi'], 
			       'idkategori'=>$flashData['posts']['idkategori'],
             'idmanufaktur'=>$flashData['posts']['idmanufaktur'],
             'idprovider'=>$flashData['posts']['idprovider'],
             'iddetilprodusen'=>NULL,
			       'idtipe'=>$flashData['posts']['src_namatipe'],
			       'iatacode'=>$flashData['posts']['iatacode'],
			       'idbarang'=>$flashData['posts']['idbarang']));
			        
		    $flashData['message'] = 'Item: <b>'.$flashData['posts']['namabarang'].'</b> is saved successfully ';
		    $flashData['messageClass'] = "success";
		     
	    }
      
    }
    
    return $flashData;
}

function ajax_save_add(){
  if($this->tank_auth->is_logged_in()){
    
    $flashData = $this->save('add');
	
    //$result = array('message' => $flashData['message'], 'messageClass' => $flashData['messageClass']);
    
    echo json_encode($flashData);

  }else{
    $flashData['redirect'] = base_url().'login';
    echo json_encode($flashData);
  }
}

function ajax_save_edit(){
  if($this->tank_auth->is_logged_in()){
    
    $flashData = $this->save('edit');
	
    //$result = array('message' => $flashData['message'], 'messageClass' => $flashData['messageClass']);
    
    echo json_encode($flashData);

  }else{
    $flashData['redirect'] = base_url().'login';
    echo json_encode($flashData);
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
    $is_passed = $this->form_validation->run();
    if ($is_passed == FALSE){
    	$flashData['message'] = 'Error filtering results with <b>'.$this->input->post('filter').'</b><br/>'.validation_errors();
        $flashData['messageClass'] = "error";
        $this->session->set_flashdata('results', $flashData);
    }else{
        $this->session->set_userdata('keyword_item', $this->input->post('filter'));
    }
    redirect('item/browse');
}

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */