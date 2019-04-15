<div class="alert alert-info">
	Program ini digunakan untuk menganalisa perbandingan jumlah penjualan produk tertentu berdasarkan jangka waktu yang ditentukan.
</div>

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
				<div class="col-sm-12">
					<label for="kriteria">Kriteria Produk</label>
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
			<a href="laporan/produk" class="btn btn-danger">Reset</a>
		</form>
	</div>
</div>


<?php
if(isset($hasil)){
	$no = 0;
	$tgl = "";
	$stok = 0;
	$out_tgl = array();
	$out_stok = array();
	foreach($hasil as $row){
		if($row['tgl'] == $tgl){
			$stok += $row['jml'];
		}
		else{
			if($stok <> 0){
				$new = array("tgl"=>$tgl, "stok"=>$stok);
				array_push($out_tgl, $tgl);
				array_push($out_stok, $stok);
			}

			$tgl = $row['tgl'];
			$stok = $row['jml'];
			$no++;
		}
	}

	if(count($out_tgl) == 0){
		echo "<div class='alert alert-danger'>Tidak ada data yang dapat ditampilkan berdasarkan filter tersebut. Silakan gunakan batasan tanggal lain, atau filter produk lain</div>";
	}
	else{
		//analyze
		//getmax
		$max = max($out_stok)."<br>";
		$min = min($out_stok)."<br>";

		$get_max = array_search($max, $out_stok);
		$get_min = array_search($min, $out_stok);

		echo "<h3>Hasil Analisa Index Penjualan</h3>";
		echo "<table width=100%>";
		echo "
			<tr>
				<th>No</th>
				<th>Tanggal</th>
				<th>Jumlah Penjualan</th>
			</tr>
		";
		$real_total = 0;
		for($i=0;$i<count($out_tgl);$i++){
			$bw = ceil($out_stok[$i] / $max * 100);
			$bar_width = $bw."%";

			if($bw < 30)
				$addClass = "bg-red";
			elseif($bw < 80)
				$addClass = "bg-green";
			else
				$addClass = "bg-blue";

			echo "
			<tr>
				<td>".($i+1)."</td>
				<td>".$this->def->indo_date($out_tgl[$i])."</td>
				<td>
					<div class='scoreBox cwhite'>
						<div class='inside $addClass' style='width:$bar_width'>".$out_stok[$i]."</div>
					</div>
				</td>
			</tr>
			";
			$real_total += $out_stok[$i];
		}
		echo "
			<tr>
				<td></td>
				<td align='right'><label class='label label-primary'>Total Penjualan</label></td>
				<td> <h3>".number_format($real_total,0,",",".")."</h3></td>
			</tr>
		";
		echo "</table>";		
	}

}
