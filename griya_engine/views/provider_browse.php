<div id="contentframe">
		  <div class="content">
             <div class="contentframe">
                 <div class="contentheader">
                      <div class="frite searchbox">
                          <a href="<?php echo base_url().'provider/new_';?>" class="aw btnadd" title="Add New">Tambah baru</a>
                      </div>
                      <div class="toolbox">
                        <h2 class="boxtitle fleft">PROVIDER</h2><br/>
                      </div>
                 </div><!-->contentheader</-->
                 <form action="<?php echo base_url();?>provider/filter" method="post" class="inputfilterspan">
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
                          <th width="15%"><span class="rowhead">Name</span></th>
                          <th width="15%">Acc. Manager</th>
                          <th width="10%">Email 1</th>
                          <th width="10%">Email 2</th>
                          <th width="7%" class="acenter">Phone</th>
                          <th width="7%" class="acenter">Office Phone</th>
                          <th width="8%" class="acenter">Cellphone</th>
                          <th width="8%" class="acenter">Fax</th>
                          <th width="5%" class="acenter">YM</th>
                          <th width="5%" class="acenter">Skype</th>
                          <th width="10%">Address</th>
                        </tr>
                      </tbody>
                    </table>
                    <div class="leftcol fullwidth withheader">
                      <div class="tableframe">
                        <div id="scrollme" class="scrolledtable">
                            <?php
                                
                                foreach($allrows as $currow){
                            
                                  echo'<div class="row">'.
                                      '<div style="width: 15%;"><span class="rowhead">'.$currow['namaprovider'].'</span></div>'.
                                      '<div style="width: 15%;">'.$currow['accountmanager'].'&nbsp;</div>'.
                                      '<div style="width: 10%;">'.$currow['email1'].'&nbsp;</div>'.
                                      '<div style="width: 10%;">'.$currow['email2'].'&nbsp;</div>'.
                                      '<div style="width: 7%;text-align:center">'.$currow['tlplokal'].'&nbsp;</div>'.
                                      '<div style="width: 7%;text-align:center">'.$currow['tlpkantor'].'&nbsp;</div>'.
                                      '<div style="width: 8%;text-align:center">'.$currow['hp'].'&nbsp;</div>'.
                                      '<div style="width: 8%;text-align:center">'.$currow['fax'].'&nbsp;</div>'.
                                      '<div style="width: 5%;text-align:center">'.$currow['ym'].'&nbsp;</div>'.
                                      '<div style="width: 5%;text-align:center">'.$currow['skype'].'&nbsp;</div>'.
                                      '<div style="width: 10%;text-align:center">'.$currow['alamat'].'</div>'.
                                      '<h2 class="rowinfo">'.
                                        '<a title="edit '.$currow['namaprovider'].'" id="'.$currow['idprovider'].'" href="'.base_url().'provider/edit/'.$currow['idprovider'].'" class="btnedit spe">edit</a>'.
                                        '<a title="hapus '.$currow['namaprovider'].'" href="#" rel="'.base_url().'provider/delete/'.$currow['idprovider'].'" class="btndel">delete</a>'.
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
                      <div class="fleft pagingbox"><a class="" id="pageup" href="<?php echo base_url().'provider/browse/1';?>">|&lt;</a>&nbsp;<a href="<?php echo base_url().'provider/browse/0';?>">&lt;&lt;</a>
                        &nbsp;&nbsp;page <input type="text" size="2" value="<?php echo $curpage;?>" name="pagedest"> from <?php echo $maxpage;?> page(s).&nbsp;&nbsp;
                        <a href="<?php echo base_url().'provider/browse/'.($curpage+1);?>">&gt;&gt;</a>&nbsp;<a href="<?php echo base_url().'provider/browse/'.$maxpage;?>">&gt;|</a>
                      </div>
                      <span class="bezeled">showing <?php echo $startcount .'</b> to <b>'. $itemcount .'</b> from <b>'.$rowcount;?> data.</span>
                  </form>
                  <?php
                    }
                  ?>
             </div><!-->class contentframe</-->
      </div><!-->content</-->
</div>