<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Def extends CI_Model {

	public function is_same($a,$b,$output=true){
		if($a == $b)
			return $output;
		else
			return false;
	}

	public function cek_login(){
		$sess = $this->session->userdata("token");
		if($sess){
			if($this->cek_token($sess)){
				return $this->cek_token($sess);
			}
		}
		return false;

	}
	public function cek_token($tkn){
		$query = $this->db->query("SELECT * FROM tb_user WHERE token = ".$this->db->escape($tkn));
		if($query->num_rows()==1)
			return $query->row_array();
		else
			return false;
	}

	public function page_validator(){
		$x = $this->cek_login();
		if(!$x)
			$this->pesan("error","Mohon maaf, Anda harus login terlebih dahulu.","");
	}


	public function get_priviledge($username=null){
		$row = $this->cek_login();
		$priviledge = $row['priviledge'];

		if(!empty($username)){
			$q = $this->db->query("SELECT * FROM tb_user WHERE username = ".$this->db->escape($username));
			$r = $q->row_array();
			return $r['priviledge'];
		}
		else
			return $priviledge;
	}


	function pesan($tipe,$isipesan,$header=null,$url="rel"){
		$this->session->set_userdata($tipe,$isipesan);
		if(!is_null($header)){
			if($url == "rel")
				redirect(base_url($header));
			else
				redirect($header);
			exit;
		}
	}

	/********************************/
	function pesan_alertify(){
		$type = ["error","success","warning"];
		foreach($type as $tp){
			if($this->session->userdata($tp)){
				echo "alertify.$tp(\"". $this->session->userdata($tp) ."\");";
				$this->session->unset_userdata($tp);
			}
		}
	}

	function tampil_pesan(){
		$type = ["error","success","warning"];
		foreach($type as $tp){
			if($this->session->userdata($tp)){
				echo "<div class='$tp'>";
					echo $this->session->userdata($tp);
				echo "</div>";
				$this->session->unset_userdata($tp);
			}
		}
	}
	

	function get_extension($nmfile){
		$x = explode(".",$nmfile);
		$extention = $x[count($x)-1];
		return $extention;
	}
	function url_origin($s, $use_forwarded_host=false){
	    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
	    $sp = strtolower($s['SERVER_PROTOCOL']);
	    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
	    $port = $s['SERVER_PORT'];
	    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
	    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
	    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
	    return $protocol . '://' . $host . $s['REQUEST_URI'];
	}
	function sess_page(){
		$x = $this->url_origin($_SERVER);
		$this->session->set_userdata("http_referer",$x);
	}

	function http_referer(){
		if(!$this->session->userdata("http_referer"))
			$loc = "";
		else
			$loc = $this->session->userdata("http_referer");
		return $loc;
	}

	function curPageURL() {
		$pageURL = 'http';
		if(isset($_SERVER['HTTPS'])){
			if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	function setting_echo($param){
		$q = $this->db->query("SELECT * FROM tb_setting WHERE param = ".$this->db->escape($param));
		foreach($q->result_array() as $r){
			$val = $r['value'];
		}
		return $val;
	}

	function indo_time($date,$format="Y-m-d H:i:s"){
		$date = date($format,strtotime($date));
		$tgl = date("d",strtotime($date));
		$bln = date("n",strtotime($date));
		$thn = date("Y",strtotime($date));
		$jam = date("H:i:s", strtotime($date));

		$arrBln = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
		$bulan = $arrBln[$bln];

		$mix = "$tgl $bulan $thn $jam";
		return $mix;
	}
	function indo_date($date){
		$date = date("Y-m-d",strtotime($date));
		$tgl = date("d",strtotime($date));
		$bln = date("n",strtotime($date));
		$thn = date("Y",strtotime($date));

		$arrBln = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
		$bulan = $arrBln[$bln];

		$mix = "$tgl $bulan $thn";
		return $mix;
	}

	function get_max_upload(){
		$kb = $this->setting_echo("max_upload");
		$max = $kb * 1024;
		return $max;
	}

	function create_sess($list,$mode="POST"){
		if($mode=="POST"){
			foreach($list as $l){
				$this->session->set_userdata($l,$_POST["$l"]);
			}
		}
		else{
			foreach($list as $l){
				$this->session->set_userdata($l,$_GET["$l"]);
			}
		}
	}

	function echo_sess($sess_name){
		if($this->session->userdata($sess_name)){
			echo $this->session->userdata($sess_name);
			$this->session->unset_userdata($sess_name);
		}
	}

	function validate($arr){
		$val = true;
		foreach($arr as $r){
			if(empty($r))
				$val = false;
		}
		return $val;
	}

	function rupiah($num){
		$x = number_format($num,0,",",".");
		return "Rp ".$x;
	}

	function dump($dmp){
		echo "<textarea style='width:100%; height:300px;'>";
		var_dump($dmp);
		echo "</textarea>";
		exit;
	}

	function runtime($action,$n=0){
		if($action == "start"){
			return microtime(true);
		}
		if($action == "stop"){
			$skrg = microtime(true);
			return $skrg - $n;
		}
	}

	function get_filesize($int){
		$stat = "Byte";
		$return = $int / 1024;
		if($return > 1){
			$stat = "KB";
			$return = $return / 1024;
			if($return > 1){
				$stat = "MB";
			}
			else{
				$return = $return * 1024;
			}
		}
		else{
			$return = $return * 1024;
		}

		return round($return, 2). " " .$stat;
	}
	
}

/* End of file def.php */
/* Location: ./application/controllers/welcome.php */