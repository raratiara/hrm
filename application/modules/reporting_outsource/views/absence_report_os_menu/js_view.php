
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />




<div id="modal-reportosabsen-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-reportosabsen-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportDataOSAbsen" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Absensi OS
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				
				<button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReportOS_absen()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button>

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReportOS_absen_pdf()">
					<i class="fa fa-download"></i>
					Download PDF
				</button>
				
				<button class="btn" style="background-color: #fc596b; color: black; border-radius: 2px !important;" data-dismiss="modal">
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
   	$('input[name="date_attendance"]').datepicker();
   	$('input[name="attendance_in"]').datetimepicker();
   	$('input[name="attendance_out"]').datetimepicker();
	$('input[name="perioddate"]').daterangepicker();
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
	var flemployee = $("#flemployee option:selected").val();
	var flproject = $("#flproject option:selected").val();
	var perioddate = $("#perioddate").val();

	if(flproject == ''){
		flproject = 0;
	}

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
		// "sAjaxSource": module_path+"/get_data?flemployee="+flemployee+"&fldatestart="+fldatestart+"&fldateend="+fldateend+"",
		"sAjaxSource": module_path+"/get_data?flemployee="+flemployee+"&flproject="+flproject+"&fldatestart="+fldatestart+"&fldateend="+fldateend+"",
		"bProcessing": true,
        "bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
    } );

}


function getReportOS_absen(){
	
	$('#modal-reportosabsen-data').modal('show');
}


function downloadReportOS_absen(){

	var flemployee = $("#flemployee option:selected").val();
	var flproject = $("#flproject option:selected").val();
	var perioddate = $("#perioddate").val();

	if(flproject == ''){
  		flproject = 0;
	}

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
	
	
	// send_url = module_path+'/getAbsenceReport?flemployee='+flemployee+'&fldatestart='+fldatestart+'&fldateend='+fldateend+'';
	send_url = module_path+'/getAbsenceReport?flemployee='+flemployee+'&flproject='+flproject+'&fldatestart='+fldatestart+'&fldateend='+fldateend+'';
	formData = $('#frmReportDataOSAbsen').serialize();
	window.location = send_url+'&'+formData;
	$('#modal-reportosabsen-data').modal('hide');
	
}


function downloadReportOS_absen_pdf(){

	var flemployee = $("#flemployee option:selected").val();
	var flproject = $("#flproject option:selected").val();
	var perioddate = $("#perioddate").val();

	if(flproject == ''){
  		flproject = 0;
	}

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
	
	
	// send_url = module_path+'/getAbsenceReport?flemployee='+flemployee+'&fldatestart='+fldatestart+'&fldateend='+fldateend+'';
	send_url = module_path+'/getAbsenceReport_pdf?flemployee='+flemployee+'&flproject='+flproject+'&fldatestart='+fldatestart+'&fldateend='+fldateend+'';
	formData = $('#frmReportDataOSAbsen').serialize();
	window.location = send_url+'&'+formData;
	$('#modal-reportosabsen-data').modal('hide');
	
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
		  { "sClass": "text-center", "aTargets": [ 0,1 ] },
		  { "flemployee": 12,},
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



</script>