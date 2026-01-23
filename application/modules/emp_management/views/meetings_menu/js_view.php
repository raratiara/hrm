<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="modal-cancel-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-cancel-data" aria-hidden="true" style="padding-left: 600px">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:80%; text-align:center;">
			<form class="form-horizontal" id="frmCancelData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Cancel  
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to Cancel this Data?</p>
				<div class="form-group">
					<input type="hidden" name="id" id="id" value="">
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-reject-data" onclick="save_cancel()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn red" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>


<div id="modal-checkin-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-checkin-data" aria-hidden="true" style="padding-left: 600px">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:80%; text-align:center;">
			<form class="form-horizontal" id="frmCheckinData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Check-IN  
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Please fill the Code</p>
				<div class="form-group">
					<!-- <label class="col-md-4 control-label no-padding-right">Code</label> -->
					<div class="col-md-4" style="margin-left:320px">
						<?=$txtcode_checkin;?>
						<input type="hidden" name="id" id="id" value="">
					</div>
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-checkin-data" onclick="save_checkin()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn red" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>


<div id="modal-checkout-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-checkout-data" aria-hidden="true" style="padding-left: 600px">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:80%; text-align:center;">
			<form class="form-horizontal" id="frmCheckoutData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Check-OUT 
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to Check out?</p>
				<div class="form-group">
					<input type="hidden" name="id" id="id" value="">
					<!-- <label class="col-md-4 control-label no-padding-right">Code</label>
					<div class="col-md-4" style="margin-left:320px">
						<?=$txtcode_checkin;?>
						<input type="hidden" name="id" id="id" value="">
					</div> -->
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-checkin-data" onclick="save_checkout()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn red" data-dismiss="modal">
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
var baseUrl = "<?= base_url(); ?>";
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string



$(document).ready(function() {
   	$(function() {
   		
        $( "#meeting_date" ).datepicker();
        $( "#start_time" ).timepicker();
        $( "#end_time" ).timepicker();
		
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
		  	[5,'desc'] //submit date desc
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

					/*$selected = explode(',', $row->participants);
					$('#participants').val(<?= json_encode($selected) ?>).trigger('change');*/


					$('[name="id"]').val(data.rowdata.id);
					$('[name="meeting_name"]').val(data.rowdata.meeting_name);
					var meeting_date = dateFormat(data.rowdata.meeting_date);
					$('[name="meeting_date"]').datepicker('setDate', meeting_date);
					$('[name="type"][value="'+data.rowdata.type+'"]').prop('checked', true);

					if(data.rowdata.type == 'custom'){
						$('#inpStartTime').show();
 						$('#inpEndTime').show();
						$('[name="start_time"]').val(data.rowdata.start_time_display);
						$('#start_time').timepicker('setTime', data.rowdata.start_time_display);

						$('[name="end_time"]').val(data.rowdata.end_time_display );
						$('#end_time').timepicker('setTime', data.rowdata.end_time_display);
					}else{
						$('#inpStartTime').hide();
 						$('#inpEndTime').hide();
					}

					$('[name="description"]').val(data.rowdata.description);
					$('select#meeting_room').val(data.rowdata.meeting_room_id).trigger('change.select2');
					$('#participants').val(data.participants).trigger('change');
					$('[name="code"]').val(data.rowdata.code);
					

					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.meeting_name').html(data.rowdata.meeting_name);
					$('span.meeting_date').html(data.rowdata.meeting_date);
					$('span.type').html(data.rowdata.type);

					if(data.rowdata.type == 'custom'){
						$('#inpStartTimeView').show();
 						$('#inpEndTimeView').show();
						$('span.start_time').html(data.rowdata.start_time_display);
						$('span.end_time').html(data.rowdata.end_time_display);
					}else{
						$('#inpStartTimeView').hide();
 						$('#inpEndTimeView').hide();
					}


					$('span.meeting_room').html(data.rowdata.room_name);
					$('span.description').html(data.rowdata.description);
					$('span.participants').html(data.rowdata.participants_name);
					$('span.code').html(data.rowdata.code);
					
					
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


function dateFormat(tanggal) {
    if (!tanggal || tanggal === "0000-00-00") {
        return ""; // kosongkan saja, jangan ditampilkan
    }

    let parts = tanggal.split("-");
    if (parts.length !== 3) {
        return tanggal; // fallback kalau format aneh
    }

    return `${parts[1]}/${parts[2]}/${parts[0]}`;
}


 	


$('input[name="type"]').on('change', function () {
  	var type = $('input[name="type"]:checked').val();
  
  	if(type == 'custom'){ //Absence
 		$('#inpStartTime').show();
 		$('#inpEndTime').show();
 	}else{
 		$('#inpStartTime').hide();
 		$('#inpEndTime').hide();
 	}


});


function cancel(id){

	$('#modal-cancel-data').modal('show');
	$('[name="id"]').val(id);

}

function checkin(id){

	$('#modal-checkin-data').modal('show');
	$('[name="id"]').val(id);

}

function checkout(id){

	$('#modal-checkout-data').modal('show');
	$('[name="id"]').val(id);

}


function save_cancel(){
	var id 	= $("#id").val();

	$('#modal-cancel-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/cancel',
			data: { id: id},
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				/*if(data != false){ 	
					alert("The data has been successfully rejected.");
				} else { 
					alert("Failed to reject the data!");
				}*/

				if (data != false) {
				    Swal.fire({
				        icon: 'success',
				        title: 'Success!',
				        text: 'The data has been successfully cancelled.',
				        timer: 5000,
				        showConfirmButton: false
				    }).then(() => {
				        location.reload();
				    });
				} else {
				    Swal.fire({
				        icon: 'error',
				        title: 'Failed!',
				        text: 'Failed to cancel the data!',
				        timer: 5000,
				        showConfirmButton: false
				    }).then(() => {
				        location.reload();
				    });
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
		/*alert("Data not found!");*/

		Swal.fire({
	        icon: 'error',
	        title: 'Failed!',
	        text: 'Data not found!',
	        timer: 5000,
	        showConfirmButton: false
	    }).then(() => {
	        location.reload();
	    });
	}

	//location.reload();


}

function save_checkin(){
	var id 				= $("#id").val();
	var code_checkin 	= $("#code_checkin").val();

	$('#modal-checkin-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/checkin',
			data: { id: id, code_checkin: code_checkin},
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if (data != false) {
					if(data == 'id / code not found'){
						icon = 'error';
						title = 'Failed!';
						text = 'Id / Code not found';
					}else if(data == 'code not valid'){
						icon = 'error';
						title = 'Failed!';
						text = 'Code not valid';
					}else if(data == 'the check-in time has expired'){
						icon = 'error';
						title = 'Failed!';
						text = 'The check-in time has expired';
					}else{
						icon = 'success';
						title = 'Success!';
						text = 'You have successfully checked in';
					}

				    Swal.fire({
				        icon: icon,
				        title: title,
				        text: text,
				        timer: 5000,
				        showConfirmButton: false
				    }).then(() => {
				        location.reload();
				    });
				} else {
				    Swal.fire({
				        icon: 'error',
				        title: 'Failed!',
				        text: 'Failed to checkin!',
				        timer: 5000,
				        showConfirmButton: false
				    }).then(() => {
				        location.reload();
				    });
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
		/*alert("Data not found!");*/

		Swal.fire({
	        icon: 'error',
	        title: 'Failed!',
	        text: 'Data not found!',
	        timer: 5000,
	        showConfirmButton: false
	    }).then(() => {
	        location.reload();
	    });
	}

	//location.reload();


}


function save_checkout(){
	var id 				= $("#id").val();
	

	$('#modal-checkout-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/checkout',
			data: { id: id},
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				/*if(data != false){ 	
					alert("The data has been successfully rejected.");
				} else { 
					alert("Failed to reject the data!");
				}*/

				if (data != false) {
					if(data == 'id not found'){
						icon = 'error';
						title = 'Failed!';
						text = 'Id not found';
					}else{
						icon = 'success';
						title = 'Success!';
						text = 'You have successfully checked out';
					}

				    Swal.fire({
				        icon: icon,
				        title: title,
				        text: text,
				        timer: 5000,
				        showConfirmButton: false
				    }).then(() => {
				        location.reload();
				    });
				} else {
				    Swal.fire({
				        icon: 'error',
				        title: 'Failed!',
				        text: 'Failed to checkout!',
				        timer: 5000,
				        showConfirmButton: false
				    }).then(() => {
				        location.reload();
				    });
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
		/*alert("Data not found!");*/

		Swal.fire({
	        icon: 'error',
	        title: 'Failed!',
	        text: 'Data not found!',
	        timer: 5000,
	        showConfirmButton: false
	    }).then(() => {
	        location.reload();
	    });
	}

	//location.reload();


}


</script>