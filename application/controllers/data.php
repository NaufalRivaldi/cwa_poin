<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->def->page_validator();
		$this->load->model("mdimport");
		$data['title'] = "Import Penjualan";
		$data['menu'] = 0;
		$data['submenu'] = 0;
		$data['state'] = $this->mdimport->cek_status_import();

		$this->load->view("header",$data);
		$this->load->view("import");
		$this->load->view("footer");
	}

	public function karyawan(){
		$this->def->page_validator();
		$data['title'] = "Data Karyawan";
		$data['menu'] = 1;
		$data['submenu'] = 11;

		$this->load->model("mdkaryawan");
		$data['result'] = $this->mdkaryawan->showKaryawan("WHERE stat = 1 ORDER BY divisi, nama");

		$this->load->view("header",$data);
		$this->load->view("data-karyawan");
		$this->load->view("footer");
	}

	public function produk(){
		$this->def->page_validator();
		$data['title'] = "Data Produk Unggulan";
		$data['menu'] = 1;
		$data['submenu'] = 12;

		$this->load->model("mdproduk");
		$data['result'] = $this->mdproduk->showProduk("WHERE stat = 1 ORDER BY rule_name ASC");

		$this->load->view("header",$data);
		$this->load->view("data-produk");
		$this->load->view("footer");
	}

	public function json($type){
		if(empty($_POST['term'])) exit;

		$q= strtolower($_POST['term']);
		if (get_magic_quotes_gpc()) $q = stripslashes($q);

		$arr = array();

		switch($type){
			case "kdbr" : 
				$query = $this->db->query("SELECT DISTINCT kd_barang FROM tb_penjualan WHERE kd_barang LIKE '$q%' LIMIT 10");
				if($query->num_rows() == 0){
					$query = $this->db->query("SELECT DISTINCT kd_barang FROM tb_penjualan WHERE kd_barang LIKE '%$q%' LIMIT 10");
				}
				foreach($query->result_array() as $r){
					array_push($arr,$r['kd_barang']);
				}
			break;
			
			case "kdmr" : 
				$query = $this->db->query("SELECT DISTINCT kd_merk FROM tb_penjualan WHERE kd_merk LIKE '$q%' LIMIT 10");
				if($query->num_rows() == 0){
					$query = $this->db->query("SELECT DISTINCT kd_merk FROM tb_penjualan WHERE kd_merk LIKE '%$q%' LIMIT 10");
				}
				foreach($query->result_array() as $r){
					array_push($arr,$r['kd_merk']);
				}
			break;


			case "kdst" : 
				$query = $this->db->query("SELECT DISTINCT kd_satuan FROM tb_penjualan WHERE kd_satuan LIKE '$q%' LIMIT 10");
				if($query->num_rows() == 0){
					$query = $this->db->query("SELECT DISTINCT kd_satuan FROM tb_penjualan WHERE kd_satuan LIKE '%$q%' LIMIT 10");
				}
				foreach($query->result_array() as $r){
					array_push($arr,$r['kd_satuan']);
				}
			break;

			case "kdgl" : 
				$query = $this->db->query("SELECT DISTINCT kd_golongan FROM tb_penjualan WHERE kd_golongan LIKE '$q%' LIMIT 10");
				if($query->num_rows() == 0){
					$query = $this->db->query("SELECT DISTINCT kd_golongan FROM tb_penjualan WHERE kd_golongan LIKE '%$q%' LIMIT 10");
				}
				foreach($query->result_array() as $r){
					array_push($arr,$r['kd_golongan']);
				}
			break;

			case "kdjn" : 
				$query = $this->db->query("SELECT DISTINCT kd_jenis FROM tb_penjualan WHERE kd_jenis LIKE '$q%' LIMIT 10");
				if($query->num_rows() == 0){
					$query = $this->db->query("SELECT DISTINCT kd_jenis FROM tb_penjualan WHERE kd_jenis LIKE '%$q%' LIMIT 10");
				}
				foreach($query->result_array() as $r){
					array_push($arr,$r['kd_jenis']);
				}
			break;
			
		}

		echo json_encode($arr);

	}


	public function rule($id){
		$data['title'] = "Detail Produk Unggulan";
		$data['menu'] = 1;
		$data['submenu'] = 12;

		$this->load->model("mdproduk");
		$data['res'] = $this->mdproduk->showProduk("WHERE id = ".$this->db->escape($id)." AND stat = 1");
		$data['table'] = $this->mdproduk->detail($data['res']);

		$this->load->view("header",$data);
		$this->load->view("kriteria-penjualan");
		$this->load->view("footer");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */