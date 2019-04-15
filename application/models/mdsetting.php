<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdsetting extends CI_Model {
	function cek_user($username){
		$cek = $this->db->query("SELECT * FROM tb_user WHERE username = ".$this->db->escape($username));
		if($cek->num_rows() == 0)
			return true;
		return false;
	}

	function listAll(){
		$cur = $this->def->get_priviledge();
		if($cur == 1){
			$q = "IN (2,3)";
		}
		else if($cur == 2){
			$q = "IN (3)";
		}
		else{
			$this->def->pesan("error","Priviledge Error. Check your current priviledge","");
		}

		$query = $this->db->query("SELECT * FROM tb_user WHERE priviledge $q");
		return $query->result_array();
	}

	
}