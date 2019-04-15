<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends CI_Controller {

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
		$this->load->model("mdlaporan");
		$data['title'] = "Import Penjualan";
		$data['menu'] = 2;
		$data['submenu'] = 21;
		$data['state'] = $this->mdimport->cek_status_import();
		$data['reindex'] = $this->mdlaporan->cek_status_reindex();

		$this->load->view("header",$data);
		$this->load->view("import");
		$this->load->view("footer");
	}

	public function process(){
		$this->load->model("mdimport");
		$this->load->model("mdlaporan");
		$a = microtime(true);
		$out = array();
		if(isset($_POST['btn'])){
			$this->mdimport->upload($_FILES['fpenjualan']);
			$out[0] = microtime(true) - $a;
			if($this->mdimport->update_db()){
				$out[1] = microtime(true) - $a;
				$this->mdlaporan->reindex_process();
				$out[2] = microtime(true) - $a;
				$this->def->pesan("success","Berhasil mengupdate data penjualan.<br>Upload : $out[0] detik.<br>Update DB : $out[1] detik.<br>Reindex : $out[2] detik.","import");
			}
			else{
				$this->def->pesan("error","Timeout Reached. Mohon import ulang kembali","import");
			}
		}
	}

	public function directory($dir="upload/Maret/"){
		$this->load->model("mdimport");
		$this->load->model("mdlaporan");
		$list = array_diff(scandir($dir), array('..', '.'));
		foreach($list as $file){
			$this->mdimport->name = $file;
			$this->mdimport->update_db();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */