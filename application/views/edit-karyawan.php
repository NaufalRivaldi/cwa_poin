<?php
foreach($res as $r){
?>
<div class="row">
	<div class="col-sm-6">
		<form action="edit/proses/karyawan" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?=$r['id']?>">
			<div class="row">
				<div class="col-xs-4 col-sm-6 col-md-4">Nama Karyawan</div>
				<div class="col-xs-8 col-sm-6 col-md-8">
					<input type="text" name="nama" maxlength="50" class="form-control" value="<?php echo $r['nama']?>">
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-sm-6 col-md-4">Kode Karyawan</div>
				<div class="col-xs-8 col-sm-6 col-md-8">
					<input type="number" maxlength="5" name="kd_sales" class="form-control" value="<?php echo $r['kd_sales']?>">
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-sm-6 col-md-4">Alamat</div>
				<div class="col-xs-8 col-sm-6 col-md-8">
					<textarea name="alamat" class="form-control"><?php echo $r['alamat']?></textarea>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-sm-6 col-md-4">Telepon</div>
				<div class="col-xs-8 col-sm-6 col-md-8">
					<input type="tel" name="telp" class="form-control" maxlength="13" value="<?php echo $r['telp']?>">
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-sm-6 col-md-4">Divisi</div>
				<div class="col-xs-8 col-sm-6 col-md-8">
					<select name="divisi" class="form-control">
						<?php
						$list = ['','CW1','CW2','CW3','CW4','CW5','CW6','CW7','CW8','CW9','CA0','CA1','CA2','CA3','CA4','CA5','CA6','CA7','CA8','CA9','CL1','CL2','Gudang'];
						foreach($list as $l){
							if($r['divisi'] == $l)
								$t = "selected";
							else
								$t = "";
							echo "<option value='$l' $t>$l</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-sm-6 col-md-4">Foto</div>
				<div class="col-xs-8 col-sm-6 col-md-8">
					<input type="file" name="foto" class="form-control" accept="image/*">
					<?php
					if(!empty($r['foto'])){
						echo "
						<strong>Foto Saat Ini : </strong>
						<br>
						<img src='picture/$r[foto]' height=200>
						<br>
						";
					}
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-sm-6 col-md-4"></div>
				<div class="col-xs-8 col-sm-6 col-md-8">
					<button name="btn" class="btn btn-primary">Update</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
break;
}
?>