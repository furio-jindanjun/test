<div id="contentframe">
  <form  method="post" class="formeditor ajaxed" id="frmeditor" action="<?php echo base_url().$form_action.$item_id;?>">
  <div class="content contentleft">
      <div class="contentheader">
            <div class="frite toolbox"></div>
            <h2 class="boxtitle fleft"><?php echo $page_title;?></h2>
      </div>
      <ul class="leftcol fullwidth formul ajaxhide">
          <?php
            foreach($input_list as $iName => $input){
        include('input_list_template.php'); 
            }
            ?>            
        </ul>
        <div class="contentfooter">
            <?php
            foreach($input_list_hidden as $iName => $input){
              include('input_list_hidden_template.php');
            }
            
            if($saveable) echo '<a href="'.base_url().$bodyId.'" class="ajaxhide button aw">Browse</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="submitbtn">Save</button>';
           ?>
        </div>
  </div>
  <div class="content contentright ajaxhide">
    <ul class="ritecol fullwidth formul ajaxhide">
          <?php
            foreach($input_list_right as $iName => $input){
          
        include('input_list_template.php');
            }
            ?>
        </ul>
    
      <div class="contentheader">
        <?php if($ritecol_editable){?>
        <div class="searchbox">
          <input type="text" value="Add item" class="keywordsearch" name="itemsearch" id="itemsearch"/>
          <input type="hidden" value="" name="items" id="items"/>         
        </div>
        <?php }?>
      </div>
      <table class="headertable">
        <tbody>
        <tr>
          <?php
          foreach($rite_columns as $header){
            echo '<th width="'.$header['width'].'">';
            if(isset($header['class'])){echo '<span class="'.$header['class'].'">';}
            echo $header['header_title'];
            if(isset($header['class'])){echo '</span>';}
            echo '</th>';
          }
          ?>
        </tr>
        </tbody>
      </table>
      <div class="leftcol fullwidth withheader">
        <div class="tableframe">
          <div id="scrollme" class="scrolledtable">
            <div class="row hidden">
            <?php
            /*
            foreach($rite_columns as $header){
              echo '<div style="width:'.$header['width'].'">';
              $browse_mode = true;
              foreach($header['input_list'] as $iName => $input){
            
                include('input_list_template.php');
                    }
              echo '</div>';
            }
            */
            ?>
            </div>
            <div class="row acenter nodata<?php if($allrows){echo ' hidden';}?>" id="emptytbl">
              <span class="rowhead">no item(s)</span>
              <div id="err-list" class="errorwrap">
                <span class="errornote"></span>
              </div>
            </div>
            
            <?php
            //log_message('error', var_export($allrows, true)); 
            foreach($allrows as $row){
                
              echo'<div class="row">';
              
              //foreach($rite_columns as $header){
                /*
                echo '<div style="width:'.$header['width'].'">';
                echo '<div'. ((isset($header['class']))? ' class="'.$header['class'].'"' : '').'>';
                foreach($header['field_names'] as $idx => $field_name){
                  echo $row[$field_name];
                  if(isset($header['field_names'][$idx+1]))echo '<br/>';
                }               
                echo '</div></div>';
                 */
                 $editstrq = ' disabled="disabled"';
                 $editstri = ' disabled="disabled"';
                 if($ritecol_editable){
                  $editstrq = '';
                  $editstri = ''; 
                 }
                 
                 $qstr = '';
                 if(strtolower($row['namatipe']) == 'logistic'){
                    $qstr = '<b>'.$row['brgquantity'].'</b> in stock';
                 }else{
                  $editstrq = ' disabled="disabled"';
                 }
                
                 echo '<div style="width:80%">';
                 echo '<b>'.$row['namabarang'].'</b><br/><b>'.$row['serialnum'].'</b><br/>'.$qstr;
                 echo '</div>';
                 echo '<div style="width:20%">';
                 echo '<input type="text" name="quantity[]" class="numonly arite" value="'.$row['quantity'].'"'.$editstrq.'/><input type="hidden" name="idbarang[]" value="'.$row['idbarang'].'"'.$editstri.'/>';
                 echo '</div>';
              //}
              echo'<h2 class="rowinfo">';
              if($ritecol_editable)echo '<a title="Delete item '.$row['namabarang'].'" href="#" onclick="delItem(this);" class="delbtn">delete</a>';
              echo '</h2>';
              echo '</div>';
              
            }
            ?>
            
          </div>
        </div>          
      </div>
    
    <div class="contentfooter arite">
      <span class="bezeled">total <b id="totitem"><?php echo count($allrows);?></b> item(s).</span>
    </div>
  </div>
  </form>
</div>