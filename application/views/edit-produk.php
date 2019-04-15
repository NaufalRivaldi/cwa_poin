<?php
foreach($res as $r){
?>
<div class="row">
	<div class="col-sm-6">
		<div id="addForm">
			<form action="edit/proses/produk" method="post" enctype="multipart/form-data">
				<input type="hidden" name="id" value="<?=$r['id']?>">
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Nama Kriteria</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="text" name="rule_name" maxlength="50" class="form-control" value="<?php echo $r['rule_name']?>">
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
									<input type="text" id="kdmr" class="form-control" name="kd_merk" value="<?php echo $r['kd_merk']?>" size=10>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<strong>Kemasan</strong>
								</div>
								<div class="col-xs-6">
									<input type="text" id="kdst" class="form-control" name="kd_satuan" value="<?php echo $r['kd_satuan']?>" size=10>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<strong>Kode Golongan</strong>
								</div>
								<div class="col-xs-6">
									<input type="text" id="kdgl" class="form-control" name="kd_golongan" value="<?php echo $r['kd_golongan']?>" size=10>
								</div>
							</div>
							
							<div class="row">
								<div class="col-xs-6">
									<strong>Kode Jenis</strong>
								</div>
								<div class="col-xs-6">
									<input type="text" id="kdjn" class="form-control" name="kd_jenis" value="<?php echo $r['kd_jenis']?>" size=10>
								</div>
							</div>
						</div>

						<div class="cont_b">
							<div class="row">
								<div class="col-xs-5">
									<strong>Kode Barang</strong>
								</div>
								<div class="col-xs-7">
									<input type="text" id="kdbr" class="form-control" name="kd_barang" value="<?php echo $r['kd_barang']?>" size=50>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Skor</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="number" name="skor" min=0 max=10000 class="form-control" value="<?php echo $r['skor']?>">
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
<?php
break;
}
?>