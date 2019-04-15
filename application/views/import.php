<fieldset>
	<form action="import/process" method="post" enctype="multipart/form-data" class="form-inline">
<!-- 
		Input penjualan per tanggal 
		<input type="date" name="tgl_import" name="import_date" class="form-control" value="<?=date("Y-m-d")?>">
 -->
		<p>Upload file Export Penjualan di kotak dibawah ini : </p>
		<input type="file" name="fpenjualan" id="fpenjualan" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control">
		<br>
		<button class="btn btn-primary" name="btn">Import</button>
		
	</form>
	<em>
		Last import : <?=$this->def->indo_time($this->def->setting_echo("last_update_penjualan"),"Y-m-d H:i:s");?>

		<br>
		<?php if($reindex):?>
		<div class="alert alert-info">
			<span class="fa fa-exclamation" aria-hidden="true"></span>
			<span class="sr-only">Info:</span>
			Pastikan Anda sudah mereindex data penjualan sebelum mengimport data penjualan baru lagi.
		</div>
		<?php endif;?>

		<?php if(!$state):?>
		<div class="alert alert-danger">
			<span class="fa fa-warning" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			Data penjualan sudah tidak update. Mohon inputkan file terbaru.
		</div>
		<?php endif;?>

	</em>
</fieldset>
