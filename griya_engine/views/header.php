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
				<a href="<?php echo base_url(); ?>" title="Citilink - Assets">Citilink</a>
			</h1>
			<ul id="mainmenu">
				<?php
				if(isset($isLoginPage)){
					
					echo '<li></li>';
						
				}else{
				
					$cp['data'] = '';
					$cp['permintaan'] = '';
					$cp['managemen'] = '';
					$cp['stok'] = '';
					$cp['transaksi'] = '';
					
					$sm['obat'] = '';
					$sm['tindakan'] = '';
					$sm['permintaanobat'] = '';
					$sm['jabatan'] = '';
					$sm['supplier'] = '';
					$sm['customer'] = '';
					$sm['depo'] = '';
					$sm['kamarobat'] = '';
					$sm['apotik'] = '';
					$sm['kasir'] = '';
                    $sm['pengeluaranklinik'] = '';
                    $sm['pegawai'] = '';
                    $sm['reportedc'] = '';
					
					$cp[$selMenu] = ' class="curpage" ';
					if(isset($subMenu)){
						$sm[$subMenu] = ' curpage';
					}				
					
					$this->allowed_level_request = $this->config->item('allowed_level_request');
					$this->allowed_level_obat = $this->config->item('allowed_level_obat');
					$this->allowed_level_obat_admin = $this->config->item('allowed_level_obat_admin');
					$this->allowed_level_tindakan = $this->config->item('allowed_level_tindakan');
					$this->allowed_level_tindakan_admin = $this->config->item('allowed_level_tindakan_admin');
					$this->allowed_level_pegawai = $this->config->item('allowed_level_pegawai');
                    $this->allowed_level_pegawai_admin = $this->config->item('allowed_level_pegawai_admin');
					$this->allowed_level_permintaanobat_admin = $this->config->item('allowed_level_permintaanobat_admin');
					$this->allowed_level_jabatan_admin = $this->config->item('allowed_level_jabatan_admin');
					$this->allowed_level_supplier_admin = $this->config->item('allowed_level_supplier_admin');
					$this->allowed_level_customer_admin = $this->config->item('allowed_level_customer_admin');
					$this->allowed_level_depo = $this->config->item('allowed_level_depo');
					$this->allowed_level_kamarobat = $this->config->item('allowed_level_kamarobat');
					$this->allowed_level_apotik_admin = $this->config->item('allowed_level_apotik_admin');
					$this->allowed_level_kasir_admin = $this->config->item('allowed_level_kasir_admin');
					$this->allowed_level_pengeluaranklinik_admin = $this->config->item('allowed_level_pengeluaranklinik_admin');
					
					if(in_array($jabatan,$this->allowed_level_obat)||in_array($jabatan,$this->allowed_level_obat_admin)){
						
		            	echo '<li'.$cp['data'].'><a href="'. base_url().'obat" class="aw">Data</a>
			                    <div class="submenu">
			                      <a href="'. base_url().'obat" class="aw'.$sm['obat'].'">Obat</a>
			                      <a href="'. base_url().'tindakan" class="aw'.$sm['tindakan'].'">Tindakan</a>
			                      
			                    </div></li>';
			       }
							
				  /*  if(in_array($jabatan,$this->allowed_level_iata_assign_admin)||in_array($jabatan,$this->allowed_level_provider_admin)||in_array($jabatan,$this->allowed_level_category_admin)||in_array($jabatan,$this->allowed_level_statusitem_admin)||in_array($jabatan,$this->allowed_level_inventory_admin)||in_array($jabatan,$this->allowed_level_division_admin)){
		            	*/echo ' <li'.$cp['permintaan'].'><a href="'. base_url().'permintaanobat" class="aw">Permintaan</a>
			            <div class="submenu">
			                      <a href="'. base_url().'permintaanobat" class="aw'.$sm['permintaanobat'].'">Permintaan Obat</a>
			                     </div></li>
			          	<li'.$cp['managemen'].'><a href="'. base_url().'customer" class="aw">Managemen</a>
			                    <div class="submenu">
			                      <a href="'.base_url().'jabatan" class="aw'.$sm['jabatan'].'">Jabatan</a>
			                       <a href="'.base_url().'pegawai" class="aw'.$sm['pegawai'].'">Pegawai</a>
			                      <a href="'.base_url().'supplier" class="aw'.$sm['supplier'].'">Supplier</a>
			                      <a href="'.base_url().'customer" class="aw'.$sm['customer'].'">Customer</a>
			                    </div></li>';    
		           //	}
		           /*	if(in_array($jabatan,$this->allowed_level_maintenance)||in_array($jabatan,$this->allowed_level_maintenance_admin)){
		            */	echo '<li'.$cp['stok'].'><a href="'.base_url().'stokobatdepo" class="aw">Stok</a>'.
		                '<div class="submenu">'.
                                  '<a href="'. base_url().'stokobatdepo" class="aw'.$sm['depo'].'">Stok Depo</a>'.
                                  '<a href="'. base_url().'stokobatkamarobat" class="aw'.$sm['kamarobat'].'">Stok Kamar Obat</a>'.
                                  '<a href="'. base_url().'stokobatapotik" class="aw'.$sm['apotik'].'">Stok Apotik</a>'.
                        '</div></li>';
		          	//}
		          
		          /*	if(in_array($jabatan,$this->allowed_level_request_admin)||in_array($jabatan,$this->allowed_level_request)){
		           */ 	echo '<li'.$cp['transaksi'].'><a href="'.base_url().'kasir" class="aw">Transaksi</a>'.
		                '<div class="submenu">'.
                                  '<a href="'. base_url().'kasir" class="aw'.$sm['kasir'].'">Kasir</a>'.
                                  '<a href="'. base_url().'pengeluaranklinik" class="aw'.$sm['pengeluaranklinik'].'">Pengeluaran Klinik</a>'.
                                  '<a href="'. base_url().'reportedc" class="aw'.$sm['kasir'].'">Laporan EDC</a>'.
                        '</div></li>';
		       //   	}
           
          		}
				?>
			</ul>
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