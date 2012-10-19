<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
class stokobatdepo extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $editmode = false;
private $editiduser = null;
private $title = 'historistokobatdepo';
private $title_ = 'Stok Obat Depo';
private $title_url = 'stokobatdepo';  
private $titledb = 'historistokobatdepomodel';

function __construct(){
	parent::__construct();
	$this->load->model($this->titledb);
}
  
function index(){
	$this->browse();
} 
 
function browse($page = 1){
	
	$this->dheader['bodyId'] = 'body-'.$this->title;
	$this->dheader['selMenu'] = 'maintenance';
	$this->dheader['pageTitle'] = 'Managemen '.ucfirst(strtolower($this->title_));
	
	$this->dheader['cssFiles'] = array('mavsuggest.css','datepicker.css');
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
                                            is_metode("metode");
                                            detilKirim("kirim","pop-add");
                                            detilKirim("editkirim","pop-add");
                                            //stopEnter("frmedit");   			
							});';
	//$rsIata = null;

	//log_message('error',var_export($data['rsIata'],true));
	$data['keyword'] = null;
	$data['curpage'] = $page;
	$data['results_per_page'] = $this->config->item('results_per_page');
	
	$data['url_add'] = $this->title_url."/save/add";
	$data['add_saveable'] = true;
	$data['url_edit'] = $this->title_url."/save/edit";
	$data['edit_saveable'] = true;
	$data['url_browse'] = $this->title_url.'/browse/';
	$data['url_filter'] = $this->title_url.'/filter';
	
	$this->load->helper('date');
	
	$titledb = $this->titledb;
	//$titfunc = 'get_'.$this->title;
	//$rsJabatan = $this->$titledb->$titfunc();
	$data['input_list_add'] = array(
				'tgl' => array('type' => 'text', 'title' => 'Tanggal', 'value'=> mdate('%d-%M-%Y'), 'class'=>'buttoncal'),
				'obat' => array('type' => 'text', 'title' => 'Obat', 'value'=> 'Cari Disini'),
				'metode' => array('type' => 'select', 'title' => 'Metode', 'value'=>'Masuk', 'class'=>'numonly', 'select_list'=> array(
                                                                                                        'Masuk'=>'Masuk',
                                                                                                        'Keluar'=>'Keluar')),
				'debet' => array('type' => 'text', 'title' => 'Jumlah', 'value'=>'', 'class'=>'numonly'),
				'kredit' => array('type' => 'text', 'title' => 'Jumlah', 'value'=> '', 'class'=>'numonly'),
				'tujuan' => array('type' => 'select', 'title' => 'Tujuan', 'value'=>'Kamar Obat', 'select_list'=> array(
                                                                                                        'Kamar Obat'=>'Kamar Obat')),
                'kirim' => array('type' => 'text', 'title' => '&nbsp;', 'value'=> 'Detil Pengiriman', 'class'=>'buttoncal btnpopadd'),
				'ket' => array('type' => 'textarea', 'title' => 'Keterangan', 'value'=> '')
	);
	
	   
    $data['input_list_add_pop'] = array(
                'closepop' => array('type' => 'text', 'title' => '&nbsp', 'value'=>'Tutup [x]', 'class'=>'buttoncal btnclose'),
                'kondisi' => array('type' => 'select', 'title' => 'Kondisi', 'value'=>'Baik', 'select_list'=> array(
                                                                                                  'Baik'=>'Baik')),
                'pemesan' => array('type' => 'text', 'title' => 'Pemesan', 'value'=> 'Cari Disini'),
                'pengirim' => array('type' => 'text', 'title' => 'Pengirim', 'value'=>'Cari Disini'),
                'pengurus' => array('type' => 'text', 'title' => 'Pengurus', 'value'=> 'Cari Disini')
    );
	
	$data['input_list_hidden_add'] = array(
	            'tgl_tmp' => array('value'=> mdate('%Y-%m-%d')),
	            'idobat' => array('value'=> ''),
	            'idpemesan' => array('value'=> ''),
	            'idpengirim' => array('value'=> ''),
	            'idpengurus' => array('value'=> '')
	);
	
	$data['input_list_edit'] = array(
				'edittgl' => array('type' => 'text', 'title' => 'Tanggal', 'value'=> '', 'class'=>'edittgl buttoncal'),
				'editobat' => array('type' => 'text', 'title' => 'Obat', 'value'=> 'Cari Disini', 'class'=>'editobat'),
				'editmetode' => array('type' => 'select', 'title' => 'Metode', 'value'=>'Masuk', 'class'=>'editmetode numonly', 'select_list'=> array(
                                                                                                        'Masuk'=>'Masuk',
                                                                                                        'Keluar'=>'Keluar')),
                'editdebet' => array('type' => 'text', 'title' => 'Jumlah', 'value'=>'', 'class'=>'editdebet numonly'),
                'editkredit' => array('type' => 'text', 'title' => 'Jumlah', 'value'=> '', 'class'=>'editkredit numonly'),
                'edittujuan' => array('type' => 'select', 'title' => 'Tujuan', 'value'=>'Kamar Obat', 'class'=>'edittujuan', 'select_list'=> array(
                                                                                                        'Kamar Obat'=>'Kamar Obat')),
                'editkirim' => array('type' => 'text', 'title' => '&nbsp;', 'value'=> 'Detil Pengiriman', 'class'=>'buttoncal btnpopadd'),
                'editket' => array('type' => 'textarea', 'title' => 'Keterangan', 'value'=> '')
	);
	
	$data['input_list_edit_pop'] = array(
                'editclosepop' => array('type' => 'text', 'title' => '&nbsp', 'value'=>'Tutup [x]', 'class'=>'buttoncal btnclose'),
                'editkondisi' => array('type' => 'select', 'title' => 'Kondisi', 'value'=>'Baik', 'select_list'=> array(
                                                                                                  'Baik'=>'Baik'), 'class'=>'editkondisi'),
                'editpemesan' => array('type' => 'text', 'title' => 'Pemesan', 'value'=> 'Cari Disini', 'class'=>'editpemesan'),
                'editpengirim' => array('type' => 'text', 'title' => 'Pengirim', 'value'=>'Cari Disini', 'class'=>'editpengirim'),
                'editpengurus' => array('type' => 'text', 'title' => 'Pengurus', 'value'=> 'Cari Disini', 'class'=>'editpengurus')
    );
	
	$data['input_list_hidden_edit'] = array(
				'editid' => array('value'=>'','class'=>'editid'),
				'edittgl_tmp' => array('value'=>'','class'=>'edittgl'),
				'editidobat' => array('value'=> '', 'class'=>'editidobat'),
				'editidpemesan' => array('value'=> '', 'class'=>'editidpemesan'),
                'editidpengirim' => array('value'=> '', 'class'=>'editidpengirim'),
                'editidpengurus' => array('value'=> '', 'class'=>'editidpengurus')
	);
	
	$data['columnHeaders'] = array(
		array('header_title'=>'Tanggal', 'field_name'=> 'tgl', 'class'=>'rowhead', 'width'=>'16%', 'rowinfo' => false),
		array('header_title'=>'Obat', 'field_name'=> 'nama', 'width'=>'16%', 'rowinfo' => false),
		array('header_title'=>'Debet', 'field_name'=> 'debet', 'width'=>'16%', 'rowinfo' => false),
		array('header_title'=>'Kredit', 'field_name'=> 'kredit', 'width'=>'16%', 'rowinfo' => false),
		array('header_title'=>'Saldo', 'field_name'=> 'ket', 'width'=>'16%', 'rowinfo' => false),
		array('header_title'=>'Keterangan', 'field_name'=> 'ket', 'width'=>'17%', 'rowinfo' => false)
	);
	
	$data['editdelid'] = 'id'; 
	
	$data['rowInfoBtns'] = NULL;
	$data['rowInfoBtnsAct'] = array(
		array('html'=> 'aktifkan', 'title'=>'Aktifkan kembali ', 'url'=> $this->title_url.'/activate/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	$data['rowInfoBtnsNon'] = array(
		array('html'=> 'non aktifkan', 'title'=>'Non aktifkan ', 'url'=> $this->title_url.'/delete/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
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
	
	//log_message('error', '$data[rowcount]: '.var_export($data['rowcount'],true));
	
	foreach($data['allrows'] as $key => $value) {
	       if($key == 0){
	           $data['allrows'][$key]['saldo'] = 'Owhkeh';
	       }
    }
	
	//log_message('error', '$data[allrows]: '.var_export($data['allrows'],true));
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
  		
      	if($data['frmaction'] == 'add' && isset($flashData['nama'])){
        	$data['input_list_add']['tgl']['value'] = $flashData['tgl'];
        	$data['input_list_add']['obat']['value'] = $flashData['obat'];
			$data['input_list_add']['debet']['value'] = $flashData['debet'];
			$data['input_list_add']['kredit']['value'] = $flashData['kredit'];
			$data['input_list_add']['metode']['value'] = $flashData['metode'];
			$data['input_list_add']['tujuan']['value'] = $flashData['tujuan'];
        	$data['input_list_add']['ket']['value'] = $flashData['ket'];
        	$data['input_list_add_pop']['kondisi']['value'] = $flashData['kondisi'];
            $data['input_list_add_pop']['pemesan']['value'] = $flashData['pemesan'];
            $data['input_list_add_pop']['pengirim']['value'] = $flashData['pengirim'];
            $data['input_list_add_pop']['pengurus']['value'] = $flashData['pengurus'];
        	$data['input_list_hidden_add']['idobat']['value'] = $flashData['idobat'];
        	$data['input_list_hidden_add']['idpemesan']['value'] = $flashData['idpemesan'];
        	$data['input_list_hidden_add']['idpengirim']['value'] = $flashData['idpengirim'];
        	$data['input_list_hidden_add']['idpengurus']['value'] = $flashData['idpengurus'];
        	$data['input_list_hidden_add']['tgl_tmp']['value'] = $flashData['tgl_tmp'];
      	}elseif($data['frmaction'] == 'edit' && isset($flashData['editid'])){
	        $data['input_list_hidden_edit']['editid']['value'] = $flashData['editid'];
            $data['input_list_hidden_edit']['editidobat']['value'] = $flashData['editidobat'];
            $data['input_list_hidden_edit']['editidpemesan']['value'] = $flashData['editidpemesan'];
            $data['input_list_hidden_edit']['editidpengirim']['value'] = $flashData['editidpengirim'];
            $data['input_list_hidden_edit']['editidpengurus']['value'] = $flashData['editidpengurus'];
            $data['input_list_hidden_edit']['edittgl_tmp']['value'] = $flashData['edittgl_tmp'];
            $data['input_list_edit_pop']['editkondisi']['value'] = $flashData['editkondisi'];
            $data['input_list_edit_pop']['editpemesan']['value'] = $flashData['editpemesan'];
            $data['input_list_edit_pop']['editpengirim']['value'] = $flashData['editpengirim'];
            $data['input_list_edit_pop']['editpengurus']['value'] = $flashData['editpengurus'];
	        $data['input_list_edit']['editobat']['value'] = $flashData['editobat'];
            $data['input_list_edit']['edittgl']['value'] = $flashData['edittgl'];
			$data['input_list_edit']['editdebet']['value'] = $flashData['editdebet'];
            $data['input_list_edit']['editkredit']['value'] = $flashData['editkredit'];
            $data['input_list_edit']['editket']['value'] = $flashData['editket'];
	    }
        
	}
	
	$this->load->view('header',$this->dheader);
	$this->load->view('editor_browse',$data);	
	$this->load->view('footer');
	
}


function save($action = 'add'){
    $this->load->helper('date');
	$titledb = $this->titledb;
    $action = trim(strtolower($action));  
    $this->load->library('form_validation');
    $this->load->helper('ozl');
  	
  	$flashData['editidpemesan'] = '';
    $flashData['editidpengirim'] = '';
    $flashData['editidpengurus'] = '';
    $flashData['edittgl_tmp'] = '';
  	
  	if($action == 'add') {
  	    $flashData['metode'] = $this->input->post('metode'); 
  	    if($flashData['metode']=='Masuk'){
  	         $this->form_validation->set_rules('kredit', 'Jumlah', 'trim|required|numeric|xss_clean');
  	    }else{
  	         $this->form_validation->set_rules('debet', 'Jumlah', 'trim|required|numeric|xss_clean');
  	    }
    	$this->form_validation->set_rules('tgl', 'Tanggal', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('obat', 'Obat', 'trim|required|callback_search_check|xss_clean');
    	$this->form_validation->set_rules('ket', 'Ketetangan', 'trim|xss_clean');	
    }else{
        $this->editmode = true;
        $flashData['editmetode'] = $this->input->post('editmetode'); 
        if($flashData['editmetode']=='Masuk'){
             $this->form_validation->set_rules('editkredit', 'Kredit', 'trim|required|numeric|xss_clean');
        }else{
             $this->form_validation->set_rules('editdebet', 'Debet', 'trim|required|numeric|xss_clean');
        }
        $this->editid = $this->input->post('editid');
        $this->form_validation->set_rules('edittgl', 'Tanggal', 'trim|required|xss_clean');
        $this->form_validation->set_rules('editobat', 'Obat', 'trim|required|callback_search_check|xss_clean');
        $this->form_validation->set_rules('editket', 'Ketetangan', 'trim|xss_clean');
    }
    $is_passed = $this->form_validation->run();
    
    if($action == 'add') {
        $flashData['message'] = 'Gagal menambah '. $this->title_ .' baru.';
        $flashData['messageClass'] = "error";
        $flashData['tgl'] = $this->input->post('tgl_tmp');
        $flashData['tgl_tmp'] = $this->input->post('tgl_tmp');
		$flashData['obat'] = $this->input->post('obat');
		$flashData['idobat'] = $this->input->post('idobat');
		$flashData['debet'] = $this->input->post('debet');
		$flashData['kredit'] = $this->input->post('kredit');
        $flashData['ket'] = $this->input->post('ket');
        $flashData['kondisi'] = $this->input->post('kondisi');
        $flashData['pemesan'] = $this->input->post('pemesan');
        $flashData['pengirim'] = $this->input->post('pengirim');
        $flashData['pengurus'] = $this->input->post('pengurus');
        $flashData['obat'] = $this->input->post('obat');
        
        if($flashData['debet'] == 0){
            $flashData['debet'] = NULL;
        }
        if($flashData['kredit'] == 0){
            $flashData['kredit'] = NULL;
        }
    
    }else{
        $flashData['message'] = 'Gagal menambah '. strtolower($this->title_);
        $flashData['messageClass'] = "error";
        $flashData['edittgl'] = $this->input->post('edittgl_tmp');
        $flashData['editdebet'] = $this->input->post('editdebet');
        $flashData['editidobat'] = $this->input->post('editidobat');
        $flashData['editkredit'] = $this->input->post('editkredit');
        $flashData['editket'] = $this->input->post('editket');
        $flashData['editid'] = $this->input->post('editid');
        $flashData['editkondisi'] = $this->input->post('editkondisi');
        $flashData['editpemesan'] = $this->input->post('editpemesan');
        $flashData['editpengirim'] = $this->input->post('editpengirim');
        $flashData['editpengurus'] = $this->input->post('editpengurus');
        $flashData['editobat'] = $this->input->post('editobat');
        $flashData['edittgl_tmp'] = $this->input->post('edittgl_tmp');
        
        if($flashData['editdebet'] == 0){
            $flashData['editdebet'] = NULL;
        }
         if($flashData['editkredit'] == 0){
            $flashData['editkredit'] = NULL;
        }
    }
    
	$this->form_validation->set_error_delimiters('', '');
    
  	if ($is_passed == FALSE){
  		
    	if($action == 'add') {

        	$flashData['errors'] = array(
        		'tgl'=>form_error('tgl'),
        		'debet'=>form_error('debet'),
        		'kredit'=>form_error('kredit'),
        		'obat'=>form_error('obat'),
        		'ket'=>form_error('ket')
        	);
        }else{
            $flashData['errors'] = array(
                'edittgl'=>form_error('edittgl'),
                'editobat'=>form_error('editobat'),
                'editdebet'=>form_error('editdebet'),
                'editkredit'=>form_error('editkredit'),
                'editket'=>form_error('editket')
            );
        }
	  	
  	}
  	else{
  		if($action == 'add') {
    			$this->$titledb->add(array(
    				'tgl'=> $flashData['tgl']. ' ' .mdate('%h:%i:%s'),
    				'idobat'=> $flashData['idobat'],
    				'debet'=> $flashData['debet'],
    				'kredit'=> $flashData['kredit'],
    				'ket'=> $flashData['ket']
    			));
    			
    			$flashData = NULL;
    			$flashData['message'] = 'Berhasil menambah '.$this->title.' baru.';
        		$flashData['messageClass'] = "success";
        }else{
			if($temp_id = $this->$titledb->get_data_by_id($flashData['editid'])){
				
	            $this->$titledb->edit(array(
	                'id'=>  $flashData['editid'],
	                'tgl'=> $flashData['edittgl']. ' ' .mdate('%h:%i:%s'),
	                'debet'=> $flashData['editdebet'],
                    'kredit'=> $flashData['editkredit'],
	                'ket'=> $flashData['editket'],
	                'idobat'=> $flashData['editidobat']
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
    redirect('stokobatdepo');
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
	redirect($this->title_url.'/browse');
	
}


function search_check($str){
    if((strtolower($str) == 'cari disini')||($str == '')){
        $this->form_validation->set_message('search_check', '%s harus diisi');
        return FALSE;
    }else{
        return TRUE;
    }
}

function idobat_check($str){
    $this->load->model('obatmodel');
    if($this->suppliermodel->get_data_by_id($str)){
        return TRUE;
    }else{
        $this->form_validation->set_message('idobat_check', '%s tidak valid');
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


}
?>