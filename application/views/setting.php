<a class="btn btn-primary" id="addBtn">Tambah Data</a>

<div class="row">
	<div class="col-sm-6">
		<div class="box" id="addForm">
			<form action="add/user" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Username</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="text" name="username" maxlength="40" class="form-control" value="<?php $this->def->echo_sess("username")?>">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Nama Lengkap</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="text" name="name" maxlength="40" class="form-control" value="<?php $this->def->echo_sess("name")?>">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Password</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="password" name="password_a" maxlength="40" class="form-control">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Ulangi Password</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<input type="password" name="password_b" maxlength="40" class="form-control">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-6 col-md-4">Priviledge</div>
					<div class="col-xs-8 col-sm-6 col-md-8">
						<select name="priviledge" class="form-control">
							<?php
							if($this->def->get_priviledge()<=1){
								echo "
								<option value='1'>Super Admin</option>
								<option value='2'>Administrator</option>
								";
							}
							echo "<option value='3'>User</option>";
							?>
						</select>
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

<h2>List User</h2>
<table class="data">
	<tr>
		<th>No</th>
		<th>Username</th>
		<th>Nama</th>
		<th>Priviledge</th>
	</tr>
	<?php
	$no = 1;
	foreach($user_list as $row){
		if($row['priviledge'] == 1)
			$p = "Super Admin";
		else if($row['priviledge'] == 2)
			$p = "Administrator";
		else
			$p = "User";
		echo "
		<tr>
			<td>$no</td>
			<td>$row[username]</td>
			<td>$row[name]</td>
			<td>$p</td>
		</tr>
		";
		$no++;
	}
	?>
</table>