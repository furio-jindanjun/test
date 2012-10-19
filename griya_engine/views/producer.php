<div id="contentframe">
		  <div class="content side">
         <div class="content contentleft">
            <div class="toggletab<?php echo ($frmaction=="add")?'':' invis';?>" id="tab-add">
                <div class="contentheader">
                    <h2 class="boxtitle fleft"><?php echo $addtitle;?> Producer</h2>
                </div>
                <form id="frm_prod_add" class="formeditor aw" method="post" action="<?php echo base_url()."producer/save_add";?>">
                   <ul class="leftcol fullwidth formul">
                      <li>
                         <label>Provider</label>
                         <div class="errorwrap<?php echo (isset($errors['src_provider'])?' iserror areaerror':''); ?>">
                            <input disabled="true" class="src" type="text" id="tmp_nameprovider" name="tmp_nameprovider" value="<?php echo $tmp_nameprovider;?>"/>
                            <input class="long" type="text" id="add_idprovider" name="add_idprovider" value="<?php echo $tmp_nameprovider;?>"/>
                            <input type="hidden" id="src_provider" name="src_provider" value="<?php echo $src_provider;?>"/>
                            <?php echo (isset($errors['src_provider'])?'<br/><span class="errornote">'.$errors['src_provider'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Manufacture</label>
                         <div class="errorwrap<?php echo (isset($errors['idmanufaktur'])?' iserror areaerror':''); ?>">
                            <input disabled="true" class="src" type="text" id="tmp_namemanu" name="tmp_namemanu" value="<?php echo $tmp_namemanu;?>"/>
                            <input class="long" type="text" id="src_namemanu" name="src_namemanu" value="<?php echo $tmp_namemanu;?>"/>
                            <input type="hidden" id="idmanufaktur" name="idmanufaktur" value="<?php echo $idmanufaktur;?>"/>
                            <?php echo (isset($errors['idmanufaktur'])?'<br/><span class="errornote">'.$errors['idmanufaktur'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Category</label>
                         <div class="errorwrap<?php echo (isset($errors['idkategori'])?' iserror areaerror':''); ?>">
                            <input disabled="true" class="src" type="text" id="tmp_namekategori" name="tmp_namekategori" value="<?php echo $tmp_namekategori;?>"/>
                            <input class="long" type="text" id="src_namekategori" name="src_namekategori" value="<?php echo $tmp_kodekategori;?>"/>
                            <input type="hidden" id="idkategori" name="idkategori" value="<?php echo $idkategori;?>"/>
                            <?php echo (isset($errors['idkategori'])?'<br/><span class="errornote">'.$errors['idkategori'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Code</label>
                         <div class="errorwrap<?php echo (isset($errors['add_kodeprodusen'])?' iserror areaerror':''); ?>">
                            <input type="text" class="add_kodeprodusen" id="add_kodeprodusen" name="add_kodeprodusen" value="<?php echo $add_kodeprodusen;?>"/>
                            <?php echo (isset($errors['add_kodeprodusen'])?'<br/><span class="errornote">'.$errors['add_kodeprodusen'].'</span>':''); ?>
                         </div>
                      </li>
                   </ul>
                     <div class="contentfooter buttonbox">
                      <input type="hidden" id="temp_namaprovider" name="temp_namaprovider" value="<?php echo $tmp_nameprovider;?>"/>
                      <input type="hidden" id="temp_namakategori" name="temp_namakategori" value="<?php echo $tmp_namekategori;?>"/>
                      <input type="hidden" id="temp_kodekategori" name="temp_kodekategori" value="<?php echo $tmp_kodekategori;?>"/>
                      <input type="hidden" id="temp_namamanufaktur" name="temp_namamanufaktur" value="<?php echo $tmp_namemanu;?>"/>
                      <button type="reset" class="resetbutton">reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">save</button>
                   </div>
                 </form>
            </div><!--tabadd--></!--tabadd-->
            <div class="toggletab <?php echo ($frmaction=="edit")?'':' invis';?>" id="tab-edit">
                <div class="contentheader">
                    <div class="frite searchbox">
                      <a href="#" onclick="chgTab('tab-add');" title="Add New Producer" class="btnadd">Tambah Kategori baru</a> 
                    </div>
                    <h2 class="boxtitle fleft">[Edit] Producer</h2>
                </div>
                <form id="frm_prod_edit" class="formeditor aw" method="post" action="<?php echo base_url()."producer/save_edit";?>">
                  <ul class="leftcol fullwidth formul">
                      <li>
                         <label>Provider</label>
                         <div class="errorwrap<?php echo (isset($errors['editidprovider'])?' iserror areaerror':''); ?>">
                            <input disabled="true" class="src editnamaprovider" type="text" id="editnamaprovider" name="editnamaprovider" value="<?php echo $editnamaprovider;?>"/>
                            <input class="long editnamaprovider" type="text" id="src_namaprovider" name="src_namaprovider" value="<?php echo $editnamaprovider;?>"/>
                            <input type="hidden" class="editidprovider" id="editidprovider" name="editidprovider" value="<?php echo $editidprovider;?>"/>
                            <?php echo (isset($errors['editidprovider'])?'<br/><span class="errornote">'.$errors['editidprovider'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Manufacture</label>
                         <div class="errorwrap<?php echo (isset($errors['editidmanufaktur'])?' iserror areaerror':''); ?>">
                            <input disabled="true" class="src editnamamanufaktur" type="text" id="editnamamanufaktur" name="editnamamanufaktur" value="<?php echo $editnamamanufaktur;?>"/>
                            <input class="long editnamamanufaktur" type="text" id="src_namamanufaktur" name="src_namamanufaktur" value="<?php echo $editnamamanufaktur;?>"/>
                            <input type="hidden" class="editidmanufaktur" id="editidmanufaktur" name="editidmanufaktur" value="<?php echo $editidmanufaktur;?>"/>
                            <?php echo (isset($errors['editidmanufaktur'])?'<br/><span class="errornote">'.$errors['editidmanufaktur'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Category</label>
                         <div class="errorwrap<?php echo (isset($errors['editidkategori'])?' iserror areaerror':''); ?>">
                            <input disabled="true" class="src editnamakategori" type="text" id="editnamakategori" name="editnamakategori" value="<?php echo $editnamakategori;?>"/>
                            <input class="long editnamakategori" type="text" id="src_namakategori" name="src_namakategori" value="<?php echo $editnamakategori;?>"/>
                            <input type="hidden" class="editidkategori" id="editidkategori" name="editidkategori" value="<?php echo $editidkategori;?>"/>
                            <?php echo (isset($errors['idkategori'])?'<br/><span class="errornote">'.$errors['editidkategori'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Code</label>
                         <div class="errorwrap<?php echo (isset($errors['editkodeprodusen'])?' iserror areaerror':''); ?>">
                            <input type="text" class="editkodeprodusen" id="editkodeprodusen" name="editkodeprodusen" value="<?php echo $editkodeprodusen;?>"/>
                            <?php echo (isset($errors['editkodeprodusen'])?'<br/><span class="errornote">'.$errors['editkodeprodusen'].'</span>':''); ?>
                         </div>
                      </li>
                  </ul>
                  <div class="contentfooter buttonbox">
                      <input type="hidden" class="editnamaprovider" name="temp_namaprovider"/>
                      <input type="hidden" class="editnamakategori" name="temp_namakategori"/>
                      <input type="hidden" class="editnamamanufaktur" name="temp_namamanufaktur"/>
                      <input type="hidden" value="<?php echo $editidprodusen;?>" class="editidprodusen" name="editidprodusen" id="editidprodusen">
                      <button type="reset" class="resetbutton">reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">save</button>
                  </div> 
                </form>
            </div><!--tabedit--></!--tabedit-->
          </div>
          <div class="content contentright">
             <div class="contentframe">
                <form action="<?php echo base_url();?>producer/filter" method="post" class="inputfilterspan">
                    <span class="emptywrap"><input id="filter" class="emptiable" name="filter" type="text" value="<?php echo(isset($keyword)?$keyword:'keyword filter')?>" onblur="if(this.value ==''){this.value = 'keyword filter';}" onfocus="if(this.value=='keyword filter'){this.value = '';}"><span style="visibility: hidden;" class="emptybtn">x</span></span>
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
                          <th width="25%"><span class="rowhead">Provider</span></th>
                          <th width="25%">Manufacture</th>
                          <th width="25%">Category</th>
                          <th width="25%">Code</th>
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
                                      '<div style="width:25%"><span class="rowhead">'.$currow['namaprovider'].'</span></div>'.
                                      '<div style="width:25%">'.$currow['namamanufaktur'].'</div>'.
                                      '<div style="width:25%">'.$currow['namakategori'].'</div>'.
                                      '<div style="width:25%">'.$currow['kodeprodusen'].'</div>'.
                                      '<h2 class="rowinfo">'.
                                        '<a title="edit '.$currow['kodeprodusen'].'" id="'.$currow['idprodusen'].'" href="#" class="btnedit spe" rowdata="'.$rowdata.'">edit</a>'.
                                        '<a title="hapus '.$currow['kodeprodusen'].'" href="#" rel="'.base_url().'producer/delete/'.$currow['idprodusen'].'" class="btndel">delete</a>'.
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
              <form class="contentfooter arite">
                  <div class="fleft pagingbox"><a class="" id="pageup" href="<?php echo base_url().'producer/browse/1';?>">|&lt;</a>&nbsp;<a href="<?php echo base_url().'producer/browse/0';?>">&lt;&lt;</a>
                    &nbsp;&nbsp;page <input type="text" size="2" value="<?php echo $curpage;?>" name="pagedest"> from <?php echo $maxpage;?> page(s).&nbsp;&nbsp;
                    <a href="<?php echo base_url().'producer/browse/'.($curpage+1);?>">&gt;&gt;</a>&nbsp;<a href="<?php echo base_url().'producer/browse/'.$maxpage;?>">&gt;|</a>
                  </div>
                  <span class="bezeled">showing <?php echo $startcount .'</b> to <b>'. $itemcount .'</b> from <b>'.$rowcount;?> data.</span>
              </form>
              <?php
                }
              ?>
           </div>
         </div><!-->class contentframe</-->
      </div><!-->content</-->
</div>