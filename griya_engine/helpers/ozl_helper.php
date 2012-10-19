<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('strip_html_comment'))
{
	function strip_html_comment($subject)
	{
		//$result = false;
		$subject = str_ireplace('<!--', '', $subject);
    	$subject = str_ireplace('-->', '', $subject);
    	$subject = str_ireplace('--', '', $subject);
    	$subject = trim($subject);
		return $subject;
	}
}

if (! function_exists('test_null_tags'))
{
  function test_null_tags($subject)
  {
    
    $subject = trim($subject);    
    $tmp = strip_tags(str_replace('<p> </p>', '', $subject));
    if($tmp == ""){
      $subject = ''; 
    }
    
    return $subject;
  }
}

if (! function_exists('php_date_view'))
{
  function php_date_view($mysqldate, $separator = '/')
  {
    
    $mysqldate = explode('-', trim($mysqldate));
    return $mysqldate[2].$separator.$mysqldate[1].$separator.$mysqldate[0]; 
  }
}
?>