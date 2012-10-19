<div id="contentframe">
		  <div class="content">
             <div class="contentframe">
                 <div class="contentheader">
                      <div class="frite searchbox">
                          <a href="<?php echo base_url().'manufacture/new_';?>" class="aw btnadd" title="Add New">Tambah baru</a>
                      </div>
                      <div class="toolbox">
                        <h2 class="boxtitle fleft">MASTER BARANG</h2><br/>
                      </div>
                 </div><!-->contentheader</-->
                 <form action="<?php echo base_url();?>manufacture/filter" method="post" class="inputfilterspan">
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
                          <th width="19%"><span class="rowhead">Name</span></th>
                          <th width="17%">Address 1</th>
                          <th width="17%">Address 2</th>
                          <th width="17%">Address 3</th>
                          <th width="7%" class="acenter">Phone</th>
                          <th width="7%" class="acenter">Fax</th>
                          <th width="8%" class="acenter">YM</th>
                          <th width="8%" class="acenter">Skype</th>
                        </tr>
                      </tbody>
                    </table>
                    <div class="leftcol fullwidth withheader">
                      <div class="tableframe">
                        <div id="scrollme" class="scrolledtable">
                            <?php
                                
                                foreach($allrows as $currow){
                            
                                  echo'<div class="row">'.
                                      '<div style="width: 19%;"><span class="rowhead">'.$currow['namamanufaktur'].'</span></div>'.
                                      '<div style="width: 17%;">'.$currow['alamatresmi'].'</div>'.
                                      '<div style="width: 17%;">'.$currow['alamatperbaikan'].'&nbsp;</div>'.
                                      '<div style="width: 17%;">'.$currow['alamat'].'&nbsp;</div>'.
                                      '<div style="width: 7%;text-align:center">'.$currow['tlp'].'</div>'.
                                      '<div style="width: 7%;text-align:center">'.$currow['fax'].'&nbsp;</div>'.
                                      '<div style="width: 8%;text-align:center">'.$currow['ym'].'&nbsp;</div>'.
                                      '<div style="width: 8%;text-align:center">'.$currow['skype'].'</div>'.
                                      '<h2 class="rowinfo">'.
                                        '<a title="edit '.$currow['namamanufaktur'].'" id="'.$currow['idmanufaktur'].'" href="'.base_url().'manufacture/edit/'.$currow['idmanufaktur'].'" class="btnedit spe">edit</a>'.
                                        '<a title="hapus '.$currow['namamanufaktur'].'" href="#" rel="'.base_url().'manufacture/delete/'.$currow['idmanufaktur'].'" class="btndel">delete</a>'.
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
                      <div class="fleft pagingbox"><a class="" id="pageup" href="<?php echo base_url().'manufacture/browse/1';?>">|&lt;</a>&nbsp;<a href="<?php echo base_url().'manufacture/browse/0';?>">&lt;&lt;</a>
                        &nbsp;&nbsp;page <input type="text" size="2" value="<?php echo $curpage;?>" name="pagedest"> from <?php echo $maxpage;?> page(s).&nbsp;&nbsp;
                        <a href="<?php echo base_url().'manufacture/browse/'.($curpage+1);?>">&gt;&gt;</a>&nbsp;<a href="<?php echo base_url().'manufacture/browse/'.$maxpage;?>">&gt;|</a>
                      </div>
                      <span class="bezeled">showing <?php echo $startcount .'</b> to <b>'. $itemcount .'</b> from <b>'.$rowcount;?> data.</span>
                  </form>
                  <?php
                    }
                  ?>
             </div><!-->class contentframe</-->
      </div><!-->content</-->
</div>