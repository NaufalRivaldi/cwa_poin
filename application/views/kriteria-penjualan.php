<?php
foreach($res as $r){
?>
<a href="data/produk" class="btn btn-primary">&laquo;Back</a>
<div class="row">
	<div class="col-sm-6">
		<div class="row">
			<div class="col-sm-4"><strong>Nama Kriteria</strong></div>
			<div class="col-sm-8"><?=$r['rule_name']?></div>
		</div>
		<div class="row">
			<div class="col-sm-4"><strong>Skor</strong></div>
			<div class="col-sm-8"><?=$r['skor']?></div>
		</div>
		<div class="row">
			<div class="col-sm-4"></div>
			<div class="col-sm-8"><a href="edit/produk/<?=$r['id']?>" class="btn btn-success">Edit Kriteria</a></div>
		</div>
	</div>
</div>

<h3>Data Penjualan sesuai Kriteria</h3>
<table class="data">
	<tr>
		<th>No</th>
		<th>Kode Barang</th>
		<th>Nama Barang</th>
		<th>Jml</th>
		<th>Harga</th>
		<th>Kemasan</th>
		<th>Penjual</th>
	</tr>
	<?php
	$no = 1;
	foreach($table as $t){
		echo "
		<tr>
			<td>$no</td>
			<td>$t[kd_barang]</td>
			<td>$t[nm_barang]</td>
			<td>$t[jml]</td>
			<td>".$this->def->rupiah($t['harga'])."</td>
			<td>$t[kd_satuan]</td>
			<td>".$this->mdproduk->get_karyawan($t['kd_sales'])."</td>
		</tr>
		";
		$no++;
	}
	if($no == 1){
		echo "<tr><td colspan=7>Belum ada barang terjual dalam kriteria ini</td></tr>";
	}
	?>
</table>

<?php
break;
}
?>