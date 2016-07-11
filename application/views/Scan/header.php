<!DOCTYPE html>
<html>
	<head>
		<title>Barcode | Export E-Faktur</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome/css/font-awesome.min.css">
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/scrollbar/jquery.mCustomScrollbar.css" />
		<script src="<?php echo base_url();?>assets/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/jquery-confirm.min.css">
	</head>
	<style>
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
			height:200px;
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
					<div style="margin-top:10px;float:right;"><a href='<?php echo base_url().'index.php/grid_scan';?>' style="margin:0px 2.5px;color:#fff;cursor:pointer;border:1px solid #666; border-radius:15px;text-decoration:none;padding:5px 10px;"><i class="fa fa-th-large" style="margin-right:5px;"></i>Grid Data Faktur</a></div>
					<div style="margin-top:10px;float:right;"><a href='<?php echo base_url();?>' style="margin:0px 2.5px;color:#fff;cursor:pointer;border:1px solid #666; border-radius:15px;text-decoration:none;padding:5px 10px;"><i class="fa fa-qrcode" style="margin-right:5px;"></i>Scan Barcode</a></div>
				</div>
			</div>