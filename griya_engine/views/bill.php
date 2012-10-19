<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Griya Amana &gt; Admin &gt; <?php if(isset($pageTitle)) echo $pageTitle;?></title>
	<?php
	if(isset($cssFiles)){
		foreach($cssFiles as $cssFile){
			echo'<link rel="stylesheet" href="'.base_url().'assets/css/'.$cssFile.'" type="text/css"/>';
		}
	}
	?>
</head>
<body id="<?php echo $bodyId;?>" class="smooth">
<div id="bill">
	<div class="bloc-frame">
		<div class="bloc-left">
			<div class="title">GRIYA AMANA</div>
			Jl. Pramuka 20 Depok<br/>
			021-7080349
		</div>
		<div class="bloc-right">
			<div id="" class="arite">Tgl <?php echo $tgl;?></div>
			Kepada Yth<br/>
			<?php echo ucwords($masterdata['namapasien']);?><br/>
			<?php echo ucwords($masterdata['alamat']);?><br/>
			<?php echo ucwords($masterdata['kota']); if($masterdata['kodepos']) {echo ' - '.$masterdata['kodepos'];}?><br/>
			<?php if($masterdata['tlp']) {echo $masterdata['tlp'].'<br/>';}?>
			<?php echo $masterdata['hp'];?><br/>
		</div>
	</div>
	<div class="bloc-frame">
		<div class="bloc-left">
			<p><label>nomor faktur</label>: <?php 
			$strkode = str_split($masterdata['kode']);
			$strkode[1] = $strkode[1].'.';
			$strkode[3] = $strkode[3].'.';
			$strkode[5] = $strkode[5].'.';
			$strkode[7] = $strkode[7].'.';
			echo implode($strkode);
			?></p>
			<p><label>jenis</label>: <?php if($masterdata['biayatunai']>0){echo 'tunai';} if($masterdata['biayatunai']>0 && $edcdata){echo '-';} if($edcdata){echo 'edc';}?></p>
		</div>
		<div class="bloc-right">
			<p><label>salesperson</label>: <?php echo ucwords($masterdata['namapegawai']);?></p>
			<p><label></label></p>
		</div>
	</div>
	<table>
		<thead>
			<tr>
				<th>kode</th>
				<th>nama barang</th>
				<th>harga</th>
				<th>jumlah</th>
				<th>subtotal</th>
				<th>diskon</th>
				<th>total</th>
			</tr>
		</thead>
		<tbody>
			<?php
			 if($obatdata){
	            foreach($obatdata as $row){
	            	$subtot = $row['harga'] * intval($row['jumlah']);
	            	echo '<tr>
						<td>'.$row['kode'].'</td>
						<td>'.$row['nama'].'</td>
						<td class="arite">'.number_format($row['harga'], 2, ',', '.').'</td>
						<td class="arite">'.$row['jumlah'].'</td>
						<td class="arite">'.number_format($subtot, 2, ',', '.').'</td>
						<td class="arite"></td>
						<td class="arite">'.number_format($subtot, 2, ',', '.').'</td>
					</tr>';

	              	//$icounter++;
	            }
            }

            if($tindakandata){
	            foreach($tindakandata as $row){
	            	echo '<tr>
						<td>'.$row['kode'].'</td>
						<td>'.$row['nama'].'</td>
						<td class="arite">'.number_format($row['harga'], 2, ',', '.').'</td>
						<td class="arite">1</td>
						<td class="arite">'.number_format($row['harga'], 2, ',', '.').'</td>
						<td class="arite"></td>
						<td class="arite">'.number_format($row['harga'], 2, ',', '.').'</td>
					</tr>';

	              	//$icounter++;
	            }
            }
			?>
		</tbody>
		<tfoot>
			<tr class="rowadmin">
				<td colspan="6" class="arite">Biaya Administrasi</td>
				<td class="arite"><?php echo number_format($biayaAdmin, 2, ',', '.');?></td>
			</tr>
			<tr class="rowkonsul">
				<td colspan="6" class="arite">Biaya Konsultasi</td>
				<td class="arite"><?php echo number_format($biayaKonsul, 2, ',', '.');?></td>
			</tr>
			<tr>
				<td colspan="5">
				<?php
				$nums = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','');
				$nlvl = array('','Puluh','Ratus','Ribu','Puluh','Ratus','Juta','Puluh','Ratus','Milyar','Puluh','Ratus');
				//$masterdata['total'] = 60140340;
				$arrno = array_reverse(str_split(strval($masterdata['total'])));
				//var_export($arrno);
				//echo count($arrno);
				$arrno[] = '0';
				$strtot = '';
				$idxn = 0;
				while($idxn < (count($arrno)-1)){
					$nom = intval($arrno[$idxn]);
					if($nom>0){
						$numw = $nums[$nom].' '. $nlvl[$idxn] ;
						if($nlvl[$idxn+1] == 'Puluh' && $arrno[$idxn+1] == '1'){
							if($arrno[$idxn] == '1'){
								$numw = 'Sebelas '. $nlvl[$idxn] ;
							}else{
								$numw = $nums[$nom].' Belas '. $nlvl[$idxn] ;
							}							
							$idxn++;
						}
						elseif($nom == 1 && ($nlvl[$idxn] == 'Ratus' || $nlvl[$idxn] == 'Puluh')){
							$numw = 'Se'. strtolower($nlvl[$idxn]);
						}
						$strtot = $numw . ' ' .$strtot;
						//echo $idxn.'--'.($idxn%3).'=='.$numw.'<br/>';
						
					}elseif($idxn%3 == 0){
						$strtot = $nlvl[$idxn] . ' ' .$strtot;
					}
					$idxn++;
				}
				echo 'Terbilang: <b><i>'.$strtot. ' Rupiah</i></b>'; 
				?>
				</td>
				<td><label>total</label></td>
				<td class="arite"><b>Rp&nbsp;<?php echo number_format($masterdata['total'], 2, ',', '.');?></b></td>
			</tr>
		</tfoot>
	</table>
	<div class="bloc-frame">
		<div class="bloc-left hormat">
			Hormat Kami
			<div class="namecol"></div>
		</div>
		<div class="bloc-right terima">
			Tanda Terima
			<div class="namecol"></div>
		</div>
	</div>
</div>
</body>
</html>