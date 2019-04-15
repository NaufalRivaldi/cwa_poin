<div class="row print_hide">
	<div class="col-sm-6">
		<form action="" method="get">
			<div class="row">
				<div class="col-sm-6">
					<label for="tgl_a">Dari tanggal</label>
					<input type="date" id="tgl_a" name="tgl_a" class="form-control" value="<?php if(isset($_GET['tgl_a'])){echo $_GET['tgl_a'];} else{ echo $now;} ?>">
				</div>
				<div class="col-sm-6">
					<label for="tgl_b">Sampai tanggal</label>
					<input type="date" value="<?php if(isset($_GET['tgl_b'])){echo $_GET['tgl_b'];} else{ echo $now;} ?>" id="tgl_b" name="tgl_b" class="form-control">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-8">
					<label for="divisi">Divisi</label>
					<select name="divisi" id="divisi" class="form-control">
						<option value="">- Semua Divisi -</option>
						<option value="CW1"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CW1'){echo "selected";}}?>>CW1</option>
						<option value="CW2"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CW2'){echo "selected";}}?>>CW2</option>
						<option value="CW3"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CW3'){echo "selected";}}?>>CW3</option>
						<option value="CW4"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CW4'){echo "selected";}}?>>CW4</option>
						<option value="CW5"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CW5'){echo "selected";}}?>>CW5</option>
						<option value="CW6"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CW6'){echo "selected";}}?>>CW6</option>
						<option value="CW7"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CW7'){echo "selected";}}?>>CW7</option>
						<option value="CW8"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CW8'){echo "selected";}}?>>CW8</option>
						<option value="CW9"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CW9'){echo "selected";}}?>>CW9</option>
						<option value="CA0"<?php if(isset($_GET['divisi'])){if($_GET['divisi'] == 'CA0'){echo "selected";}}?>>CW10</option>
					</select>
					<label for="order">Urutkan Berdasarkan</label>
					<select name="order" id="order" class="form-control">
						<option value="1"<?php if(isset($_GET['order'])){if($_GET['order'] == 1){echo "selected";}}?>>Divisi</option>
						<option value="2"<?php if(isset($_GET['order'])){if($_GET['order'] == 2){echo "selected";}}?>>Alfabet</option>
					</select>


					<label for="kriteria">Kriteria</label>
					<select name="kriteria[]" multiple="multiple" id="kriteria" class="form-control chosen-select">
						<?php
						$list = $this->mdlaporan->kode();
						foreach($list as $key=>$v){
							if(in_array($key, $_GET['kriteria'])){
								$sel = "selected";
							}
							else
								$sel = "";
							echo "<option $sel value='$key'>$v</option>";
						}
						?>
					</select>


				</div>
			</div>
			<button class="btn btn-primary">Proses</button>
		</form>
	</div>
</div>


<div class="print">
<?php
if(isset($_GET['tgl_a']) and isset($_GET['tgl_b']) and isset($_GET['divisi']) and isset($_GET['order'])){
	if(empty($_GET['tgl_a']) or empty($_GET['tgl_b'])){
	?>
	<div class="alert alert-danger">
		<span class="fa fa-warning" aria-hidden="true"></span>
		<span class="sr-only">Error:</span>
		Mohon masukkan range tanggal dengan lengkap
	</div>
	<?php
	}
	else{
		$a = $this->def->indo_date($_GET['tgl_a']);
		$b = $this->def->indo_date($_GET['tgl_b']);
	?>
	<br>
	<h3>Skor Penjualan Per Tanggal <?=$a?> s/d <?=$b?></h3>
		<table class="data">
			<tr>
				<th>No</th>
				<th>Nama Karyawan</th>
				<th>Divisi</th>
				<th>Skor</th>
				<th class="print_hide">Aksi</th>
				<th>Waktu Eksekusi</th>
			</tr>

		<?php
		$no = 1;

		if(isset($_GET['kriteria'])){
			$krit = $this->mdlaporan->build_kriteria($_GET['kriteria']);
		}
		else
			$krit = "";



		$max_score = $this->mdlaporan->get_max_score($_GET['tgl_a'],$_GET['tgl_b'],$_GET['divisi'],$krit);

		$txt = "";
		if(isset($_GET['kriteria'])){
			if(is_array($_GET['kriteria'])){
				foreach($_GET['kriteria'] as $kr){
					$txt .= "&kriteria[]=$kr";
				}
			}
		}
		foreach($table as $row){
			$mulai = $this->def->runtime("start");
			$val = intval($this->mdlaporan->get_score($row['kd_sales'],$row['divisi'],$_GET['tgl_a'],$_GET['tgl_b'], $krit));
			
			$addcss = "";
			//cari lebar kotak
			if($max_score > 0)
				$width = round($val / $max_score, 4) * 100;
			else
				$width = 0;
			
			if($width==100)
				$addcss .= "background:#38C0E9; ";
			elseif($width>55)
				$addcss .= "background:#38C053; ";
			elseif($width>10)
				$addcss .= "background:#EFDE2F; ";
			else
				$addcss .= "background:#333; ";
			
			$addcss .= "width:".$width."%; ";
			$selesai = $this->def->runtime("stop",$mulai);
			

			echo "
			<tr>
				<td>$no</td>
				<td>$row[nama] ($row[kd_sales])</td>
				<td>$row[divisi]</td>
				<td>
					<strong>".$val."</strong>
					<br>
					<div class='scoreBox print_hide'>
						<div class='inside' style='$addcss'></div>
					</div>
				</td>
				<td class='print_hide'><a href='laporan/detail/$row[id]?tgl_a=$_GET[tgl_a]&tgl_b=$_GET[tgl_b]$txt' class='btn btn-primary btn-sm'>Detail</a></td>
				<td>$selesai s</td>
			</tr>
			";
			$no++;
		}
		?>
		</table>

		<div class="control print_hide">
			<a href="laporan/export/<?php echo $_GET['tgl_a']. "/" .$_GET['tgl_b'] . "?divisi=$_GET[divisi]". $txt?>" class="btn btn-success"><span class="fa fa-file-excel-o"></span> Export</a>
			<a onclick="window.print()" class="btn btn-success"><span class="fa fa-print"></span> Print</a>
		</div>
		<?php
	}
}

?>
</div>