<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdimport extends CI_Model {
	var $name;

	function upload($file){
		$allowedExt = ['xls','xlsx','csv'];

		$ext = $this->def->get_extension($file['name']);
		if(in_array(strtolower($ext),$allowedExt)){
			$fname = "Penjualan-".date("YmdHis").".$ext";
			$this->name = $fname;
			move_uploaded_file($file['tmp_name'], "upload/".$fname);
		}
		else{
			$this->def->pesan("error","Mohon hanya masukkan format file Excel ke kotak yang sudah disediakan.","import");
		}
	}


	function update_db($tgl=null){
		include "class/excel_reader2.php";
		include "class/SpreadsheetReader.php";		

		$del = $this->db->query("TRUNCATE TABLE tb_penjualan");
		$reader = new SpreadsheetReader("upload/".$this->name);
		$no = 1;

		// Nambah" rule banyak aja jadinya kamprettt!, klo mau nambah disini aja
		$allowedEntity = [
			'CW1',
			'CW2',
			'CW3',
			'CW4',
			'CW5',
			'CW6',
			'CW7',
			'CW8', 
			'CW9', 
			'CA0',
			'CA1',
			'CA2',
			'CA3',
			'CA4',
			'CA5',
			'CA6',
			'CA7',
			'CA8',
			'CA9',
			'CB0',
			'CL1',
			'CS1'
		];

		$dissTransaction = [
			'1CWA1',
			'2CWA2',
			'3CWA',
			'4CWA',
			'5CWA',
			'6CWA',
			'7CWA',
			'8CWA',
			'9CWA',
			'ACW10',
			'BA1',
			'DA3',
			'ECA4',
			'FCA5',
			'GCA6',
			'HCA7',
			'ICA8',
			'JCA9',
			'QCL1',
			'1MXG',
			'2MXG',
			'3MXG',
			'4MXG',
			'5MXG',
			'6MXG',
			'7MXG',
			'8MXG',
			'9MXG',
			'AMXG',
			'CA2MXG',
			'EMXG',
			'GMXG',
			'HMXG',
			'JMXG9',
			'QMXG'
		];
		// tes
		//jaga-jaga cwa10-19.. wkwkw || masak ?? wowww
		$query = "INSERT INTO tb_penjualan VALUES";
		foreach($reader as $row){
			$tanggal = date("Y-m-d",strtotime($row[1]));
		
			if(in_array($row['13'],$allowedEntity) && !in_array($row['2'],$dissTransaction)){
				$query .= "(NULL, '$tanggal', '$row[13]', '$row[4]', ".$this->db->escape($row[5]).", '$row[6]', '$row[7]', '$row[9]', '$row[10]', '$row[11]', '$row[12]', '$row[14]', '$row[16]'), ";
			}
		}
		$query = substr($query,0,-2);
		$run = $this->db->query($query.";");

		//masukkan data tabel file
		$cex = $this->db->query("SELECT * FROM tb_file WHERE tgl = ".$this->db->escape($tanggal));
		$arr = array(
			"filename" => $this->name,
			"tgl" => $tanggal,
			"stat" => 1
		);
		if($cex->num_rows() > 0){
			//sudah ada, update
			$row = $cex->row_array();
			$this->db->where("id",$row['id']);
			$this->db->update("tb_file",$arr);
		}
		else{
			//belum ada, insert
			$this->db->insert("tb_file",$arr);
		}

		$this->update_time();
		return true;
	}

	function update_time(){
		$tgl = date("Y-m-d H:i:s");
		$update = $this->db->query("UPDATE tb_setting SET value = '$tgl' WHERE param = 'last_update_penjualan'");
		return true;
	}

	function cek_status_import(){
		$a = date("Y-m-d",strtotime($this->def->setting_echo("last_update_penjualan")));
		$that = date("U",strtotime($a));
		$now = date("U");

		$selisih = $now - $that;
		if($selisih > 86400){
			//data sudah lebih dari sehari.. Harus diupdate
			return false;
		}
		return true;
	}
}