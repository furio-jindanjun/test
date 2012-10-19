<div id="contentframe">
		  <div class="content side">
         <div class="content contentleft">
            <div class="toggletab<?php echo ($frmaction=="add")?'':' invis';?>" id="tab-add">
                <div class="contentheader">
                    <h2 class="boxtitle fleft"><?php echo $addtitle;?> Status Item</h2>
                </div>
                <form id="frm_prod_add" class="formeditor aw" method="post" action="<?php echo base_url()."statusitem/save_add";?>">
                   <ul class="leftcol fullwidth formul">
                      <li>
                         <label>Employee Name</label>
                         <div class="errorwrap<?php echo (isset($errors['idbranch'])?' iserror areaerror':''); ?>">
                            <input type="text" id="src_nama" name="src_nama" value="<?php echo(isset($tmp_nama)?$tmp_nama:'Search here');?>" onblur="if(this.value ==''){this.value = 'Search here';}" onfocus="if(this.value=='Search here'){this.value = '';}"/>
                            <input type="hidden" id="idbranch" name="idbranch" value="<?php echo $idbranch;?>"/>
                            <?php echo (isset($errors['idbranch'])?'<br/><span class="errornote">'.$errors['idbranch'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Branch Location</label>
                         <div class="errorwrap<?php echo (isset($errors['iata_code'])?' iserror areaerror':''); ?>">
                            <input disabled="true" class="long" type="text" id="iata_code" name="iata_code" value="<?php echo $iata_code;?>"/>
                            <input type="hidden" id="hiddeniata_code" name="hiddeniata_code" value="<?php echo $iata_code;?>"/>
                            <input disabled="true" class="src" type="text" id="nama_bandara" name="nama_bandara" value="<?php echo $nama_bandara;?>"/>
                            <input type="hidden" id="hiddennama_bandara" name="hiddennama_bandara" value="<?php echo $nama_bandara;?>"/>
                            <?php echo (isset($errors['iata_code'])?'<br/><span class="errornote">'.$errors['idbranch'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Item</label>
                         <div class="errorwrap<?php echo (isset($errors['idbarang'])?' iserror areaerror':''); ?>">
                            <input type="text" id="src_namabarang" name="src_namabarang" value="<?php echo(isset($tmp_namabarang)?$tmp_namabarang:'Search here');?>" onblur="if(this.value ==''){this.value = 'Search here';}" onfocus="if(this.value=='Search here'){this.value = '';}"/>
                            <input type="hidden" id="idbarang" name="idbarang" value="<?php echo $idbarang;?>"/>
                            <?php echo (isset($errors['idbarang'])?'<br/><span class="errornote">'.$errors['idbarang'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Given Date</label>
                         <div class="errorwrap<?php echo (isset($errors['tglberi'])?' iserror areaerror':''); ?>">
                            <input class="button fatacal acenter" type="text" id="tglberi" name="tglberi"  value="<?php echo(isset($tglberi)?$tglberi:date('d-M-Y'));?>"/>
                            <?php echo (isset($errors['tglberi'])?'<br/><span class="errornote">'.$errors['tglberi'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Revoke Date</label>
                         <div class="errorwrap<?php echo (isset($errors['tgltarik'])?' iserror areaerror':''); ?>">
                            <input class="button fatacal acenter" type="text" id="tgltarik" name="tgltarik"  value="<?php echo(isset($tgltarik)?$tgltarik:'');?>"/>
                            <?php echo (isset($errors['tgltarik'])?'<br/><span class="errornote">'.$errors['tgltarik'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Status</label>
                         <div class="errorwrap<?php echo (isset($errors['status'])?' iserror areaerror':''); ?>">
                            <textarea id="status" name="status"><?php echo $status;?></textarea>
                            <?php echo (isset($errors['status'])?'<br/><span class="errornote">'.$errors['status'].'</span>':''); ?>
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
                      <a href="#" onclick="chgTab('tab-add');" title="Add New statusitem" class="btnadd">Tambah Kategori baru</a> 
                    </div>
                    <h2 class="boxtitle fleft">&#91;Edit&#93; Status Item</h2>
                </div>
                <form id="frm_prod_edit" class="formeditor aw" method="post" action="<?php echo base_url()."statusitem/save_edit";?>">
                  <ul class="leftcol fullwidth formul">
                      <li>
                         <label>Employee Name</label>
                         <div class="errorwrap<?php echo (isset($errors['editidbranch'])?' iserror areaerror':''); ?>">
                            <input class="editnama" type="text" id="editsrc_nama" name="editsrc_nama" value="<?php echo $edittmp_nama;?>"/>
                            <input type="hidden" class="editidbranch" id="editidbranch" name="editidbranch" value="<?php echo $editidbranch;?>"/>
                            <?php echo (isset($errors['editidbranch'])?'<br/><span class="errornote">'.$errors['editidbranch'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Branch Location</label>
                         <div class="errorwrap<?php echo (isset($errors['editiata_code'])?' iserror areaerror':''); ?>">
                            <input disabled="true" class="long editiata_code" type="text" id="editiata_code" name="editiata_code" value="<?php echo $editiata_code;?>"/>
                            <input class="editiata_code" type="hidden" id="hiddeneditiata_code" name="hiddeneditiata_code" value="<?php echo $editiata_code;?>"/>
                            <input disabled="true" class="src editnama_bandara" type="text" id="editnama_bandara" name="editnama_bandara" value="<?php echo $editnama_bandara;?>"/>
                            <input class="editnama_bandara" type="hidden" id="hiddeneditnama_bandara" name="hiddeneditnama_bandara" value="<?php echo $editnama_bandara;?>"/>
                            <?php echo (isset($errors['editiata_code'])?'<br/><span class="errornote">'.$errors['editiata_code'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Item</label>
                         <div class="errorwrap<?php echo (isset($errors['editidbarang'])?' iserror areaerror':''); ?>">
                            <input class="editnamabarang" type="text" id="editsrc_namabarang" name="editsrc_namabarang" value="<?php echo $edittmp_namabarang;?>"/>
                            <input type="hidden" class="editidbarang" id="editidbarang" name="editidbarang" value="<?php echo $editidbarang;?>"/>
                            <?php echo (isset($errors['editidbarang'])?'<br/><span class="errornote">'.$errors['editidbarang'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Given Date</label>
                         <div class="errorwrap<?php echo (isset($errors['edittglberi'])?' iserror areaerror':''); ?>">
                            <input class="button fatacal acenter edittglberi" type="text" id="edittglberi" name="edittglberi"  value="<?php echo(isset($edittglberi)?$edittglberi:date('d-M-Y'));?>"/>
                            <?php echo (isset($errors['edittglberi'])?'<br/><span class="errornote">'.$errors['edittglberi'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Revoke Date</label>
                         <div class="errorwrap<?php echo (isset($errors['edittgltarik'])?' iserror areaerror':''); ?>">
                            <?php
                              if(strtotime($edittgltarik) == strtotime('0000-00-00')){
                                $edittgltarik = NULL;echo $edittgltarik.'-'.strtotime('0000-00-00');
                              }
                            ?>
                            <input class="button fatacal acenter edittgltarik" type="text" id="edittgltarik" name="edittgltarik"  value="<?php echo(isset($edittgltarik)?$edittgltarik:'');?>"/>
                            <?php echo (isset($errors['edittgltarik'])?'<br/><span class="errornote">'.$errors['edittgltarik'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Status</label>
                         <div class="errorwrap<?php echo (isset($errors['editstatus'])?' iserror areaerror':''); ?>">
                            <textarea class="editstat" id="editstatus" name="editstatus"><?php echo $editstatus;?></textarea>
                            <?php echo (isset($errors['editstatus'])?'<br/><span class="errornote">'.$errors['editstatus'].'</span>':''); ?>
                         </div>
                      </li>
                  </ul>
                  <div class="contentfooter buttonbox">
                      <input type="hidden" value="<?php echo $idstatasset;?>" class="editidstatasset" name="idstatasset" id="idstatasset">
                      <button type="reset" class="resetbutton">reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">save</button>
                  </div> 
                </form>
            </div><!--tabedit--></!--tabedit-->
          </div>
          <div class="content contentright">
             <div class="contentframe">
                <form action="<?php echo base_url();?>statusitem/filter" method="post" class="inputfilterspan">
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
                          <th width="20%"><span class="rowhead">Employee Name</span></th>
                          <th width="20%">Item</th>
                          <th width="20%">Given Date</th>
                          <th width="20%">Revoke Date</th>
                          <th width="20%">Status</th>
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
                                      '<div style="width:20%"><span class="rowhead">'.$currow['nama'].'</span></div>'.
                                      '<div style="width:20%">'.$currow['namabarang'].'</div>'.
                                      '<div style="width:20%">'.date('d-M-Y',strtotime($currow['tglberi'])).'</div>'.
                                      '<div style="width:20%">'.date('d-M-Y',strtotime($currow['tgltarik'])).'</div>'.
                                      '<div style="width:20%">'.$currow['stat'].'</div>'.
                                      '<h2 class="rowinfo">'.
                                        '<a title="edit '.$currow['namabarang'].'" id="'.$currow['idstatasset'].'" href="#" class="btnedit spe" rowdata="'.$rowdata.'">edit</a>'.
                                        '<a title="hapus '.$currow['namabarang'].'" href="#" rel="'.base_url().'statusitem/delete/'.$currow['idstatasset'].'" class="btndel">delete</a>'.
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
                  <div class="fleft pagingbox"><a class="" id="pageup" href="<?php echo base_url().'statusitem/browse/1';?>">|&lt;</a>&nbsp;<a href="<?php echo base_url().'statusitem/browse/0';?>">&lt;&lt;</a>
                    &nbsp;&nbsp;page <input type="text" size="2" value="<?php echo $curpage;?>" name="pagedest"> from <?php echo $maxpage;?> page(s).&nbsp;&nbsp;
                    <a href="<?php echo base_url().'statusitem/browse/'.($curpage+1);?>">&gt;&gt;</a>&nbsp;<a href="<?php echo base_url().'statusitem/browse/'.$maxpage;?>">&gt;|</a>
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