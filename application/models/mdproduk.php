<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdproduk extends CI_Model {

	public function showProduk($addQuery){
		$query = $this->db->query("SELECT * FROM tb_kriteria $addQuery");
		return $query->result_array();
	}

	function cekProduk($kode,$cek_by="rule_name"){
		$query = $this->db->query("SELECT * FROM tb_kriteria WHERE $cek_by = ".$this->db->escape($kode));
		return $query->num_rows();
	}

	function hide($id){
		//$query = $this->db->query("UPDATE tb_kriteria SET stat = 0 WHERE id = '$id'");
		$query = $this->db->query("DELETE FROM tb_kriteria WHERE id = '$id'");
		return true;
	}

	function detail($result){
		$arr = array();
		foreach($result as $r){
			if(!empty($r['kd_barang']))
				array_push($arr,"kd_barang = '$r[kd_barang]'");
			else{
				if(!empty($r['kd_merk']))
					array_push($arr,"kd_merk = '$r[kd_merk]'");
				if(!empty($r['kd_golongan']))
					array_push($arr,"kd_golongan = '$r[kd_golongan]'");
				if(!empty($r['kd_satuan']))
					array_push($arr,"kd_satuan = '$r[kd_satuan]'");
				if(!empty($r['kd_jenis']))
					array_push($arr,"kd_jenis = '$r[kd_jenis]'");
			}
		}
		$part = implode(" AND ",$arr);
		$query = $this->db->query("SELECT * FROM tb_penjualan WHERE $part");
		return $query->result_array();
	}

	function get_karyawan($id){
		$query = $this->db->query("SELECT kd_sales, nama FROM tb_karyawan WHERE id = ".$this->db->escape($id));
		if($query->num_rows() == 0)
			return $id;
		else{
			foreach($query->result_array() as $r){
				return $r['nama']." ($r[kd_sales])";
			}
		}
	}

}