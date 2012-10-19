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
            
            if($saveable) echo '<a class="ajaxhide button submitbtn" href="'.base_url().'customer/editor/edit/'.$id_cust.'">Kembali ke halaman Customer</a>';
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
            <a href="#" id="select-0" title="Please upload only images, maximum 2 Mb filesize!" class="">Unggah Foto Baru</a>
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
          	<div class="row acenter nodata<?php if($allrows){echo ' hidden';}?>" id="emptytbl">
              <span class="rowhead">no photo(s)</span>
              <div id="err-list" class="errorwrap">
                <span class="errornote"></span>
              </div>
            </div>
            <div id="photoalbum">
            <?php
            //log_message('error', var_export($allrows, true)); 
            foreach($allrows as $row){
            	$filen2 = explode('.', $row);    
	            echo'<div class="row" id="'.$filen2[0].'">';
	            
	            echo '<img src="'.base_url().'photos/'.$id_cust.'/'.$row.'"/><br/><a target="_BLANK" href="'.base_url().'photos/'.$id_cust.'/'.$fulljpg.'" >view</a> | <a rel="'.base_url().'customer/del_img/'.$id_cust.'/'.$filen2[0].'" href="#" onclick="delItem(this);">hapus</a>';
	            echo '</div>';
              
            }
            ?>
            </div>
          </div>
        </div>          
      </div>
    
    <div class="contentfooter arite">
      <span class="bezeled">total <b id="totitem"><?php echo count($allrows);?></b> foto.</span>
    </div>
  </div>
  </form>
</div>