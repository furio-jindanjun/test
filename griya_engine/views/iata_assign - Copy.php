		<div id="contentframe">
			<div class="content contentleft">
				<div id="tab-add" class="toggletab<?php echo ($frmaction=="add")?'':' invis';?>">
			  		<div class="contentheader">
						<h2 class="boxtitle fleft"><?php echo $addtitle;?></h2>
				  	</div>
				  	<form id="frmadd" action="<?php echo base_url().$url_add;?>" method="post" class="formeditor aw">
  						<ul class="leftcol fullwidth formul">
  							<li>
  								<label for="crewsearch">Nama</label>
  								<div class="errorwrap<?php echo (isset($errors['addiduser'])?' iserror areaerror':''); ?>">
  							   		<input type="text" id="crewsearch" name="crewsearch" class="keywordsearch" value="<?php if($addnama){echo $addnama;}else{echo $defaultnamacrew;}?>"/>
  							   		<?php echo (isset($errors['addiduser'])?'<br/><span class="errornote">'.$errors['addiduser'].'</span>':''); ?>
  							   		<input type="hidden" id="addnama" name="addnama" value="<?php echo $addnama;?>"/>
  							   		<input type="hidden" id="addiduser" name="addiduser" value="<?php echo $addiduser;?>"/>
  								</div>
  							</li>
  							<li>
  								<label for="addiatacode">IATA</label>
  								<div class="errorwrap<?php echo (isset($errors['addiata'])?' iserror areaerror':''); ?>">
	  							   <select id="addiatacode" name="addiatacode">
	  							   		<?php
	  							   		foreach($rsIata as $iata){
	  							   			echo '<option value="'.$iata["iata_code"].'">'.$iata["iata_code"].' - '.$iata["nama_bandara"].'</option>';
	  							   		} 
	  							   		?>
	  							   </select>
	  							   <?php 
	  							     if(isset($errors['addiata'])){
	  							       echo '<span class="errornote">'.$errors['addiata'].'</span>';
	  							     } 
	  							   ?>
  								</div>
  								<br/><br/><hr/>
  							</li>
  		       			</ul>
  		       			<div class="contentfooter">
  		        			<button type="reset">Reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">Save</button>
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
    						<li>
    							<label for="editnama">Nama</label>
    							<div class="errorwrap<?php echo (isset($errors['editnama'])?' iserror areaerror':''); ?>">
    							   <input type="text" id="editnama" class="editnama" name="editnama" value="<?php echo $editnama;?>" disabled="disabled"/>
    							   <?php echo (isset($errors['editnama'])?'<span class="errornote">'.$errors['editnama'].'</span>':''); ?>
    							</div>
    						</li>
    						<li>
    							<label for="editiatacode">IATA</label>
    							<div class="errorwrap<?php echo (isset($errors['"editiatacode"'])?' iserror areaerror':''); ?>">
	  							   <select id="editiatacode" name="editiatacode" class="editiatacode">
	  							   		<?php
	  							   		foreach($rsIata as $iata){
	  							   			echo '<option value="'.$iata["iata_code"].'">'.$iata["iata_code"].' - '.$iata["nama_bandara"].'</option>';
	  							   		} 
	  							   		?>
	  							   </select>
	  							   <?php 
	  							     if(isset($errors['"editiata"'])){
	  							       echo '<span class="errornote">'.$errors['"editiatacode"'].'</span>';
	  							     } 
	  							   ?>
	  							</div>
    							<br/><br/><hr/>
    						</li>
		          		</ul>
				    	<div class="contentfooter">
				        	<input type="hidden" id="editiduser" name="editiduser" class="editiduser" value="<?php echo $editiduser;?>"/>
				            <button type="reset">Reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">Save</button>
				        </div>
	          		</form>
          		</div>
		    </div>
		<div class="content contentright">
			<form action="<?php echo base_url();?>iata_assign/filter" method="post">
					<div class="contentheader">
						<div class="frite searchbox">
								<div class="toolbox">
  									<span class="emptywrap"><input id="filter" class="emptiable" type="text" name="filter" value="<?php echo $keyword;?>"/></span>
								</div>
						</div>
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
												'<a title="hapus '.$currow['nama'].'" href="#" rel="'.base_url().'iata_assign/delete/'.$currow['iduser'].'" class="btndel">delete</a>'.
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
				<form id="frmpaging" class="contentfooter arite aw" method="get" action="<?php echo base_url().'iata_assign/browse/';?>">
					<div class="fleft pagingbox"><a href="<?php echo base_url().'iata_assign/browse/1';?>" id="pageup" class="aw">|&lt;</a>&nbsp;<a class="aw" href="<?php echo base_url().'iata_assign/browse/'.($curpage-1);?>">&lt;&lt;</a>
						&nbsp;&nbsp;hal. ke <input type="text" name="page" value="<?php echo $curpage;?>" size="2"/> dari <?php echo $maxpage;?> hal.&nbsp;&nbsp;
						<a class="aw" href="<?php echo base_url().'iata_assign/browse/'.($curpage+1);?>">&gt;&gt;</a>&nbsp;<a class="aw" href="<?php echo base_url().'iata_assign/browse/'.$maxpage;?>" >&gt;|</a>
					</div>
					<span class="bezeled">menampilkan <b><?php echo $startcount .'</b> s/d <b>'. $itemcount .'</b> dari <b>'.$rowcount;?></b> data.</span>
				</form>
				<?php
				}
				?>
		</div>
		</div>