<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string



$(document).ready(function() {
   	$(function() {
   		
        /*$( "#date_attendance" ).datepicker();*/
        /*$( "#attendance_in" ).datetimepicker();
        $( "#attendance_out" ).datetimepicker();*/
        
		
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
		  	[2,'desc'] 
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
	//local=> 
	var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
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
					var date_attendance_out = getFormattedDateTime();
					/*if(data.date_attendance_out != null && data.date_attendance_out != '0000-00-00 00:00:00'){
						var date_attendance_out = data.date_attendance_out;
					}*/

					$('[name="id"]').val(data.id);
					$('[name="date_attendance"]').val(data.date_attendance);
					$('[name="hdnempid"]').val(data.employee_id);
					$('[name="employee"]').val(data.employee_name);
					/*$('select#employee').val(data.employee_id).trigger('change.select2');*/
					$('[name="emp_type"]').val(data.attendance_type);
					$('[name="time_in"]').val(data.time_in);
					$('[name="time_out"]').val(data.time_out);
					$('[name="attendance_in"]').val(data.date_attendance_in);
					$('[name="attendance_out"]').val(date_attendance_out);
					$('[name="description"]').val(data.notes);
					$('select#location').val(data.work_location).trigger('change.select2');
					//document.getElementById('location').disabled = true;
					var latitude=''; var longitude='';
					if(data.lat_checkout != null && data.long_checkout != null){
						var latitude = data.lat_checkout;
						var longitude = data.long_checkout;
					}
					else if(data.lat_checkin != null && data.long_checkin != null){
						var latitude = data.lat_checkin;
						var longitude = data.long_checkin;
					}
					$('[name="latitude"]').val(latitude);
					$('[name="longitude"]').val(longitude);

					if(data.photo != '' && data.photo != null){
						$('span.photo').html('<img src="'+baseUrl+'/uploads/absensi/'+data.photo+'" width="150" height="150" >');
					}else{
						$('span.photo').html('');
					}
					

					
					$.uniform.update();
					$('#mfdata').text('Form Check-OUT');
					document.getElementById("submit-data").innerText = "Check Out";

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
					$('span.work_loc').html(data.work_location_name);
					$('span.description').html(data.notes);

					var latitude=''; var longitude='';
					if(data.lat_checkout != null && data.long_checkout != null){
						var latitude = data.lat_checkout;
						var longitude = data.long_checkout;
					}
					else if(data.lat_checkin != null && data.long_checkin != null){
						var latitude = data.lat_checkin;
						var longitude = data.long_checkin;
					}
					$('span.latitude').html(latitude);
					$('span.longitude').html(longitude);

					if(data.photo != '' && data.photo != null){
						$('span.photo').html('<img src="'+baseUrl+'/uploads/absensi/'+data.photo+'" width="150" height="150" >');
					}else{
						$('span.photo').html('');
					}
				
					
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


function getFormattedDateTime() {
  const now = new Date();

  const pad = (n) => n.toString().padStart(2, '0');

  const year = now.getFullYear();
  const month = pad(now.getMonth() + 1);     // bulan dimulai dari 0
  const day = pad(now.getDate());
  const hours = pad(now.getHours());
  const minutes = pad(now.getMinutes());
  const seconds = pad(now.getSeconds());

  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}




</script>