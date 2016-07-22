<!--<html>
	<head>
		<title>Barcode | Export E-Faktur</title>
		<link rel="stylesheet" href="<?php //echo base_url();?>assets/css/font-awesome/css/font-awesome.min.css">
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<link rel="stylesheet" href="<?php //echo base_url();?>assets/scrollbar/jquery.mCustomScrollbar.css" />
		<script src="<?php //echo base_url();?>assets/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php //echo base_url();?>assets/css/jquery-confirm.min.css">
	</head>-->
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
		
		function total_data(){
				$.ajax({
					url:"<?php echo base_url();?>index.php/scan/total_temp_scan/",
					cache:false,
					//data:"id="+data,
					type: "POST",
					dataType: 'json',
					success:function(result){
						//$("#table-grid option").remove();
						//$("#kode").append('<option value="" selected disabled> -- Select Distributor -- </option>');
						
							document.getElementById('total_dpp').value = 0;
							document.getElementById('total_ppn').value = 0;
							document.getElementById('count_faktur').value = 0;
						$.each(result, function(i, data){
							document.getElementById('total_dpp').value = currencyFormat(data.a);
							document.getElementById('total_ppn').value = currencyFormat(data.b);
							document.getElementById('count_faktur').value = numberFormat(data.c);
						});
					}
				});
			}
	
		function delete_data(e){
			$.confirm({
				title: '<span style="color:#FF6B6B;"><i class="fa fa-exclamation" style="margin-right:5px;"></i>Confirmation</span>',
				content: 'Are you sure delete this record?',
				confirm: function(){
					var element = e.attr('id');
					$.ajax({
						url:"<?php echo base_url();?>index.php/scan/delete_data/",
						cache:false,
						data:{
							id:element
						},
						type: "POST",
						dataType: 'json',
						success:function(result){
							$.ajax({
								url:"<?php echo base_url();?>index.php/scan/get_items_grid/",
								cache:false,
								type: "POST",
								dataType: 'json',
								success:function(result){
									$("#grid-table tbody tr").remove();
									//$("#kode").append('<option value="" selected disabled> -- Select Distributor -- </option>');
									
									
									$.each(result, function(i, data){
									$('#grid-table tbody').append("<tr><td>"+data.no_seri+"</td><td>"+data.tgl_faktur+"</td><td>"+data.masa_pajak+"</td><td>"+data.tahun_pajak+"</td><td>"+data.nama_penjual+"</td><td>"+currencyFormat(data.jumlah_dpp)+"</td><td>"+currencyFormat(data.jumlah_ppn)+"</td><td>"+data.status_generate+"</td><td style='border-right:transparent;width:25px;'><a style='color:#F9896D' id='"+data.no_seri+"' onclick='delete_data($(this))'><i style='color:#F9896D'  class='fa fa-close'></i></a></td></tr>");
									});
									total_data();
									document.getElementById("scan").focus();
								}
							});
						}
					});
				},
				cancel: function(){
					$.alert('Canceled!');
				}
			});
		}

	</script>
	<script>
	  (function($){
		$(window).load(function(){
		  $(".content").mCustomScrollbar({
			theme:"light-3",
			scrollButtons:{
			  enable:true
			}
		  });
		});
	  })(jQuery);
	</script>
	<script>
      $(function () {
        $('form').on('submit', function (e) {
			
          e.preventDefault();
		  $("#dvloader").show();
          $.ajax({
            type: 'post',
            url:"<?php echo base_url();?>index.php/scan/proses/",
            data: $('form').serialize(),
			dataType: 'json',
            success: function(data){
				if(data.status == 'Double'){
					$("#dvloader").hide();
					alert_double();
				}else if(data.status == 'Empty'){
					$("#dvloader").hide();
					alert_empty();
				}else if(data.status == 'Expired'){
					$("#dvloader").hide();
					alert_expired();
				}else if(data.status == 'NotYet'){
					$("#dvloader").hide();
					alert_notyet();
				}else if(data.status == 'Available'){
					$("#dvloader").hide();
					conf_overwrite();
				}else if(data.status == 'Failed'){
					$("#dvloader").hide();
					alert_fail();
				}else if(data.status == 'Sukses'){
					$("#dvloader").hide();
					$.ajax({
						url:"<?php echo base_url();?>index.php/scan/get_items_grid/",
						cache:false,
						type: "POST",
						dataType: 'json',
						success:function(result){
							$("#dvloader").hide();
							$("#grid-table tbody tr").remove();	
							$.each(result, function(i, data){
									$('#grid-table tbody').append("<tr><td>"+data.no_seri+"</td><td>"+data.tgl_faktur+"</td><td>"+data.masa_pajak+"</td><td>"+data.tahun_pajak+"</td><td>"+data.nama_penjual+"</td><td>"+currencyFormat(data.jumlah_dpp)+"</td><td>"+currencyFormat(data.jumlah_ppn)+"</td><td>"+data.status_generate+"</td><td style='border-right:transparent;width:25px;'><a style='color:#F9896D' id='"+data.no_seri+"' onclick='delete_data($(this))'><i style='color:#F9896D'  class='fa fa-close'></i></a></td></tr>");
									});
							total_data();
							document.getElementById("scan").value  = '';
							//document.getElementById("masa_pajak").value  = '';
							//document.getElementById("tahun_pajak").value  = '';
							document.getElementById("scan").focus();
						}
					});	 
				}
             }
          });
        });
		$('#form-filter').keyup(function(){
         // e.preventDefault();
		  $("#dvloader1").show();
          $.ajax({
            type: 'post',
            url:"<?php echo base_url();?>index.php/scan/filter/",
            data: $('form').serialize(),
			dataType: "json",
            success: function(result) {
			$("#dvloader1").hide();
				$("#grid-table tbody tr").remove();	
				$.each(result, function(i, data){
									$('#grid-table tbody').append("<tr><td>"+data.no_seri+"</td><td>"+data.tgl_faktur+"</td><td>"+data.masa_pajak+"</td><td>"+data.tahun_pajak+"</td><td>"+data.nama_penjual+"</td><td>"+currencyFormat(data.jumlah_dpp)+"</td><td>"+currencyFormat(data.jumlah_ppn)+"</td><td>"+data.status_generate+"</td><td style='border-right:transparent;width:25px;'><a style='color:#F9896D' id='"+data.no_seri+"' onclick='delete_data($(this))'><i style='color:#F9896D'  class='fa fa-close'></i></a></td></tr>");
									});
				document.getElementById("scan").value  = '';
				//document.getElementById("masa_pajak").value  = '';
				//document.getElementById("tahun_pajak").value  = '';
				document.getElementById("filter").focus();
            }
          });
        });	
		$('#save').click(function(){
         // e.preventDefault();
		  $("#dvloader2").show();
          $.ajax({
            type: 'post',
            url:"<?php echo base_url();?>index.php/scan/save_data/",
            //data: $('form').serialize(),
			dataType: "json",
            success: function(data) {
				if(data.status == 'Failed'){
					$("#dvloader2").hide();
					alert_fail();
				}else if(data.status == 'Sukses'){
					$("#dvloader2").hide();
					alert_sukses();
						$.ajax({
							url:"<?php echo base_url();?>index.php/scan/get_items_grid/",
							cache:false,
							type: "POST",
							dataType: 'json',
							success:function(result){
								$("#grid-table tbody tr").remove();
								//$("#kode").append('<option value="" selected disabled> -- Select Distributor -- </option>');
								
								
								$.each(result, function(i, data){
									$('#grid-table tbody').append("<tr><td>"+data.no_seri+"</td><td>"+data.tgl_faktur+"</td><td>"+data.masa_pajak+"</td><td>"+data.tahun_pajak+"</td><td>"+data.nama_penjual+"</td><td>"+currencyFormat(data.jumlah_dpp)+"</td><td>"+currencyFormat(data.jumlah_ppn)+"</td><td>"+data.status_generate+"</td><td style='border-right:transparent;width:25px;'><a style='color:#F9896D' id='"+data.no_seri+"' onclick='delete_data($(this))'><i style='color:#F9896D'  class='fa fa-close'></i></a></td></tr>");
									});
								total_data();
								document.getElementById("scan").focus();
							}
						});
				}
            }
          });
        });
	  });
    </script>
	<script>
		$(function(){
			$(function(){
				$.ajax({
					url:"<?php echo base_url();?>index.php/scan/get_items_grid/",
					cache:false,
					type: "POST",
					dataType: 'json',
					success:function(result){
						$("#grid-table tbody tr").remove();
						//$("#kode").append('<option value="" selected disabled> -- Select Distributor -- </option>');
						
						
						$.each(result, function(i, data){
									$('#grid-table tbody').append("<tr><td>"+data.no_seri+"</td><td>"+data.tgl_faktur+"</td><td>"+data.masa_pajak+"</td><td>"+data.tahun_pajak+"</td><td>"+data.nama_penjual+"</td><td>"+currencyFormat(data.jumlah_dpp)+"</td><td>"+currencyFormat(data.jumlah_ppn)+"</td><td>"+data.status_generate+"</td><td style='border-right:transparent;width:25px;'><a style='color:#F9896D' id='"+data.no_seri+"' onclick='delete_data($(this))'><i style='color:#F9896D'  class='fa fa-close'></i></a></td></tr>");
									});
						total_data();
						document.getElementById("scan").focus();
					}
				});
			});
		});
	</script>
	<!--<style>
		.container-fluid{
			width:100%;
			padding:0px;
			margin:0px;
			top:0px;
			left:0px;
			position:absolute;
			background-color:#eee;
			height:100%;
			font-family:calibri;
			color:#444;
		}
		
		.container{
			width:96%;
			padding:2%;
			padding-bottom:0.1%;
		}
		
		.side-top{
			width:100%;
			background-color:#444;
			height:15%;
		}
		
		.side-content{
			width:100%;
			background-color:#fff;
			//height:75%;
		}
		
		.box-scanner{
			background-color:#fbfbfb;
			width:98%;
			padding:1%;
			height:100px;
			display: -webkit-flex; /* Safari */ 
			-webkit-align-items: center; /* Safari 7.0+ */
			display: flex;
			align-items: center;border-radius:3px;
			border:1px solid #e1e1e1;
		}
	</style>
	<body>
		<div class="container-fluid">
			<div class="side-top">
				<div class="container">
					<h2 style="color:#fff;margin:0px;padding:0px;float:left;"> <i style="font-size:42px;" class="fa fa-qrcode"></i></h2><p style="float:left;color:#fff;font-size:24px;margin:5px;margin-left:5px;">e-Faktur | <i style="background-color:#fff;color:#222;font-style:normal;padding:2px 5px;">Scanner</i></p>
					<div style="margin-top:10px;float:right;"><a style="color:#fff;cursor:pointer;"><i class="fa fa-th-large" style="margin-right:5px;"></i>Grid Data Faktur</a></div>
				</div>
			</div>-->
			<div class="side-content">
				<div class="container">
					<h2>Scan QR Barcode</h2>
					<div class="box-scanner">
					<?php 
						//$attribute = array('style'=>'width:100%;float:left;');
						//echo form_open('');
					?>
					<form style="width:100%;float:left;"/>
						<div style="width:100%;float:left;">
							<div style="width:30%;float:left;margin-right:10px;">
								<span>Tax Month</span>
								<input type="number" id='masa_pajak' name='masa_pajak' style="width:98.5%;padding:1.5%;border-radius:3px;border:1px solid #e1e1e1;margin:2.5px 0px;" placeholder="Tax Month"/>
							</div>
							<div style="width:30%;float:left;">
								<span>Tax Year</span>
								<input type="number" id='tahun_pajak' name='tahun_pajak' style="width:98.5%;padding:1.5%;border-radius:3px;border:1px solid #e1e1e1;margin:2.5px 0px;" placeholder="Tax Year"/>
							</div>
						</div>
						<span>QR Code :</span>
						<input type="text" id='scan' name='scan' style="width:98.5%;padding:0.5%;border-radius:3px;border:1px solid #e1e1e1;margin:2.5px 0px;" placeholder="Scan QR Code"/>
						<button type="submit" id='insert_item' style="cursor:pointer;float:left;background-color:#444;font-weight:bold;color:#fff;padding:7px 10px;margin:2.5px 0px;border:1px solid #e1e1e1;border-radius:5px;font-size:14px;"><i class="fa fa-search" style="margin-right:5px;"></i>Scan</button>
						<div style="display:none;float:left;margin:10px;" id="dvloader"><i class="fa fa-spinner fa-spin" style="margin-right:5px;"></i>Load Data . . .</div>
					</form>
					</div>
				</div>
				<style>
					table{
						border:none;
						width:100%;
						border-collapse:collapse;
						margin:20px 0px;
					}
					
					table thead tr td{
						font-weight:bold;
						padding:10px;
						border-bottom:1px solid #e1e1e1;
					}
					
					table tbody tr td{
						border-bottom:1px solid #e1e1e1;
						padding:10px;
					}
				</style>
				<div class="main">
				<div class="container">
					<h2>All Data e-Faktur</h2>
					<form id='form-filter'>
						<span>Filter :</span>
						<input type="text" id='filter' name='filter' style="width:98.5%;padding:0.5%;border-radius:3px;border:1px solid #e1e1e1;margin:2.5px 0px;" placeholder="No Invoice e.g. 0011603576264"/>
						<div style="display:none;float:left;margin:10px;" id="dvloader1"><i class="fa fa-spinner fa-spin" style="margin-right:5px;"></i>Load Data . . .</div>
					</form>
					<div class="content" data-mcs-theme="dark-3" style="height:250px;margin:20px 0px;">
						<table id="grid-table">
							<thead>
								<tr>
									<td>Invoice No.</td>
									<td>Invoice Date</td>
									<td>Tax Month</td>
									<td>Tax Year</td>
									<td>Company Name</td>
									<td>DPP Amount</td>
									<td>PPN Amount</td>
									<td>Generate Status</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<div style="margin-bottom:20px;float:left;color:#666;">Total DPP :&nbsp;&nbsp;<input id="total_dpp" style="background-color:none; border:none; color:#666;" readonly/></div>
					<div style="margin-bottom:20px;float:left;color:#666;">Total PPN :&nbsp;&nbsp;<input id="total_ppn" style="background-color:none; border:none; color:#666;" readonly/></div>
					<div style="margin-bottom:20px;float:left;color:#666;">Count :&nbsp;&nbsp;<input id="count_faktur" style="background-color:none; border:none; color:#666;" readonly/></div>
					<div style="margin-bottom:20px;float:right;color:#666;"><a id="save" style="cursor:pointer;margin-right:5px;float:left;background-color:#7C9BEF;color:#fff;padding:7px 10px;margin:2.5px 0px;border:1px solid #e1e1e1;border-radius:5px;font-size:14px;font-weight:bold"><i class="fa fa-save"></i> Save</a></div>					
					<div style="display:none;float:right;margin:10px;" id="dvloader2"><i class="fa fa-spinner fa-spin" style="margin-right:5px;"></i>Load Data . . .</div>
				</div>
				</div>
				</div>