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
  		       			<div class="contentfooter">
  		       				<?php
				          	foreach($input_list_hidden_add as $iName => $input){
				          		include('input_list_hidden_template.php');
				          	}
				          	
				          	if($add_saveable) echo '<button type="reset" class="ajaxhide">Reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="submitbtn">Save</button>';
				           ?>
  		       			</div>
	         		</form>
         </div>
         <div id="tab-edit" class="toggletab<?php echo ($frmaction=="edit")?'':' invis';?>">
					<div class="contentheader">
						<div class="frite searchbox">
							<a href="#" onclick="chgTab('tab-add');" class="btnadd">Tambah Kategori baru</a> 
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
				          	
				          	if($add_saveable) echo '<button type="reset" class="ajaxhide">Reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="submitbtn">Save</button>';
				           ?>
				      </div>
	          </form>
         </div>
		    </div>
		<div class="content contentright">
			<form action="<?php echo base_url();?>iata_assign/filter" method="post" class="inputfilterspan">
					<div class="toolbox">
						<span class="emptywrap filterwrap"><input id="filter" class="emptiable" type="text" name="filter" value="<?php echo $keyword;?>" title="filter keyword"/></span>
					</div>
					<?php
					if($rowcount < 1){
						echo '<div class="nodata acenter">Data kosong</div>';
					}
					else{
					?>
					<table class="headertable">
						<tbody>
						<tr>
							<th width="40%">Nama</th>
							<th width="60%">IATA</th>
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
								  
								  $jsonData = array();;
								  foreach($currow as $field => $value){
				                    $jsonData[$field] = htmlentities($value);
				                  }
				                  $rowdata = str_replace('"','\'', json_encode($jsonData));  
								  
								
									//$currow['ket'] = character_limiter(strip_tags($currow['ket']), 60);
																		
									echo'<div class="row">'.
											'<div style="width:40%"><span class="rowhead">'.$currow['nama'].'</span></div>'.
											'<div style="width:58%">'.$currow['iatacode'].' - '.$currow['nama_bandara'].'</div>'.
											'<h2 class="rowinfo">'.
												'<a title="edit '.$currow['nama'].'" id="'.$currow['iduser'].'" href="#" class="btnedit spe" rowdata="'.$rowdata.'">edit</a>'.
												'<a title="hapus '.$currow['nama'].'" href="'.base_url().'iata_assign/delete/'.$currow['iduser'].'" rel="'.base_url().'iata_assign/delete/'.$currow['iduser'].'" class="btndel">delete</a>'.
											'</h2>'.
										'</div>';
                    
                  
                  
								}
								?>
								
						</div>
						</div>					
					</div>
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
		</div>