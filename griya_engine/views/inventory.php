<div id="contentframe">
		  <div class="content side">
         <div class="content contentleft">
            <div class="toggletab<?php echo ($frmaction=="add")?'':' invis';?>" id="tab-add">
                <div class="contentheader">
                    <h2 class="boxtitle fleft"><?php echo $addtitle;?></h2>
                </div>
                <form id="frmadd" action="<?php echo base_url().$url_add;?>" method="post" class="formeditor aw">
					<ul class="leftcol fullwidth formul">
						<?php
						foreach($input_list_add as $iName => $input){
							include('input_list_template.php');
						}
						?>
	       			</ul>
	       			<div class="contentfooter">
	       				<?php
			          	foreach($input_list_hidden_add as $iName => $input){
			          		include('input_list_hidden_template.php');
			          	}
			          ?>
	       			</div>
         		</form>
            </div><!--tabadd--></!--tabadd-->
            <div class="toggletab <?php echo ($frmaction=="edit")?'':' invis';?>" id="tab-edit">
               <div class="contentheader">
						<div class="frite searchbox">
							<a href="#" onclick="chgTab('tab-add');" class="btnadd hidden">Tambah Kategori baru</a> 
					   	</div>
					   	<h2 class="boxtitle fleft"><?php echo $edittitle;?></h2>
				    </div>
    				<form action="<?php echo base_url().$url_edit;?>" method="post" class="formeditor aw">
    					<ul class="leftcol fullwidth formul">
    						<?php
  							foreach($input_list_edit as $iName => $input){
  								include('input_list_template.php');
							}
  							?>
		          		</ul>
				    	<div class="contentfooter">
				            <?php
				          	foreach($input_list_hidden_edit as $iName => $input){
				          		include('input_list_hidden_template.php');
				          	}
				          	
				          	if($add_saveable) echo '<a class="button editurl aw" href="'.base_url().'item/editor/edit/">edit location</a>';
				           ?>
				        </div>
	          		</form>
            </div><!--tabedit--></!--tabedit-->
          </div>
          <div class="content contentright">
             <div class="contentframe">
                <form action="<?php echo base_url();?>inventory/filter" method="post" class="inputfilterspan">
                    <span class="emptywrap">
                    	<span class="filterwrap">
	                    	<input id="filter" class="emptiable" name="filter" type="text" title="keyword filter" value="<?php echo(isset($keyword)?$keyword:'')?>"/>
	                    </span>
                    </span>
                    <button type="submit" class="hidden">&gt;</button>
                    <?php
                        if($rowcount < 1){
                          echo '<div class="nodata">Data kosong</div>';
                        }
                        else{
                    ?>
                    <table class="headertable">
                        <tbody>
                        <tr>
                          <th width="40%"><span class="rowhead">Item Name</span></th>
                          <th width="60%">Location</th>
                        </tr>
                      </tbody>
                    </table>
                    <div class="leftcol fullwidth withheader">
                      <div class="tableframe">
                        <div id="scrollme" class="scrolledtable">
                            <?php
                                $jsondata = null;
                                $this->load->helper('text');
                                
                                foreach($allrows as $currow){
                                  $currow['url'] = base_url().'item/editor/edit/'.$currow['idbarang'];
                                  $jsonData = array();
                                  foreach($currow as $field => $value){
                                      $jsonData[$field] = htmlentities($value);
                                  }
                                  $rowdata = str_replace('"','\'', json_encode($jsonData));  
                                  
                                  echo'<div class="row">'.
                                      '<div style="width:40%"><span class="rowhead">'.$currow['namabarang'].'</span></div>'.
                                      '<div style="width:58%">'.$currow['iatacode'].' - '.$currow['nama_bandara'].'</div>'.
                                      '<h2 class="rowinfo">'.
                                        '<a title="view detail'.$currow['namabarang'].'" id="'.$currow['idbarang'].'" href="#" class="btnedit spe" rowdata="'.$rowdata.'">view detail</a>'.
                                      '</h2>'.
                                    '</div>';
                                  }
                            ?>
                        </div><!-->scrollme</-->
                      </div><!-->tableframe</-->          
                    </div><!-->leftcol fullwidth withheader</-->
                    <?php
                        }//end else if($rowcount < 1){
                    ?>
              </form>
              <?php
                  if($rowcount > 0){
                  
                    $startcount= ($curpage-1)* $results_per_page +1;
                    $itemcount = $startcount + count($allrows) - 1;
                
              ?>
              <form id="frmpaging" class="contentfooter arite aw" method="post" action="<?php echo base_url().$url_browse;?>">
					<div class="fleft pagingbox"><a href="<?php echo base_url().$url_browse.'1';?>" id="pageup" class="aw">|&lt;</a>&nbsp;<a class="aw" href="<?php echo base_url().$url_browse.($curpage-1);?>">&lt;&lt;</a>
						&nbsp;&nbsp;page # <input type="text" name="page" value="<?php echo $curpage;?>" size="2"/> from <?php echo $maxpage;?> page(s).&nbsp;&nbsp;
						<a class="aw" href="<?php echo base_url().$url_browse.($curpage+1);?>">&gt;&gt;</a>&nbsp;<a class="aw" href="<?php echo base_url().$url_browse.$maxpage;?>" >&gt;|</a>
					</div>
					<span class="bezeled">showing <b><?php echo $startcount .'</b> to <b>'. $itemcount .'</b> from <b>'.$rowcount;?></b> data.</span>
				</form>
              <?php
                }
              ?>
           </div>
         </div><!-->class contentframe</-->
      </div><!-->content</-->
</div>