<?php
foreach($row as $r){
?>

<div class="row">
	<div class="col-sm-2"><strong>Nama Karyawan</strong></div>
	<div class="col-sm-4"><?=$r['nama'];?></div>
</div>
<div class="row">
	<div class="col-sm-2"><strong>Alamat</strong></div>
	<div class="col-sm-4"><?=$r['alamat'];?></div>
</div>
<div class="row">
	<div class="col-sm-2"><strong>Telepon</strong></div>
	<div class="col-sm-4"><?=$r['telp'];?></div>
</div>
<div class="row">
	<div class="col-sm-2"><strong>Divisi</strong></div>
	<div class="col-sm-4"><?=$r['divisi'];?></div>
</div>


<h2>Rekap Produk Unggulan Terjual</h2>
<a href="javascript:history.go(-1)" class="btn btn-sm btn-danger">Back</a>
<table class="data">
	<tr>
		<th>No</th>
		<th>Kode Barang</th>
		<th>Tanggal</th>
		<th>Nama Barang</th>
		<th>Jml</th>
		<th>Skor</th>
	</tr>
<?php
	$no = 1;

	if(!isset($_GET['kriteria'])){
		$tb = $this->mdlaporan->create_detail($r['kd_sales'],$r['divisi'], $_GET['tgl_a'], $_GET['tgl_b']);
	}
	else{
		$tb = $this->mdlaporan->create_detail($r['kd_sales'],$r['divisi'], $_GET['tgl_a'], $_GET['tgl_b'], $_GET['kriteria']);
	}

	$arr_jml = [];
	foreach($tb as $rt){
		$tb2 = $this->mdlaporan->get_nama_barang($rt['kd_barang']);
		$subtotal = intval($rt['skor']);
		array_push($arr_jml,$subtotal);

		if(empty($tb2['nm_barang'])){
			$nmbr = "<em>".$rt['kd_barang']."</em>";
		}
		else{
			$nmbr = $tb2['nm_barang'];
		}

		echo "
		<tr>
			<td>$rt[id]</td>
			<td>$rt[kd_barang]</td>
			<td>". $this->def->indo_date($rt['tgl']) ."</td>
			<td>$nmbr</td>
			<td>$rt[jml]</td>
			<td>".($subtotal)."</td>
		</tr>
		";
		$no++;
	}
	$total = array_sum($arr_jml);
	echo "
	<tr>
		<th colspan=4></th>
		<th align='right'>Total</th>
		<th>$total</th>
	</tr>
	";
?>
</table>

<?php
}
?>