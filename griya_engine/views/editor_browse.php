<?php
/*
 * Required for this template:
 * 
 * 	$data['url_add'] = "";
	$data['add_saveable'] = true;
	$data['url_edit'] = "";
	$data['edit_saveable'] = true;
	$data['editdelid'] = field_name;
	$data['url_browse'] = '';
	$data['url_filter'] = '';
 * 
 * $data['input_list_add'] = array(
				'inputname/id' => array('type' => '', 'title' => '', 'value'=>'', 'class'=>optional, , 'select_list'=> if type is SELECT)
	);
	$data['input_list_hidden_add'] = array(
				'inputname/id' => array('value' => '')
	);
	
	$data['input_list_edit'] = array(
				'inputname/id' => array('type' => '', 'title' => '', 'value'=>'', 'class'=>optional, , 'select_list'=> if type is SELECT)
	);
	
	$data['input_list_hidden_edit'] = array(
				'inputname/id' => array('value' => '')
	);
 * 
 * 
 * $data['columnHeaders'] = array(
              array('header_title'=>'', 'field_name'=> '', 'class'=>'', 'width'=>'%', 'rowinfo' => bool, 'format' => optional php scripts)
  );
 *
 * $data['rowInfoBtns'] = array(
              array('html'=> '', 'title'=>'delete ticket id ', 'url'=> 'maintenance/delete/',  'field_name'=>'idrequestmain', 'class'=> 'btndel')
              
  );
 * 
*/
?>
		<div id="contentframe">
			<div class="content contentleft">
				<div id="tab-add" class="toggletab<?php echo ($frmaction=="add")?'':' invis';?>">
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
  		       			<?php
  		       			  if(isset($input_list_add_pop)){
  		       			      echo '<ul id="pop-add" class="hide leftcol fullwidth formul">';
          		       			      foreach($input_list_add_pop as $iName => $input){
                                            include('input_list_template.php');
                                      }
  		       			      echo '</ul>';
  		       			  }
  		       			?>
  		       			<div class="contentfooter">
  		       				<?php
				          	foreach($input_list_hidden_add as $iName => $input){
				          		include('input_list_hidden_template.php');
				          	}
				          	
				          	if($add_saveable) echo '<button type="submit" class="submitbtn">Save</button>';
				          	
				          	if(isset($stockStat)) echo '<a href="'.base_url().$stockStat.'" class="button browseback">Kembali ke Menu Stok</a>';
                            
				           ?>
  		       			</div>
	         		</form>
         </div>
         <div id="tab-edit" class="toggletab<?php echo ($frmaction=="edit")?'':' invis';?>">
					<div class="contentheader">
						<div class="frite searchbox">
							<a href="#" onclick="chgTab('tab-add');" class="btnadd" title="Add New">New</a> 
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
				          	
				          	if($edit_saveable) echo '<button type="submit" class="submitbtn">Update</button>';
				           ?>
				      </div>
	          </form>
         </div>
		    </div>
		<div class="content contentright">
			<form id="frmedit" action="<?php echo base_url().$url_filter;?>" method="post" class="aw inputfilterspan">
					<div class="toolbox">
						
					   <?php
					      if(isset($stockStat)){
					   ?>
					   <div class="stocktimeline">
					       <input id="filter" class="" alt="keyword filter" type="hidden" name="filter" value="<?php echo $keyword;?>"/>
					       <label class="judulstok">Mode: </label>
					       <input type="radio" id="semua" name="viewstock" value="semua" checked onclick="document.getElement('.toolbox .stocktimeline .trangep').addClass('hidden');$('frmedit').submit();"/>
					       <label for="semua" class="radbut">Lihat Semua</label>
					   </div>
					   <div class="stocktimeline">
                           <input type="radio" id="waktu" name="viewstock" value="waktu" onclick="document.getElement('.toolbox .stocktimeline .trangep').removeClass('hidden');" />
                           <label for="waktu" class="radbut">Waktu Tertentu</label>
                           <p class="trangep hidden">
                             <input class="buttoncal" type="text" name="dfrom" id="dfrom" value="<?php echo date('d-M-Y');?>" />
                             <input class="buttoncal" type="hidden" name="dfrom_tmp" id="dfrom_tmp" value="<?php echo date('Y-m-d');?>" />
                             <label class="tselab">Sampai</label>
                             <input class="buttoncal" type="text" name="dto" id="dto" value="<?php echo date('d-M-Y');?>" />
                             <input class="buttoncal" type="hidden" name="dto_tmp" id="dto_tmp" value="<?php echo date('Y-m-d');?>" />
                           </p>
                       </div>
					   <?php
					      }
					      else{
					   ?>
    					    <span class="emptywrap filterwrap">
    					    <input id="filter" class="emptiable overTexted" alt="keyword filter" type="text" name="filter" value="<?php echo $keyword;?>"/>
    					    </span>
				       <?php }?>
						
					</div>
					<table class="headertable">
						<tbody>
						<tr>
							<?php 
							foreach($columnHeaders as $header){
								if(!$header['rowinfo']){
									echo '<th width="'.$header['width'].'">';
									if(isset($header['class'])){echo '<span class="'.$header['class'].'">';}
									echo $header['header_title'];
									if(isset($header['class'])){echo '</span>';}
									echo '</th>';
								}
							}
							?>
						</tr>
						</tbody>
					</table>
					<div class="leftcol fullwidth withheader">
						<div class="tableframe">
							<div id="scrollme" class="scrolledtable">
								<?php
								
								$jsondata = null;
								$this->load->helper('text');
								
								if($rowcount < 1){
									echo '<div class="nodata acenter">No Data Found</div>';
								}
								else{
																
									foreach($allrows as $row){
										
										$jsonData = array();
										$btns = $rowInfoBtns;
										
										foreach($row as $field => $value){
											$jsonData[$field] = htmlentities($value);
	                                        if(($field == 'kredit') && ($value)){
	                                           $jsonData['metode'] = htmlentities('Masuk'); 
	                                        }
	                                        elseif(($field == 'debet') && ($value)){
	                                           $jsonData['metode'] = htmlentities('Keluar'); 
	                                        }
	                                        if(($field == 'nama') && ($value)){
                                               $jsonData['obat'] = htmlentities($value); 
                                            }
	                                        
										}   
										$rowdata = str_replace('"','\'', json_encode($jsonData));
										//echo 'jason:'. var_export($jsonData,true);
										echo'<div class="row';
											if(isset($row['activated'])&&$row['activated']==0){
											    if(isset($rowInfoBtnsAct)){
											         $btns = $rowInfoBtnsAct;
                                                     echo ' nonaktif';
											    }
												
											}
										echo '">';
										$rowinfo_str = '';
										
										foreach($columnHeaders as $header){
											if($header['rowinfo']){
											     if(isset($row['rowinfo'])){
											         $rowinfo_str .= $header['header_title'].': '.$row[$header['field_name']];
											     }
												
											}else{
												echo '<div style="width:'.$header['width'].'">';
												if(isset($header['class'])){echo '<span class="'.$header['class'].'">';}
												if(isset($header['format'])){
													eval($header['format']);
												}else{
													echo $row[$header['field_name']].'&nbsp;';
												}
												if(isset($header['class'])){echo '</span>';}
												echo '</div>';
											}
										}
										echo'<h2 class="rowinfo">';
										echo $rowinfo_str.'<br/>';
										
										//default edit button
										if(isset($is_stock)){
										  echo '<a title="Lihat Detil Stok" href="'.base_url().$link_stock.$row['idobat'].'" class="btnedit spe">Detil Stok</a>';
										}
										elseif(isset($stockStat)){
										  
										}
										else{
										  echo '<a title="rubah data" href="#" class="btnedit spe" rowdata="'.$rowdata.'">edit</a>';
										}
										//echo var_export($row,true);
										
										if(isset($nonaktif)){
											foreach($nonaktif as $non){
												if(($row['id']==$non['id'])&&($row['activated']==1)){
													if(isset($rowInfoBtnsNon)){
													   $btns = $rowInfoBtnsNon;
													}
													
												}
											}
										}
										
										if(isset($btns)){
										  foreach($btns as $button){
                                            if(isset($button['visibleVar'])){
                                                    
                                            }
                                            if(isset($button['field_name'])){
                                                $button['title'] = $button['title'].$row[$button['field_name']];
                                            }
                                            
                                            echo '<a href="'.base_url().$button['url'].$row[$editdelid].'"'.((isset($button['class']))? ' class="'.$button['class'].'"' :'').'" title="'.$button['title'].'">'
                                            .$button['html'].'</a>';
                                           }
										}
										
										echo '</h2></div>';
										
									}
								}
								
								?>
							</div>
						</div>					
					</div>
					
				</form>
				<?php
				if($rowcount > 0){
				
					$startcount= ($curpage-1)* $results_per_page +1;
					$itemcount = $startcount + count($allrows) - 1;
					
				?>
				
    				<form id="frmpaging" class="contentfooter arite aw" method="post" action="<?php echo base_url().$url_browse;?>">
    					<div class="fleft pagingbox <?php if(isset($thepage)){echo ' hidden';}?>">
    					  
    					    <a href="<?php echo base_url().$url_browse.'1';?>" id="pageup" class="aw">|&lt;</a>&nbsp;<a class="aw" href="<?php echo base_url().$url_browse.($curpage-1);?>">&lt;&lt;</a>
    						&nbsp;&nbsp;page # <input type="text" name="page" value="<?php echo $curpage;?>" size="2"/> from <?php echo $maxpage;?> page(s).&nbsp;&nbsp;
    						<a class="aw" href="<?php echo base_url().$url_browse.($curpage+1);?>">&gt;&gt;</a>&nbsp;<a class="aw" href="<?php echo base_url().$url_browse.$maxpage;?>" >&gt;|</a>
    				
    					</div>
    					<span class="bezeled">showing <b><?php echo $startcount .'</b> to <b>'. $itemcount .'</b> from <b>'.$rowcount;?></b> data.</span>
    				</form>
    				
				<?php
				}
				?>
		</div>
		</div>