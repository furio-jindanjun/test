<?php
class stokobatapotik extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $editmode = false;
private $editiduser = null;
private $title = 'historiklinik';
private $title_ = 'Stok Obat Apotik';
private $title_url = 'stokobatapotik';  
private $titledb = 'historiklinikmodel';

function __construct(){
    parent::__construct();

    if($this->tank_auth->is_logged_in() ){
        $this->load->model($this->titledb);
        $jabatan = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id()); 
        $this->allowed_level = $this->config->item('allowed_level_obat_admin');
        $this->allowed_level_admin = $this->config->item('allowed_level_obat_admin');
        
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
        $this->session->set_flashdata('adminfrom', '/stokobatapotik');
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
    $this->dheader['selMenu'] = 'maintenance';
    $this->dheader['pageTitle'] = 'Managemen '.ucfirst(strtolower($this->title_));
    
    $this->dheader['cssFiles'] = array('mavsuggest.css','datepicker.css');
    $this->dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js','Picker.js','Picker.Attach.js','Picker.Date.js','Meio.Mask.js','Meio.Mask.Fixed.js','Meio.Mask.Extras.js');
    $this->dheader['jsText'] = 'window.addEvent("domready", function(){
                                           init_numonly();
                                           
                                        /*  new DatePicker($("tgl"), {
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
                                            */
                                            var obatSuggest = onSuggest.pass(["obat",["idobat"], ["id"]]);
                                            predict_obat = new MavSuggest.Request.JSON({
                                                "elem": "obat",
                                                "url":"'.base_url().'ajaxquery/obat_stok/historiklinikmodel",
                                                "requestVar": "obat", 
                                                "singleMode": true,
                                                "onSelect": obatSuggest
                                            });
                  
                                            var editObatSuggest = onSuggest.pass(["editobat",["editidobat"], ["editid"]]);
                                            predict_editObat = new MavSuggest.Request.JSON({
                                                "elem": "editobat",
                                                "url":"'.base_url().'ajaxquery/obat_stok/historiklinikmodel/edit",
                                                "requestVar": "editobat", 
                                                "singleMode": true,
                                                "onSelect": editObatSuggest
                                            });
                                            
                                            var pemesanSuggest = onSuggest.pass(["pemesan",["idpemesan"], ["id"]]);
                                            predict_pemesan = new MavSuggest.Request.JSON({
                                                "elem": "pemesan",
                                                "url":"'.base_url().'ajaxquery/pegawai/pemesan",
                                                "requestVar": "pemesan", 
                                                "singleMode": true,
                                                "onSelect": pemesanSuggest
                                            });
                                            
                                            var pengirimSuggest = onSuggest.pass(["pengirim",["idpengirim"], ["id"]]);
                                            predict_pengirim = new MavSuggest.Request.JSON({
                                                "elem": "pengirim",
                                                "url":"'.base_url().'ajaxquery/pegawai/pengirim",
                                                "requestVar": "pengirim", 
                                                "singleMode": true,
                                                "onSelect": pengirimSuggest
                                            });
                                            var pengurusSuggest = onSuggest.pass(["pengurus",["idpengurus"], ["id"]]);
                                            predict_pengurus = new MavSuggest.Request.JSON({
                                                "elem": "pengurus",
                                                "url":"'.base_url().'ajaxquery/pegawai/pengurus",
                                                "requestVar": "pengurus", 
                                                "singleMode": true,
                                                "onSelect": pengurusSuggest
                                            });
                                            
                                            chgBlur("obat","Cari Disini", ["idobat"], [""]);
                                            chgBlur("pengurus","Cari Disini", ["idpengurus"], [""]);
                                            chgBlur("pengirim","Cari Disini", ["idpengirim"], [""]);
                                            chgBlur("pemesan","Cari Disini", ["idpemesan"], [""]);
                                            
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
    $data['is_stock'] = true;
    $data['link_stock'] = 'stokobatapotik/browse_stock/1/';
    
    $data['url_add'] = $this->title_url."/save/add";
    $data['add_saveable'] = true;
    $data['url_edit'] = $this->title_url."/save/edit";
    $data['edit_saveable'] = true;
    $data['url_browse'] = $this->title_url.'/browse/';
    $data['url_filter'] = $this->title_url.'/filter';
    
    $this->session->set_userdata('linkbrowse', 'browse');
    
    $this->load->helper('date');
    
    $titledb = $this->titledb;
    //$titfunc = 'get_'.$this->title;
    //$rsJabatan = $this->$titledb->$titfunc();
    $data['input_list_add'] = array(
                'tgl' => array('type' => 'text', 'title' => 'Tanggal', 'value'=> mdate('%d-%M-%Y'), 'class'=>'buttoncal','disabled'=>'true'),
                'obat' => array('type' => 'text', 'title' => 'Obat', 'value'=> 'Cari Disini'),
                'metode' => array('type' => 'select', 'title' => 'Metode', 'value'=>'Keluar', 'select_list'=> array(
                                                                                                        'Keluar'=>'Keluar')),
                'debet' => array('type' => 'text', 'title' => 'Jumlah', 'value'=>'', 'class'=>'numonly'),
                'kredit' => array('type' => 'text', 'title' => 'Jumlah', 'value'=> '', 'class'=>'numonly'),
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
                                                                                                        'Kamar Obat'=>'Kamar Obat',
                                                                                                        'Rumah Sakit'=>'Rumah Sakit')),
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
        array('header_title'=>'Kode', 'field_name'=> 'kode', 'class'=>'rowhead', 'width'=>'33%', 'rowinfo' => false),
        array('header_title'=>'Obat', 'field_name'=> 'nama', 'width'=>'33%', 'rowinfo' => false),
        array('header_title'=>'Last Stock', 'field_name'=> 'saldo', 'width'=>'34%', 'rowinfo' => false)
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
    
    $titfunc = 'get_id_'.$this->title;
    $allData = $this->$titledb->$titfunc($data['keyword'], $data['curpage']);
    $data['rowcount'] = $allData['rowcount'];
    $data['curpage'] = $allData['curpage'];
    $data['maxpage'] = $allData['maxpage'];
    $data['allrows'] = $allData['rows'];
    $data['addtitle'] = ucfirst(strtolower($this->title_)). ' baru';
    $data['edittitle'] = 'Ubah '. ucfirst(strtolower($this->title_));
    
    //log_message('error', '$data[rowcount]: '.var_export($data['rowcount'],true));
    if($data['allrows']){
        foreach($data['allrows'] as $key => $value) {
           $data['allrows'][$key]['saldo'] = $this->last_stock($data['allrows'][$key]['idobat']);
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
        
        //$data['frmaction'] = $flashData['frmaction'];
        
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
            $data['input_list_add']['metode']['value'] = $flashData['metode'];
            if ($flashData['metode'] == 'Masuk'){
                $data['input_list_add']['kredit']['value'] = $flashData['kredit'];
            }
            else{
                $data['input_list_add']['debet']['value'] = $flashData['debet'];
               /* $data['input_list_add']['tujuan']['value'] = $flashData['tujuan'];
                $data['input_list_add_pop']['kondisi']['value'] = $flashData['kondisi'];
                $data['input_list_add_pop']['pemesan']['value'] = $flashData['pemesan'];
                $data['input_list_add_pop']['pengirim']['value'] = $flashData['pengirim'];
                $data['input_list_add_pop']['pengurus']['value'] = $flashData['pengurus'];
                $data['input_list_hidden_add']['idpemesan']['value'] = $flashData['idpemesan'];
                $data['input_list_hidden_add']['idpengirim']['value'] = $flashData['idpengirim'];
                $data['input_list_hidden_add']['idpengurus']['value'] = $flashData['idpengurus'];*/
            }
            
            $data['input_list_add']['ket']['value'] = $flashData['ket'];
            
            $data['input_list_hidden_add']['idobat']['value'] = $flashData['idobat'];
            $data['input_list_hidden_add']['tgl_tmp']['value'] = $flashData['tgl_tmp'];
            //log_message('error','$flashData[metode] ='.$flashData['metode']);
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

function browse_stock($page = 1, $idstock = NULL){
    
    $data['userId']  = $this->tank_auth->get_user_id();
    $data['userName']  = $this->tank_auth->get_username();
    $data['jabatan']  = $this->jabatanmodel->get_jabatan_by_pegawai_id($this->tank_auth->get_user_id());
    $this->dheader['bodyId'] = 'body-'.$this->title;
    $this->dheader['selMenu'] = 'maintenance';
    $this->dheader['pageTitle'] = 'Managemen '.ucfirst(strtolower($this->title_));
    
    $this->dheader['cssFiles'] = array('mavsuggest.css','datepicker.css');
    $this->dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js','Picker.js','Picker.Attach.js','Picker.Date.js','Meio.Mask.js','Meio.Mask.Fixed.js','Meio.Mask.Extras.js');
    $this->dheader['jsText'] = 'window.addEvent("domready", function(){
                                           init_numonly();
                                           
                                            new DatePicker($("dfrom"), {
                                                pickerClass: "datepicker",
                                                allowEmpty: false,
                                                format: "%d-%b-%Y",onSelect: function(date){
                                                            $("dfrom_tmp").set("value", date.format("%Y-%m-%d"));
                                                          }
                                            });   
                                            
                                            new DatePicker($("dto"), {
                                                pickerClass: "datepicker",
                                                allowEmpty: false,
                                                format: "%d-%b-%Y",onSelect: function(date){
                                                            $("dto_tmp").set("value", date.format("%Y-%m-%d"));
                                                            $("frmedit").submit();
                                                          }
                                            });   
                                            /*
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
                                            */
                                            var obatSuggest = onSuggest.pass(["obat",["idobat"], ["id"]]);
                                            predict_obat = new MavSuggest.Request.JSON({
                                                "elem": "obat",
                                                "url":"'.base_url().'ajaxquery/obat_stok/historiklinikmodel",
                                                "requestVar": "obat", 
                                                "singleMode": true,
                                                "onSelect": obatSuggest
                                            });
                  
                                            var editObatSuggest = onSuggest.pass(["editobat",["editidobat"], ["editid"]]);
                                            predict_editObat = new MavSuggest.Request.JSON({
                                                "elem": "editobat",
                                                "url":"'.base_url().'ajaxquery/obat_stok/historiklinikmodel/edit",
                                                "requestVar": "editobat", 
                                                "singleMode": true,
                                                "onSelect": editObatSuggest
                                            });
                                            
                                            var pemesanSuggest = onSuggest.pass(["pemesan",["idpemesan"], ["id"]]);
                                            predict_pemesan = new MavSuggest.Request.JSON({
                                                "elem": "pemesan",
                                                "url":"'.base_url().'ajaxquery/pegawai/pemesan",
                                                "requestVar": "pemesan", 
                                                "singleMode": true,
                                                "onSelect": pemesanSuggest
                                            });
                                            
                                            var pengirimSuggest = onSuggest.pass(["pengirim",["idpengirim"], ["id"]]);
                                            predict_pengirim = new MavSuggest.Request.JSON({
                                                "elem": "pengirim",
                                                "url":"'.base_url().'ajaxquery/pegawai/pengirim",
                                                "requestVar": "pengirim", 
                                                "singleMode": true,
                                                "onSelect": pengirimSuggest
                                            });
                                            var pengurusSuggest = onSuggest.pass(["pengurus",["idpengurus"], ["id"]]);
                                            predict_pengurus = new MavSuggest.Request.JSON({
                                                "elem": "pengurus",
                                                "url":"'.base_url().'ajaxquery/pegawai/pengurus",
                                                "requestVar": "pengurus", 
                                                "singleMode": true,
                                                "onSelect": pengurusSuggest
                                            });
                                            
                                            //chgBlur("obat","Cari Disini", ["idobat"], [""]);
                                            //chgBlur("pengurus","Cari Disini", ["idpengurus"], [""]);
                                            //chgBlur("pengirim","Cari Disini", ["idpengirim"], [""]);
                                            //chgBlur("pemesan","Cari Disini", ["idpemesan"], [""]);
                                            stopEnter("frmadd"); 
                                            is_metode("metode");
                                            //detilKirim("kirim","pop-add");
                                            //detilKirim("editkirim","pop-add");
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
    $data['url_browse'] = $this->title_url.'/browse_stock/'.$page.'/'.$idstock;
    $data['url_filter'] = $this->title_url.'/filter_stock';
    
    $this->session->set_userdata('linkbrowse', 'browse_stock');
    
    $this->load->helper('date');
    //log_message('error','JAM: '. ' ' .date('H:i:s'));
    $titledb = $this->titledb;
    //$titfunc = 'get_'.$this->title;
    //$rsJabatan = $this->$titledb->$titfunc();
    $data['input_list_add'] = array(
                'tgl' => array('type' => 'text', 'title' => 'Tanggal', 'value'=> mdate('%d-%M-%Y'), 'class'=>'buttoncal','disabled'=>'true'),
                'obat' => array('type' => 'text', 'title' => 'Obat', 'value'=> 'Cari Disini'),
                'metode' => array('type' => 'select', 'title' => 'Metode', 'value'=>'Masuk', 'class'=>'numonly', 'select_list'=> array(
                                                                                                        'Keluar'=>'Keluar')),
                'debet' => array('type' => 'text', 'title' => 'Jumlah', 'value'=>'', 'class'=>'numonly'),
                'kredit' => array('type' => 'text', 'title' => 'Jumlah', 'value'=> '', 'class'=>'numonly'),
                
                
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
                                                                                                        'Kamar Obat'=>'Kamar Obat',
                                                                                                        'Rumah Sakit'=>'Rumah Sakit')),
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
        array('header_title'=>'Tanggal', 'field_name'=> 'tgl', 'class'=>'rowhead', 'width'=>'20%', 'rowinfo' => false),
        array('header_title'=>'Masuk', 'field_name'=> 'kredit', 'width'=>'20%', 'rowinfo' => false),
        array('header_title'=>'Keluar', 'field_name'=> 'debet', 'width'=>'20%', 'rowinfo' => false),
        array('header_title'=>'Jmlh Stok', 'field_name'=> 'saldo', 'width'=>'20%', 'rowinfo' => false),
        array('header_title'=>'Keterangan', 'field_name'=> 'ket', 'width'=>'20%', 'rowinfo' => false)
    );
    
    
    $data['editdelid'] = 'id'; 
    $data['stockStat'] = TRUE;
    
    $data['rowInfoBtns'] = NULL;
    $data['rowInfoBtnsAct'] = array(
        array('html'=> 'aktifkan', 'title'=>'Aktifkan kembali ', 'url'=> $this->title_url.'/activate/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
    $data['rowInfoBtnsNon'] = array(
        array('html'=> 'non aktifkan', 'title'=>'Non aktifkan ', 'url'=> $this->title_url.'/delete/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
    
    $data['keyword'] = $idstock;
    
    $titfunc = 'get_'.$this->title.'1';
    $allData = $this->$titledb->$titfunc($data['keyword'], $data['curpage']);
    $data['rowcount'] = $allData['rowcount'];
    $data['curpage'] = $allData['curpage'];
    $data['maxpage'] = $allData['maxpage'];
    $data['allrows'] = $allData['rows'];
    $data['addtitle'] = ucfirst(strtolower($this->title_)). ' Baru';
    $data['edittitle'] = 'Ubah '. ucfirst(strtolower($this->title_));
    $data['thepage'] = TRUE;
    
    $data['input_list_add']['obat']['value'] = $allData['rows'][0]['nama'];
    $data['input_list_hidden_add']['idobat']['value'] = $idstock;
    
   //log_message('error','column header b4: '. var_export($data['columnHeaders'],true));   
    foreach($allData['rows'] as $keyw => $valu){
        if(isset($allData['rows'][$keyw]['rowinfo'])){
             $data['columnHeaders'][5] = array('header_title'=>'Pemesan', 'field_name'=> 'pemesan', 'rowinfo' => true);
             $data['columnHeaders'][6] = array('header_title'=>' | Pengirim', 'field_name'=> 'pengirim', 'rowinfo' => true);
             $data['columnHeaders'][7] = array('header_title'=>' | Pengurus', 'field_name'=> 'pengurus', 'rowinfo' => true);
             $data['columnHeaders'][8] = array('header_title'=>' | Tujuan', 'field_name'=> 'ket_tujuan', 'rowinfo' => true);
            //log_message('error','ada tgl header: '. var_export($data['columnHeaders'],true));   
        }
    }
    //log_message('error','column header after: '. var_export($data['columnHeaders'],true));   
    //log_message('error', '$allData[rows]: '. var_export($allData['rows'],true));
    //log_message('error','rowcount: '. $data['rowcount']. '---'.$this->input->post('viewstock'));
    $saldo = 0;
    
    if($data['allrows']){
        foreach($data['allrows'] as $key => $value) {
        
               //if($this->input->post('tgl_tmp')){}
        
               if($key == 0){
                   $saldo = $data['allrows'][$key]['kredit'];
                   $data['allrows'][$key]['saldo'] = $saldo;
               }
               else{
                    if($data['allrows'][$key]['kredit']){
                        $saldo = $saldo + $data['allrows'][$key]['kredit'];
                        $data['allrows'][$key]['saldo'] = $saldo;
                        
                    }
                    elseif($data['allrows'][$key]['debet']){
                        $saldo = $saldo - $data['allrows'][$key]['debet'];
                        $data['allrows'][$key]['saldo'] = $saldo;
                    }
               }
        }
    }
    
    if($this->session->userdata('keyword_'.$this->title)){
        $keyword = $this->session->userdata('keyword_'.$this->title);
        $data['keyword'] = $keyword;
    }
    if($this->session->userdata('stokmode')){
        $stokmode = $this->session->userdata('stokmode');
        if($this->session->userdata('keyfrom')){
            $keyfrom = $this->session->userdata('keyfrom');
        }
        if($this->session->userdata('keyto')){
            $keyto = $this->session->userdata('keyto');
        }
        
        $counter = 0;
        $tampung = NULL;
        if($stokmode == 'waktu'){
            for ($i=0;$i<$data['rowcount'];$i++){
                //log_message('error','keyform: '.$keyfrom .' strfrom:'. strtotime($keyfrom). ' -tgl: '.$data['allrows'][$i]['tgl'].' strtgl:'. strtotime($data['allrows'][$i]['tgl']).' --keyto: '.$keyto.' strto:'. strtotime($keyto). ' -tglto: '.$data['allrows'][$i]['tgl'].' strto:'. strtotime($data['allrows'][$i]['tgl']));
                if((strtotime($data['allrows'][$i]['tgl']) >= strtotime($keyfrom))&&(strtotime($data['allrows'][$i]['tgl']) <= strtotime(mdate('%Y-%m-%d', strtotime($keyto)).' 24:00:00'))){
                    //log_message('error','$i: '.$i. ' tgl: '.$data['allrows'][$i]['tgl']);
                    $tampung[$counter]=$data['allrows'][$i];
                    $counter++;
                }
                else{}
            }
           // 
           $data['allrows'] = NULL;
           $data['allrows'] = $tampung;
        }
        
        //$flashData['message'];
        //log_message('error','node baru--'. var_export($tampung,true));
        //log_message('error','node baru allrows--'. var_export($data['allrows'],true));
        //log_message('error','stokmode: '.$stokmode. ' keyfrom: '.$keyfrom. ' keyto: '.$keyto);
        
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
        
        //$data['frmaction'] = $flashData['frmaction'];
        
        if(isset($flashData['errors'])){    
        $data['errors'] = $flashData['errors'];
        foreach($data['errors'] as $key => $value) {
            if($value == "") {
                unset($data['errors'][$key]);
             }
           }
        }
        //log_message('error','$flashData[metode] ='.$flashData['metode']);
        if($data['frmaction'] == 'add' && isset($flashData['tgl'])){
            $data['input_list_add']['tgl']['value'] = $flashData['tgl'];
            $data['input_list_add']['obat']['value'] = $flashData['obat'];
            $data['input_list_add']['debet']['value'] = $flashData['debet'];
            $data['input_list_add']['kredit']['value'] = $flashData['kredit'];
            $data['input_list_add']['metode']['value'] = $flashData['metode'];
            //$data['input_list_add']['tujuan']['value'] = $flashData['tujuan'];
            $data['input_list_add']['ket']['value'] = $flashData['ket'];
            /*$data['input_list_add_pop']['kondisi']['value'] = $flashData['kondisi'];
            $data['input_list_add_pop']['pemesan']['value'] = $flashData['pemesan'];
            $data['input_list_add_pop']['pengirim']['value'] = $flashData['pengirim'];
            $data['input_list_add_pop']['pengurus']['value'] = $flashData['pengurus'];
            $data['input_list_hidden_add']['idobat']['value'] = $flashData['idobat'];
            $data['input_list_hidden_add']['idpemesan']['value'] = $flashData['idpemesan'];
            $data['input_list_hidden_add']['idpengirim']['value'] = $flashData['idpengirim'];
            $data['input_list_hidden_add']['idpengurus']['value'] = $flashData['idpengurus'];*/
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

function last_stock($id){
    $saldo = 0;
    $titledb = $this->titledb;
    $titfunc = 'get_'.$this->title.'1';
    $allData = $this->$titledb->$titfunc($id);
    
    //log_message('error','last stok: '.var_export($allData,true));
    
    if($allData['rows']){
        foreach($allData['rows'] as $key => $value) {
               if($key == 0){
                   $saldo = $allData['rows'][$key]['kredit'];
               }
               else{
                    if($allData['rows'][$key]['kredit']){
                        $saldo = $saldo + $allData['rows'][$key]['kredit'];
                        
                    }
                    elseif($allData['rows'][$key]['debet']){
                        $saldo = $saldo - $allData['rows'][$key]['debet'];
                    }
               }
        }
    }
    
    return $saldo;
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
    
    if($this->session->userdata('linkbrowse')){
        $linkbrowse = $this->session->userdata('linkbrowse');
    }
    
    if($action == 'add') {
        $flashData['metode'] = $this->input->post('metode'); 
        if($flashData['metode']=='Keluar'){
             $this->form_validation->set_rules('debet', 'Jumlah', 'trim|required|numeric|xss_clean');
        }else{
             $this->form_validation->set_rules('kredit', 'Jumlah', 'trim|required|numeric|xss_clean');
             
        }
        
        //$this->form_validation->set_rules('tgl', 'Tanggal', 'trim|required|xss_clean');
        $this->form_validation->set_rules('obat', 'Obat', 'trim|required|callback_search_check|xss_clean');
        $this->form_validation->set_rules('ket', 'Ketetangan', 'trim|xss_clean');   
    }
    
    $is_passed = $this->form_validation->run();
    $idobat = '';
    
    if($action == 'add') {
        $flashData['message'] = 'Gagal menambah '. $this->title_ .' baru.';
        $flashData['messageClass'] = "error";
        $flashData['tgl'] = mdate('%d-%M-%Y');
        $flashData['tgl_tmp'] = $this->input->post('tgl_tmp');
        $flashData['obat'] = $this->input->post('obat');
        $flashData['idobat'] = $this->input->post('idobat');
        if($linkbrowse == 'browse_stock'){
          $idobat = $flashData['idobat'];
        }
        $flashData['debet'] = $this->input->post('debet');
        $flashData['kredit'] = $this->input->post('kredit');
        $flashData['ket'] = $this->input->post('ket');
        $flashData['obat'] = $this->input->post('obat');
        
            
        if(($flashData['metode'] == 'Keluar') && ($flashData['idobat'] != '')){
                if($flashData['debet'] > $this->last_stock($flashData['idobat'])){
                    $flashData['message'] = 'Stok obat tidak cukup.<br/> Stok yang tersedia saat ini: '.$this->last_stock($flashData['idobat']);
                    $flashData['messageClass'] = "error";
                    $this->session->set_flashdata('results', $flashData);
                    //log_message('error','$linkbrowse: '.$linkbrowse.' | stok: '.$flashData['idobat']);
                    redirect('stokobatapotik/'.$linkbrowse.'/1/'.$idobat);     
                }
        }
        
        if($flashData['debet'] == 0){
            $flashData['debet'] = NULL;
        }
        if($flashData['kredit'] == 0){
            $flashData['kredit'] = NULL;
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
            //log_message('error','debet: '.form_error('debet'));
        }
        
    }
    else{
     //log_message('error','debet: '.$flashData['tgl']. ' ' .mdate('%h:%i:%s'));
        if($action == 'add') {
                if($flashData['metode']=='Masuk'){
                    $this->$titledb->add(array(
                        'tgl'=> $flashData['tgl_tmp']. ' ' .mdate('%h:%i:%s'),
                        'idobat'=> $flashData['idobat'],
                        'debet'=> NULL,
                        'kredit'=> $flashData['kredit'],
                        'ket'=> $flashData['ket']
                    ));
        
                }
                else{
                    $this->$titledb->add(array(
                        'tgl'=> $flashData['tgl_tmp']. ' ' .mdate('%h:%i:%s'),
                        'idobat'=> $flashData['idobat'],
                        'debet'=> $flashData['debet'],
                        'kredit'=> NULL,
                        'ket'=> $flashData['ket']
                    ));
                    
                    /*$this->load->model('kirimkamarobatmodel');
                    $this->kirimkamarobatmodel->add(array(
                        'tgl'=> $flashData['tgl_tmp']. ' ' .mdate('%h:%i:%s'),
                    ));*/
                    
                }
                
                $flashData['debet'] = NULL;
                $flashData['ket'] = NULL;
                $flashData['kredit'] = NULL;
                if($linkbrowse == 'browse'){
                  $flashData = NULL;
                }
                $flashData['message'] = 'Berhasil menambah '.$this->title_.' baru.';
                $flashData['messageClass'] = "success";
        }
    }
    
    if($action == 'add') {
         $flashData['frmaction'] = 'add';
    }
    //log_message('error','disave: '.var_export($flashData,true). ' ||||'.$idobat);
    $this->session->set_flashdata('results', $flashData);
    redirect('stokobatapotik/'.$linkbrowse.'/1/'.$idobat);  
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
      $flashData['message'] = 'Gagal menghapus '.$this->title.'. '.$this->title_.' tidak valid';
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

function filter_stock(){
    
    //set cookies
    //if($this->input->post('keyword')){
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('filter', 'Keyword', 'xss_clean');
        $is_passed = $this->form_validation->run();
        //log_message('error', '----waktu---'.$this->input->post('viewstock').'-------');
            
        if ($is_passed == FALSE)
        {
            $flashData['message'] = 'Gagal menyaring dengan <b>'.$this->input->post('filter').'</b><br/>'.validation_errors();
            $flashData['messageClass'] = "error";
            $this->session->set_flashdata('results', $flashData);

        }else{
        
            if(strtotime($this->input->post('dfrom_tmp')) > strtotime($this->input->post('dto_tmp'))){
                $flashData['message'] = 'Tanggal Awal harus lebih kecil dari tanggal akhir';
                $flashData['messageClass'] = "error";
                $this->session->set_flashdata('results', $flashData);
            }
            else{
                $this->session->set_userdata('keyword_'.$this->title, $this->input->post('filter'));
                $this->session->set_userdata('stokmode', $this->input->post('viewstock'));
                $this->session->set_userdata('keyfrom', $this->input->post('dfrom_tmp'));
                $this->session->set_userdata('keyto', $this->input->post('dto_tmp'));
            }
            
        }
    //}
    
    redirect($this->title_url.'/browse_stock/1/'. $this->input->post('filter'));
    
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