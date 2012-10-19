<div id="contentframe">
		  <div class="content auto-height">
         <div class="contentframe">
            <form class="formeditor aw" method="post" action="<?php echo base_url()."provider/".$form_action.'/'.$idprovider;?>">
                <div class="contentheader">
                   <h2 class="boxtitle fleft"><?php echo $addtitle;?> provider</h2>
                </div>
                <ul class="leftcol fullwidth formul">
                    <li>
                       <label>Name</label>
                       <div class="errorwrap<?php echo (isset($errors['namaprovider'])?' iserror areaerror':''); ?>">
                          <input type="text" id="namaprovider" name="namaprovider" value="<?php echo $namaprovider;?>"/>
                          <?php echo (isset($errors['namaprovider'])?'<br/><span class="errornote">'.$errors['namaprovider'].'</span>':''); ?>
                       </div>
                    </li>
                    <li>
                       <label>Acc. Manager</label>
                       <div class="errorwrap<?php echo (isset($errors['accountmanager'])?' iserror areaerror':''); ?>">
                          <input type="text" id="accountmanager" name="accountmanager" value="<?php echo $accountmanager;?>"/>
                          <?php echo (isset($errors['accountmanager'])?'<br/><span class="errornote">'.$errors['accountmanager'].'</span>':''); ?>
                       </div>
                    </li>
                    <li>
                       <label>Email (1)</label>
                       <div class="errorwrap<?php echo (isset($errors['email1'])?' iserror areaerror':''); ?>">
                          <input type="text" id="email1" name="email1" value="<?php echo $email1;?>"/>
                          <?php echo (isset($errors['email1'])?'<br/><span class="errornote">'.$errors['email1'].'</span>':''); ?>
                       </div>
                    </li> 
                    <li>
                       <label>Email (2)</label>
                       <div class="errorwrap<?php echo (isset($errors['email2'])?' iserror areaerror':''); ?>">
                          <input type="text" id="email2" name="email2" value="<?php echo $email2;?>"/>
                          <?php echo (isset($errors['email2'])?'<br/><span class="errornote">'.$errors['email2'].'</span>':''); ?>
                       </div>
                    </li>
                    <li>
                       <label>Phone</label>
                       <div class="errorwrap<?php echo (isset($errors['tlplokal'])?' iserror areaerror':''); ?>">
                          <input class="numonly" type="text" id="tlplokal" name="tlplokal" value="<?php echo $tlplokal;?>"/>
                          <?php echo (isset($errors['tlplokal'])?'<br/><span class="errornote">'.$errors['tlplokal'].'</span>':''); ?>
                       </div>
                    </li>
                    <li>
                       <label>Office Phone</label>
                       <div class="errorwrap<?php echo (isset($errors['tlpkantor'])?' iserror areaerror':''); ?>">
                          <input class="numonly" type="text" id="tlpkantor" name="tlpkantor" value="<?php echo $tlpkantor;?>"/>
                          <?php echo (isset($errors['tlpkantor'])?'<br/><span class="errornote">'.$errors['tlpkantor'].'</span>':''); ?>
                       </div>
                    </li>
                </ul>
                <ul class="leftcol fullwidth formul">
                    <li>
                       <label>Cellphone</label>
                       <div class="errorwrap<?php echo (isset($errors['hp'])?' iserror areaerror':''); ?>">
                          <input class="numonly" type="text" id="hp" name="hp" value="<?php echo $hp;?>"/>
                          <?php echo (isset($errors['hp'])?'<br/><span class="errornote">'.$errors['hp'].'</span>':''); ?>
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
                    <li>
                       <label>Address</label>
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
                    <input type="hidden" class="idprovider" name="idprovider" id="idprovider" value="<?php echo(isset($idprovider)?$idprovider:'')?>">
                    <a class="button" href="<?php echo base_url();?>provider/browse">browse</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="reset" class="resetbutton">reset</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">save</button>
                </div>
            </form>
         </div>
      </div><!-->content</-->
</div>