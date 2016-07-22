<?php
	if(!defined('BASEPATH'))exit('No direct script access allowed');
	class Grid_Scan extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->load->model('model_db');
		}
		
		function index(){
			$this->load->library('ci_phpgrid');
			$data['phpgrid'] = $this->ci_phpgrid->grid_faktur();
			$this->load->view('scan/header');
			$this->load->view('scan/grid_scan',$data);
			$this->load->view('scan/footer');
		}
		
		/*function export_csv($data){
			echo $data = $this->uri->segment(3);
		}*/
		
		function export_csv(){
            $this->load->library("Excel/PHPExcel");
            $objPHPExcel = new PHPExcel();
 
            //set Sheet yang akan diolah 
            $objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->setActiveSheetIndex(0)
                    //mengisikan value pada tiap-tiap cell, A1 itu alamat cellnya 
                    //Hello merupakan isinya
                                        ->setCellValue('A1', 'FM')
                                        ->setCellValue('B1', 'KD_JENIS_TRANSAKSI')
                                        ->setCellValue('C1', 'FG_PENGGANTI')
										->setCellValue('D1', 'NOMOR_FAKTUR')
										->setCellValue('E1', 'MASA_PAJAK')
										->setCellValue('F1', 'TAHUN_PAJAK')
										->setCellValue('G1', 'TANGGAL_FAKTUR')
										->setCellValue('H1', 'NPWP')
										->setCellValue('I1', 'NAMA')
										->setCellValue('J1', 'ALAMAT_LENGKAP')
										->setCellValue('K1', 'JUMLAH_DPP')
										->setCellValue('L1', 'JUMLAH_PPN')
										->setCellValue('M1', 'JUMLAH_PPNBM')
										->setCellValue('N1', 'IS_CREDITABLE');
                    //mengisikan value pada tiap-tiap cell, A1 itu alamat cellnya 
                    //Hello merupakan isinya
			//$dt = $this->uri->segment(3);
			//$data = explode(",", $dt);
			$dt = $_POST['selectedRows'];
			foreach($dt as $d1){
				$data = explode(",", $d1);
			}
			
			$i = 2;
			foreach($data as $d){
				//echo $d.'<br/>';
			//}
				$query1 = $this->db->query("select * from tblExport_Faktur_Header where ObjectID='".$d."'");
				

				foreach($query1->result() as $h){
					$thn = substr($h->tgl_faktur,0,4);
					$bln = substr($h->tgl_faktur,5,2);
					$tgl = substr($h->tgl_faktur,8,2);
					$date = $tgl.'/'.$bln.'/'.$thn;
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A'.$i, 'FM')
								->setCellValue('B'.$i, $h->kd_jenis_transaksi)
								->setCellValue('C'.$i, $h->fg_pengganti)
								->setCellValue('D'.$i, $h->no_seri)
								->setCellValue('E'.$i, $h->masa_pajak)
								->setCellValue('F'.$i, $h->tahun_pajak)
								->setCellValue('G'.$i, $date)
								->setCellValue('H'.$i, $h->npwp_penjual)
								->setCellValue('I'.$i, $h->nama_penjual)
								->setCellValue('J'.$i, $h->alamat_penjual)
								->setCellValue('K'.$i, $h->jumlah_dpp)
								->setCellValue('L'.$i, $h->jumlah_ppn)
								->setCellValue('M'.$i, $h->jumlah_ppnbm)
								->setCellValue('N'.$i, '1');
				$i++;
				
				$data1['status_scan'] = 'Export';
				$this->model_db->update_data("tblExport_Faktur_Header", 'ObjectID', $h->ObjectID, $data1);
				}
				
				
			}
            //set title pada sheet (me rename nama sheet)
            $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
			 
            //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
			$objWriter->setDelimiter(';');
            //sesuaikan headernya 
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="faktur_pajak.csv"');
			$objWriter->save("php://output");
		}
		
		/*function force_close(){
			$data = $_POST['selectedRows'];

			foreach($data as $d){				 
				$query = $this->db->query("select * from tblExport_Faktur_Header where no_seri='".$d."'");
				foreach($query->result() as $h){
					$array[]
				}
			}
			
			$fp = fopen('file.csv', 'w');
			
			
			foreach($data as $d){				 

				 //foreach ($list as $fields) {
					fputcsv($fp, $d);
				 //}

				 
			}
			fclose($fp);
			 
			 
			 $response_array['status'] = 'success'; 
			 
			 header('Content-type: application/json');
			 echo json_encode($response_array);
		}
	*/
	}
/*End of file grid_scan.php*/
/*Location:.application/controllers/grid_scan/*/