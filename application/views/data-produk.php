<a class="btn btn-primary" id="addBtn">Tambah Data</a>

<div class="row">
	<div class="col-sm-6">
		<div class="box" id="addForm">
			<form action="add/produk" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Nama Kriteria</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="text" name="rule_name" maxlength="50" class="form-control" value="<?php $this->def->echo_sess("rule_name")?>">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Kriteria</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<a class="btn btn-sm btn-success btnActive bykat">Kategori</a>
						<a class="btn btn-sm btn-success byspec">Produk Spesifik</a>

						<div class="cont_a">
							<div class="row">
								<div class="col-xs-6">
									<strong>Kode Merk</strong>
								</div>
								<div class="col-xs-6">
									<input type="text" id="kdmr" class="form-control" name="kd_merk" value="<?php $this->def->echo_sess("kd_merk")?>" size=10>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<strong>Kemasan</strong>
								</div>
								<div class="col-xs-6">
									<input type="text" id="kdst" class="form-control" name="kd_satuan" value="<?php $this->def->echo_sess("kd_satuan")?>" size=10>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<strong>Kode Golongan</strong>
								</div>
								<div class="col-xs-6">
									<input type="text" id="kdgl" class="form-control" name="kd_golongan" value="<?php $this->def->echo_sess("kd_golongan")?>" size=10>
								</div>
							</div>
							
							<div class="row">
								<div class="col-xs-6">
									<strong>Kode Jenis</strong>
								</div>
								<div class="col-xs-6">
									<input type="text" id="kdjn" class="form-control" name="kd_jenis" value="<?php $this->def->echo_sess("kd_jenis")?>" size=10>
								</div>
							</div>
						</div>

						<div class="cont_b">
							<div class="row">
								<div class="col-xs-5">
									<strong>Kode Barang</strong>
								</div>
								<div class="col-xs-7">
									<input type="text" id="kdbr" class="form-control" name="kd_barang" value="<?php $this->def->echo_sess("kd_barang")?>" size=50>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Skor</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="number" name="skor" min=0 max=10000 class="form-control" value="<?php $this->def->echo_sess("skor")?>">
					</div>
				</div>
				
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4"></div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<button name="btn" class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<table class="data">
	<tr>
		<th>No</th>
		<th>Nama Kriteria</th>
		<th>Kriteria</th>
		<th>Skor</th>
		<th>Aksi</th>
	</tr>
	<?php
	$no = 1;
	foreach($result as $row){
		
		$desc = array();
		if(!empty($row['kd_barang']))
			array_push($desc,"kode barang <strong>$row[kd_barang]</strong>");
		else{
			if(!empty($row['kd_merk']))
				array_push($desc,"kode merk <strong>$row[kd_merk]</strong>");
			if(!empty($row['kd_satuan']))
				array_push($desc,"kemasan <strong>$row[kd_satuan]</strong>");
			if(!empty($row['kd_golongan']))
				array_push($desc,"golongan <strong>$row[kd_golongan]</strong>");
			if(!empty($row['kd_jenis']))
				array_push($desc,"jenis <strong>$row[kd_jenis]</strong>");
		}
		$ec = "Produk dengan ".implode(", ",$desc);		

		echo "
		<tr>
			<td>$no</td>
			<td>$row[rule_name]</td>
			<td>$ec</td>
			<td>$row[skor]</td>
			<td align='center'>
				<a href='data/rule/$row[id]' class='btn btn-primary'>Detail</a>
				<a href='delete/produk/$row[id]' class='delete btn btn-danger'>Hapus</a>
				<a href='edit/produk/$row[id]' class='btn btn-success'>Ubah</a>
			</td>
		</tr>
		";
		$no++;
	}
	?>
</table>