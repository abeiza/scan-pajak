<footer style="float:left;width:100%;padding:20px 0px;">
	<div class="container">
		<span style="font-size:11px; color:#999"><i class="fa fa-usb"></i>Develop by Evan Abeiza</span>
	</div>
</footer>

<script src="<?php echo base_url();?>assets/js/jquery-confirm.min.js"></script>
		<script>
			function conf_overwrite(){
				$.alert({
					title: '<span style="color:#FF6B6B;"><i class="fa fa-exclamation" style="margin-right:5px;"></i>Message</span>',
					content: 'Data is available.',
					confirm: function(){
						//$.alert('Confirmed!'); // shorthand.
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
								document.getElementById("scan").value  = '';
								//document.getElementById("masa_pajak").value  = '';
								//document.getElementById("tahun_pajak").value  = '';
								document.getElementById("scan").focus();
							}
						});
						//document.getElementById("scan").value  = '';
						//document.getElementById("scan").focus();
					}
				});
			}
			
			function alert_double(){
				$.alert({
					title: '<span style="color:#FF6B6B;"><i class="fa fa-exclamation" style="margin-right:5px;"></i>Message</span>',
					content: 'Double data! Please check again . . .',
					confirm: function(){
						//$.alert('Confirmed!'); // shorthand.
						document.getElementById("scan").value  = '';
						//document.getElementById("masa_pajak").value  = '';
						//document.getElementById("tahun_pajak").value  = '';
						document.getElementById("scan").focus();
					}
				});
			}
			
			function alert_fail(){
				$.alert({
					title: '<span style="color:#FF6B6B;"><i class="fa fa-close" style="margin-right:5px;"></i>Message</span>',
					content: 'Failed',
					confirm: function(){
						//$.alert('Confirmed!'); // shorthand.
						document.getElementById("scan").value  = '';
						//document.getElementById("masa_pajak").value  = '';
						//document.getElementById("tahun_pajak").value  = '';
						document.getElementById("scan").focus();
					}
				});
			}

			function alert_empty(){
				$.alert({
					title: '<span style="color:#FF6B6B;"><i class="fa fa-ban" style="margin-right:5px;"></i>Warning</span>',
					content: 'Scan Column is requred!',
					confirm: function(){
						//$.alert('Confirmed!'); // shorthand.
						document.getElementById("scan").value  = '';
						//document.getElementById("masa_pajak").value  = '';
						//document.getElementById("tahun_pajak").value  = '';
						document.getElementById("scan").focus();
					}
				});
			}
			
			function alert_expired(){
				$.alert({
					title: '<span style="color:#FF6B6B;"><i class="fa fa-close" style="margin-right:5px;"></i>Message</span>',
					content: 'Faktur Pajak Date has Expired!',
					confirm: function(){
						//$.alert('Confirmed!'); // shorthand.
						document.getElementById("scan").value  = '';
						//document.getElementById("masa_pajak").value  = '';
						//document.getElementById("tahun_pajak").value  = '';
						document.getElementById("scan").focus();
					}
				});
			}
			
			function alert_notyet(){
				$.alert({
					title: '<span style="color:#FF6B6B;"><i class="fa fa-close" style="margin-right:5px;"></i>Message</span>',
					content: 'Invoice Cant be Scanned! Please Check Invoice Date . . .',
					confirm: function(){
						//$.alert('Confirmed!'); // shorthand.
						document.getElementById("scan").value  = '';
						//document.getElementById("masa_pajak").value  = '';
						//document.getElementById("tahun_pajak").value  = '';
						document.getElementById("scan").focus();
					}
				});
			}
			
			function alert_sukses(){
				$.alert({
					title: '<span style="color:#FF6B6B;"><i class="fa fa-info-circle" style="margin-right:5px;"></i>Information</span>',
					content: 'Success',
					confirm: function(){
						//$.alert('Confirmed!'); // shorthand.
						document.getElementById("scan").value  = '';
						//document.getElementById("masa_pajak").value  = '';
						//document.getElementById("tahun_pajak").value  = '';
						document.getElementById("scan").focus();
					}
				});
			}
			
			function url_redirect(options){
                 var $form = $("<form />");
                 
                 $form.attr("action",options.url);
                 $form.attr("method",options.method);
                 
                 for (var data in options.data)
                 $form.append('<input type="hidden" name="'+data+'" value="'+options.data[data]+'" />');
                  
                 $("body").append($form);
                 $form.submit();
            }
			
			$('#multi-export').confirm({
					title: '<span style="color:#FF6B6B;"><i class="fa fa-exclamation" style="margin-right:5px;"></i>Confirmation</span>',
					content: 'Are you sure? Do you want to export this faktur?',
					confirm: function(){
						var rows = getSelRows();
						var self = this;
							if (rows == "") {
								alert("no rows selected");
								return;
							} else { 
								$('<form />')
								  .hide()
								  .attr({ method : "post" })
								  .attr({ action : "<?php echo base_url();?>index.php/grid_scan/export_csv/"})
								  .append($('<input />')
									.attr("type","hidden")
									.attr({ "name" : "selectedRows[]" })
									.val(getSelRows())
								  )
								  .append('<input type="submit" />')
								  .appendTo($("body"))
								  .submit();
								/*url_redirect({url: "<?php echo base_url();?>index.php/grid_scan/export_csv/",
								  method: "post",
								  data: {"selectedRows[]": "0001687680089"}
								 });*/
								// window.location = '<?php echo base_url();?>index.php/grid_scan/export_csv/' + rows;
								//redirect('<?php echo base_url();?>index.php/grid_scan/export_csv/', 'rows');
								//$.ajax({
								 // url: '<?php echo base_url();?>index.php/grid_scan/export_csv/',
								 // data: {selectedRows: rows},
								 // type: 'POST',
								  //dataType: 'JSON',
								  //cache: false,
								 // success : function(){
									//  window.open('<?php echo base_url();?>index.php/grid_scan/export_csv/','_blank' );
									//		if(response_array.status == 'Sukses'){
									//			alert_sukses();
									//		}else if(response_array.status == 'Failed'){
									//			alert_fail();
									//		}
												//self.close()
									//		  },
								  //error: function() {
									//		  $.alert("There was an error. Try again please!");
									//		}
								  //error: onFailRegistered*/
								//}
								//});
								//return false;//alert(rows + ' row Ids were posted to a remote URL via $.ajax');
							//});
						}
					},
					cancel: function(){
						$.alert('Canceled!');
					}
				});

		</script>
	</body>
</html>