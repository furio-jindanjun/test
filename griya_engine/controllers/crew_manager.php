<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
class Crew_manager extends CI_Controller {

private $dheader = array();
private $allowed_level_admin = array();
private $editmode = false;
private $editiduser = null;

function __construct(){
	parent::__construct();
	
	if($this->tank_auth->is_logged_in()){
	   
	    $jabatan = $this->crew->get_jabatan_by_id($this->tank_auth->get_user_id());
        $this->allowed_level_admin = $this->config->item('allowed_level_iata_assign_admin');
      
      if(in_array($jabatan,$this->allowed_level_admin)){
      	
        $this->dheader['userId']  = $this->tank_auth->get_user_id();
		$this->dheader['userName']  = $this->tank_auth->get_username();
		$this->dheader['jabatan']  = $this->crew->get_jabatan_by_id($this->dheader['userId']); 
		$this->dheader['subMenu'] = 'crewmanager';
        
      }else{
        $this->session->set_flashdata('results', array('message'=>'You do not have access level to the previous page, please login using appropriate credentials', 'messageClass'=>'error'));
        redirect('login/logout');
      }
    
      
    }else{
      $this->session->set_flashdata('adminfrom', '/crew_manager');
      redirect('login');
    }
}
  
function index(){
	$this->browse();
} 
 
function browse($page = 1){
	
	$this->dheader['bodyId'] = 'body-crew';
	$this->dheader['selMenu'] = 'office';
	$this->dheader['pageTitle'] = 'Crew Management';
	
	$this->dheader['cssFiles'] = array('mavsuggest.css','crew_manager.css');
  	$this->dheader['jsFiles'] = array('utilities.js','class.MavSuggest.js','mavbuddy.js');
	$this->dheader['jsText'] = 'window.addEvent("domready", function(){
																
							});';
	//$rsIata = null;

	//log_message('error',var_export($data['rsIata'],true));
	$data['keyword'] = null;
	$data['curpage'] = $page;
	$data['results_per_page'] = $this->config->item('results_per_page');
	
	$data['url_add'] = "crew_manager/save/add";
	$data['add_saveable'] = true;
	$data['url_edit'] = "crew_manager/save/edit";
	$data['edit_saveable'] = true;
	$data['url_browse'] = 'crew_manager/browse/';
	$data['url_filter'] = "crew_manager/filter";
	
	
	$rsJabatan = $this->crew->get_jabatan();
	$data['input_list_add'] = array(
				'employeeid' => array('type' => 'text', 'title' => 'Crew ID', 'value'=>''),
				'nama' => array('type' => 'text', 'title' => 'Crew Name', 'value'=>'' ),
				'idjabatan' => array('type' => 'select', 'title' => 'Rank', 'value'=>'', 'select_list'=> $rsJabatan),
				'rek' => array('type' => 'text', 'title' => 'Acc #', 'value'=>'' ),
				'user_email' => array('type' => 'text', 'title' => 'Email', 'value'=>'' ),
				'user_pass' => array('type' => 'password', 'title' => 'Password', 'value'=>'' ),
				'password2' => array('type' => 'password', 'title' => 'Confirm Pass', 'value'=>'' )
	);
	$data['input_list_hidden_add'] = array();
	
	$data['input_list_edit'] = array(
				'editemployeeid' => array('type' => 'text', 'title' => 'Crew ID', 'value'=>'', 'class'=>'editemployeeid'),
				'editnama' => array('type' => 'text', 'title' => 'Crew Name', 'value'=>'', 'class'=>'editnama' ),
				'editidjabatan' => array('type' => 'select', 'title' => 'Rank', 'value'=>'', 'select_list'=> $rsJabatan, 'class'=>'editidjabatan' ),
				'editrek' => array('type' => 'text', 'title' => 'Acc #', 'value'=>'', 'class'=>'editrek' ),
				'edituser_email' => array('type' => 'text', 'title' => 'Email', 'value'=>'', 'class'=>'editemail' ),
				'edituser_pass' => array('type' => 'password', 'title' => 'New Password', 'value'=>'', 'class'=>'editpassword' ),
				'editpassword2' => array('type' => 'password', 'title' => 'Confirm Pass', 'value'=>'', 'class'=>'editpassword2' ),
				'editactivated' => array('type' => 'checkbox', 'title' => 'Activated', 'value'=>'1', 'class'=>'editactivated', 'def_val'=>'1')
	);
	
	$data['input_list_hidden_edit'] = array(
				'editid' => array('value'=>'','class'=>'editid')
	);
	
	$data['columnHeaders'] = array(
		array('header_title'=>'ID', 'field_name'=> 'employeeid', 'class'=>'rowhead', 'width'=>'10%', 'rowinfo' => false),
		array('header_title'=>'Name', 'field_name'=> 'nama', 'width'=>'30%', 'rowinfo' => false),
		array('header_title'=>'Email', 'field_name'=> 'email', 'width'=>'30%', 'rowinfo' => false),
		array('header_title'=>'Rank', 'field_name'=> 'namajabatan', 'width'=>'15%', 'rowinfo' => false),
		array('header_title'=>'Branch', 'field_name'=> 'iatacode', 'width'=>'15%', 'rowinfo' => false)  
	);
	
	$data['editdelid'] = 'id'; 
	
	$data['rowInfoBtns'] = array(
		array('html'=> 'delete', 'title'=>'delete ', 'url'=> 'crew_manager/delete/',  'field_name'=>'nama', 'class'=> 'btndel delbtn')
    );
	
	if($this->session->userdata('keyword_crew_manager')){
		$keyword = $this->session->userdata('keyword_crew_manager');
		$data['keyword'] = $keyword;
	}
	
	$allData = $this->crew->get_browse($data['keyword'], $data['curpage']);
	$data['rowcount'] = $allData['rowcount'];
	$data['curpage'] = $allData['curpage'];
	$data['maxpage'] = $allData['maxpage'];
	$data['allrows'] = $allData['rows'];
	$data['addtitle'] = 'New Crew';
	$data['edittitle'] = 'Edit Crew';
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
  
      	if($data['frmaction'] == 'add' && isset($flashData['employeeid'])){
      		$data['input_list_add']['employeeid']['value'] = $flashData['employeeid'];
        	$data['input_list_add']['nama']['value'] = $flashData['nama'];
        	$data['input_list_add']['idjabatan']['value'] = $flashData['idjabatan'];
        	$data['input_list_add']['user_email']['value'] = $flashData['user_email'];
        	$data['input_list_add']['rek']['value'] = $flashData['rek'];
        	$data['input_list_add']['user_pass']['value'] = $flashData['user_pass'];
        	$data['input_list_add']['password2']['value'] = $flashData['password2'];
      	}elseif($data['frmaction'] == 'edit' && $flashData['editid']){
	        $data['input_list_hidden_edit']['editid']['value'] = $flashData['editid'];
        	$data['input_list_edit']['editemployeeid']['value'] = $flashData['employeeid'];
            $data['input_list_edit']['editnama']['value'] = $flashData['nama'];
            $data['input_list_edit']['editidjabatan']['value'] = $flashData['idjabatan'];
            $data['input_list_edit']['edituser_email']['value'] = $flashData['user_email'];
            $data['input_list_edit']['editrek']['value'] = $flashData['rek'];
            $data['input_list_edit']['edituser_pass']['value'] = $flashData['user_pass'];
            $data['input_list_edit']['editpassword2']['value'] = $flashData['password2'];
			$data['input_list_edit']['editactivated']['value'] = $flashData['activated'];
	    }
        
	}
	
	
	$this->load->view('header',$this->dheader);
	$this->load->view('editor_browse',$data);	
	$this->load->view('footer');
	
}


function save($action = 'add'){
    $action = trim(strtolower($action));  
    $this->load->library('form_validation');
    $this->load->helper('ozl');
  	
  	if($action == 'add') {
      	$this->form_validation->set_rules('employeeid', 'ID', 'trim|required|xss_clean|callback_empid_check');
    	$this->form_validation->set_rules('nama', 'Name', 'trim|required|xss_clean|callback_nama_check');
    	$this->form_validation->set_rules('idjabatan', 'Rank', 'trim|required|xss_clean|callback_jabatan_check');		
    	$this->form_validation->set_rules('rek', 'Acc', 'trim|xss_clean');
    	$this->form_validation->set_rules('user_email', 'Email', 'required|valid_email|xss_clean|callback_email_check');
    	$this->form_validation->set_rules('user_pass', ' ', 'required|min_length[5]|max_length[12]|alpha_dash');
    	$this->form_validation->set_rules('password2', 'Password', 'required|matches[user_pass]');
    }else{
        $this->editmode = true;
        $this->editiduser = $this->input->post('editid');
        $this->form_validation->set_rules('editemployeeid', 'ID', 'trim|required|xss_clean|callback_empid_check');
        $this->form_validation->set_rules('editnama', 'Name', 'trim|required|xss_clean|callback_nama_check');
        $this->form_validation->set_rules('editidjabatan', 'Rank', 'trim|required|xss_clean|callback_jabatan_check');       
        $this->form_validation->set_rules('editrek', 'Acc', 'trim|xss_clean');
        $this->form_validation->set_rules('edituser_email', 'Email', 'required|valid_email|xss_clean|callback_email_check');
        $this->form_validation->set_rules('edituser_pass', ' ', 'min_length[5]|max_length[12]|alpha_dash');
        $this->form_validation->set_rules('editpassword2', 'Password', 'matches[edituser_pass]');
        $this->form_validation->set_rules('editid', ' ', 'required|xss_clean|callback_id_check');
		$this->form_validation->set_rules('editactivated', ' ', 'xss_clean');
    }
    $is_passed = $this->form_validation->run();
    
    if($action == 'add') {
        $flashData['message'] = 'Failed to create new crew.';
        $flashData['messageClass'] = "error";
        $flashData['employeeid'] = $this->input->post('employeeid');
        $flashData['nama'] = $this->input->post('nama');
        $flashData['idjabatan'] = $this->input->post('idjabatan');
        $flashData['rek'] = $this->input->post('rek');
        $flashData['user_email'] = $this->input->post('user_email');
        $flashData['user_pass'] = $this->input->post('user_pass');
        $flashData['password2'] = $this->input->post('password2');
    }else{
        $flashData['message'] = 'Failed to update the crew.';
        $flashData['messageClass'] = "error";
        $flashData['employeeid'] = $this->input->post('editemployeeid');
        $flashData['nama'] = $this->input->post('editnama');
        $flashData['idjabatan'] = $this->input->post('editidjabatan');
        $flashData['rek'] = $this->input->post('editrek');
        $flashData['user_email'] = $this->input->post('edituser_email');
        $flashData['user_pass'] = $this->input->post('edituser_pass');
        $flashData['password2'] = $this->input->post('editpassword2');
        $flashData['editid'] = $this->input->post('editid');
		$flashData['activated'] = $this->input->post('editactivated');
    }
	$this->form_validation->set_error_delimiters('', '');
    
  	if ($is_passed == FALSE){
  		
    	if($action == 'add') {

        	$flashData['errors'] = array(
        		'employeeid'=>form_error('employeeid'),
        		'nama'=>form_error('nama'),
        		'idjabatan'=>form_error('idjabatan'),
        		'rek'=>form_error('rek'),
        		'user_email'=>form_error('user_email'),
        		'user_pass'=>form_error('user_pass'),
        		'password2'=>form_error('password2')
        	);
        }else{
            $flashData['errors'] = array(
                'editemployeeid'=>form_error('editemployeeid'),
                'editnama'=>form_error('editnama'),
                'editidjabatan'=>form_error('editidjabatan'),
                'editrek'=>form_error('editrek'),
                'edituser_email'=>form_error('edituser_email'),
                'edituser_pass'=>form_error('edituser_pass'),
                'editpassword2'=>form_error('editpassword2')
            );
        }
	  	
  	}
  	else{
  		
  		if($action == 'add') {
    		$iduser = $this->tank_auth->create_user(
    			'',
    			$this->form_validation->set_value('user_email'),
    			$this->form_validation->set_value('user_pass'),
    			''
    		);
    		if($iduser){
    			$this->crew->add(array(
    				'id'=>	$iduser['user_id'],
    				'employeeid'=> $flashData['employeeid'],
    				'nama'=> $flashData['nama'],
    				'idjabatan'=> $flashData['idjabatan'],
    				'rek'=> $flashData['rek']
    			));
    			
    			$flashData['message'] = 'Successfully created a new crew.';
        		$flashData['messageClass'] = "success";
    			
    		}
        }else{
        	
            if($flashData['activated'] != 0 && $flashData['activated'] != 1){
            	$flashData['activated'] = 1;
            }
			
			//superadmin is always activated
			$arrjab = $this->crew->get_jabatan();
			if($arrjab[$flashData['idjabatan']] == 'superadmin'){
				$flashData['activated'] = 1;
			}
			
            $this->crew->edit_crew(array(
                'id'=>  $flashData['editid'],
                'employeeid'=> $flashData['employeeid'],
                'nama'=> $flashData['nama'],
                'idjabatan'=> $flashData['idjabatan'],
                'rek'=> $flashData['rek']
            ));
            
            $this->crew->edit_user(array(
                'user_id'=> $flashData['editid'],
                'user_email'=> $flashData['user_email'],
                'activated'=> $flashData['activated']
                
            ));
            
            if($flashData['user_pass'] != ''){
                $newpass = $this->tank_auth->hash_password($flashData['user_pass']);
                $this->crew->edit_user(array(
                    'user_id'=> $flashData['editid'],
                    'user_pass'=> $newpass
                ));
                $flashData['user_pass'] = '';
                $flashData['password2'] = '';
            }
            
            $flashData['message'] = 'Successfully updated '.$flashData['nama'];
            $flashData['messageClass'] = "success";
        
        }
		
  		      
  	}
    
    if($action == 'add') {
  	     $flashData['frmaction'] = 'add';
  	}else{
  	     $flashData['frmaction'] = 'edit';
  	}
    $this->session->set_flashdata('results', $flashData);
    redirect('crew_manager');
}




function delete($iduser){
  	
	if($this->crew->get_crew_by_id($iduser)){
	   if($this->crew->is_crew_connected($iduser)){
	       
	       $flashData['message'] = 'Cannot delete crew connected to request/maintenance';
           $flashData['messageClass'] = "error";
           
	   }else{
	   
    		$this->crew->delete($iduser);
    	  	$flashData['message'] = 'Crew successfully deleted';
    	  	$flashData['messageClass'] = "success";
	  }
	  
	}else{
	  $flashData['message'] = 'Failed to delete the crew. invalid crew';
	  $flashData['messageClass'] = "error";
	}
	
	
	$flashData['frmaction'] = 'add';
	$this->session->set_flashdata('results', $flashData);
	redirect('crew_manager');
    
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
			$this->session->set_userdata('keyword_crew_manager', $this->input->post('filter'));
			
		}
	//}
	redirect('crew_manager/browse');
	
}


function empid_check($str){
    //log_message('error', 'emp check editmode: '.$this->editmode.' - editid: '.$this->editid);
    if($this->editmode){
        if($crew = $this->crew->get_crews_by_empid($str)){
            if($crew['id'] != $this->editiduser){
                $this->form_validation->set_message('empid_check', '%s already used');
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
          return TRUE;
        }
    }else{
        if($this->crew->get_crews_by_empid($str)){
          $this->form_validation->set_message('empid_check', '%s already used');
          if($this->editmode)
          return FALSE;
        }else{
          return TRUE;
        }
    }
}


function email_check($str){
    if($this->editmode){
        if($crew = $this->crew->get_crews_by_email($str)){
            //log_message('error', $crew['user_id']. ' --- ' .$this->editiduser);
            if($crew['user_id'] != $this->editiduser){
                $this->form_validation->set_message('email_check', '%s already used');
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
          return TRUE;
        }
    }else{
        if($this->crew->get_crews_by_email($str)){
          $this->form_validation->set_message('email_check', '%s already used');
          return FALSE;
        }else{
          return TRUE;
        }
    }
}


function jabatan_check($str){
	$result = FALSE;
	$arrjab = $this->crew->get_jabatan();
	foreach($arrjab as $idjabatan => $namajabatan){
		if($idjabatan == $str){
			$result = TRUE;
		}
	}

    if(!$result){
    	$this->form_validation->set_message('jabatan_check', '%s is invalid');
      	return FALSE;
    }else{
      	return TRUE;
    }
}


function nama_check($str){
    if($this->editmode){
        if($crew = $this->crew->get_crew_by_nama($str)){
            if($crew['id'] != $this->editiduser){
                $this->form_validation->set_message('nama_check', '%s already used');
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
          return TRUE;
        }
    }else{
        if($this->crew->get_crew_by_nama($str)){
          $this->form_validation->set_message('nama_check', '%s already existed');
          return FALSE;
        }else{
          return TRUE;
        }
    }
}


function id_check($str){
    
    if($this->crew->get_crew_by_id($str)){
        return TRUE;
    }else{
        $this->form_validation->set_message('id_check', '%s is invalid');
        return FALSE;
    }
}


}
?>