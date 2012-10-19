<div id="contentframe">
		  <div class="content side">
         <div class="content contentleft">
            <div class="toggletab<?php echo ($frmaction=="add")?'':' invis';?>" id="tab-add">
                <div class="contentheader">
                    <h2 class="boxtitle fleft"><?php echo $addtitle;?> Division</h2>
                </div>
                <form class="formeditor aw" method="post" action="<?php echo base_url()."division/save_add";?>">
                   <ul class="leftcol fullwidth formul">
                      <li>
                         <label>Name</label>
                         <div class="errorwrap<?php echo (isset($errors['add_name'])?' iserror areaerror':''); ?>">
                            <input type="text" id="add_name" name="add_name" value="<?php echo $add_name;?>"/>
                            <?php echo (isset($errors['add_name'])?'<br/><span class="errornote">'.$errors['add_name'].'</span>':''); ?>
                         </div>
                      </li>
                   </ul>
                     <div class="contentfooter buttonbox">
                      <button type="reset" class="resetbutton">reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">save</button>
                   </div>
                 </form>
            </div><!--tabadd--></!--tabadd-->
            <div class="toggletab <?php echo ($frmaction=="edit")?'':' invis';?>" id="tab-edit">
                <div class="contentheader">
                    <div class="frite searchbox">
                      <a href="#" onclick="chgTab('tab-add');" title="Add New Division" class="btnadd">Tambah Kategori baru</a> 
                    </div>
                    <h2 class="boxtitle fleft">[Edit] Division</h2>
                </div>
                <form class="formeditor aw" method="post" action="<?php echo base_url()."division/save_edit";?>">
                  <ul class="leftcol fullwidth formul">
                      <li>
                         <label>Name</label>
                         <div class="errorwrap<?php echo (isset($errors['editnamatipe'])?' iserror areaerror':''); ?>">
                            <input type="text" class="editnamatipe" id="editnamatipe" name="editnamatipe" value="<?php echo $editnamatipe;?>"/>
                            <?php echo (isset($errors['editnamatipe'])?'<br/><span class="errornote">'.$errors['editnamatipe'].'</span>':''); ?>
                         </div>
                      </li>
                  </ul>
                  <div class="contentfooter buttonbox">
                      <input type="hidden" value="<?php echo $editidtipe;?>" class="editidtipe" name="editidtipe" id="editidtipe">
                      <button type="reset" class="resetbutton">reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">save</button>
                  </div> 
                </form>
            </div><!--tabedit--></!--tabedit-->
          </div>
          <div class="content contentright">
             <div class="contentframe">
                <form action="<?php echo base_url();?>division/filter" method="post" class="inputfilterspan">
                    <span class="emptywrap filterwrap">
                    	<input id="filter" class="emptiable" name="filter" type="text" value="<?php echo(isset($keyword)?$keyword:'')?>" title="filter keyword"/>
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
                          <th width="40%"><span class="rowhead">Name</span></th>
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
                                  
                                  echo'<div class="row">'.
                                      '<div style="width:100%"><span class="rowhead">'.$currow['namatipe'].'</span></div>'.
                                      '<h2 class="rowinfo">'.
                                        '<a title="edit '.$currow['namatipe'].'" id="'.$currow['idtipe'].'" href="#" class="btnedit spe" rowdata="'.$rowdata.'">edit</a>'.
                                        '<a title="hapus '.$currow['namatipe'].'" href="#" rel="'.base_url().'division/delete/'.$currow['idtipe'].'" class="btndel">delete</a>'.
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