<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MdLogin extends CI_Model {
	var $username, $password, $hash, $token;

	public function set($username,$password){
		$this->username = $username;
		$this->password = $password;
		$this->hash = sha1($password);
	}

	public function cek_user(){
		#MAIN FUNCTION
		$query = $this->db->query("SELECT * FROM tb_user WHERE username = ".$this->db->escape($this->username));
		if($query->num_rows()==1){
			foreach($query->result_array() as $row){
				if($this->cek_password($row['password'])){
					//password betul
					$this->action(0);
				}
				else{
					//password salah
					$this->action(1);
				}
			}
		}
		else{
			//username salah
			$this->action(2);
		}
	}


	public function cek_password($dbpass){
		return $dbpass == $this->hash;
	}

	public function action($kode){
		switch($kode){
			case 0:
				$this->make_sess();
				$this->def->pesan("success","Anda sudah login ke sistem","");
			case 1:
				$this->def->pesan("error","Mohon maaf, password yang Anda masukkan salah. Silakan coba lagi","");
			case 2:
				$this->def->pesan("error","Mohon maaf, Username yang Anda masukkan belum terdaftar. Silakan mendaftar ke Admin dahulu","");
		}
	}

	public function make_sess(){
		$token = sha1(md5(rand(1,5928)));
		$upd = $this->db->simple_query("UPDATE tb_user SET token = '$token' WHERE username = ".$this->db->escape($this->username));
		$this->session->set_userdata("token",$token);
	}


}

/* End of file def.php */
/* Location: ./application/controllers/welcome.php */