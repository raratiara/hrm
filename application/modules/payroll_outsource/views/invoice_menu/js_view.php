
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />




<div id="modal-invoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-invoice" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportInvoice" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Invoice
					<input type="hidden" id="hdninvoiceid" name="hdninvoiceid" />
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>

				<!-- <button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReport()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button> -->

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadInvoice_pdf()">
					<i class="fa fa-download"></i>
					Download PDF
				</button>
				
				<button class="btn" style="background-color: #fc596b; color: white; border-radius: 2px !important;" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Close
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>



<div id="modal-rincian-biaya" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-rincian-biaya" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportRincianBiaya" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Rincian Biaya
					<input type="hidden" id="hdninvoiceid2" name="hdninvoiceid2" />
				</div>
			</div>
		 	</form>

			<div class="modal-footer no-margin-top">
				<center>

				<!-- <button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReport()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button> -->

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadRincianBiaya_pdf()">
					<i class="fa fa-download"></i>
					Download PDF
				</button>
				
				<button class="btn" style="background-color: #fc596b; color: white; border-radius: 2px !important;" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Close
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>


<div id="modal-berita-acara-pekerjaan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-berita-acara-pekerjaan" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportBeritaAcaraPekerjaan" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Berita Acara Pekerjaan
					<input type="hidden" id="hdninvoiceid3" name="hdninvoiceid3" />
				</div>
			</div>
		 	</form>

			<div class="modal-footer no-margin-top">
				<center>

				<!-- <button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReport()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button> -->

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadBeritaAcaraPekerjaan_pdf()">
					<i class="fa fa-download"></i>
					Download PDF
				</button>
				
				<button class="btn" style="background-color: #fc596b; color: white; border-radius: 2px !important;" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Close
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



$(document).ready(function() {
   	$('input[name="jatuh_tempo"]').datepicker();
   
});


function toYYYYMMDD(dateStr) {
  const parts = dateStr.split('/');
  const date = new Date(`${parts[2]}-${parts[0]}-${parts[1]}`); // YYYY-MM-DD
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}


function subFilter(){
	var flproject = $("#flproject option:selected").val();
	var perioddate = $("#perioddate").val();

	if(flproject == ''){
		flproject=0;
	}

	/*fldatestart=0;
	fldateend=0;
	if(perioddate != ''){
		var myArray = perioddate.split(" - ");
		var start = myArray[0];
		var end = myArray[1];

		fldatestart=toYYYYMMDD(start);
		fldateend=toYYYYMMDD(end);
	}*/
	

	
	$('#dynamic-table').DataTable().clear().destroy(); 
	$('#dynamic-table')
	.DataTable( {
		fixedHeader: {
			headerOffset: $('.page-header').outerHeight()
		},
		responsive: true,
		bAutoWidth: false,
		"aoColumnDefs": [
		  { "bSortable": false, "aTargets": [ 0,1 ] },
		  { "sClass": "text-center", "aTargets": [ 0,1 ] },
		],
		"aaSorting": [
		  	[2,'asc'] 
		],
		"sAjaxSource": module_path+"/get_data?flproject="+flproject+"",
		"bProcessing": true,
        "bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
    } );

}


function getInvoice(invoice_id){
	
	$('#modal-invoice').modal('show');

	$('[name="hdninvoiceid"]').val(invoice_id);

}


function getRincianBiaya(invoice_id){
	
	$('#modal-rincian-biaya').modal('show');

	$('[name="hdninvoiceid2"]').val(invoice_id);

}

function getBeritaAcaraPekerjaan(invoice_id){
	
	$('#modal-berita-acara-pekerjaan').modal('show');

	$('[name="hdninvoiceid3"]').val(invoice_id);

}


function downloadInvoice(){

	var flemployee = $("#flemployee option:selected").val();
	var perioddate = $("#perioddate").val();

	if(flemployee == ''){
		flemployee=0;
	}

	fldatestart=0;
	fldateend=0;
	if(perioddate != ''){
		var myArray = perioddate.split(" - ");
		var start = myArray[0];
		var end = myArray[1];

		fldatestart=toYYYYMMDD(start);
		fldateend=toYYYYMMDD(end);
	}
	
	
	send_url = module_path+'/getAbsenceReport?flemployee='+flemployee+'&fldatestart='+fldatestart+'&fldateend='+fldateend+'';
	formData = $('#frmReportData').serialize();
	window.location = send_url+'&'+formData;
	$('#modal-invoice').modal('hide');
	
}


function downloadInvoice_pdf(){

	var invoice_id = $("#hdninvoiceid").val();


	if(invoice_id != ''){
		send_url = module_path+'/getInvoiceReport_pdf?id='+invoice_id+'';
		formData = $('#frmReportData').serialize();
		window.location = send_url+'&'+formData;
		$('#modal-invoice').modal('hide');
	}else{
		alert("Invoice tidak ditemukan");
	}

	
}


function downloadRincianBiaya_pdf(){
	var invoice_id = $("#hdninvoiceid2").val();

	if(invoice_id != ''){

		send_url = module_path+'/getRincianBiayaReport_pdf?id='+invoice_id+'';
		formData = $('#frmReportRincianBiaya').serialize();
		window.location = send_url;
		$('#modal-rincian-biaya').modal('hide');

	}else{
		alert("Data tidak ditemukan");
	}

	
}


function downloadBeritaAcaraPekerjaan_pdf(){
	var invoice_id = $("#hdninvoiceid3").val();

	if(invoice_id != ''){
		
		send_url = module_path+'/getBeritaAcaraPekerjaanReport_pdf?id='+invoice_id+'';
		formData = $('#frmReportBeritaAcaraPekerjaan').serialize();
		window.location = send_url;
		$('#modal-berita-acara-pekerjaan').modal('hide');

	}else{
		alert("Data tidak ditemukan");
	}

	
}


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
		/*"sAjaxSource": module_path+"/get_data?flemployee=12",*/
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
					$('[name="id"]').val(data.id);
					$('[name="date_attendance"]').val(data.date_attendance);
					$('select#employee').val(data.employee_id).trigger('change.select2');
					$('[name="emp_type"]').val(data.attendance_type);
					$('[name="time_in"]').val(data.time_in);
					$('[name="time_out"]').val(data.time_out);
					$('[name="attendance_in"]').val(data.date_attendance_in);
					$('[name="attendance_out"]').val(data.date_attendance_out);
				
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.date_attendance').html(data.date_attendance);
					$('span.employee').html(data.employee_name);
					$('span.emp_type').html(data.attendance_type);
					$('span.time_in').html(data.time_in);
					$('span.time_out').html(data.time_out);
					$('span.attendance_in').html(data.date_attendance_in);
					$('span.attendance_out').html(data.date_attendance_out);
				
					
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


$('#employee').on('change', function () { 
 	var empid = $("#employee option:selected").val();
 	
 	if(empid != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataEmp',
			data: { empid: empid },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 	
					$('#emp_type').val(data.name);
					$('#time_in').val(data.time_in);
					$('#time_out').val(data.time_out);

				} else { 
					$('#emp_type').val('');
					$('#time_in').val('');
					$('#time_out').val('');

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
 		alert("Please choose Order Name");
 	}

});



$('#project').on('change', function () {
    var project = $(this).val();

    $('#periode_gaji')
        .val(null)
        .empty()
        .append('<option value=""></option>')
        .trigger('change.select2');

    $('[name="start_absen"]').val('');
	$('[name="end_absen"]').val('');

    if (project) {
        getPeriodeGaji(project);
    }
});



function getPeriodeGaji(project,selected='',idVal=''){ 

	if(project != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataPeriodeGaji',
			data: { project: project },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					
					var $el = $("#periode_gaji");

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msperiode, function(key,value) {
						$el.append($("<option></option>")
				     	.attr("value", value.id).text(value.periode_penggajian));
					  	
					});

					if(selected=='selected'){
						$('select#periode_gaji').val(idVal).trigger('change.select2');
					}
					
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

}


$('#periode_gaji').on('change', function () {
    var payroll_id = $(this).val();


    $('[name="start_absen"]').val('');
	$('[name="end_absen"]').val('');

    if (payroll_id) {
        getPayroll(payroll_id);
    }
});



function getPayroll(payroll_id,selected='',idVal=''){ 

	if(payroll_id != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataPayroll',
			data: { payroll_id: payroll_id },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					
					$('[name="start_absen"]').val(data[0].tgl_start_absen);
					$('[name="end_absen"]').val(data[0].tgl_end_absen);
					
				} else { 
					
					$('[name="start_absen"]').val('');
					$('[name="end_absen"]').val('');
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

}





</script>