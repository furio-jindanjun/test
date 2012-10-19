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
  							/*foreach($input_list_add as $iName => $input){
  								include('input_list_template.php');
							}*/
							     echo '<li><label for="tgl">'.$input_list_add['tgl']['title'].'</label>';
							     echo '<div id="err-tgl" class="errorwrap areaerror'. (isset($errors['tgl'])? ' iserror' : '').'">';
                                 echo '<input type="text" id="tgl" name="tgl" value="'.$input_list_add['tgl']['value'].'"'. (isset($input_list_add['tgl']['class'])? ' class="'.$input_list_add['tgl']['class'].'"' : '').'/><span class="errornote">'. (isset($errors['tgl'])? $errors['tgl'] : '').'</span>';
                                 echo '</div></li>';
                                 
                                 echo '<li><label for="obat">'.$input_list_add['obat']['title'].'</label>';
                                 echo '<div id="err-obat" class="errorwrap areaerror'. (isset($errors['obat'])? ' iserror' : '').'">';
                                 echo '<input type="text" id="obat" name="obat" value="'.$input_list_add['obat']['value'].'"'. (isset($input_list_add['obat']['class'])? ' class="'.$input_list_add['obat']['class'].'"' : '').'/><span class="errornote">'. (isset($errors['obat'])? $errors['obat'] : '').'</span>';
                                 echo '</div></li>';
                                 
                                 echo '<li><label for="obat">'.$input_list_add['obat']['title'].'</label>';
                                 echo '<div id="err-obat" class="errorwrap areaerror'. (isset($errors['obat'])? ' iserror' : '').'">';
                                 echo '<input type="text" id="obat" name="obat" value="'.$input_list_add['obat']['value'].'"'. (isset($input_list_add['obat']['class'])? ' class="'.$input_list_add['obat']['class'].'"' : '').'/><span class="errornote">'. (isset($errors['obat'])? $errors['obat'] : '').'</span>';
                                 echo '</div></li>';
  							?>
  		       			</ul>
  		       			<div class="contentfooter">
  		       				<?php
				          	/*foreach($input_list_hidden_add as $iName => $input){
				          		include('input_list_hidden_template.php');
				          	}*/
				          	echo '<input type="hidden" id="tgl_tmp" name="tgl_tmp" value="'.$input_list_hidden_add['tgl_tmp']['value'].'"'. (isset($input_list_hidden_add['tgl_tmp']['class'])? ' class="'.$input_list_hidden_add['tgl_tmp']['class'].'"' : '').'/>';
				          	echo '<input type="hidden" id="idobat" name="idobat" value="'.$input_list_hidden_add['idobat']['value'].'"'. (isset($input_list_hidden_add['idobat']['class'])? ' class="'.$input_list_hidden_add['idobat']['class'].'"' : '').'/>';
				          	
				          	if($add_saveable) echo '<button type="submit" class="submitbtn">Save</button>';
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
  							//foreach($input_list_edit as $iName => $input){
  							//	include('input_list_template.php');
							//}
  							?>
		          		</ul>
				    	<div class="contentfooter">
				            <?php
				          	//foreach($input_list_hidden_edit as $iName => $input){
				          	//	include('input_list_hidden_template.php');
				          	//}
				          	
				          	if($edit_saveable) echo '<button type="submit" class="submitbtn">Update</button>';
				           ?>
				      </div>
	          </form>
         </div>
		    </div>
		<div class="content contentright">
			<formid="frmedit" action="<?php echo base_url().$url_filter;?>" method="post" class="aw inputfilterspan">
					<div class="toolbox">
						<span class="emptywrap filterwrap"><input id="filter" class="emptiable overTexted" alt="keyword filter" type="text" name="filter" value="<?php echo $keyword;?>"/></span>
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
										}
										$rowdata = str_replace('"','\'', json_encode($jsonData));
										 
										echo'<div class="row';
											if(isset($row['activated'])&&$row['activated']==0){
												$btns = $rowInfoBtnsAct;
												echo ' nonaktif';
											}
										echo '">';
										$rowinfo_str = '';
										
										foreach($columnHeaders as $header){
											if($header['rowinfo']){
												$rowinfo_str .= $header['header_title'].':'.$row[$header['field_name']];
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
										echo '<a title="rubah data" href="#" class="btnedit spe" rowdata="'.$rowdata.'">edit</a>';
										
										if(isset($nonaktif)){
											foreach($nonaktif as $non){
												if(($row['id']==$non['id'])&&($row['activated']==1)){
													$btns = $rowInfoBtnsNon;
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
		</div>