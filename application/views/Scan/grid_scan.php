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
					<div class="container">
						<h2 style="display:inline;text-align:right;margin-top:20px;">DataGrid e-Faktur</h2>
					</div>
					<div class="container" style="margin-bottom:20px;width:100%;">
						<button id='multi-export' style="margin-left:20px;cursor:pointer;background-color:#444;color:#fff;padding:7px 10px;margin:2.5px 0px;border:1px solid #e1e1e1;border-radius:5px;font-size:14px;"><i class='fa fa-file-text-o' style='margin-right:5px;'></i>Export CSV</button> 
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="float:left;">
						<label>Filter Data : </label>
						<select name="bulan" id="bulan" style="height:35px;border-radius:3px;border: solid 1px #DADADA;box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);padding:1px 10px;">
							<?php 
								foreach(range('1', '12') as $m) : 
								if(isset($_POST['bulan']))
								{
									?>
								 <option value="<?php echo $m; ?>" <?php if ($_POST['bulan'] == $m) { echo 'selected="selected"'; } ?>>
									<?php echo date('F', mktime(0, 0, 0, $m, 10)) ?>
								 </option>
							<?php 
								}else{
							?>
								 <option value="<?php echo $m; ?>" <?php if (date('n') == $m) { echo 'selected="selected"'; } ?>>
									<?php echo date('F', mktime(0, 0, 0, $m, 10)) ?>
								 </option>
							<?php
								}
								endforeach; 
							?>
					   </select>
					   <input id="tahun" name="tahun" placeholder="YYYY - Year" value="<?php if(isset($_POST['tahun'])){echo $_POST['tahun'];}else{echo date('Y');}?>" type="number" style="border-radius:3px;border: solid 1px #DADADA;box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);height:32px;padding:1px 10px;"/>
					   <select name="status" style="height:35px;border-radius:3px;border: solid 1px #DADADA;box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);padding:1px 10px;">
							<option disabled>-- Select Status --</option>
							<?php 
								if(isset($_POST['status'])){
									if($_POST['status'] == 'Export'){
										?>
										<option value="all">All</option>
										<option value="Export" selected >Export</option>
										<option value="Scanned">Scanned</option>
										<?php
									}else if($_POST['status'] == 'All'){
										?>
										<option value="all" selected>All</option>
										<option value="Export">Export</option>
										<option value="Scanned">Scanned</option>
										<?php
									}else{
										?>
										<option value="all">All</option>
										<option value="Export">Export</option>
										<option selected value="Scanned">Scanned</option>
										<?php
									}
								}else{
								?>
									<option value="all">All</option>
									<option value="Export">Export</option>
									<option value="Scanned" selected>Scanned</option>
								<?php
								}
							?>
					   </select>
					   <button name="submit" type="submit" style="margin-left:20px;cursor:pointer;background-color:#444;color:#fff;padding:7px 10px;margin:2.5px 0px;border:1px solid #e1e1e1;border-radius:5px;font-size:14px;"><i class='fa fa-filter' style='margin-right:5px;'></i>Filter</button> 

					   <?php 
							echo form_close();
					   ?>
					</div>
					<div id="body">
						<?php 
							if(isset($_POST['submit'])) 
							{ 
								$bulan = $_POST['bulan'];
								$tahun = $_POST['tahun'];
								$status = $_POST['status'];
								if($status == "all"){
									$phpgrid->set_query_filter("tahun_pajak='".$tahun."' and masa_pajak='".$bulan."'");
								}else{
									$phpgrid->set_query_filter("tahun_pajak='".$tahun."' and masa_pajak='".$bulan."' and status_scan='".$status."'");
								}
							}else{
								$phpgrid->set_query_filter("tahun_pajak='".date('Y')."' and masa_pajak='".date('m')."' and status_scan='Scanned'");
							}
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
							$phpgrid->set_col_align("status_scan", 'center'); 
							
							//hidden column on datagrid
							$phpgrid->set_col_hidden('npwp_lawan_transaksi');
							$phpgrid->set_col_hidden('nama_lawan_transaksi');
							$phpgrid->set_col_hidden('alamat_lawan_transaksi');
							$phpgrid->set_col_hidden('kd_jenis_transaksi');
							$phpgrid->set_col_hidden('fg_pengganti');
							$phpgrid->set_col_hidden('tgl_scan');
							$phpgrid->set_col_hidden('ObjectID');
							
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
			