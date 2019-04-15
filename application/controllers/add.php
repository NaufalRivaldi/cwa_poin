<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add extends CI_Controller {

	public function karyawan(){
		if(isset($_POST['btn'])){
			$this->load->model("mdkaryawan");
			$list = ["nama","kd_sales","alamat","telp","divisi"];

			$nama = $_POST['nama'];
			$kd_sales = $_POST['kd_sales'];
			$alamat = $_POST['alamat'];
			$telp = $_POST['telp'];
			$divisi = $_POST['divisi'];
			$foto = $_FILES['foto'];

			//filter
			if(!$this->def->validate([$nama,$kd_sales,$divisi])){
				$this->def->create_sess($list);
				$this->def->pesan("error","Mohon isi seluruh field yang sudah disediakan dengan lengkap.","data/karyawan");
			}
			elseif($foto['size'] > $this->def->get_max_upload()){
				$this->def->create_sess($list);
				$this->def->pesan("error","File gambar yang Anda upload terlalu besar. (Max : ".$this->def->setting_echo("max_upload")."KB)","data/karyawan");
			}
			elseif($this->mdkaryawan->cek_karyawans($kd_sales) > 0){
				$this->def->create_sess($list);
				$this->def->pesan("error","Karyawan dengan kode tersebut sudah ada di divisi tersebut. Silakan masukkan kode yang lain","data/karyawan");
			}
			else{
				//proses penyimpanan
				if($foto['error'] == 0){
					//kalau ada upload foto
					$ext = $this->def->get_extension($foto['name']);
					$fname = substr(sha1(rand(0,1000)),0,20).".".$ext;

					move_uploaded_file($foto['tmp_name'], "picture/$fname");
				}
				else{
					//kalau tdk ada yg upload foto
					$fname ="";
				}

				$this->db->query("
					INSERT INTO tb_karyawan VALUES
					(NULL,'$kd_sales','$nama','$alamat','$telp','$divisi','$fname','1');
				");
				$this->def->pesan("success","Berhasil menyimpan data karyawan","data/karyawan");
			}


		}
		else{
			redirect(base_url()."data/karyawan");
		}
	}

	public function produk(){
		if(isset($_POST['btn'])){
			$this->load->model("mdproduk");
			$list = ["rule_name","kd_merk","kd_satuan","kd_golongan","kd_jenis","kd_barang","skor"];

			$rule_name = $_POST['rule_name'];
			$kd_merk = $_POST['kd_merk'];
			$kd_satuan = $_POST['kd_satuan'];
			$kd_golongan = $_POST['kd_golongan'];
			$kd_jenis = $_POST['kd_jenis'];
			$kd_barang = $_POST['kd_barang'];
			$skor = $_POST['skor'];

			if(!$this->def->validate([$rule_name,$skor])){
				$this->def->create_sess($list);
				$this->def->pesan("error","Mohon isi nama kriteria maupun skor dengan lengkap.","data/produk");
			}
			$cek = $this->db->query("SELECT * FROM tb_kriteria WHERE rule_name = ".$this->db->escape($rule_name)." AND stat = 1");
			if($cek->num_rows() <> 0){
				$this->def->create_sess($list);
				$this->def->pesan("error","Nama kriteria tersebut sudah ada. Silakan masukkan dengan nama lain","data/produk");
			}

			if(empty($kd_merk) && empty($kd_satuan) && empty($kd_golongan) && empty($kd_jenis) && empty($kd_barang)){
				$this->def->create_sess($list);
				$this->def->pesan("error","Mohon mengisi minimal salah satu kriteria produk unggulan","data/produk");
			}

			//PROSES INSERT
			$this->db->query("INSERT INTO tb_kriteria VALUES
				(NULL, ".$this->db->escape($rule_name).", ".$this->db->escape($kd_barang).", ".$this->db->escape($kd_merk).", ".$this->db->escape($kd_golongan).", ".$this->db->escape($kd_satuan).", ".$this->db->escape($kd_jenis).", ".$this->db->escape($skor).", '1');
				");
			$this->def->pesan("success","Berhasil menginput data kriteria produk unggulan","data/produk");

		}
	}


	public function user(){
		$this->load->model("mdsetting");
		$username = $_POST['username'];
		$password_a = $_POST['password_a'];
		$password_b = $_POST['password_b'];
		$name = $_POST['name'];
		$priviledge = $_POST['priviledge'];

		//cek
		if(!$this->mdsetting->cek_user($username)){
			$this->def->pesan("error","Username tersebut sudah ada. Silakan coba username lainnya","setting/user");
		}
		else{
			if($password_a <> $password_b){
				$this->def->pesan("error","Password yang anda masukkan tidak sama. Silakan coba lagi dengan hati-hati.","setting/user");
			}
			else if(strlen($password_a) < 6){
				$this->def->pesan("error","Masukkan password dengan minimal 6 karakter. Semakin banyak semakin baik");
			}
			else{
				$pass = sha1($password_a);
				$token = sha1($pass);
				$input = $this->db->query("INSERT INTO tb_user VALUES (".$this->db->escape($username).", ".$this->db->escape($pass).", ".$this->db->escape($name).", ".$this->db->escape($priviledge).", ".$this->db->escape($token).");");
				$this->def->pesan("success","Berhasil menginput data pengguna baru.","setting/user");
			}
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */