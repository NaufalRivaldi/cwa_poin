<!doctype html>
<html lang="en">
<head>
	<base href="<?=base_url()?>">
	<meta charset="UTF-8">
	<title><?php
	if(isset($title)){echo $title." - ";}
	?>Aplikasi Skor Produk Unggulan</title>
	<link rel="stylesheet" href="<?=base_url()?>css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url()?>css/font-awesome.min.css">
	<link rel="stylesheet" href="<?=base_url()?>css/alertify.core.css">
	<link rel="stylesheet" href="<?=base_url()?>css/alertify.default.css">
	<link rel="stylesheet" href="<?=base_url()?>css/chosen.css">
	<link rel="stylesheet" href="<?=base_url()?>css/style.min.css">	
</head>
<Body>
	

<header>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<a href="" class="title">
					<span class="logo"></span>
					Sistem Penilaian Penjualan Produk Unggulan
				</a>
			</div>
			<div class="col-sm-6">
				<ul class="top-nav">
					<li>
						<a href="login/logout" class="logout">Logout</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>

<main>
	<aside>
		<div class="containers">
			<nav>
				<ul>
					<li><a class="first <?=$this->def->is_same($menu,1,"active")?>">Data</a>
						<ul class="submenu <?=$this->def->is_same($menu,1,"showmenu")?>">
							<li><a href="data/karyawan" class=" <?=$this->def->is_same($submenu,11,"active")?>">Karyawan</a></li>
							<li><a href="data/produk" class=" <?=$this->def->is_same($submenu,12,"active")?>">Produk Unggulan</a></li>
						</ul>
					</li>
					<li><a class="first <?=$this->def->is_same($menu,2,"active")?>">Laporan</a>
						<ul class="submenu <?=$this->def->is_same($menu,2,"showmenu")?>">
							<li><a href="import" class="<?=$this->def->is_same($submenu,21,"active")?>">Import Penjualan</a></li>
<!-- 
							<li><a href="laporan/reindex" class=" <?=$this->def->is_same($submenu,22,"active")?>">Reindex</a></li>
 -->
							<li><a href="laporan/skor" class=" <?=$this->def->is_same($submenu,23,"active")?>">Skor Karyawan</a></li>
							<li><a href="laporan/web" class=" <?=$this->def->is_same($submenu,25,"active")?>">Export Skor</a></li>

						</ul>
					</li>
					<?php if($this->def->get_priviledge()==1) :?>
					<li><a class="first <?=$this->def->is_same($menu,3,"active")?>">Setting</a>
						<ul class="submenu <?=$this->def->is_same($menu,3,"showmenu")?>">
							<li><a href="setting" class=" <?=$this->def->is_same($submenu,31,"active")?>">Kode Master</a></li>
							<li><a href="setting/user" class="<?=$this->def->is_same($submenu,32,"active")?>">User Setting</a></li>
						</ul>
					</li>
					<?php
					else:
						echo $this->def->get_priviledge();
					?>
					<?php endif; ?>
				</ul>
			</nav>
		</div>
	</aside>
	<section>
	<div class="container-fluid">
		<h1><?=$title?></h1>