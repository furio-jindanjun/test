<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploader extends CI_Controller {
	
function __construct(){
    parent::__construct();
    log_message('error', 'starting upload');
    $this->load->library('form_validation');
	/*
	 
	if($this->tank_auth->is_logged_in()){
      	$this->load->library('form_validation');
    }else{
      $this->session->set_flashdata('adminfrom', '/backend/portfolio');
	  $this->session->set_flashdata('results', array('message' => 'user seesion no longer exists, please login again', 'messageClass' => 'error'));
      redirect('backend/login');
    }
	 */
	
 }
 
function image($id_portfolio){
  	//log_message('error', 'starting upload');
	$folder_name = $id_portfolio;
	//log_message('error','uploadIDP:'.$id_portfolio);
	if(!is_dir('./photos/'.$folder_name.'/')){
		mkdir('./photos/'.$folder_name.'/', 0, true);
	}
  $config['upload_path'] = './photos/'.$folder_name.'/';
  $config['allowed_types'] = 'gif|jpg|jpg|png|bmp';
  $config['max_size'] = '2000';
  $config['max_width']  = '2000';
  $config['max_height']  = '3000';
  $config['overwrite']  = true;
  //$config['file_name']  = intval($idproduk).'.jpg';
    
  $this->load->library('upload', $config);
  
  $this->load->helper('file');
  
  //delete_files('./photos/tmp/', TRUE);
  
  
  if ( ! $this->upload->do_upload('Filedata'))
  {
    $error = $this->upload->display_errors('<b>', '</b>');
    
    $return = array(
      'status' => '0',
      'error' => $error
    );
    
  } 
  else
  {
    $uploadData = $this->upload->data();
	//log_message('error', 'dataupload'.var_export($uploadData, true));
    /*
    Array
    (
        [file_name]    => mypic.jpg
        [file_type]    => image/jpeg
        [file_path]    => /path/to/your/upload/
        [full_path]    => /path/to/your/upload/jpg.jpg
        [raw_name]     => mypic
        [orig_name]    => mypic.jpg
        [file_ext]     => .jpg
        [file_size]    => 22.2
        [is_image]     => 1
        [image_width]  => 800
        [image_height] => 600
        [image_type]   => jpeg
        [image_size_str] => width="800" height="200"
    )
     */
     
    //
    //RESIZE IT
    //
    $config['image_library'] = 'gd2';
    $config['source_image'] = $config['upload_path'] . $uploadData['file_name'];
    $config['maintain_ratio'] = TRUE;
    $config['width'] = $this->config->item('image_width');
    $config['height'] = $this->config->item('image_height');
	
    $imgRatio = $this->config->item('image_width')/$this->config->item('image_height');
    $curRatio = $uploadData['image_width']/$uploadData['image_height'];
    if($imgRatio >= $curRatio){
        
      $config['height'] = ceil( $uploadData['image_height']/($uploadData['image_width']/$this->config->item('image_width')) );
      
    }else{
      
      $config['width'] = ceil( $uploadData['image_width']/($uploadData['image_height']/$this->config->item('image_height')) );
      
    }
	$this->load->library('image_lib', $config);
	
    if($uploadData['image_height'] > $this->config->item('image_height') || $uploadData['image_width'] > $this->config->item('image_width')){
         //log_message('error', 'width: '.$config['width'].' --- height: '.$config['height']);
	    if ( ! $this->image_lib->resize())
	    {
	        log_message('error', 'error CROP : '.$this->image_lib->display_errors());
	    }	
    }
    
    //
    //CROP IT
    //
    $config['maintain_ratio'] = TRUE;
    $config['width'] = $this->config->item('thumb_width');
    $config['height'] = $this->config->item('thumb_height');
    $config['x_axis'] = 0;
    $config['y_axis'] = 0; 	
	$config['new_image'] = 'thumb_'.$uploadData['file_name'];
	$config['create_thumb'] = TRUE;
	//$config['thumb_marker'] = '_thumb';
	if($uploadData['image_width']>$uploadData['image_height']){
		$config['master_dim'] = 'height';
	}else{
		$config['master_dim'] = 'width';
	}
    
    $this->image_lib->initialize($config);
    if ( ! $this->image_lib->resize())
    {
        log_message('error', 'error CROP : '.$this->image_lib->display_errors());
    }
	
	$config['maintain_ratio'] = FALSE;
	unset($config['new_image']);
	$config['source_image'] = $config['upload_path'] . 'thumb_'. $uploadData['file_name'];
	$this->image_lib->initialize($config);
    if ( ! $this->image_lib->crop())
    {
        log_message('error', 'error CROP : '.$this->image_lib->display_errors());
    }
  
    $return = array(
      'status' => '1',
      'name' => $uploadData['file_name']
    );
    
	$thumbn = $uploadData['raw_name'].strtolower($uploadData['file_ext']); 
    $uploadData['raw_name'] = sha1($uploadData['raw_name']);
    $uploadData['file_ext'] = strtolower($uploadData['file_ext']);
    $uploadData['file_name'] = $uploadData['raw_name'].$uploadData['file_ext'];
	    
    //RENAME THE BIG IMAGE
    rename($uploadData['full_path'], $uploadData['file_path'] . $uploadData['file_name'] );
    //log_message('error', 'rename : '.$uploadData['full_path'] .' to:'. $uploadData['file_path'] . $uploadData['file_name']);
    //RENAME THE THUMB IMAGE
    rename($uploadData['file_path'] . 'thumb_' .$thumbn, $uploadData['file_path'] . 'thumb_' . $uploadData['file_name'] );
    
    $uploadData['full_pathx'] = $uploadData['full_path'];
	$uploadData['full_path'] = $uploadData['file_path'] . $uploadData['file_name'];
  
    // Our processing, we get a hash value from the file
    $return['hash'] = sha1_file($uploadData['full_path']);
    //$return['hash'] = var_export($uploadData, true);
    
    //move_uploaded_file($_FILES['Filedata']['tmp_name'], '../uploads/' . $_FILES['Filedata']['name']);
    $return['src'] = base_url().'photos/'.$folder_name.'/thumb_' .$uploadData['file_name'];
    
    $return['filename'] = $uploadData['file_name'];
	$return['filen2'] = $uploadData['raw_name'];
  
    // ... and if available, we get image data
    //$info = @getimagesize($_FILES['Filedata']['tmp_name']);
  
    if ($uploadData['is_image']) {
      $return['width'] = $uploadData['image_width'];
      $return['height'] = $uploadData['image_height'];
      $return['mime'] = $uploadData['file_type'];
    }
  }//end else if ( ! $this->upload->do_upload())
  
  if (isset($_REQUEST['response']) && $_REQUEST['response'] == 'xml') {
    // header('Content-type: text/xml');
  
    // Really dirty, use DOM and CDATA section!
    echo '<response>';
    foreach ($return as $key => $value) {
      echo "<$key><![CDATA[$value]]></$key>";
    }
    echo '</response>';
  } else {  	
    // header('Content-type: application/json');
    //log_message('error', var_export($uploadData, true));
  	echo json_encode($return);
  }
	
}

}
?>