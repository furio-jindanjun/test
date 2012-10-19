<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Division extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();

  function __construct()
  {
    parent::__construct();
    
    if($this->tank_auth->is_logged_in()){
    
      $jabatan = $this->crew->get_jabatan_by_id($this->tank_auth->get_user_id());
      $this->allowed_level_admin = $this->config->item('allowed_level_division_admin');
      
      if(in_array($jabatan,$this->allowed_level_admin)){
         $this->load->model('tipebarangmodel');
         $this->dheader['userId']  = $this->tank_auth->get_user_id();
         $this->dheader['userName']  = $this->tank_auth->get_username();
         $this->dheader['jabatan']  = $jabatan;
         $this->dheader['bodyId'] = 'division';
         $this->dheader['selMenu'] = 'office';
		 $this->dheader['subMenu'] = 'division';
		 $this->dheader['jsFiles'] = array('utilities.js');
         $this->dheader['pageTitle'] = 'Division Management';
    
    $dheader['jsFiles'] = array('utilities.js');
      }else{
        $this->session->set_flashdata('results', array('message'=>'You do not have access level to the previous page, please login using appropriate credentials', 'messageClass'=>'error'));
        redirect('login/logout');
      }
    
      
    }else{
      $this->session->set_flashdata('adminfrom', '/division');
      redirect('login');
    }
  }
  
  function _admin_check(){
    if(!in_array($this->dheader['jabatan'],$this->allowed_level_admin)){
      $this->session->set_flashdata('results', array('message'=>'You do not have access level to the previous page, please login using appropriate credentials', 'messageClass'=>'error'));
      redirect('login/logout');
    }
  }

  function index()
  {
    $this->browse();  
  }
  
  function browse($page = 1)
  {
    $this->_admin_check();
    
    $data['rs_category'] = null;
    $data['keyword'] = null;
    $data['curpage'] = $page;
    $data['add_name'] = null;
    $data['editnamatipe'] = null;
    $data['editidtipe'] = null;
    
    //$this->config->load("aat",true);
    //$aat = $this->config->item('aat');
    $data['results_per_page'] = $this->config->item('result_per_page');
    
    if($this->session->userdata('keyword_tipebarang')){
      $keyword = $this->session->userdata('keyword_tipebarang');
      $data['keyword'] = $keyword;
    }
    
    $allData = $this->tipebarangmodel->get_tipebarang($data['keyword'], $data['curpage']);
    //log_message('error', 'data:'. $allData['rowcount']. ' | datana:'. var_export($allData,true));
    $data['rowcount'] = $allData['rowcount'];
    $data['curpage'] = $allData['curpage'];
    $data['maxpage'] = $allData['maxpage'];
    $data['allrows'] = $allData['rows'];
    $data['addtitle'] = '[New]';
    $data['edittitle'] = '[Edit]';
    $data['frmaction'] = 'add';
	$data['url_browse'] = 'division/browse/';
    
    $flashData = $this->session->flashdata('results');
    if($flashData){
      
      $dheader['message'] = $flashData['message'];
      $dheader['messageClass'] = $flashData['messageClass'];
      
      $data['frmaction'] = $flashData['frmaction'];
      
      if(isset($flashData['errors'])){    
          $data['errors'] = $flashData['errors'];
          foreach($data['errors'] as $key => $value) {
            if($value == "") {
                unset($data['errors'][$key]);
             }
           }
         }
      
          if(isset($flashData['add_name'])){
            $data['add_name'] = $flashData['add_name'];
          }
          
      if(isset($flashData['editnamatipe'])){
            $data['editnamatipe'] = $flashData['editnamatipe'];
            $data['editidtipe'] = $flashData['editidtipe'];
        }
          
    }
    
    $this->load->view('header',$this->dheader);
    $this->load->view('division',$data);
    $this->load->view('footer');
    
  }
  
  function save_add()
  {
    //if($this->tank_auth->is_admin_logged_in()){
      
      $this->load->library('form_validation');
      $this->load->helper('ozl');
        
      $this->form_validation->set_rules('add_name', 'Name', 'strip_html_comment|test_null_tags|required|min_length[3]|callback_name_check|xss_clean');
      
      $is_passed = $this->form_validation->run();
      
      $flashData['message'] = 'Division failed to save.';
      $flashData['messageClass'] = "error";
      $flashData['add_code'] = $this->input->post('add_code');
      $flashData['add_name'] = $this->input->post('add_name');
      
      if($is_passed == FALSE){
        $this->form_validation->set_error_delimiters('', '');
        $flashData['errors'] = array('add_code'=>form_error('add_code'), 'add_name'=>form_error('add_name'));
        
      }
      else{
        
        $this->tipebarangmodel->add(array('namatipe' => $flashData['add_name']));
        $flashData['message'] = 'Division: <b>'.$flashData['add_name'].'</b> is saved successfully ';
        $flashData['messageClass'] = "success";
        $flashData['add_name'] = '';
        
      }
      
      $flashData['frmaction'] = 'add';
      $this->session->set_flashdata('results', $flashData);
      redirect('division');
  
    /*}else{
      $this->session->set_flashdata('adminfrom', '/backend/kategori');
      redirect('backend/login');
    }*/
  }
  
  function save_edit()
  {
    //if($this->tank_auth->is_admin_logged_in()){
      
      $this->load->library('form_validation');
      $this->load->helper('ozl');
      $kategoriData = null;
      $idsubject = intval($this->input->post('editidtipe'));
      
      if($kategoriData = $this->tipebarangmodel->get_kat_by_id($idsubject)){
          
        $this->form_validation->set_rules('editnamatipe', 'Name', 'strip_html_comment|test_null_tags|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('editidtipe', 'Id Kategori', 'trim|required|numeric');
        
        $is_passed = $this->form_validation->run();
       
        $flashData['message'] = 'Failed to save division.';
        $flashData['messageClass'] = "error";
        $flashData['editnamatipe'] = $this->input->post('editnamatipe');
        $flashData['editidtipe'] = $this->input->post('editidtipe');
        
        $namename = FALSE;
        
        $name_exist = $this->tipebarangmodel->get_name($flashData['editnamatipe']);
        if($name_exist && ($name_exist['idtipe'] != $flashData['editidtipe'])){
            //log_message('error',$name_exist. ' nmaa:' .$flashData['editnamakategori']);
            $is_passed = FALSE;
            $namename = TRUE;
         }
          
        if ($is_passed == FALSE){
          $this->form_validation->set_error_delimiters('', '');
          
          if($namename){
            $a = 'The name has already existed';
          }
          else{
            $a = form_error('editnamatipe');
          }
          
          $flashData['errors'] = array('editnamatipe'=>$a);
          $stat = 'edit';
          
        }
        else{
            
          $this->tipebarangmodel->edit(array('idtipe' => $idsubject, 'namatipe' => $flashData['editnamatipe']));
          $flashData['message'] = 'Saving <b>'.$flashData['editnamatipe'].' succeeded</b>';
          $flashData['messageClass'] = "success";
          $stat = 'add';
        }
        
        $flashData['frmaction'] = $stat;
        $this->session->set_flashdata('results', $flashData);
        redirect('division/browse');
        
      }else{
        
        //kategori is not valid
        $flashData['frmaction'] = 'add';
        $flashData['message'] = 'Invalid Division';
        $flashData['messageClass'] = "error";
        $this->session->set_flashdata('results', $flashData);
        redirect('division/browse');
        
      }//end else if($kategoriData...
  
    /*}else{
      $this->session->set_flashdata('adminfrom', '/backend/kategori');
      redirect('backend/login');
    }*/
  }
  
  function name_check($str)
  {
    $is_code_duplicate = $this->tipebarangmodel->get_name($str);
    
    if($is_code_duplicate){
      $this->form_validation->set_message('name_check', 'Duplicate entry for %s field');
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
  
  function delete($idkategori)
  {
    //if($this->tank_auth->is_admin_logged_in()){
    
      if($idkategori == 1){
        $flashData['message'] = 'Default division cannot be deleted';
        $flashData['messageClass'] = "error";
      }else{
      
        $this->load->model('tipebarangmodel');
        if($this->tipebarangmodel->get_kat_by_id($idkategori)){
            
            /*revert all produks' kategori to default kategori
          $this->load->model('produkmodel');
          $this->produkmodel->switch_to_default_kategori($idkategori);*/
        
          $this->tipebarangmodel->delete($idkategori);
          $flashData['message'] = 'Division is successfully deleted.';
          $flashData['messageClass'] = "success";
          
        }else{
          $flashData['message'] = 'Deletion failed. Invalid division';
          $flashData['messageClass'] = "error";
        }
      }
      
      $flashData['frmaction'] = 'add';
      $this->session->set_flashdata('results', $flashData);
      redirect('division');
      
    /*}else{
      $this->session->set_flashdata('adminfrom', '/backend/kategori');
      redirect('backend/login');
    }*/
  
 }
 
 function filter()
 {
    //if($this->tank_auth->is_logged_in()){
      
      //set cookies
      //if($this->input->post('keyword')){
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('filter', 'Keyword', 'xss_clean');
        $is_passed = $this->form_validation->run();
        //log_message('error', $this->input->post('pagep').'-------'.$this->input->post('pagej').'-------'.$this->input->post('pagef'));
        //log_message('error','filter:'. $this->input->post('filter'));
          
        if ($is_passed == FALSE)
        {
          $flashData['message'] = 'Error filtering results with <b>'.$this->input->post('filter').'</b><br/>'.validation_errors();
          $flashData['messageClass'] = "error";
          $this->session->set_flashdata('results', $flashData);
  
        }else{
          $this->session->set_userdata('keyword_tipebarang', $this->input->post('filter'));
        }
      //}
      redirect('division/browse');
      
    /*}else{//not logged in.
      $this->session->set_flashdata('adminfrom', '/backend/kategori');
      redirect('backend/login');
    }*/
  }

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */