<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
class Login extends CI_Controller {

function __construct(){
	parent::__construct();
	$this->load->library('form_validation');
	//$this->tank_auth->create_user('griyaadmin', 'admin@griyaamana.com', '8cb2237d0679ca88db6464eac60da96345513964', false);
}

function index(){
	
	if($this->tank_auth->is_logged_in()){
		$adminfrom = $this->session->flashdata('adminfrom');
    if(is_null($adminfrom) || $adminfrom == ''){
      $adminfrom = 'item';
    }
    redirect($adminfrom);
	}else{
		$this->session->keep_flashdata('adminfrom');
		
		$dheader['pageTitle'] = 'Login';
		$dheader['bodyId'] = 'body-login';
		$dheader['isLoginPage'] = true;
		$dheader['cssFiles'] = array('login.css');
		$dheader['jsFiles'] = array('log.js','passshark-jspck.js','sha-util.js');
		$dheader['jsText'] = '  window.addEvent("domready", function(){
            		              //new PassShark("key2",{
                                  //interval: 200,
                                  //duration: 500,
                                  //replacement: "%u25CF",
                                //debug: false
                              	//});
							});';
				
		$dheader['message'] = "Welcome to Administration Area";
		$dheader['messageClass'] = "updated";
		
		
		$flashData = $this->session->flashdata('results');
		if($flashData){
			$dheader['message'] = $flashData['message'];
			$dheader['messageClass'] = $flashData['messageClass'];
		}
		$this->load->view('header',$dheader);
		$this->load->view('login');
		$this->load->view('footer');
	}
	
}

function logout(){
	//$this->session->sess_destroy();
	$this->tank_auth->logout();
	$this->session->sess_destroy();
	
	$dheader['pageTitle'] = 'Login';
	$dheader['bodyId'] = 'body-login';
	$dheader['isLoginPage'] = true;
  	$dheader['cssFiles'] = array('login.css');
	$dheader['jsFiles'] = array('log.js','passshark-jspck.js');
	$dheader['jsText'] = '  window.addEvent("domready", function(){
            		              //new PassShark("key2",{
                                  //interval: 200,
                                  //duration: 500,
                                  //replacement: "%u25CF",
                                //debug: false
                              //});
                              
            								});';
	
	$this->session->keep_flashdata('adminfrom');
  $dheader['message'] = "You are successfully logged out";
  $dheader['messageClass'] = "updated";
  $flashData = $this->session->flashdata('results');
  if($flashData){
    $dheader['message'] = $flashData['message'];
    $dheader['messageClass'] = $flashData['messageClass'];
  }
  //$this->session->set_flashdata('results', $flashData);
	
	$this->load->view('header',$dheader);
	$this->load->view('login');
	$this->load->view('footer');

}

function validasi(){
	
	$this->form_validation->set_rules('key1', 'Key1', 'trim|strip_tags|required|xss_clean');
	$this->form_validation->set_rules('key2', 'Key2', 'trim|strip_tags|required|xss_clean');
	
	if ($this->form_validation->run() == FALSE){
	
		$this->session->keep_flashdata('adminfrom');
		$flashData['message'] = "Username/Password cannot be empty";
		$flashData['messageClass'] = "error";
		$this->session->set_flashdata('results', $flashData);
		redirect('login');
	}
	else{
				
		$u = $this->input->post('key1');
		$p = $this->input->post('key2');
		//log_message('error', $u.'--'.$p);
    
    if ($this->tank_auth->is_max_login_attempts_exceeded($u)){
        
      $maxRetries = $this->config->item('login_max_attempts', 'tank_auth');
        
      $flashData['message'] = " You have ".$maxRetries."x failed login. login is now temporarily blocked.";
      $flashData['messageClass'] = "error";
      $this->session->set_flashdata('results', $flashData);
      redirect('login');
      
    }else{
      
      if($this->tank_auth->login($u, $p, false, true, false)) {
 
      
        $adminfrom = $this->session->flashdata('adminfrom');
        if(is_null($adminfrom) || $adminfrom == ''){
          $adminfrom = '/obat';
        }
        redirect($adminfrom);         
        
      }else{
        $this->session->keep_flashdata('adminfrom');
        //log_message('error', 'key1: '.$u);
        //log_message('error', 'key2: '.$p);
        $terr = $this->tank_auth->get_error_message();
        //log_message('error', 'terr: '.var_export($terr, true));
		$flashData['message'] = "Username/Password do not match.";
		if(isset($terr['not_activated'])){
			$flashData['message'] = "account <b>".$u."</b> is deactivated by the admin.";
		}
        
        
        $flashData['messageClass'] = "error";
        $this->session->set_flashdata('results', $flashData);
        redirect('login');
        
      }//end else($this->tank_auth->login($u, $p, false, true, false))
      
    }//end else if ($this->tank_auth->is_max_login_attempts_exceeded($u))
			
		
	}//end else if ($this->form_validation->run() == FALSE)
}

}
?>