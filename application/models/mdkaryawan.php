<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdkaryawan extends CI_Model {

	function showKaryawan($addQuery=""){
		$query = $this->db->query("SELECT * FROM tb_karyawan $addQuery");
		return $query->result_array();
	}

	function cek_karyawan($kode,$cek_by="kd_sales"){
		$query = $this->db->query("SELECT kd_sales FROM tb_karyawan WHERE $cek_by = ".$this->db->escape($kode)." AND stat = 1");
		return $query->num_rows();
	}

	//fungsi bikinan vangke again.. crutttt!!!!!!!!!
	function cek_karyawans($kode,$divisi){
		$query = $this->db->query("SELECT kd_sales FROM tb_karyawan WHERE kd_sales = ".$this->db->escape($kode)." AND divisi = ". $this->db->escape($divisi) ." AND stat = 1");
		return $query->num_rows();
	}

	function cek_karyawan_spec($kode,$divisi,$id){
		$query = $this->db->query("SELECT kd_sales FROM tb_karyawan WHERE kd_sales = ".$this->db->escape($kode)." AND divisi = " . $this->db->escape($divisi) . " AND id <> $id");
		return $query->num_rows();
	}

	function hide($id){
		$query = $this->db->query("UPDATE tb_karyawan SET stat = 0 WHERE id = '$id'");
		return true;
	}

	function delete_cur_pic($id){
		$this->db->query("UPDATE tb_karyawan SET foto = '' WHERE id = ".$this->db->escape($id));
		return true;
	}

	

}