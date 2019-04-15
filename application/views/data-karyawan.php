<a class="btn btn-primary" id="addBtn">Tambah Data</a>

<div class="row">
	<div class="col-sm-6">
		<div class="box" id="addForm">
			<form action="add/karyawan" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Nama Karyawan</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="text" name="nama" maxlength="50" class="form-control" value="<?php $this->def->echo_sess("nama")?>">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Kode Karyawan</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="number" maxlength="5" name="kd_sales" class="form-control" value="<?php $this->def->echo_sess("kd_sales")?>">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Alamat</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<textarea name="alamat" class="form-control"><?php $this->def->echo_sess("alamat")?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Telepon</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="tel" name="telp" class="form-control" maxlength="13" value="<?php $this->def->echo_sess("telp")?>">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Divisi</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<select name="divisi" class="form-control">
							<?php
							$list = ['','CW1','CW2','CW3','CW4','CW5','CW6','CW7','CW8','CW9','CA0','CA1','CA2','CA3','CA4','CA5','CA6','CA7','CA8','CA9','CL1','CL2','Gudang'];
							foreach($list as $l){
								echo "<option value='$l'>$l</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Foto</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="file" name="foto" class="form-control" accept="image/*">
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
		<th>Nama Karyawan</th>
		<th>Divisi</th>
		<th>Alamat</th>
		<th>Telepon</th>
		<th>Aksi</th>
	</tr>
	<?php
	$no = 1;
	foreach($result as $row){
		if($row['foto'] == "")
			$loc = "picture/default.jpg";
		else
			$loc = "picture/$row[foto]";
		
		echo "
		<tr>
			<td>$no</td>
			<td><img src='$loc' height=30> $row[nama] ($row[kd_sales])</td>
			<td>$row[divisi]</td>
			<td>$row[alamat]</td>
			<td>$row[telp]</td>
			<td align='center'>
				<a href='delete/karyawan/$row[id]' class='delete btn btn-danger'>Hapus</a>
				<a href='edit/karyawan/$row[id]' class='btn btn-success'>Update</a>
			</td>
		</tr>
		";
		$no++;
	}
	?>
</table>