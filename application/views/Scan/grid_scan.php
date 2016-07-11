			<script>
				function currencyFormat (num) {
					var c = parseFloat(num);
					var a = String(c);
					return "IDR " + a.toString(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
				}
				
				function numberFormat (num) {
					var c = parseFloat(num);
					var a = String(c);
					return a.toString(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
				}
			</script>
			<script src="<?php echo base_url();?>assets/js/jquery-1.9.1.js"></script>
			<div class="main side-content">
				<div class="container" style="margin:0px;margin-bottom:5px;padding:0;">
					<div class="container" style="margin-bottom:20px;">
						<h2 style="display:inline;margin-top:20px;padding-left:20px;">DataGrid e-Faktur</h2>
						<button id='multi-export' style="margin-left:20px;cursor:pointer;float:left;background-color:#444;font-weight:bold;color:#fff;padding:7px 10px;margin:2.5px 0px;border:1px solid #e1e1e1;border-radius:5px;font-size:14px;"><i class='fa fa-file-text-o' style='margin-right:5px;'></i>Export CSV</button> 
					</div>
					<div id="body">
						<?php 
							$data = null;
							$phpgrid->set_caption("<div><h4 style='text-align:center;width:200px;margin:auto;padding:20px 0px;'><i class='fa fa-desktop' style='margin-right:5px;'></i>ALL FAKTUR</h4></div>");
							$phpgrid->enable_edit("FORM","R"); 
							$phpgrid->enable_autowidth(true);
							$phpgrid->enable_autoheight(true);
							$phpgrid->set_theme('aristo');
							
							$query = $this->db->query("select kd_jenis_transaksi, fg_pengganti, no_seri from tblExport_Faktur_Header");
							foreach($query->result() as $db){
								$data .= $db->no_seri.': 0'.$db->kd_jenis_transaksi.$db->fg_pengganti.'.'.substr($db->no_seri,0,3).'-'.substr($db->no_seri,3,2).'.'.substr($db->no_seri,5).';';
							}
							$phpgrid->set_col_edittype("no_seri", "autocomplete", $data, false);
							
							$phpgrid->set_col_date("tgl_faktur", "Y-m-d", "d/m/Y", "d/m/yy");
							
							//desc. header
							$phpgrid->set_col_title("no_seri", "Code and Seri no.");
							$phpgrid->set_col_title("tgl_faktur", "Faktur Date");
							$phpgrid->set_col_title("masa_pajak", "Tax(Month)");
							$phpgrid->set_col_title("tahun_pajak", "Tax(Year)");
							$phpgrid->set_col_title("npwp_penjual", "NPWP (PKP)");
							$phpgrid->set_col_title("nama_penjual", "Company Name");
							$phpgrid->set_col_title("alamat_penjual", "Address");
							$phpgrid->set_col_title("jumlah_dpp", "DPP");
							$phpgrid->set_col_title("jumlah_ppn", "PPN");
							$phpgrid->set_col_title("jumlah_ppnbm", "PPNBM");
							$phpgrid->set_col_title("status_approval", "Approval");
							$phpgrid->set_col_title("status_faktur", "Faktur Status");
							
							//size column
							$phpgrid->set_col_width("jumlah_dpp", 100);
							$phpgrid->set_col_width("jumlah_ppn", 100);
							$phpgrid->set_col_width("jumlah_ppnbm", 100);
							$phpgrid->set_col_width("tgl_faktur", 100);
							$phpgrid->set_col_width("masa_pajak", 100);
							$phpgrid->set_col_width("tahun_pajak", 100);
							
							//currency format
							$phpgrid->set_col_currency("jumlah_dpp", "", "", ",", 2, "0.00");
							$phpgrid->set_col_currency("jumlah_ppn", "", "", ",", 2, "0.00");
							$phpgrid->set_col_currency("jumlah_ppnbm", "", "", ",", 2, "0.00");
							
							$phpgrid->set_col_align("masa_pajak", 'center'); 
							$phpgrid->set_col_align("tahun_pajak", 'center'); 
							
							//hidden column on datagrid
							$phpgrid->set_col_hidden('npwp_lawan_transaksi');
							$phpgrid->set_col_hidden('nama_lawan_transaksi');
							$phpgrid->set_col_hidden('alamat_lawan_transaksi');
							$phpgrid->set_col_hidden('kd_jenis_transaksi');
							$phpgrid->set_col_hidden('fg_pengganti');
							$phpgrid->set_col_hidden('tgl_scan');
							
							$phpgrid->enable_search(true);
							$phpgrid->set_multiselect(true);
							
							$phpgrid->display();
						?>	
					</div>
				</div>
			</div>
			<script>
				function ShowSelectedRows(){
				 if(confirm('Sure To Remove This Record?'))
				 {
					
				 }
				}
			</script>
			