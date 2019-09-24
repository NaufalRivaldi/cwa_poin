<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdlaporan extends CI_Model {

	function last_history(){
		$sql = "SELECT distinct tgl FROM `tb_history_jual` ORDER BY tgl DESC LIMIT 1";
		$run = $this->db->query($sql);
		$row = $run->row_array();
		return $row['tgl'];
	}

	function cek_status_reindex(){
		$a = date("Y-m-d H:i:s",strtotime($this->def->setting_echo("last_update_penjualan")));
		$that = date("U",strtotime($a));

		$b = date("Y-m-d H:i:s",strtotime($this->def->setting_echo("last_reindex")));
		$these = date("U",strtotime($b));


		if($these < $that){
			return true;
		}
		else{
			return false;
		}
	}

	function create_table_karyawan($divisi="", $order=""){
		$adq = "";
		if($divisi <> ""){
			$adq .= "divisi = ".$this->db->escape($divisi)." AND ";
		}

		switch($order){
			case "1":
				$ord = "ORDER BY divisi, id";
			break;
			case "2" :
				$ord = "ORDER BY nama";
			break;
			default :
				$ord = "ORDER BY divisi, id";

		}


		$query = $this->db->query("SELECT * FROM tb_karyawan WHERE $adq stat <> 0 $ord");
		return $query->result_array();
	}

	function get_score($kd_sales, $divisi, $tgl_a,$tgl_b,$kriteria=""){
		$a = date("Y-m-d",strtotime($tgl_a));
		$b = date("Y-m-d",strtotime($tgl_b));

		//FUNGSI INI AKAN MENGAMBIL NILAI DARI TABEL HISTORY NILAI

		$addLagi = "";
		if($kriteria <> ""){
			$addLagi = "AND ($kriteria)";
		}
		
		$q = $this->db->query("SELECT SUM(skor) AS ttl FROM tb_history_jual WHERE kd_sales = '$kd_sales' AND divisi = '$divisi' AND tgl BETWEEN '$a' AND '$b' AND skor > 0 $addLagi ");
		$rw = $q->row_array();
		$skor = $rw['ttl'];
		
		return $skor;
	}


	function build_kriteria($kriteria){
		if(is_array($kriteria)){
			$arr = array();
			foreach($kriteria as $k){
				array_push($arr,$this->run_krit($k));
			}
			$ret = implode(" OR ", $arr);
			return $ret;
		}
		else
			return $this->run_krit($kriteria);
	}

	function run_krit($item){
		return "kd_barang LIKE ".$this->db->escape("$item%");
	}


	function get_max_score($tgl,$tgl_b,$divisi='',$kriteria=""){
		$x = date("Y-m-d",strtotime($tgl));
		$y = date("Y-m-d",strtotime($tgl_b));

//		$query = $this->db->query("SELECT SUM(skor) AS total_score FROM tb_history_jual WHERE tgl BETWEEN '$x' AND '$y'");
		if($divisi <> ''){

			$addQ = "divisi = ".$this->db->escape($divisi)." AND ";
		}
		else{
			$addQ = "";
		}

		$addLagi = "";
		if($kriteria <> ""){
			$addLagi = "AND ($kriteria)";
		}

		$q = $this->db->query("SELECT SUM(skor) AS total_score FROM tb_history_jual WHERE $addQ tgl BETWEEN '$x' AND '$y' $addLagi GROUP BY divisi,kd_sales  ORDER BY total_score DESC LIMIT 1");
		
		$row = $q->row_array();
		return $row['total_score'];
	}

	public function ex($tg_a,$tg_b,$divisi="",$kriteria=null){
		$indos_1 = $this->def->indo_date($tg_a);
		$indos_2 = $this->def->indo_date($tg_b);

		$indo_1 = date("Ymd",strtotime($tg_a));
		$indo_2 = date("Ymd",strtotime($tg_b));

		if($tg_a == $tg_b){
			$out = $divisi." ".$indo_1;
			$outs = $indos_1;
		}
		else{
			$out = $divisi." $indo_1 - $indo_2";
			$outs = "$indos_1 - $indos_2";
		}


		//PHP Excel Properties
		include "ExcelClass/PHPExcel.php";
		$ex = new PHPExcel();
		//set Document Properties
		$ex->getProperties()->setCreator("Christian Rosandhy")
							->setLastModifiedBy("Christian Rosandhy")
							->setTitle("Skor Produk Unggulan");

		$adaa = "";
		if($divisi <> "")
			$adaa = "Divisi $divisi ";

		$ex ->setActiveSheetIndex(0)
			->setCellValue("A1","Laporan Skor Penjualan Produk Unggulan")
			->setCellValue("A2",$adaa."per tanggal $outs")
			->setCellValue("A3","No")
			->setCellValue("B3","Nama Karyawan")
			->setCellValue("C3","Kode Sales")
			->setCellValue("D3","Divisi")
			->setCellValue("E3","Alamat")
			->setCellValue("F3","Telepon")
			->setCellValue("G3","Skor");

		$addDv = "";
		if($divisi <> "")
			$addDv = "divisi = ".$this->db->escape($divisi)." AND ";

		$query = $this->db->query("SELECT * FROM tb_karyawan WHERE $addDv stat <> 0");

		$c = 5;
		$no = 1;
		foreach($query->result_array() as $row){

			$kr = "";
			if($kriteria <> "" and !is_null($kriteria)){
				$kr = $this->build_kriteria($kriteria);
			}

			$nilai = $this->get_score($row['kd_sales'],$row['divisi'],$tg_a,$tg_b,$kr);

			$ex ->setActiveSheetIndex(0)
				->setCellValue("A$c",$no)
				->setCellValue("B$c",$row['nama'])
				->setCellValue("C$c",$row['kd_sales'])
				->setCellValue("D$c",$row['divisi'])
				->setCellValue("E$c",$row['alamat'])
				->setCellValue("F$c",$row['telp'])
				->setCellValue("G$c",$nilai);
			$no++;
			$c++;
		}

		$ex->getActiveSheet()->setTitle("Skor Produk Unggulan");
		$ex->setActiveSheetIndex(0);
		//Redirecting to Save
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="SkorPU '.$out.' .xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($ex, 'Excel2007');
		$objWriter->save('php://output');	
	}


	function get_karyawan($id){
		$query = $this->db->query("SELECT * FROM tb_karyawan WHERE id = ".$this->db->escape($id));
		return $query->result_array();
	}

	function create_detail($kd_sales,$divisi, $tga, $tgb, $krit=""){

		$kt = "";
		if($krit <> "")
			$kt = "AND (" . $this->build_kriteria($krit) .") ";

		$query = $this->db->query("SELECT * FROM tb_history_jual WHERE kd_sales = ".$this->db->escape($kd_sales)." AND divisi = ".$this->db->escape($divisi)." AND tgl BETWEEN ".$this->db->escape($tga)." AND ". $this->db->escape($tgb)." AND skor > 0 $kt");
		return $query->result_array();
	}

	function get_nama_barang($kd_barang){
		$q = $this->db->query("SELECT nm_barang FROM tb_penjualan WHERE kd_barang = ".$this->db->escape($kd_barang));
		return $q->row_array();
	}











	//NEW FUNCTION
	public function reindex_process(){
		//LANGKAH AWAL : MEMASUKKAN DATA PENJUALAN YANG SESUAI KRITERIA KE TABEL YANG DITUNJUK
		$del = "DELETE FROM tb_history_jual WHERE tgl = (SELECT DISTINCT tgl FROM tb_penjualan)";
		$rundel = $this->db->simple_query($del);
		$arr = [];
		$qwajib = "SELECT * FROM vw_penjualan WHERE ";
		$query = $this->db->query("SELECT * FROM tb_kriteria WHERE stat = 1");
		foreach($query->result_array() as $row){
			if($row['kd_barang'] <> ""){
				$output = "kd_barang = ".$this->db->escape($row['kd_barang']);
			}
			else{
				$out = [];
				if($row['kd_merk'] <> ""){
					array_push($out,"kd_merk = ".$this->db->escape($row['kd_merk']));
				}
				if($row['kd_golongan'] <> ""){
					array_push($out,"kd_golongan = ".$this->db->escape($row['kd_golongan']));
				}
				if($row['kd_satuan'] <> ""){
					array_push($out,"kd_satuan = ".$this->db->escape($row['kd_satuan']));
				}
				if($row['kd_jenis'] <> ""){
					#TAI SALAH KETIK JANCUK
					array_push($out,"kd_jenis = ".$this->db->escape($row['kd_jenis']));
				}

				$output = implode(" AND ",$out);
			}

			//GENERATE QUERY KRITERIA
			$kriteria = $this->db->query($qwajib.$output);

			/*
			10 November 2016
			Logikanya diubah. data history jual tanggal bersangkutan dihapus kalau ada, lalu direwrite yg baru
			*/

			$batch = array();
			foreach($kriteria->result_array() as $r){
				// ambil berat dari penjualan sesuai id
				$brt = $this->db->where('id', $r['id'])->get('tb_penjualan')->row();
				
				$divisi = $r['kd_gudang'];
				$tgl = $r['tgl'];
				$kd_sales = $r['kd_sales'];
				$kd_barang = $r['kd_barang'];
				$divisi = $r['kd_gudang'];
				$jml = $r['jml'];
				$skor = $jml * $row['skor'];
				$brt = $brt->brt;

				$batch[] = array(
					"id" => null,
					"kd_sales" => $kd_sales,
					"tgl" => $tgl,
					"divisi" => $divisi,
					"kd_barang" => $kd_barang,
					"jml" => $jml,
					"skor" => $skor,
					"brt" => $brt
				);

//				$ins = $this->db->query("INSERT INTO tb_history_jual VALUES (NULL, '$kd_sales', '$tgl', '$divisi', '$kd_barang', '$jml', '$skor', '$brt')");
			}

			if(count($batch) > 0)
				$execute_insert = $this->db->insert_batch("tb_history_jual", $batch);


		}

		//SAMPAI DISINI, SEHARUSNYA SEMUA DATA PENJUALAN SUDAH DISESUAIKAN DENGAN HISTORY JUAL.
		$date = date("Y-m-d H:i:s");
		$up = $this->db->query("UPDATE tb_setting SET value = '$date' WHERE param = 'last_reindex'");
		return true;
	}

	public function get_rule(){
		$this->db->where("stat",1);
		$this->db->select("id, rule_name");
		$query = $this->db->get("tb_kriteria");
		return $query->result_array();
	}

	public function get_rule_list($tgl_a, $tgl_b, $filter){

		$krit = array();
		if(count($filter) > 0){
			foreach($filter as $f){
				$krit[] = $f;
			}
		}
		else{
			$krit = array($filter);
		}


		$this->db->where("tgl BETWEEN '$tgl_a' AND '$tgl_b'");
		$n = 0;

		$group = "(";
		$artmp = array();
		foreach($krit as $kr){
			array_push($artmp,"kd_barang LIKE '%$kr%'");
		}
		$imp = implode(" OR ",$artmp);
		$group .= $imp;
		$group .=")";
		

		$this->db->where($group);
		$query = $this->db->get("tb_history_jual");
		return $query->result_array();
	}

	public function kode_convert($input=null){
		$arr = array(
			"PGG" => ["G2.5"],
			"PGP" => ["G20"],
			"PSG" => ["V2.5"],
			"PSP" => ["V20"],
			"BL" => ["BLZ"],
			"EL" => ["EVL"],
			"EC" => ["EVT"],
			"OX" => ["OXG"],
			"TS" => ["TN-SPD"],
			"WDG" => ["WLD05"],
			"WDP" => ["WLD25P"],

		);

		if(!empty($input)){
			if(isset($arr[$input]))
				return $arr[$input];
		}
		else return $arr;
	}

	public function kode(){
		$arr = array(
			"WLD05" => "Weldon Galon",
			"WLD25P" => "Weldon Pail",
			"G2.5" => "Paladin Gold Galon",
			"G20" => "Paladin Gold Pail",
			"V2.5" => "Paladin Silver Galon",
			"V20" => "Paladin Silver Pail",
			"BLZ-" => "Belazo Wood",
			"BLZ2.5-EF" => "Belazo EF Galon",
			"BLZ20-EF" => "Belazo EF Pail",
			"BLZ2.5-ME" => "Belazo ME Galon",
			"BLZ20-ME" => "Belazo ME Pail",
			"EVL" => "Envi Lux",
			"EVT" => "Envi Cat Tembok",
			"OXG" => "Oxygen",
			"TN-SPD" => "Thinner Speedy"
		);
		return $arr;
	}

	public function export_web($json, $tgl_a, $tgl_b, $format="cwa", $dir="export/"){
		if($tgl_a == $tgl_b){
			$txt = date("Y-m-d",strtotime($tgl_a));
		}
		else{
			$txt = date("Y-m-d",strtotime($tgl_a)). "-sd-" . date("Y-m-d",strtotime($tgl_b));
		}

		$filename = "Web-Scoreboard-".$txt.".".$format;
		$isi = json_encode($json);

		//make file to temporary folder
		$file = fopen($dir.$filename,"w");
		fwrite($file, $isi);
		fclose($file);

		//file sudah disimpan, return link upload
		$link = $dir.$filename;
		return $link;
	}

}