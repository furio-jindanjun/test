<div id="contentframe">
		  <div class="content auto-height">
         <div class="contentframe">
            <form class="formeditor aw" method="post" action="<?php echo base_url()."manufacture/".$form_action.'/'.$idmanufaktur;?>">
                <div class="contentheader">
                   <h2 class="boxtitle fleft"><?php echo $addtitle;?> Manufacture</h2>
                </div>
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
                          <input class="numonly" type="text" id="tlp" name="tlp" value="<?php echo $tlp;?>"/>
                          <?php echo (isset($errors['tlp'])?'<br/><span class="errornote">'.$errors['tlp'].'</span>':''); ?>
                       </div>
                    </li>
                    <li>
                       <label>Fax</label>
                       <div class="errorwrap<?php echo (isset($errors['fax'])?' iserror areaerror':''); ?>">
                          <input class="numonly" type="text" id="fax" name="fax" value="<?php echo $fax;?>"/>
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
                </ul>
                <ul class="leftcol fullwidth formul">
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
                         <textarea id="alamat" name="alamat"><?php echo $alamat;?></textarea>
                         <?php 
                           if(isset($errors['alamat'])){
                             echo '<span class="errornote">'.$errors['alamat'].'</span>';
                           } 
                         ?>
                      </div>
                    </li>
                </ul>
                <div class="contentfooter buttonbox">
                    <input type="hidden" class="idmanufaktur" name="idmanufaktur" id="idmanufaktur" value="<?php echo(isset($idmanufaktur)?$idmanufaktur:'')?>">
                    <a class="button" href="<?php echo base_url();?>manufacture/browse">browse</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="reset" class="resetbutton">reset</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">save</button>
                </div>
            </form>
         </div>
      </div><!-->content</-->
</div>