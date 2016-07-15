<?php
	if(!defined('BASEPATH'))exit('No direct script access allowed');
	class Scan extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->load->model('model_db');
		}
		
		function index(){
			//$this->load->library('ci_phpgrid');
			//$data['phpgrid'] = $this->ci_phpgrid->grid_faktur();
			$this->load->view('scan/header');
			$this->load->view('scan/index');
			$this->load->view('scan/footer');
		}
		
		function delete_data(){
			//$id = $this->uri->segment(4);
			$id2 = $this->input->post('id');
			$query1 = $this->db->query("DELETE FROM tblExportEvanHeadTemp where no_seri='".$id2."'");
			$query2 = $this->db->query("DELETE FROM tblExportEvanDetailTemp where no_seri_fk='".$id2."'");
			if($query1 and $query2){
				$data = array(
					'status' => 'Sukses',
					'color' => 'FF6B6B'
				);
				echo json_encode($data);
			}else{
				$data = array(
					'status' => 'Fail',
					'color' => 'FF6B6B'
				);
				echo json_encode($data);
			}
		}
		
		function total_temp_scan(){
			//$id = $this->input->post("id");
			$query = $this->db->query("select sum(CAST(jumlah_dpp AS BIGINT)) as a, sum(CAST(jumlah_ppn AS BIGINT)) as b, count(no_seri) as c from tblExportEvanHeadTemp");
			$data = array();
			foreach($query->result() as $db){
				$data[] = $db; 
			}
			echo json_encode($data);
		}
		
		function get_items_grid(){
			//$id = $this->input->post("id");
			$queryGrid = $this->db->query("select * from tblExportEvanHeadTemp order by no_seri asc");
			$data1 = array();
			foreach($queryGrid->result() as $grid){
				$data1[] = $grid; 
			}
			echo json_encode($data1);
		}
		
		function filter(){
			$id = $this->input->post("filter");
			$queryGrid = $this->db->query("select * from tblExportEvanHeadTemp where no_seri like '%".$id."%' order by no_seri asc");
			$data1 = array();
			foreach($queryGrid->result() as $grid){
				$data1[] = $grid; 
			}
			echo json_encode($data1);
		}
		
		/*function proses(){
		  $url = $this->input->post('scan');
		  $masa = $this->input->post('masa_pajak');
		  $tahun = $this->input->post('tahun_pajak');
		  if($url != ''){
		  //$url = $this->input->post('scan');
		  $xml = simplexml_load_file($url) or die("feed not loading");
		  
		  //looping header ke dalam array
		  foreach ($xml->children() as $child)
		  {
			  $data[] = $child;
		  }
		  
		  for($i = 0;$i < count($data);$i++){
			  $data[$i];
		  }
		  
		  //set up tanggal dan query header
		  $header['kd_jenis_transaksi'] = $data[0];
		  $header['fg_pengganti'] = $data[1];
		  $header['no_seri'] = $data[2];
		  $thn = substr($data[3],6,4);
		  $bln = substr($data[3],3,2);
		  $tgl = substr($data[3],0,2);
		  $header['tgl_faktur'] = date('Y-m-d',strtotime($thn.'-'.$bln.'-'.$tgl));
		  $header['npwp_penjual'] = $data[4];
		  $header['nama_penjual'] = $data[5];
		  $header['alamat_penjual'] = $data[6];
		  $header['npwp_lawan_transaksi'] = $data[7];
		  $header['nama_lawan_transaksi'] = $data[8];
		  $header['alamat_lawan_transaksi'] = $data[9];
		  $header['jumlah_dpp'] = $data[10];
		  $header['jumlah_ppn'] = $data[11];
		  $header['jumlah_ppnbm'] = $data[12];
		  $header['status_approval'] = $data[13];
		  $header['status_faktur'] = $data[14];
		  date_default_timezone_set('Asia/Jakarta');
		  $scan_dt = date("Y-m-d H:i:s");
		  //Looping detail faktur
		  $cek1 = $this->db->query("select * from tblExportEvanHeadTemp where kd_jenis_transaksi='".$data[0]."' and fg_pengganti='".$data[1]."' and no_seri='".$data[2]."'");
		  if($cek1->num_rows() == 0){
				$cek2 = $this->db->query("select * from tblExport_Faktur_Header where kd_jenis_transaksi='".$data[0]."' and fg_pengganti='".$data[1]."' and no_seri='".$data[2]."'");
				if($cek2->num_rows() == 0){
					  $query_header = $this->db->query("insert into tblExportEvanHeadTemp 
					  (kd_jenis_transaksi,fg_pengganti,no_seri,tgl_faktur,masa_pajak,tahun_pajak,npwp_penjual,nama_penjual,alamat_penjual,npwp_lawan_transaksi,
					  nama_lawan_transaksi,alamat_lawan_transaksi,jumlah_dpp,jumlah_ppn,jumlah_ppnbm,status_approval,status_faktur,status_generate,tgl_scan) VALUES 
					  (".$data[0].",".$data[1].",'".$data[2]."','".date('Y-m-d',strtotime($thn.'-'.$bln.'-'.$tgl))."','".$masa."','".$tahun."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."'
					  ,".$data[10].",".$data[11].",".$data[12].",'".$data[13]."','".$data[14]."','Success','".$scan_dt."')");
					  
					  //Detail faktur looping array
					  for($j = 0;$j < count($xml->detailTransaksi);$j++){
						  $i = 0;
						  foreach ($xml->detailTransaksi[$j]->children() as $child)
						  {
							  $data1[$j][$i] = $child;
							  $i++;
						  }
					  }
					  //query detail faktur
					  for($j = 0;$j < count($xml->detailTransaksi);$j++){
						  $detail['no_seri_fk'] = $header['no_seri'];
						  $detail['nama'] = $data1[$j][0];
						  $detail['harga_satuan'] = $data1[$j][1];
						  $detail['jumlah_barang'] = $data1[$j][2];
						  $detail['harga_total'] = $data1[$j][3];
						  $detail['diskon'] = $data1[$j][4];
						  $detail['dpp'] = $data1[$j][5];
						  $detail['ppn'] = $data1[$j][6];
						  $detail['tarif_ppnbm'] = $data1[$j][7];
						  $detail['ppnbm'] = $data1[$j][8];
						  
						  $query_detail = $this->db->query("insert into tblExportEvanDetailTemp (no_seri_fk,nama,harga_satuan,jumlah_barang,harga_total,diskon,dpp,ppn,tarif_ppnbm,ppnbm) 
						  values ('".$header['no_seri']."','".$data1[$j][0]."','".$data1[$j][1]."','".$data1[$j][2]."','".$data1[$j][3]."','".$data1[$j][4]."','".$data1[$j][5]."','".$data1[$j][6]."','".$data1[$j][7]."','".$data1[$j][8]."')");
					  }
					  
					  if($query_header){
						$dataz = array(
							'status' => 'Sukses',
							'color' => 'FF6B6B'
						);
						echo json_encode($dataz);
					  }else{
						$dataz = array(
							'status' => 'Failed',
							'color' => 'FF6B6B'
						);
						echo json_encode($dataz);
					  }
				}else{
					$query_header = $this->db->query("insert into tblExportEvanHeadTemp 
					  (kd_jenis_transaksi,fg_pengganti,no_seri,tgl_faktur,masa_pajak,tahun_pajak,npwp_penjual,nama_penjual,alamat_penjual,npwp_lawan_transaksi,
					  nama_lawan_transaksi,alamat_lawan_transaksi,jumlah_dpp,jumlah_ppn,jumlah_ppnbm,status_approval,status_faktur,status_generate,tgl_scan) VALUES 
					  (".$data[0].",".$data[1].",'".$data[2]."','".date('Y-m-d',strtotime($thn.'-'.$bln.'-'.$tgl))."','".$masa."','".$tahun."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."'
					  ,".$data[10].",".$data[11].",".$data[12].",'".$data[13]."','".$data[14]."','Available','".$scan_dt."')");
					  
					  //Detail faktur looping array
					  for($j = 0;$j < count($xml->detailTransaksi);$j++){
						  $i = 0;
						  foreach ($xml->detailTransaksi[$j]->children() as $child)
						  {
							  $data1[$j][$i] = $child;
							  $i++;
						  }
					  }
					  //query detail faktur
					  for($j = 0;$j < count($xml->detailTransaksi);$j++){
						  $detail['no_seri_fk'] = $header['no_seri'];
						  $detail['nama'] = $data1[$j][0];
						  $detail['harga_satuan'] = $data1[$j][1];
						  $detail['jumlah_barang'] = $data1[$j][2];
						  $detail['harga_total'] = $data1[$j][3];
						  $detail['diskon'] = $data1[$j][4];
						  $detail['dpp'] = $data1[$j][5];
						  $detail['ppn'] = $data1[$j][6];
						  $detail['tarif_ppnbm'] = $data1[$j][7];
						  $detail['ppnbm'] = $data1[$j][8];
						  
						  $query_detail = $this->db->query("insert into tblExportEvanDetailTemp (no_seri_fk,nama,harga_satuan,jumlah_barang,harga_total,diskon,dpp,ppn,tarif_ppnbm,ppnbm) 
						  values ('".$header['no_seri']."','".$data1[$j][0]."','".$data1[$j][1]."','".$data1[$j][2]."','".$data1[$j][3]."','".$data1[$j][4]."','".$data1[$j][5]."','".$data1[$j][6]."','".$data1[$j][7]."','".$data1[$j][8]."')");
					  }
					$dataz = array(
						'status' => 'Available',
						'color' => 'FF6B6B'
					);
					echo json_encode($dataz);
				}
			  }else{
				  $dataz = array(
					'status' => 'Double',
					'color' => 'FF6B6B'
				  );
				  echo json_encode($dataz);
			  }
		  }else{
			  $dataz = array(
				'status' => 'Empty',
				'color' => 'FF6B6B'
			  );
			  echo json_encode($dataz);
		  }
		  }*/
		
		function proses(){
		  $url = $this->input->post('scan');
		  $masa = $this->input->post('masa_pajak');
		  $tahun = $this->input->post('tahun_pajak');
		  if($url != ''){
		  //$url = $this->input->post('scan');
		  $xml = simplexml_load_file($url) or die("feed not loading");
		  
		  //looping header ke dalam array
		  foreach ($xml->children() as $child)
		  {
			  $data[] = $child;
		  }
		  
		  for($i = 0;$i < count($data);$i++){
			  $data[$i];
		  }
		  
		  //set up tanggal dan query header
		  $header['kd_jenis_transaksi'] = $data[0];
		  $header['fg_pengganti'] = $data[1];
		  $header['no_seri'] = $data[2];
		  $thn = substr($data[3],6,4);
		  $bln = substr($data[3],3,2);
		  $tgl = substr($data[3],0,2);
		  
		  //input2
		  $header['tgl_faktur'] = date('Y-m-d',strtotime($thn.'-'.$bln.'-'.$tgl));
		  $input2 = date('Y-F', strtotime($thn.'-'.$bln));
		  $header['npwp_penjual'] = $data[4];
		  $header['nama_penjual'] = $data[5];
		  $header['alamat_penjual'] = $data[6];
		  $header['npwp_lawan_transaksi'] = $data[7];
		  $header['nama_lawan_transaksi'] = $data[8];
		  $header['alamat_lawan_transaksi'] = $data[9];
		  $header['jumlah_dpp'] = $data[10];
		  $header['jumlah_ppn'] = $data[11];
		  $header['jumlah_ppnbm'] = $data[12];
		  $header['status_approval'] = $data[13];
		  $header['status_faktur'] = $data[14];
		  date_default_timezone_set('Asia/Jakarta');
		  //input1
		  $scan_dt = date("Y-m-d H:i:s");
		  $input = date('Y-F');
		  //Looping detail faktur
		  
		  //input3
		  $input3 = date("Y-F",strtotime("-3 Months", strtotime($input)));
		  
		  if(strtotime($input2) < strtotime($input3)){
			  $dataz = array(
				'status' => 'Expired',
				'color' => 'FF6B6B'
			  );
			  echo json_encode($dataz);
		  }else if(strtotime($input2) >= strtotime($input)){
			  $dataz = array(
				'status' => 'NotYet',
				'color' => 'FF6B6B'
			  );
			  echo json_encode($dataz);
		  }else{
			  $cek1 = $this->db->query("select * from tblExportEvanHeadTemp where kd_jenis_transaksi='".$data[0]."' and fg_pengganti='".$data[1]."' and no_seri='".$data[2]."'");
			  if($cek1->num_rows() == 0){
					$cek2 = $this->db->query("select * from tblExport_Faktur_Header where kd_jenis_transaksi='".$data[0]."' and fg_pengganti='".$data[1]."' and no_seri='".$data[2]."'");
					if($cek2->num_rows() == 0){
						  $query_header = $this->db->query("insert into tblExportEvanHeadTemp 
						  (kd_jenis_transaksi,fg_pengganti,no_seri,tgl_faktur,masa_pajak,tahun_pajak,npwp_penjual,nama_penjual,alamat_penjual,npwp_lawan_transaksi,
						  nama_lawan_transaksi,alamat_lawan_transaksi,jumlah_dpp,jumlah_ppn,jumlah_ppnbm,status_approval,status_faktur,status_generate,tgl_scan) VALUES 
						  (".$data[0].",".$data[1].",'".$data[2]."','".date('Y-m-d',strtotime($thn.'-'.$bln.'-'.$tgl))."','".$masa."','".$tahun."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."'
						  ,".$data[10].",".$data[11].",".$data[12].",'".$data[13]."','".$data[14]."','Success','".$scan_dt."')");
						  
						  //Detail faktur looping array
						  for($j = 0;$j < count($xml->detailTransaksi);$j++){
							  $i = 0;
							  foreach ($xml->detailTransaksi[$j]->children() as $child)
							  {
								  $data1[$j][$i] = $child;
								  $i++;
							  }
						  }
						  //query detail faktur
						  for($j = 0;$j < count($xml->detailTransaksi);$j++){
							  $detail['no_seri_fk'] = $header['no_seri'];
							  $detail['nama'] = $data1[$j][0];
							  $detail['harga_satuan'] = $data1[$j][1];
							  $detail['jumlah_barang'] = $data1[$j][2];
							  $detail['harga_total'] = $data1[$j][3];
							  $detail['diskon'] = $data1[$j][4];
							  $detail['dpp'] = $data1[$j][5];
							  $detail['ppn'] = $data1[$j][6];
							  $detail['tarif_ppnbm'] = $data1[$j][7];
							  $detail['ppnbm'] = $data1[$j][8];
							  
							  $query_detail = $this->db->query("insert into tblExportEvanDetailTemp (no_seri_fk,nama,harga_satuan,jumlah_barang,harga_total,diskon,dpp,ppn,tarif_ppnbm,ppnbm) 
							  values ('".$header['no_seri']."','".$data1[$j][0]."','".$data1[$j][1]."','".$data1[$j][2]."','".$data1[$j][3]."','".$data1[$j][4]."','".$data1[$j][5]."','".$data1[$j][6]."','".$data1[$j][7]."','".$data1[$j][8]."')");
						  }
						  
						  if($query_header){
							$dataz = array(
								'status' => 'Sukses',
								'color' => 'FF6B6B'
							);
							echo json_encode($dataz);
						  }else{
							$dataz = array(
								'status' => 'Failed',
								'color' => 'FF6B6B'
							);
							echo json_encode($dataz);
						  }
					}else{
						$query_header = $this->db->query("insert into tblExportEvanHeadTemp 
						  (kd_jenis_transaksi,fg_pengganti,no_seri,tgl_faktur,masa_pajak,tahun_pajak,npwp_penjual,nama_penjual,alamat_penjual,npwp_lawan_transaksi,
						  nama_lawan_transaksi,alamat_lawan_transaksi,jumlah_dpp,jumlah_ppn,jumlah_ppnbm,status_approval,status_faktur,status_generate,tgl_scan) VALUES 
						  (".$data[0].",".$data[1].",'".$data[2]."','".date('Y-m-d',strtotime($thn.'-'.$bln.'-'.$tgl))."','".$masa."','".$tahun."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."'
						  ,".$data[10].",".$data[11].",".$data[12].",'".$data[13]."','".$data[14]."','Available','".$scan_dt."')");
						  
						  //Detail faktur looping array
						  for($j = 0;$j < count($xml->detailTransaksi);$j++){
							  $i = 0;
							  foreach ($xml->detailTransaksi[$j]->children() as $child)
							  {
								  $data1[$j][$i] = $child;
								  $i++;
							  }
						  }
						  //query detail faktur
						  for($j = 0;$j < count($xml->detailTransaksi);$j++){
							  $detail['no_seri_fk'] = $header['no_seri'];
							  $detail['nama'] = $data1[$j][0];
							  $detail['harga_satuan'] = $data1[$j][1];
							  $detail['jumlah_barang'] = $data1[$j][2];
							  $detail['harga_total'] = $data1[$j][3];
							  $detail['diskon'] = $data1[$j][4];
							  $detail['dpp'] = $data1[$j][5];
							  $detail['ppn'] = $data1[$j][6];
							  $detail['tarif_ppnbm'] = $data1[$j][7];
							  $detail['ppnbm'] = $data1[$j][8];
							  
							  $query_detail = $this->db->query("insert into tblExportEvanDetailTemp (no_seri_fk,nama,harga_satuan,jumlah_barang,harga_total,diskon,dpp,ppn,tarif_ppnbm,ppnbm) 
							  values ('".$header['no_seri']."','".$data1[$j][0]."','".$data1[$j][1]."','".$data1[$j][2]."','".$data1[$j][3]."','".$data1[$j][4]."','".$data1[$j][5]."','".$data1[$j][6]."','".$data1[$j][7]."','".$data1[$j][8]."')");
						  }
						$dataz = array(
							'status' => 'Available',
							'color' => 'FF6B6B'
						);
						echo json_encode($dataz);
					}
			  }else{
				  $dataz = array(
					'status' => 'Double',
					'color' => 'FF6B6B'
				  );
				  echo json_encode($dataz);
			  }
			  
		  }
		  
		  }else{
			  $dataz = array(
				'status' => 'Empty',
				'color' => 'FF6B6B'
			  );
			  echo json_encode($dataz);
		  }
		}
		
		
		function overwrite($id){			
			$id = $this->uri->segment(3);
			$query = $this->db->query("update tbl_ANPKSP_RealisasiANP set Req_Status='Canceled', UpdateBy='".$this->session->userdata('nama')."' where ObjectID='".$id2."'");
			if($query){
				$this->session->set_flashdata('delete_result',"<div style='display:inline;'><i class='fa fa-check-circle' style='margin-right:3px;color:#1d9d74'></i> The record was deleted!</div>");
				Header('Location:'.base_url().'index.php/application/req_realisasi/grid_modify/'.$id);
			}else{
				$this->session->set_flashdata('delete_result',"<div style='display:inline;'><i class='fa fa-exclamation-circle' style='margin-right:3px;color:#FF6B6B'></i> Delete process was failed!</div>");
				Header('Location:'.base_url().'index.php/application/req_realisasi/grid_modify/'.$id);
			}
		}
		
		function save_data(){
			$query_get_header = $this->db->query("select * from tblExportEvanHeadTemp order by kd_jenis_transaksi, fg_pengganti, no_seri");
			foreach($query_get_header->result() as $header){
				$query_valid_header = $this->db->query("select kd_jenis_transaksi, fg_pengganti, no_seri from tblExport_Faktur_Header where kd_jenis_transaksi='".$header->kd_jenis_transaksi."' and fg_pengganti='".$header->fg_pengganti."' and no_seri='".$header->no_seri."'");
				if($query_valid_header->num_rows() == 0){
					$data_h['no_seri'] = $header->no_seri;
					$data_h['kd_jenis_transaksi'] = $header->kd_jenis_transaksi;
					$data_h['fg_pengganti'] = $header->fg_pengganti;
					$data_h['tgl_faktur'] = $header->tgl_faktur;
					$data_h['masa_pajak'] = $header->masa_pajak;
					$data_h['tahun_pajak'] = $header->tahun_pajak;
					$data_h['npwp_penjual'] = $header->npwp_penjual;
					$data_h['nama_penjual'] = $header->nama_penjual;
					$data_h['alamat_penjual'] = $header->alamat_penjual;
					$data_h['npwp_lawan_transaksi'] = $header->npwp_lawan_transaksi;
					$data_h['nama_lawan_transaksi'] = $header->nama_lawan_transaksi;
					$data_h['alamat_lawan_transaksi'] = $header->alamat_lawan_transaksi;
					$data_h['jumlah_dpp'] = $header->jumlah_dpp;
					$data_h['jumlah_ppn'] = $header->jumlah_ppn;
					$data_h['jumlah_ppnbm'] = $header->jumlah_ppnbm;
					$data_h['status_approval'] = $header->status_approval;
					$data_h['status_faktur'] = $header->status_faktur;
					$data_h['tgl_scan'] = $header->tgl_scan;
					
					$insert = $this->model_db->insert_data('tblExport_Faktur_Header', $data_h);
					
				}else{
					/*$id = $header->no_seri;
					$data_h1['kd_jenis_transaksi'] = $header->kd_jenis_transaksi;
					$data_h1['fg_pengganti'] = $header->fg_pengganti;
					$data_h1['tgl_faktur'] = $header->tgl_faktur;
					$data_h1['masa_pajak'] = $header->masa_pajak;
					$data_h1['tahun_pajak'] = $header->tahun_pajak;
					$data_h1['npwp_penjual'] = $header->npwp_penjual;
					$data_h1['nama_penjual'] = $header->nama_penjual;
					$data_h1['alamat_penjual'] = $header->alamat_penjual;
					$data_h1['npwp_lawan_transaksi'] = $header->npwp_lawan_transaksi;
					$data_h1['nama_lawan_transaksi'] = $header->nama_lawan_transaksi;
					$data_h1['alamat_lawan_transaksi'] = $header->alamat_lawan_transaksi;
					$data_h1['jumlah_dpp'] = $header->jumlah_dpp;
					$data_h1['jumlah_ppn'] = $header->jumlah_ppn;
					$data_h1['jumlah_ppnbm'] = $header->jumlah_ppnbm;
					$data_h1['status_approval'] = $header->status_approval;
					$data_h1['status_faktur'] = $header->status_faktur;
					$data_h1['tgl_scan'] = $header->tgl_scan;*/
					
					$update = $this->db->query("update tblExport_Faktur_Header set 
						tgl_faktur='".$header->tgl_faktur."', 
						masa_pajak='".$header->masa_pajak."', 
						tahun_pajak='".$header->tahun_pajak."',
						npwp_penjual='".$header->npwp_penjual."',
						nama_penjual='".$header->nama_penjual."',
						alamat_penjual='".$header->alamat_penjual."',
						npwp_lawan_transaksi='".$header->npwp_lawan_transaksi."',
						nama_lawan_transaksi='".$header->nama_lawan_transaksi."',
						alamat_lawan_transaksi='".$header->alamat_lawan_transaksi."',
						jumlah_dpp='".$header->jumlah_dpp."',
						jumlah_ppn='".$header->jumlah_ppn."',
						jumlah_ppnbm='".$header->jumlah_ppnbm."',
						status_approval='".$header->status_approval."',
						status_faktur='".$header->status_faktur."',
						tgl_scan='".$header->tgl_scan."' where fg_pengganti='".$header->fg_pengganti."' and kd_jenis_transaksi='".$header->kd_jenis_transaksi."' and no_seri='".$header->no_seri."'");
				}
			}
			
			$query_get_detail = $this->db->query("select distinct no_seri_fk from tblExportEvanDetailTemp order by no_seri_fk");
			foreach($query_get_detail->result() as $detail){
				$query_valid_detail = $this->db->query("select no_seri_fk from tblExport_Faktur_Detail where no_seri_fk='".$detail->no_seri_fk."'");
				if($query_valid_detail->num_rows() > 0){
					$this->db->query("delete from tblExport_Faktur_Detail where no_seri_fk='".$detail->no_seri_fk."'");
					
					$query1 = $this->db->query("select * from tblExportEvanDetailTemp where no_seri_fk='".$detail->no_seri_fk."'");
					foreach($query1->result() as $dt){
						$data['no_seri_fk'] = $dt->no_seri_fk;
						$data['nama'] = $dt->nama;
						$data['harga_satuan'] = $dt->harga_satuan;
						$data['jumlah_barang'] = $dt->jumlah_barang;
						$data['harga_total'] = $dt->harga_total;
						$data['diskon'] = $dt->diskon;
						$data['dpp'] = $dt->dpp;
						$data['ppn'] = $dt->ppn;
						$data['tarif_ppnbm'] = $dt->tarif_ppnbm;
						$data['ppnbm'] = $dt->ppnbm;
						
						$insert_dt1 = $this->model_db->insert_data('tblExport_Faktur_Detail', $data);
					}
				}else{
					$query2 = $this->db->query("select * from tblExportEvanDetailTemp where no_seri_fk='".$detail->no_seri_fk."'");
					foreach($query2->result() as $dt2){
						$data1['no_seri_fk'] = $dt2->no_seri_fk;
						$data1['nama'] = $dt2->nama;
						$data1['harga_satuan'] = $dt2->harga_satuan;
						$data1['jumlah_barang'] = $dt2->jumlah_barang;
						$data1['harga_total'] = $dt2->harga_total;
						$data1['diskon'] = $dt2->diskon;
						$data1['dpp'] = $dt2->dpp;
						$data1['ppn'] = $dt2->ppn;
						$data1['tarif_ppnbm'] = $dt2->tarif_ppnbm;
						$data1['ppnbm'] = $dt2->ppnbm;
						
						$insert_dt2 = $this->model_db->insert_data('tblExport_Faktur_Detail', $data1);
					}
				}
			}
			//if($query1){
				$this->db->query("delete from tblExportEvanHeadTemp");
				$this->db->query("delete from tblExportEvanDetailTemp");
				$data = array(
					'status' => 'Sukses',
					'color' => 'FF6B6B'
				);
				echo json_encode($data);
				
				
			//}else{
			//	$data = array(
			//		'status' => 'Failed',
			//		'color' => 'FF6B6B'
			//	);
			//	echo json_encode($data);
			//}
		}
	}
/*End of file scan.php*/
/*Location:application/controllers/scan.php*/