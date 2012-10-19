<div id="contentframe">
		  <div class="content side">
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
            <form id="frmedit" action="<?php echo base_url().$url_edit;?>" method="post" class="formeditor aw">
              <ul class="leftcol fullwidth formul">
                <?php
               // log_message('error','flash: '.var_export($input_list_edit,true));
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
         </div><!--tabedit--></!--tabedit-->
          </div>
          <div class="content contentright">
             <div class="contentframe">
                <form action="<?php echo base_url();?>statusitem/filter" method="post" class="inputfilterspan">
                    <span class="emptywrap">
                    	<span class="filterwrap">
	                    	<input id="filter" class="emptiable" name="filter" type="text" value="<?php echo(isset($keyword)?$keyword:'keyword filter')?>" onblur="if(this.value ==''){this.value = 'keyword filter';}" onfocus="if(this.value=='keyword filter'){this.value = '';}"/>
	                    	<span style="visibility: hidden;" class="emptybtn">x</span>
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
                          <th width="25%"><span class="rowhead">Employee Name</span></th>
                          <th width="20%">Item</th>
                          <th width="15%">S/N</th>
                          <th width="20%">Given Date</th>
                          <th width="20%">Revoke Date</th>
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
                                      $this->load->helper('date');
                                      if(($field == 'tgltarik')||($field == 'tglberi')){
                                        if(strtotime($value) == 0)
                                        {
                                          $value = '';
                                        }else{
                                          $value = mdate('%d-%M-%Y',strtotime($value));
                                        }
                                      }
                                      
                                      //log_message('error', '$field:'.$field.' $value:'.$value);
                                      $jsonData[$field] = htmlentities($value);
                                  }
                                  $rowdata = str_replace('"','\'', json_encode($jsonData));  
                                  $tgltarik = date('d-M-Y',strtotime($currow['tgltarik']));
                                  if(strtotime($currow['tgltarik']) == 0)
                                    {
                                      $tgltarik = '-';
                                    }
                                  
                                  echo'<div class="row">'.
                                      '<div style="width:25%"><span class="rowhead">'.$currow['nama'].'</span></div>'.
                                      '<div style="width:20%">'.$currow['namabarang'].'</div>'.
                                      '<div style="width:15%">'.$currow['serialnum'].'</div>'.
                                      '<div style="width:20%">'.date('d-M-Y',strtotime($currow['tglberi'])).'</div>'.
                                      '<div style="width:20%">'.$tgltarik.'</div>'.
                                      
                                      '<h2 class="rowinfo">'.
                                        '<div>Remark : '.$currow['stat'].'</div><br/>'.
                                        '<a title="edit '.$currow['namabarang'].'" id="'.$currow['idstatasset'].'" href="#" class="btnedit spe" rowdata="'.$rowdata.'">edit</a>'.
                                        '<a title="hapus '.$currow['namabarang'].'" href="'.base_url().'statusitem/delete/'.$currow['idstatasset'].'" rel="'.base_url().'statusitem/delete/'.$currow['idstatasset'].'" class="btndel">delete</a>'.
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