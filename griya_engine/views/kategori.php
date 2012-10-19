<div class="toggletab<?php echo ($frmaction=="add")?'':' invis';?>" id="tab-add">
                <div class="contentheader">
                    <h2 class="boxtitle fleft"><?php echo $addtitle;?> Manufacture</h2>
                </div>
                <form class="formeditor aw" method="post" action="<?php echo base_url()."manufacture/save_add";?>">
                   <ul class="leftcol fullwidth formul">
                      <li>
                         <label>Name</label>
                         <div class="errorwrap<?php echo (isset($errors['namamanufaktur'])?' iserror areaerror':''); ?>">
                            <input type="text" id="namamanufaktur" name="namamanufaktur" value="<?php echo $namamanufaktur;?>"/>
                            <?php echo (isset($errors['namamanufaktur'])?'<br/><span class="errornote">'.$errors['namamanufaktur'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Phone</label>
                         <div class="errorwrap<?php echo (isset($errors['tlp'])?' iserror areaerror':''); ?>">
                            <input type="text" id="tlp" name="tlp" value="<?php echo $tlp;?>"/>
                            <?php echo (isset($errors['tlp'])?'<br/><span class="errornote">'.$errors['tlp'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Fax</label>
                         <div class="errorwrap<?php echo (isset($errors['fax'])?' iserror areaerror':''); ?>">
                            <input type="text" id="fax" name="fax" value="<?php echo $fax;?>"/>
                            <?php echo (isset($errors['fax'])?'<br/><span class="errornote">'.$errors['fax'].'</span>':''); ?>
                         </div>
                      </li> 
                      <li>
                         <label>YM</label>
                         <div class="errorwrap<?php echo (isset($errors['ym'])?' iserror areaerror':''); ?>">
                            <input type="text" id="ym" name="ym" value="<?php echo $ym;?>"/>
                            <?php echo (isset($errors['ym'])?'<br/><span class="errornote">'.$errors['ym'].'</span>':''); ?>
                         </div>
                      </li>
                      <li>
                         <label>Skype</label>
                         <div class="errorwrap<?php echo (isset($errors['skype'])?' iserror areaerror':''); ?>">
                            <input type="text" id="skype" name="skype" value="<?php echo $skype;?>"/>
                            <?php echo (isset($errors['skype'])?'<br/><span class="errornote">'.$errors['skype'].'</span>':''); ?>
                         </div>
                      </li>
                       <li>
                         <label>Address (1)</label>
                         <div class="errorwrap txtareaerr<?php echo (isset($errors['alamatresmi'])?' iserror areaerror':''); ?>">
                           <textarea id="alamatresmi" name="alamatresmi"><?php echo $alamatresmi;?></textarea><?php echo $alamatresmi;?>
                           <?php 
                             if(isset($errors['alamatresmi'])){
                               echo '<span class="errornote">'.$errors['alamatresmi'].'</span>';
                             } 
                           ?>
                        </div>
                      </li>
                      <li>
                         <label>Address (2)</label>
                         <div class="errorwrap txtareaerr<?php echo (isset($errors['alamatperbaikan'])?' iserror areaerror':''); ?>">
                           <textarea id="alamatperbaikan" name="alamatperbaikan"><?php echo $alamatperbaikan;?></textarea><?php echo $alamatperbaikan;?>
                           <?php 
                             if(isset($errors['alamatperbaikan'])){
                               echo '<span class="errornote">'.$errors['alamatperbaikan'].'</span>';
                             } 
                           ?>
                        </div>
                      </li>
                      <li>
                         <label>Address (3)</label>
                         <div class="errorwrap txtareaerr<?php echo (isset($errors['alamat'])?' iserror areaerror':''); ?>">
                           <textarea id="alamat" name="alamat"><?php echo $alamat;?></textarea><?php echo $alamat;?>
                           <?php 
                             if(isset($errors['alamat'])){
                               echo '<span class="errornote">'.$errors['alamat'].'</span>';
                             } 
                           ?>
                        </div>
                      </li>
                   </ul>
                     <div class="contentfooter buttonbox">
                      <button type="reset" class="resetbutton">reset</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">save</button>
                   </div>
                 </form>
            </div><!--tabadd--></!--tabadd-->