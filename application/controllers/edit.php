<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit extends CI_Controller {


	public function karyawan($id){
		$this->def->page_validator();
		
		$data['title'] = "Edit Data Karyawan";
		$data['menu'] = 1;
		$data['submenu'] = 11;

		$this->load->model("mdkaryawan");
		$data['res'] = $this->mdkaryawan->showKaryawan("WHERE id = ".$this->db->escape($id)." AND stat = 1");

		$this->load->view("header",$data);
		$this->load->view("edit-karyawan");
		$this->load->view("footer");
	}
	public function produk($id){
		$this->def->page_validator();

		$data['title'] = "Edit Kriteria Produk Unggulan";
		$data['menu'] = 1;
		$data['submenu'] = 12;

		$this->load->model("mdproduk");
		$data['res'] = $this->mdproduk->showProduk("WHERE id = ".$this->db->escape($id)." AND stat = 1 ORDER BY skor DESC");

		$this->load->view("header",$data);
		$this->load->view("edit-produk");
		$this->load->view("footer");
	}


	public function proses($part){
		if(isset($_POST['btn'])){
			switch($part){
				case "karyawan":

				//PROSES UPDATE KARYAWAN
				$this->load->model("mdkaryawan");
				$list = ["nama","kd_sales","alamat","telp","divisi"];

				$id = $_POST['id'];
				$nama = $_POST['nama'];
				$kd_sales = $_POST['kd_sales'];
				$alamat = $_POST['alamat'];
				$telp = $_POST['telp'];
				$divisi = $_POST['divisi'];
				$foto = $_FILES['foto'];

				//filter
				if(!$this->def->validate([$nama,$kd_sales,$divisi])){
					$this->def->pesan("error","Mohon isi seluruh field yang sudah disediakan dengan lengkap.","edit/karyawan/$id");
				}
				elseif($foto['size'] > $this->def->get_max_upload()){
					$this->def->pesan("error","File gambar yang Anda upload terlalu besar. (Max : ".$this->def->setting_echo("max_upload")."KB)","edit/karyawan/$id");
				}
				elseif($this->mdkaryawan->cek_karyawan_spec($kd_sales,$divisi,$id) > 0){
					$this->def->pesan("error","Karyawan dengan kode tersebut sudah ada. Silakan masukkan kode yang lain","edit/karyawan/$id");
				}
				else{
					//proses penyimpanan
					if($foto['error'] == 0){
						//kalau ada upload foto
						$ext = $this->def->get_extension($foto['name']);
						$fname = substr(sha1(rand(0,1000)),0,20).".".$ext;

						$this->mdkaryawan->delete_cur_pic($id);
						move_uploaded_file($foto['tmp_name'], "picture/$fname");
						$addQ = "foto = '$fname', ";
					}
					else{
						//kalau tdk ada yg upload foto
						$addQ = "";
					}

					$this->db->query("
						UPDATE tb_karyawan SET nama = ".$this->db->escape($nama).", kd_sales = ".$this->db->escape($kd_sales).", alamat = ".$this->db->escape($alamat).", telp = ".$this->db->escape($telp).", $addQ divisi = ".$this->db->escape($divisi)." WHERE id = ".$this->db->escape($id));
					$this->def->pesan("success","Berhasil mengupdate data karyawan","data/karyawan");
				}

				break;

				case "produk" :

				$this->load->model("mdproduk");
				$list = ["rule_name","kd_merk","kd_satuan","kd_golongan","kd_jenis","kd_barang","skor"];

				$id = $_POST['id'];
				$rule_name = strtoupper($_POST['rule_name']);
				$kd_merk = $_POST['kd_merk'];
				$kd_satuan = $_POST['kd_satuan'];
				$kd_golongan = $_POST['kd_golongan'];
				$kd_jenis = $_POST['kd_jenis'];
				$kd_barang = $_POST['kd_barang'];
				$skor = $_POST['skor'];

				if(!$this->def->validate([$rule_name,$skor])){
					$this->def->pesan("error","Mohon isi nama kriteria maupun skor dengan lengkap.","edit/produk/$id");
				}
				$cek = $this->db->query("SELECT * FROM tb_kriteria WHERE rule_name = ".$this->db->escape($rule_name)." AND stat = 1 AND id <> ".$this->db->escape($id));
				if($cek->num_rows() <> 0){
					$this->def->pesan("error","Nama kriteria tersebut sudah ada. Silakan masukkan dengan nama lain","edit/produk/$id");
				}

				if(empty($kd_merk) && empty($kd_satuan) && empty($kd_golongan) && empty($kd_jenis) && empty($kd_barang)){
					$this->def->pesan("error","Mohon mengisi minimal salah satu kriteria produk unggulan","edit/produk/$id");
				}

				//PROSES INSERT
				$this->db->query("
				UPDATE tb_kriteria SET
					rule_name = ".$this->db->escape($rule_name).",
					kd_barang = ".$this->db->escape($kd_barang).",
					kd_merk = ".$this->db->escape($kd_merk).",
					kd_golongan = ".$this->db->escape($kd_golongan).",
					kd_satuan = ".$this->db->escape($kd_satuan).",
					kd_jenis = ".$this->db->escape($kd_jenis).",
					skor = ".$this->db->escape($skor)."
				WHERE id = ".$this->db->escape($id)."
				");
				$this->def->pesan("success","Berhasil mengupdate data kriteria produk unggulan","data/produk");

				break;
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */