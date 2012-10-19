<?php
//foreach($input_list_add as $iName => $input){
    
  $errorspan = '<span class="errornote">'. (isset($errors[$iName])? $errors[$iName] : '').'</span>';  
  
  if(isset($browse_mode)){
    
  }else{
  	//log_message('error',var_export($iName,true));
    if(strtolower($input['type']) != 'html'){
      echo '<li><label for="'.$iName.'">'.$input['title'].'</label>';
    }elseif(strtolower($input['type']) == 'label'){
      echo '<li><label>'.$input['title'].'</label>';
    }
  }
  
  switch (strtolower($input['type'])){
    
    case 'text':
      //
      echo '<div id="err-'.$iName.'" class="errorwrap areaerror'. (isset($errors[$iName])? ' iserror' : '').'">';
      echo '<input type="text" id="'.$iName.'" name="'.$iName.'" value="'.$input['value'].'"'. (isset($input['class'])? ' class="'.$input['class'].'"' : '');
      if(isset($input['disabled'])){
        echo 'disabled='.$input['disabled'];
      }
      echo ' />'.$errorspan;
      break;
      
    case 'password':
      //
      echo '<div id="err-'.$iName.'" class="errorwrap areaerror'. (isset($errors[$iName])? ' iserror' : '').'">';
      echo '<input type="password" id="'.$iName.'" name="'.$iName.'" value="'.$input['value'].'"'. (isset($input['class'])? ' class="'.$input['class'].'"' : '').'/>'.$errorspan;
      break;
      
    case 'select':
      
      //
      echo '<div id="err-'.$iName.'" class="errorwrap'. (isset($errors[$iName])? ' iserror' : '').' ">';
      echo '<select id="'.$iName.'" name="'.$iName.'"'. (isset($input['class'])? ' class="'.$input['class'].'"' : '').'>';
      foreach($input['select_list'] as $selval => $sellbl){
        if(isset($input['select_list'][0])){
          //there's no label for the options, so we use the values as labels
          $sellbl = $selval;
        }
            echo '<option value="'.$selval.'" '.(($selval == $input['value'])? ' selected="selected"' : '').'>'.$sellbl.'</option>';
      } 
      echo '</select>'.$errorspan;
      break;
    
    case 'textarea':
      //
      echo '<div id="err-'.$iName.'" class="widerr errorwrap areaerror'. (isset($errors[$iName])? ' iserror' : '').' ">';
      echo '<textarea id="'.$iName.'" name="'.$iName.'" class="'. (isset($input['class'])? ' '.$input['class'] : '').'" rows="10" cols="50">'.$input['value'].'</textarea><br/>'.$errorspan;
      break;
    
    case 'checkbox':
      //
      $dval = 'y';
      if(isset($input['def_val'])){
      	$dval = $input['def_val'];
      }
      echo '<div id="err-'.$iName.'" class="errorwrap'. (isset($errors[$iName])? ' iserror' : '').'">';
      echo '<input type="checkbox" name="'.$iName.'" id="'.$iName.'" value="'.$dval.'"'.((strtolower($input['value']) === $dval)? ' checked="checked"' : ''). (isset($input['class'])? ' class="'.$input['class'].'"' : '').'/>'.$errorspan;
      break;
    
    case 'hidden':
      //
      echo '<div id="err-'.$iName.'" class="hidden errorwrap areaerror'. (isset($errors[$iName])? ' iserror' : '').'">';
      echo '<input type="hidden" id="'.$iName.'" name="'.$iName.'" value="'.$input['value'].'"'. (isset($input['class'])? ' class="'.$input['class'].'"' : '').'/>'.$errorspan;
      break;
      
    case 'label':
      //
      echo '<div id="err-'.$iName.'" class="errorwrap">';
      echo '<label id="'.$iName.'"'.(isset($input['class'])? ' class="'.$input['class'].'"' : '').'>'.$input['value'].'</label>';
      break;
      
    default:
      //HTML only;
      if(!isset($browse_mode)){echo '<li>';}
      echo '<div'.(isset($input['class'])? ' class="'.$input['class'].'"' : '').'>'.$input['value'];
  }
  
  echo '</div>';
  if(isset($browse_mode)){
    
  }else{
    echo '</li>';
  } 
//}
?>