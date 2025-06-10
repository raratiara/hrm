

<!-- Modal Reject Data -->
<div id="modal-rfu-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-rfu-data" aria-hidden="true" style="padding-left: 600px">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:80%; text-align:center;">
			<form class="form-horizontal" id="frmRfuData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Request For Update 
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to request for update this Data?</p>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Reason</label>
					<div class="col-md-8">
						<?=$rfu_reason;?>
						<input type="hidden" name="id" id="id" value="">
					</div>
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-rfu-data" onclick="save_rfu()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn blue" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>



<!---Modal Reject-->
<div id="modal-reject-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-reject-data" aria-hidden="true" style="padding-left: 600px">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:80%; text-align:center;">
			<form class="form-horizontal" id="frmRejectData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Reject  
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to Reject this Data?</p>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Reason</label>
					<div class="col-md-8">
						<?=$reject_reason;?>
						<input type="hidden" name="id" id="id" value="">
					</div>
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-reject-data" onclick="save_reject()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn blue" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>





<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var modloc = '/_hrm/cash_advance/settlement_menu/';
var opsForm = 'form#frmInputData';
var locate = 'table.sett-list';
var wcount = 0; //for ca list row identify




$(document).ready(function() {
   	$(function() {
   		
        $( "#date" ).datepicker();
		
   	});
});


<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {
	/* load table list */
	myTable =
	$('#dynamic-table')
	.DataTable( {
		fixedHeader: {
			headerOffset: $('.page-header').outerHeight()
		},
		responsive: true,
		bAutoWidth: false,
		"aoColumnDefs": [
		  { "bSortable": false, "aTargets": [ 0,1 ] },
		  { "sClass": "text-center", "aTargets": [ 0,1 ] }
		],
		"aaSorting": [
		  	[2,'asc'] 
		],
		"sAjaxSource": module_path+"/get_data",
		"bProcessing": true,
        "bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
    } );

	<?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
	validator = $("#frmInputData").validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		ignore: "", // validate all fields including form hidden input
		rules: {
			title: {
				required: true
			},
			module_name: {
				required: true
			},
			url: {
				required: true
			}
		},
		messages: { // custom messages for radio buttons and checkboxes
		},
		errorPlacement: function (error, element) { // render error placement for each input type
			if (element.parent(".input-group").size() > 0) {
				error.insertAfter(element.parent(".input-group"));
			} else if (element.attr("data-error-container")) { 
				error.appendTo(element.attr("data-error-container"));
			} else if (element.parents('.radio-list').size() > 0) { 
				error.appendTo(element.parents('.radio-list').attr("data-error-container"));
			} else if (element.parents('.radio-inline').size() > 0) { 
				error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
			} else if (element.parents('.checkbox-list').size() > 0) {
				error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
			} else if (element.parents('.checkbox-inline').size() > 0) { 
				error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
			} else {
				error.insertAfter(element); // for other inputs, just perform default behavior
			}
		},
		highlight: function (element) { // hightlight error inputs
			$(element)
				.closest('.form-group').addClass('has-error'); // set error class to the control group
		},
		unhighlight: function (element) { // revert the change done by hightlight
			$(element)
				.closest('.form-group').removeClass('has-error'); // set error class to the control group
		},
		success: function (label) {
			label
				.closest('.form-group').removeClass('has-error'); // set success class to the control group
		}
	});
	<?php } ?>

	<?php if  (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
    //check all
    $("#check-all").click(function () {
        $(".data-check").prop('checked', $(this).prop('checked'));
    });
	<?php } ?>
})

<?php $this->load->view(_TEMPLATE_PATH . "common_module_js"); ?>
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_UPDATE == "1" || _USER_ACCESS_LEVEL_DETAIL == "1")) { ?>
function load_data()
{

	var getUrl = window.location;
	/*local=>*/ var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
	//var baseUrl = getUrl .protocol + "//" + getUrl.host;


    $.ajax({
		type: "POST",
        url : module_path+'/get_detail_data',
		data: { id: idx },
		cache: false,		
        dataType: "JSON",
        success: function(data)
        {
			if(data != false){
				if(save_method == 'update'){ 

					if(data.rowdata.status_id == 1 && data.isdirect == 1){
						
						$('[name="action_type"]').val('approval');
						document.getElementById("submit-data").innerText = "Approve";
						document.getElementById("submit-data").className = "btn btn-success";

						var modalFooter =  document.getElementById('mdlFooter');

						
						// Create a new button
						var rfuButton = document.createElement('button');
						rfuButton.innerText = 'RFU';
						rfuButton.className = 'btn btn-warning btnRfu';
						rfuButton.id = 'idbtnRfu';
						// Append the button to the footer
						modalFooter.appendChild(rfuButton);

						rfuButton.addEventListener('click', function() {
							$('#modal-rfu-data').modal('show');
							$('[name="id"]').val(data.rowdata.id);
						});


						//button reject
						var rejectButton = document.createElement('button');
						rejectButton.innerText = 'Reject';
						rejectButton.className = 'btn btn-danger btnReject';
						rejectButton.id = 'idbtnReject';
						// Append the button to the footer
						modalFooter.appendChild(rejectButton);

						rejectButton.addEventListener('click', function() {
							$('#modal-reject-data').modal('show');
							$('[name="id"]').val(data.rowdata.id);
						});

					}


					$('[name="settlement_number"]').val(data.rowdata.settlement_number);
					$('[name="settlement_date"]').val(data.rowdata.settlement_date);
					$('[name="settlement_amount"]').val(data.rowdata.settlement_amount);
					$('[name="ca_cost"]').val(data.rowdata.total_cost_ca);
					$('[name="prepared_by"]').val(data.rowdata.prepared_by_name);
					$('[name="id"]').val(data.rowdata.id);
					$('select#requested_by').val(data.rowdata.requested_by).trigger('change.select2');
					$('select#ca_number').val(data.rowdata.cash_advance_id).trigger('change.select2');
					$('[name="total_cost_sett"]').val(data.rowdata.total_cost);
					var total_cost_terbilang = terbilang(data.rowdata.total_cost);
      				$('[name="total_cost_terbilang_sett"]').val(total_cost_terbilang);
      				$('[name="no_rekening"]').val(data.rowdata.no_rekening);
      				$('[name="bank"]').val(data.rowdata.bank_rekening);
      				$('[name="nama_rekening"]').val(data.rowdata.nama_rekening);


      				setinputanSettlement(data.rowdata.total_cost_ca, data.rowdata.total_cost);


      				$('[name="hdndoc"]').val(data.rowdata.document);
					if(data.rowdata.document != '' && data.rowdata.document != null){
						$('span.file_doc').html('<img src="'+baseUrl+'/uploads/cashadvance/settlement/'+data.rowdata.document+'" width="150" height="150" >');
					}else{
						$('span.file_doc').html('');
					}


					//bukti transfer
					$('[name="hdndoc_buktitransfer"]').val(data.rowdata.bukti_transfer);
					if(data.rowdata.bukti_transfer != '' && data.rowdata.bukti_transfer != null){
						$('span.file_doc_buktitransfer').html('<img src="'+baseUrl+'/uploads/cashadvance/settlement/'+data.rowdata.bukti_transfer+'" width="150" height="150" >');
					}else{
						$('span.file_doc_buktitransfer').html('');
					}
					

					$.ajax({type: 'post',url: module_path+'/gensettrow',data: { id:data.rowdata.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					
					$('span.ca_number').html(data.rowdata.ca_number);
					$('span.ca_cost').html(data.rowdata.total_cost_ca);
					$('span.settlement_number').html(data.rowdata.settlement_number);
					$('span.settlement_amount').html(data.rowdata.settlement_amount);
					$('span.no_rekening').html(data.rowdata.no_rekening);
					$('span.bank').html(data.rowdata.bank_rekening);
					$('span.nama_rekening').html(data.rowdata.nama_rekening);
					$('span.prepared_by').html(data.rowdata.prepared_by_name);
					$('span.settlement_date').html(data.rowdata.settlement_date);
					$('span.requested_by').html(data.rowdata.requested_by_name);
					$('span.total_cost_sett').html(data.rowdata.total_cost);
					var total_cost_terbilang = terbilang(data.rowdata.total_cost);
					$('span.total_cost_terbilang_sett').html(total_cost_terbilang);


					setinputanSettlement(data.rowdata.total_cost_ca, data.rowdata.total_cost,'view');
					
					if(data.rowdata.document != '' && data.rowdata.document != null){
						$('span.document').html('<img src="'+baseUrl+'/uploads/cashadvance/settlement/'+data.rowdata.document+'" width="150" height="150" >');
					}else{
						$('span.document').html('');
					}

					//bukti transfer
					if(data.rowdata.bukti_transfer != '' && data.rowdata.bukti_transfer != null){
						$('span.bukti_transfer').html('<img src="'+baseUrl+'/uploads/cashadvance/settlement/'+data.rowdata.bukti_transfer+'" width="150" height="150" >');
					}else{
						$('span.bukti_transfer').html('');
					}
					
					var locate = 'table.sett-list-view';
					$.ajax({type: 'post',url: module_path+'/gensettrow',data: { id:data.rowdata.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

					
					$('#modal-view-data').modal('show');
				}
			} else {
				title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
				btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
				msg = '<p>Gagal peroleh data.</p>';
				var dialog = bootbox.dialog({
					message: title+'<center>'+msg+btn+'</center>'
				});
				if(response.status){
					setTimeout(function(){
						dialog.modal('hide');
					}, 1500);
				}
			}
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
			var dialog = bootbox.dialog({
				title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
				message: jqXHR.responseText,
				buttons: {
					confirm: {
						label: 'Ok',
						className: 'btn blue'
					}
				}
			});
        }
    });
}
<?php } ?>



$("#addsettrow").on("click", function () { 
	var ca_number 		= $("#ca_number").val();

	if(ca_number == ''){
		alert("Please choose FPU/FPP Number");
	}else{
		expire();
		var newRow = $("<tr>");
		$.ajax({type: 'post',url: module_path+'/gensettrow',data: { count:wcount },success: function (response) {
				newRow.append(response);
				$(locate).append(newRow);
				wcount++;
				
			}
		}).done(function() {
			tSawBclear('table.sett-list');
		});
	}


});


function del(idx,hdnid){
	
	if(hdnid != ''){ //delete dr table
		$.ajax({type: 'post',url: module_path+'/delrowDetailSett',data: { id:hdnid },success: function (response) {}
		}).done(function() {
			tSawBclear('table.sett-list');


			var sum=0; 
		    $(".total_amount_sett").each(function(index, element){ 
		        sum += +$(this).val(); 
		    });
		    
		    $('[name="total_cost_sett"]').val(sum);
		    updateTerbilang();

		});
	}

	//delete tampilan row
	var table = document.getElementById("tblDetailSett");
	table.deleteRow(idx);
}


$(document).on("keyup", ".total_amount_sett", function() {
    var sum = 0;
    $(".total_amount_sett").each(function(){
        sum += +$(this).val();
    });
    $("#total_cost_sett").val(sum);
    
});



function set_total_amount_sett(val){ 
	var row = val.dataset.id;  
	var amount = val.value;
	var pajak = $('[name="ppn_pph_sett['+row+']"]').val();

	if(pajak == 0){
		var total_amount = amount;
	}else{
		var amount_pajak = pajak/100;
		var total_amount = Number((amount*amount_pajak))+Number(amount);
	}
	

	$('[name="total_amount_sett['+row+']"]').val(total_amount);

	var sum=0; 
    $(".total_amount_sett").each(function(index, element){ 
        sum += +$(this).val(); 
    });
    
    $('[name="total_cost_sett"]').val(sum);
    updateTerbilang();

    var ca_cost = $("#ca_cost").val();
    setinputanSettlement(ca_cost, sum);

}

function set_total_amount2_sett(val){ 
	var row = val.dataset.id;  
	var pajak = val.value;
	var amount = $('[name="amount_sett['+row+']"]').val();

	if(pajak == 0){
		var total_amount = amount;
	}else{ 
		var amount_pajak = pajak/100; 
		var total_amount = Number((amount*amount_pajak))+Number(amount);
	}
	

	$('[name="total_amount_sett['+row+']"]').val(total_amount);

	var sum=0; 
    $(".total_amount_sett").each(function(index, element){ 
        sum += +$(this).val(); 
    });
    
    $('[name="total_cost_sett"]').val(sum);
    updateTerbilang();


    var ca_cost = $("#ca_cost").val();
    setinputanSettlement(ca_cost, sum);

    
}



function terbilang(n) {
    const angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

    function toWords(n) {
        n = Math.floor(n);
        if (n < 12) return angka[n];
        if (n < 20) return toWords(n - 10) + " Belas";
        if (n < 100) return toWords(n / 10) + " Puluh " + toWords(n % 10);
        if (n < 200) return "Seratus " + toWords(n - 100);
        if (n < 1000) return toWords(n / 100) + " Ratus " + toWords(n % 100);
        if (n < 2000) return "Seribu " + toWords(n - 1000);
        if (n < 1000000) return toWords(n / 1000) + " Ribu " + toWords(n % 1000);
        if (n < 1000000000) return toWords(n / 1000000) + " Juta " + toWords(n % 1000000);
        if (n < 1000000000000) return toWords(n / 1000000000) + " Miliar " + toWords(n % 1000000000);
        return "Angka terlalu besar";
    }

    return toWords(n).replace(/\s+/g, ' ').trim() + " Rupiah";
}


function updateTerbilang() { 
    const nilai = document.getElementById("total_cost_sett").value;
   
    if (nilai) {
      total_cost_terbilang = terbilang(nilai);
      $('[name="total_cost_terbilang_sett"]').val(total_cost_terbilang);
    } else {
      $('[name="total_cost_terbilang_sett"]').val("");
    }
}


function save_rfu(){
	var id 		= $("#id").val();
	var reason 	= $("#rfu_reason").val();

	$('#modal-rfu-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/rfu',
			data: { id: id, reason:reason },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if(data != false){ 	
					alert("The data has been successfully rfu.");
				} else { 
					alert("Failed to rfu the data!");
				}


	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
				var dialog = bootbox.dialog({
					title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
					message: jqXHR.responseText,
					buttons: {
						confirm: {
							label: 'Ok',
							className: 'btn blue'
						}
					}
				});
	        }
	    });
	}else{
		alert("Data not found!");
	}

	location.reload();


}



function save_reject(){
	var id 		= $("#id").val();
	var reason 	= $("#reject_reason").val();

	$('#modal-reject-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/reject',
			data: { id: id, reason:reason },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if(data != false){ 	
					alert("The data has been successfully reject.");
				} else { 
					alert("Failed to reject the data!");
				}


	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
				var dialog = bootbox.dialog({
					title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
					message: jqXHR.responseText,
					buttons: {
						confirm: {
							label: 'Ok',
							className: 'btn blue'
						}
					}
				});
	        }
	    });
	}else{
		alert("Data not found!");
	}

	location.reload();


}


$('#ca_number').on('change', function () { 
 	var ca_number = $("#ca_number option:selected").val();
 	
 	if(ca_number != ''){

 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataCashadvance',
			data: { id: ca_number },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 	

					$('[name="ca_cost"]').val(data[0].total_cost);
					$('[name="total_cost_sett"]').val(data[0].total_cost);
					var total_cost_terbilang = terbilang(data[0].total_cost);
      				$('[name="total_cost_terbilang_sett"]').val(total_cost_terbilang);
      				$('[name="settlement_amount"]').val('0');

					
					$.ajax({type: 'post',url: module_path+'/gensettrow',data: { id:ca_number },success: function (response) {
						var obj = JSON.parse(response);
						$(locate+' tbody').html(obj[0]);
						
						wcount=obj[1];
					}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

				} else { 
					

				}

	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
				var dialog = bootbox.dialog({
					title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
					message: jqXHR.responseText,
					buttons: {
						confirm: {
							label: 'Ok',
							className: 'btn blue'
						}
					}
				});
	        }
	    });

 	}

});


function setinputanSettlement(ca_cost, sett_cost, type=''){ 
	var requested_by = $("#requested_by option:selected").val();

	var selisih = Number(ca_cost)-Number(sett_cost);
    $('[name="settlement_amount"]').val(selisih);

    if(selisih != 0){
    	if(type == 'view'){ 
    		if(selisih > 0){ //ada sisa
	    		document.getElementById("div_bukti_transfer_view").style.display = "";
	    		document.getElementById("div_no_rekening_view").style.display = "none";
	    		document.getElementById("div_bank_view").style.display = "none";
	    		document.getElementById("div_nama_rekening_view").style.display = "none";
		    }else{
		    	document.getElementById("div_bukti_transfer_view").style.display = "none";
		    	document.getElementById("div_no_rekening_view").style.display = "";
	    		document.getElementById("div_bank_view").style.display = "";
	    		document.getElementById("div_nama_rekening_view").style.display = "";
		    }
    	}else{ 
    		if(selisih > 0){ //ada sisa
	    		document.getElementById("div_bukti_transfer").style.display = "";
	    		document.getElementById("div_no_rekening").style.display = "none";
	    		document.getElementById("div_bank").style.display = "none";
	    		document.getElementById("div_nama_rekening").style.display = "none";
		    }else{ 
		    	document.getElementById("div_bukti_transfer").style.display = "none";
		    	document.getElementById("div_no_rekening").style.display = "";
	    		document.getElementById("div_bank").style.display = "";
	    		document.getElementById("div_nama_rekening").style.display = "";

	    		if(requested_by != ''){ 
	    			$.ajax({
						type: "POST",
				        url : module_path+'/getDataEmp',
						data: { employee_id: requested_by },
						cache: false,		
				        dataType: "JSON",
				        success: function(data)
				        {  
							if(data != null){ 	

								$('[name="no_rekening"]').val(data[0].bank_acc_no);
				  				$('[name="bank"]').val(data[0].bank_name);
				  				$('[name="nama_rekening"]').val(data[0].bank_acc_name);

							} else { 
								$('[name="no_rekening"]').val('');
				  				$('[name="bank"]').val('');
				  				$('[name="nama_rekening"]').val('');
							}

				        },
				        error: function (jqXHR, textStatus, errorThrown)
				        {
							var dialog = bootbox.dialog({
								title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
								message: jqXHR.responseText,
								buttons: {
									confirm: {
										label: 'Ok',
										className: 'btn blue'
									}
								}
							});
				        }
				    });
	    		}else{ 
	    			$('[name="no_rekening"]').val('');
	  				$('[name="bank"]').val('');
	  				$('[name="nama_rekening"]').val('');
	    		}

		    }
    	}
    	
    }
}




</script>