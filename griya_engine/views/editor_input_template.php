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
          <?php if($input_list_right){?>
          <ul class="ritecol fullwidth formul ajaxhide">
            <?php
              foreach($input_list_right as $iName => $input){
            
          include('input_list_template.php');
              }
              ?>
          </ul>
          <?php } ?>
            <div class="contentfooter">
              <?php
              foreach($input_list_hidden as $iName => $input){
                include('input_list_hidden_template.php');
              }
              
              if($saveable) echo '<a href="'.base_url().$bodyId.'" class="ajaxhide button aw">Browse</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="submitbtn">Save</button>';
             ?>
            </div>