<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Griya Amana &gt; Admin &gt; <?php if(isset($pageTitle)) echo $pageTitle;?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/styleadm.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style-orange-br.css"/>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/mootools-core-1.3.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/mootools-more.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/anim.js"></script>
	<?php
	if(isset($jsFiles)){
		foreach($jsFiles as $jsFile){
			echo'<script type="text/javascript" src="'.base_url().'assets/js/'.$jsFile.'"></script>';
		}
	}
	if(isset($cssFiles)){
		foreach($cssFiles as $cssFile){
			echo'<link rel="stylesheet" href="'.base_url().'assets/css/'.$cssFile.'" type="text/css"/>';
		}
	}
	if(isset($jsText)){
		echo '<script type="text/javascript">'.$jsText.'</script>';
		
	}
	?>
</head>
<body id="<?php echo $bodyId;?>" class="smooth">
<div id="topbox"></div>
<div id="wrap" class="loadingbg">
	<div id="header" class="slidedown topslide">
			<?php
			if(isset($message)){
	        	echo '<div id="prevresult" class="'.$messageClass.'" onclick="prevResHide();">'.$message.'</div>';
				
			}else{
				echo '<div id="prevresult" class="invi" onclick="prevResHide();"></div>';
			}
			?>
			<h1 id="logo">
				<a href="<?php echo base_url().$url_browse; ?>" title="Griya Admin - Transaksi">Go To Main</a>
			</h1>
			<div id="kasir-header">
				<label>Tanggal</label><input type="text" name="lbltgl" id="lbltgl" disabled="disabled" value="<?php echo $tgl; ?>"/>
        		<label>Kode</label><input type="text" name="lblkode" id="lblkode" disabled="disabled" value="<?php echo $kode;?>"/>
        		<?php if($isAddAble){?>
        		<label for="namapasien">Nama Pasien</label><input type="text" name="namapasien" id="namapasien" class="" value=""/>
        		<?php
        		}else{
        			echo '<label for="namapasien">Nama Pasien</label><input type="text" name="namapasien" id="namapasien" disabled="disabled" value="'.$masterdata['namapasien'].'"/>';
        		} 
        		?>
			</div>
			<div id="logoutdiv">
				<?php
				if(isset($isLoginPage)){
					
				}else{

					echo '<div style="display:inline-block;font-size:12px">
							Login sebagai <b>'.$jabatan.'</b><br/><b>'.$userName.'</b>
						</div>
						<span class="divider-vertical"></span>
						<a id="logout" title="logout" class="aw" href="'.base_url().'login/logout">
							&nbsp;
						</a>';

				}
				?>
			</div>
		</div>
<div id="contentframe">
	<form id="frmeditor" method="post" class="ajaxed" action="<?php echo $form_action;?>">
	<div class="content side ajaxhide">
		<div class="content">
        	<div class="contentheader">
	        	<?php if($isAddAble || isset($returMode)){?>
		        <div class="searchbox">
		          	<input type="text" value="Tambah Barang" class="keywordsearch searchin" name="itemsearch" id="itemsearch"/>
		          	<input type="hidden" value="" name="items" id="items"/>
		          	<input type="hidden" value="<?php echo $tgltrans?>" name="tgltrans" id="tgltrans"/>
		          	<input type="hidden" value="<?php echo $kode;?>" name="kodetrans" id="kodetrans"/>
		          	<input type="hidden" value="" name="idpasien" id="idpasien"/>
		        </div>
	        	<?php }?>
	      	</div>
	      	<table class="headertable">
	        	<tbody>
		        	<tr>
			        <?php
			        	foreach($rite_columns as $header){
				            echo '<th width="'.$header['width'].'">';
				            if(isset($header['class'])){echo '<span class="'.$header['class'].'">';}
				            echo $header['header_title'];
				            if(isset($header['class'])){echo '</span>';}
				            echo '</th>';
			        	}
			        ?>
			        </tr>
		        </tbody>
		      </table>
		      <div class="leftcol fullwidth withheader">
		        <div class="tableframe">
		          <div id="scrollme" class="scrolledtable">
		            <div class="row hidden">
		            
		            </div>
		            <div class="row acenter nodata<?php if($obatdata || $tindakandata){echo ' hidden';}?>" id="emptytbl">
		              <span class="rowhead">no item(s)</span>
		              <div id="err-list" class="errorwrap">
		                <span class="errornote"></span>
		              </div>
		            </div>
		            
		            <?php
		            //log_message('error', var_export($allrows, true));
		            $icounter = 1;
		            if($obatdata){
			            foreach($obatdata as $row){
			            	
			    		    $kurutCls = '';        
			            	if(isset($returMode)){
			            		$row['harga']=0;
			            		$icounter = 0;
			            		$kurutCls = ' nokurut';
			            	}
			            
							echo '<div id="obat'.$row['id'].'" class="row rowitem">
								<div class="kurut'.$kurutCls.'" style="width: 5%;">'.$icounter.'</div>
								<div style="width: 15%;"><div>'.$row['kode'].'</div></div>
								<div style="width: 35%;"><div>'.$row['nama'].'</div></div>
								<div class="kprice" style="width: 15%;"><div>'.$row['harga'].'</div></div>
								<div class="kamountdiv" style="width: 15%;">
									<input type="hidden" class="ktipe" value="obat"/>
									<input type="hidden" class="kmaxval" value="'.$row['jumlah'].'"/>
									<input type="text" disabled="disabled" class="kamount numonly arite" value="'.$row['jumlah'].'"/>
								</div>
								<div class="ksub" style="width: 15%;"><div>'.(intval($row['harga']) * intval($row['jumlah'])).'</div></div>
								</div>';		            
			            
			              	$icounter++;
			              
			            }
		            }

		            if($tindakandata){
			            foreach($tindakandata as $row){
			            	
			            	$kurutCls = '';        
			            	if(isset($returMode)){
			            		$row['harga']=0;
			            		$icounter = 0;
			            		$kurutCls = ' nokurut';
			            	}
							echo '<div id="obat'.$row['id'].'" class="row rowitem">
								<div class="kurut'.$kurutCls.'" style="width: 5%;">'.$icounter.'</div>
								<div style="width: 15%;"><div>'.$row['kode'].'</div></div>
								<div style="width: 35%;"><div>'.$row['nama'].'</div></div>
								<div class="kprice" style="width: 15%;"><div>'.$row['harga'].'</div></div>
								<div class="kamountdiv" style="width: 15%;">
									<div class="kamount numonly arite">1</div>
									<input type="hidden" class="ktipe" value="tindakan"/>
									<input type="hidden" disabled="disabled" class="kamount numonly arite" value="1"/>
								</div>
								<div class="ksub" style="width: 15%;"><div>'.$row['harga'].'</div></div>
								</div>';		            
			            
			              	$icounter++;
			              
			            }
		            }
		            
		            ?>
		            
		          </div>
		        </div>          
		      </div>
		      <div id="tblfooter">
		      	<div id="keterangan">  
				    <label>Keterangan</label>
				    <textarea name="keterangan" rows="20" cols="3"><?php echo $ketTrans;?></textarea>
			    </div>
			    <?php
			    $newtxt = 'disabled="disabled"';
			    if($isAddAble || isset($returMode)){
			    	$newtxt = 'onchange="count_kasir()"';
			    } 
			    ?>
			    <div id="totalfooter">
			    	<ul class="detilbayar">
			    		<li><label>Biaya Konsul</label><input type="text" id="badmin" name="badmin" class="numonly arite" value="<?php echo $biayaAdmin;?>" <?php echo $newtxt;?>/></li>
			    		<li><label>Biaya Admin</label><input type="text" id="bkonsul" name="bkonsul" class="numonly arite" value="<?php echo $biayaKonsul;?>" <?php echo $newtxt;?>/></li>
			    	</ul>
			    	<label>Total</label><div id="ktotal" class="arite"><?php echo $biayaTotal;?></div>
			    	<ul class="detilbayar">
			    		<li><h2 class="arite">Metode Pembayaran</h2></li>
			    		<li><label>Tunai</label><input type="text" id="ktunai" name="ktunai" class="numonly arite" value="<?php echo $biayaTunai;?>" onchange="count_kasir()"/></li>
			    		<li>
			    			<label>EDC Bank</label>
			    			<select id="metodeedc" name="metodeedc" onchange="count_kasir()">
			    				<option value="none">--</option>
			    				<?php
			    				foreach($metodeedc as $val => $txt){
			    					echo '<option value="'.$val.'">'.$txt.'</option>';
			    				} 
			    				?>
			    			</select>
			    			<?php
			    			foreach($taxes as $tidx => $tax){
			    				echo '<input type="hidden" name="tax-'.$tidx.'" id="tax-'.$tidx.'" value="'.$tax.'"/>';
			    			} 
			    			?>
			    		</li>
			    		<li><label>EDC</label><input type="text" id="kedc" name="kedc" class="numonly arite" value="0" onchange="count_kasir()"/><div id="edcptax">0</div></li>
			    		<li><label>Ket. EDC</label><input type="text" id="kbname" name="kbname" class="" value=""/></li>
			    		<li><label>Sisa</label><div id="sisa" class="sisa arite"><?php echo $sisa;?></div></li>
			    	</ul>
			  	</div>
			  	<div id="lunas"><?php if ($sisa <= 0){echo'LUNAS';}?></div>
			  	<div id="buttonframe">
				  	<button type="submit" name="submit" id="submit" class="submitbtn">Simpan</button>
				  	<?php if(!$isAddAble && !isset($returMode)){?>
				  	<button type="button" name="print" id="print" class="printbtn" onclick="popitup('<?php echo base_url().'kasir/print_bill/'.$idtrans;?>','bill',700,800)">Cetak</button>
				  	<button type="button" name="print" id="batal" class="printbtn btndel" href="<?php echo base_url().'kasir/batal/'.$idtrans;?>" title="Lanjut Batalkan Transaksi Ini?">Batal & Retur</button>
				  	<?php }?>
			  	</div>
			  	<?php
		    	if (!$isAddAble && $edcdata){
		    		$this->load->helper('date');
		    		echo'<div id="prevedcframe"><div>EDC sebelumnya</div><ul id="prevedc">';
		    		foreach($edcdata as $edc){
		    			$unix = human_to_unix($edc['tgl']);
		    			if($edc['ket'] != "")$edc['ket'] = '['.$edc['ket'].']';
		    			echo '<li><div>'.date('d/m/Y', $unix).'</div> - '.$edc['metode'].$edc['ket'].'<b>'.number_format($edc['jumlah'], 2, ',', '.').'<br/>[+tax]'.number_format($edc['jumlahplustax'], 2, ',', '.').'</b></li>';
		    		}
		    		echo'	</ul></div>';
		    	} 
		    	?>
			  	<?php
			  	if (!$isAddAble){
			  		//echo '<a href="'.base_url().'/kasir/editor/'.$idtrans.'" id="resetbtn" class="submitbtn button">Reset</a>';
			  		echo '<input type="hidden" id="idtrans" name="idtrans" value="'.$idtrans.'"/>';
			  		echo '<input type="hidden" id="totaledc" name="totaledc" value="'.$totaledc.'"/>';
			  	} 
			  	?>
		      </div>
        </div>     
    </div>
    </form>
</div>