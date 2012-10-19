	<div id="contentframe">
		<div class="content">
			<div class="contentframe">
				<form id="browseform" action="<?php echo base_url() . $urlFilter;?>" method="post" class="aw">
					<div class="contentheader">
						<div class="frite searchbox">
							<?php
							
							$this->load->helper('text');
							$this->load->helper('date');
              
							if($isAddAble){
								echo '<a href="'.base_url() . $urlNew.'" class="aw btnadd">'.$addTitle.'</a>';
								//echo '<a href="'.base_url() . $urlNew.'" class="aw btnadd">Tambah Baru</a>';
							}
							?>
							
						</div>
						<div class="toolbox">
                  <button type="submit" class="btnwhite hidden">&gt;</button>
            </div>
						<h2 class="boxtitle fleft"><?php echo $pageTitle;?></h2>
					</div>
					<span class="emptywrap">
					  <span class="filterwrap">
					  	<input id="filter" class="emptiable" type="text" name="filter" value="<?php echo(isset($keyword)?$keyword:'')?>" title="keyword filter"/>
					    <?php
					       if(isset($idnyapasien)){
					           echo '<input id="idnyapasien" name="idnyapasien" type="hidden" value="'.$idnyapasien.'"/>';
					       }
					    ?>
					  </span>
					  &nbsp;&nbsp;
					</span>
					<?php
					     if(isset($searchtglmode)){
					  ?>
    					  	<input id="searchmode" type="hidden" value="<?php echo(isset($searchmode)?$searchmode:'off');?>" name="searchmode">
                			<input id="tglsearch_tmp" type="hidden" value="<?php echo $curdate;?>" name="tglsearch_tmp">
                			<div class="datefilterwrap">
	                			<button type="button" rel="off" class="hidden" id="srchswitcher"><?php echo(isset($searchmodetitle)?'defined date':'undefined date');?></button>
	                   			<input class="buttoncal" type="text" name="tglsearch" id="tglsearch" style="width:80px !important" value="<?php echo(isset($curdate)?date('d-M-Y',strtotime($curdate)):date('d-M-Y')); ?>"/>
	                		</div>
             		<?php }?>
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
								if($rowcount < 1){
									echo '<div class="nodata acenter">No Data Found</div>';
								}
								else{
																
									foreach($allrows as $row){
										
										$btns = $rowInfoBtns;
										
										echo'<div class="row">';
										
										$rowinfo_str = '';
										
										foreach($columnHeaders as $header){
											if($header['rowinfo'] && $row[$header['field_name']]){
												$rowinfo_str .= $header['header_title'].' : '.$row[$header['field_name']];
											}else{
												echo '<div style="width:'.$header['width']. (isset($header['bold'])?';font-weight:800':'').'">';
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
										
										
										if ($btns){
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
										//echo '<a href="'.base_url().$urlEdit.$row[$editdelid].'" class="btnedit aw">edit</a><a title="'.$delete_str.$row[$delete_title].'" href="#" rel="'.base_url().$urlDelete.$row[$editdelid].'" class="btndel">delete</a></h2>';
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
						&nbsp;&nbsp;hal # <input type="text" name="page" value="<?php echo $curpage;?>" size="2"/> dari total <?php echo $maxpage;?> hal.&nbsp;&nbsp;
						<a class="aw" href="<?php echo base_url().$url_browse.($curpage+1);?>">&gt;&gt;</a>&nbsp;<a class="aw" href="<?php echo base_url().$url_browse.$maxpage;?>" >&gt;|</a>
					</div>
					<span class="bezeled">menampilkan <b><?php echo $startcount .'</b> s/d <b>'. $itemcount .'</b> dari total <b>'.$rowcount;?></b> data.</span>
				</form>
				<?php
				}
				?>
			</div>
		</div>
	</div>