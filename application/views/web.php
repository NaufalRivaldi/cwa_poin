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

			<button class="btn btn-primary">Proses</button>
		</form>
	</div>
</div>
<br>
<?php
if(isset($_GET['tgl_a']) and isset($_GET['tgl_b'])){
	/*
	Data yg perlu diexport : 
	- Data karyawan
	- Data history jual
	- Tgl
	- Data Kriteria
	*/
	$array = array();
	$array['tgl'] = date("Y-m-d H:i:s");	

	if(strtotime($_GET['tgl_a']) > strtotime($_GET['tgl_b'])){
		$tgl_a = $_GET['tgl_b'];
		$tgl_b = $_GET['tgl_a'];
	}
	else{
		$tgl_a = $_GET['tgl_a'];
		$tgl_b = $_GET['tgl_b'];
	}

	$array['query'] = $this->db->escape($tgl_a). " AND " .$this->db->escape($tgl_b);

	$cek = $this->db->query("SELECT * FROM tb_history_jual WHERE tgl BETWEEN ".$this->db->escape($tgl_a)." AND ".$this->db->escape($tgl_b));
	if($cek->num_rows() > 0){
		$n = 0;
		foreach($cek->result_array() as $rc){
			$array['skor'][$n]['kd_sales'] = $rc['kd_sales'];
			$array['skor'][$n]['tgl'] = $rc['tgl'];
			$array['skor'][$n]['divisi'] = $rc['divisi'];
			$array['skor'][$n]['kd_barang'] = $rc['kd_barang'];
			$array['skor'][$n]['jml'] = $rc['jml'];
			$array['skor'][$n]['skor'] = $rc['skor'];
			$array['skor'][$n]['brt'] = $rc['brt'];
			$n++;
		}


		$kary = $this->db->query("SELECT * FROM tb_karyawan WHERE stat = 1");
		$n = 0;
		foreach($kary->result_array() as $rk){
			$array['karyawan'][$n]['kd_sales'] = $rk['kd_sales'];
			$array['karyawan'][$n]['nama'] = $rk['nama'];
			$array['karyawan'][$n]['divisi'] = $rk['divisi'];
			$n++;
		}

		$krit = $this->db->query("SELECT * FROM tb_kriteria");
		$n = 0;
		foreach($krit->result_array() as $rr){
			$array['kriteria'][$n]['id'] = $rr['id'];
			$array['kriteria'][$n]['rule_name'] = $rr['rule_name'];
			$array['kriteria'][$n]['kd_barang'] = $rr['kd_barang'];
			$array['kriteria'][$n]['kd_merk'] = $rr['kd_merk'];
			$array['kriteria'][$n]['kd_golongan'] = $rr['kd_golongan'];
			$array['kriteria'][$n]['kd_satuan'] = $rr['kd_satuan'];
			$array['kriteria'][$n]['kd_jenis'] = $rr['kd_jenis'];
			$array['kriteria'][$n]['skor'] = $rr['skor'];
			$array['kriteria'][$n]['stat'] = $rr['stat'];
			$n++;
		}


		//setelah itu semua,, simpan dalam format JSON ke sebuah file
		$link_download = $this->mdlaporan->export_web($array, $tgl_a, $tgl_b);
		if(strlen($link_download) > 0){
			$exlink = explode("/",$link_download);
			$n = count($exlink);
			$fname = $exlink[$n-1];

			echo "
			<p>
			Tekan tombol dibawah ini untuk mendownload file Scoreboard untuk Website data tanggal ".$this->def->indo_date($tgl_a)." s/d ".$this->def->indo_date($tgl_b)."
			</p>
			<a href='$link_download' download='$fname' class='btn btn-lg btn-info'>Download File Export untuk Web</a>
			";
		}


	}
	else{
		echo "
		<div class='alert alert-danger'><strong>Error</strong> : Tidak ditemukan data scoreboard untuk tanggal sekian.</div>
		";
	}


}
?>


<br>
<br>
<h3>History Export Scoreboard</h3>

<?php
$dir = "export/";
$list = array_diff(scandir($dir,1),array("..","."));

if(count($list) > 0){

	echo "
	<table class='data'>
	<thead>
		<tr>
			<th>Nama File</th>
			<th>Ukuran</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	";

	foreach($list as $file){
		$fsize = $this->def->get_filesize(filesize($dir.$file));
		echo "
		<tr>
			<td>$file</td>
			<td>$fsize</td>
			<td><a href='$dir$file' download='$file' class='btn btn-sm btn-success'>Download</a></td>
		</tr>
		";
	}

	echo "
	</tbody>
	</table>
	";
}
else{
	echo "<div class='alert alert-danger'>Belum ada rekapan data export yang pernah dibuat</div>";
}
?>
