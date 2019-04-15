<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller {

	public function reindex(){
		$this->def->page_validator();
		$this->load->model("mdlaporan");
		$data['title'] = "Reindex Data";
		$data['menu'] = 2;
		$data['submenu'] = 22;

		$data['index_stat'] = $this->mdlaporan->cek_status_reindex();

		$this->load->view("header",$data);
		$this->load->view("reindex");
		$this->load->view("footer");
	}


	public function reindexnow(){
		$this->load->model("mdlaporan");
		$this->load->model("mdproduk");

		$this->mdlaporan->reindex_process();
		$this->def->pesan("success","Berhasil mereindex data","laporan/reindex");
	}

	public function reindexnow_old(){
		$this->load->model("mdlaporan");
		$this->load->model("mdproduk");

		$fulldate = date("Y-m-d H:i:s"); //untuk setting
//			$date = date("Y-m-d"); //untuk field tanggal

		$q = $this->db->query("SELECT id FROM tb_kriteria WHERE stat = 1");
		foreach($q->result_array() as $r){
			$x = $this->mdproduk->showProduk("WHERE id = '$r[id]'");
			$y = $this->mdproduk->detail($x);
			//hasil dari masing-masing detail tsb disimpan ke tabel
			foreach($y as $z){
				$tanggals = $z['tgl'];
				$input = $this->db->query("
				INSERT INTO tb_rekap_penjualan VALUES
				(NULL, ".$this->db->escape($tanggals).", ".$this->db->escape($z['kd_gudang']).", ".$this->db->escape($z['kd_barang']).", ".$this->db->escape($z['nm_barang']).", ".$this->db->escape($z['jml']).", ".$this->db->escape($z['harga']).", ".$this->db->escape($z['kd_merk']).", ".$this->db->escape($z['kd_golongan']).", ".$this->db->escape($z['kd_satuan']).", ".$this->db->escape($z['kd_jenis']).", ".$this->db->escape($z['kd_sales']).");
				");
			}
		}


		
		//dari masing-masing karyawan, langsung tentukan skornya
		$kary = $this->mdlaporan->create_table_karyawan();
		foreach($kary as $rk){
			$skor = $this->mdlaporan->get_produk_by_sales($rk['kd_sales'],$rk['divisi'],$tanggals);
		}
		

		exit;
		$this->db->query("UPDATE tb_setting SET value = '$fulldate' WHERE param = 'last_reindex'");

		//truncate tb_rekap ??? 
		#hanya hapus tanggal yang bersangkutan
		 $ex = $this->db->query("DELETE FROM tb_rekap_penjualan WHERE tgl = '$tanggals'");


		$this->def->pesan("success","Berhasil mereindex data penjualan","laporan/reindex");
		
	}


	public function web(){
		$this->def->page_validator();
		$this->load->model("mdlaporan");

		$data['title'] = "Export Data Scoreboard ke Website";
		$data['menu'] = 2;
		$data['submenu'] = 25;
		$data['now'] = $this->mdlaporan->last_history();

		$this->load->view("header",$data);
		$this->load->view("web");
		$this->load->view("footer");

	}




	public function skor(){
		$this->def->page_validator();
		$this->load->model("mdlaporan");
		$start = $this->def->runtime("start");
		$data['title'] = "Laporan Skor Penjualan";
		$data['menu'] = 2;
		$data['submenu'] = 23;

		$data['now'] = date("Y-m-d");
		if(isset($_GET['divisi']) and isset($_GET['order'])){
			$data['table'] = $this->mdlaporan->create_table_karyawan($_GET['divisi'],$_GET['order']);
		}
		else{
			$data['table'] = $this->mdlaporan->create_table_karyawan();
		}


		$start = $this->def->runtime("start");
		$this->load->view("header",$data);
		$this->load->view("skor");
		$data['stop'] = $this->def->runtime("stop",$start);
		$this->load->view("footer",$data);
	}

	public function export($tg_a,$tg_b){
		$this->load->model("mdlaporan");

		$divisi = $_GET['divisi'];

		if(isset($_GET['kriteria'])){
			$this->mdlaporan->ex($tg_a,$tg_b,$divisi,$_GET['kriteria']);
		}
		else
			$this->mdlaporan->ex($tg_a,$tg_b,$divisi);

	}

	public function detail($id){
		$this->def->page_validator();
		$this->load->model("mdlaporan");
		$data['title'] = "Detail Penjualan Karyawan";
		$data['menu'] = 2;
		$data['submenu'] = 23;

		$data['now'] = date("Y-m-d");
		$data['row'] = $this->mdlaporan->get_karyawan($id);

		$this->load->view("header",$data);
		$this->load->view("detail");
		$this->load->view("footer");
	}

	public function produk(){
		$this->def->page_validator();
		$this->load->model("mdlaporan");
		$data['title'] = "Laporan Analisa Index Produk";
		$data['menu'] = 2;
		$data['submenu'] = 24;
		$data['now'] = date("Y-m-d");

		$data['rule'] = $this->mdlaporan->get_rule();

		if(isset($_GET['tgl_a']) && isset($_GET['tgl_b']) && isset($_GET['kriteria'])){
			$data['hasil'] = $this->mdlaporan->get_rule_list($_GET['tgl_a'], $_GET['tgl_b'], $_GET['kriteria']);
		}

		$this->load->view("header",$data);
		$this->load->view("indexproduk");
		$this->load->view("footer");
	}
}