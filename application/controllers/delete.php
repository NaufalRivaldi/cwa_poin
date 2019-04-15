<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delete extends CI_Controller {

	public function karyawan($id){
		$this->load->model("mdkaryawan");
		if($this->mdkaryawan->cek_karyawan($id,"id")){
			$this->mdkaryawan->hide($id);
		}
		$this->def->pesan("success","Berhasil menghapus data karyawan","data/karyawan");
	}

	public function produk($id){
		$this->load->model("mdproduk");
		if($this->mdproduk->cekProduk($id,"id")){
			$this->mdproduk->hide($id);
		}
		$this->def->pesan("success","Berhasil menghapus data kriteria produk","data/produk");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */