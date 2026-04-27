
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Modal Approval Log -->
<div class="modal fade" id="modalApprovalLog" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Approval Log</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body" id="approvalLogContent">
      	<input type="hidden" id="hdnid-approvallog" name="hdnid-approvallog">
        <table class="table table-striped table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th style="width: 50px;">Level</th>
              <th>Approver</th>
              <th>Status</th>
              <th>Approval Date</th>
            </tr>
          </thead>
          <tbody>
          
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>





<!-- Modal Reject Data -->
<div id="modal-reject-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-reject-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content">
			<form class="form-horizontal" id="frmRejectData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Reject Ijin
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to reject this Data?</p>
				<input type="hidden" name="id" id="id" value="">
				<input type="hidden" name="approval_level" id="approval_level" value="">
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


<!-- Modal Reject Data -->
<div id="modal-approve-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-approve-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content">
			<form class="form-horizontal" id="frmApproveData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Approval Ijin
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to approve this Data?</p>
				<input type="hidden" name="id" id="id" value="">
				<input type="hidden" name="approval_level" id="approval_level" value="">
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-approve-data" onclick="save_approve()">
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


<!-- Modal Reject Data -->
<!-- <div id="modal-success" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-success" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content">
			
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Delete Ijin
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center"><h1 style="cente">Success !</h1></p>
			</div>
			

		</div>
	</div>
	</div>
</div> -->


<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string




$(document).ready(function() {
   	$(function() {
   		
        $( "#attendance_date" ).datepicker({
        	/*startDate: '+0d'*/
        });

        $( "#attendance_in" ).datetimepicker({
        	/*startDate: '+0d'*/
        });

        $( "#attendance_out" ).datetimepicker({
        	/*startDate: '+0d'*/
        });

        
   	});
});

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
	//var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
	var baseUrl = getUrl .protocol + "//" + getUrl.host;



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

					$('select#employee').val(data.employee_id).trigger('change.select2');
					
					var date_attendance = dateFormat(data.date_attendance);
					$('[name="attendance_date"]').datepicker('setDate', date_attendance);

					$('[name="time_in"]').val(data.time_in);
					$('[name="attendance_in"]').val(data.date_attendance_in);

					
					$('[name="time_out"]').val(data.time_out);
					$('[name="attendance_out"]').val(data.date_attendance_out);
					$('[name="description"]').val(data.description);
					$('select#location').val(data.work_location).trigger('change.select2');
					$('[name="absence_type"]').val(data.attendance_type);
					

					$('[name="hdnattachment"]').val(data.attachment);
					if(data.attachment != '' && data.attachment != null){
						$('span.file_attachment').html('<img src="'+baseUrl+'/uploads/attendance_revision/'+data.attachment+'" width="150" height="150" >');
					}else{
						$('span.file_attachment').html('');
					}

					
					$('[name="hdnid-approvallog"]').val(data.id);
					document.getElementById('btnApprovalLog').style.display = 'block';


					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){
					$('[name="id"]').val(data.id);
					$('span.d_full_name').html(data.full_name);
					$('span.d_date_attendance').html(data.date_attendance);
					$('span.d_attendance_type').html(data.attendance_type);
					$('span.d_time_in').html(data.time_in);
					$('span.d_time_out').html(data.time_out);
					$('span.d_date_attendance_in').html(data.date_attendance_in);
					$('span.d_date_attendance_out').html(data.date_attendance_out);
					$('span.d_is_late').html(data.is_late == 'Y' ? 'Yes' : 'No');
					$('span.d_is_leaving_office_early').html(data.is_leaving_office_early == 'Y' ? 'Yes' : 'No');
					$('span.d_num_of_working_hours').html(data.num_of_working_hours);
					$('span.d_description').html(data.description);
					$('span.d_work_location').html(data.work_location);
					$('span.d_status').html(data.status);

					if(data.attachment != '' && data.attachment != null){
						$('span.attachment').html('<img src="'+baseUrl+'/uploads/attendance_revision/'+data.attachment+'" width="150" height="150" >');
					}else{
						$('span.attachment').html('');
					}

					$('[name="hdnid-approvallog"]').val(data.id);
					document.getElementById('btnApprovalLogView').style.display = 'block';


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

$('#employee').on('change', function () { 
	var employee 		= $("#employee option:selected").val();
	var attendance_date = $("#attendance_date").val();
 	
 	if(employee != '' && attendance_date != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataEmp',
			data: { employee: employee, attendance_date: attendance_date },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != false){ 	
					$('[name="absence_type"]').val(data.attendance_type);
					$('[name="time_in"]').val(data.time_in);
					$('[name="time_out"]').val(data.time_out);
				} else { 
					$('[name="absence_type"]').val('');
					$('[name="time_in"]').val('');
					$('[name="time_out"]').val('');

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
 		$('[name="absence_type"]').val('');
		$('[name="time_in"]').val('');
		$('[name="time_out"]').val('');
 	}

});



$('#attendance_date').on('change', function () { 
	var employee 		= $("#employee option:selected").val();
	var attendance_date = $("#attendance_date").val();
 	
 	if(employee != '' && attendance_date != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataEmp',
			data: { employee: employee, attendance_date: attendance_date },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != false){ 	
					$('[name="absence_type"]').val(data.attendance_type);
					$('[name="time_in"]').val(data.time_in);
					$('[name="time_out"]').val(data.time_out);
				} else { 
					$('[name="absence_type"]').val('');
					$('[name="time_in"]').val('');
					$('[name="time_out"]').val('');

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
 		$('[name="absence_type"]').val('');
		$('[name="time_in"]').val('');
		$('[name="time_out"]').val('');
 	}

});


function reject(id, approval_level){

	$('#modal-reject-data').modal('show');
	$('[name="id"]').val(id);
	$('[name="approval_level"]').val(approval_level);

}

function approve(id,approval_level){

	$('#modal-approve-data').modal('show');
	$('[name="id"]').val(id);
	$('[name="approval_level"]').val(approval_level);

}


function save_reject(){
	var id 	= $("#id").val();
	var approval_level 	= $("#approval_level").val();

	$('#modal-reject-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/rejectIjin',
			data: { id: id, approval_level: approval_level },
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
				        text: 'The data has been successfully rejected.',
				        timer: 5000,
				        showConfirmButton: false
				    }).then(() => {
				        location.reload();
				    });
				} else {
				    Swal.fire({
				        icon: 'error',
				        title: 'Failed!',
				        text: 'Failed to reject the data!',
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


function save_approve(){
	var id 	= $("#id").val();
	var approval_level 	= $("#approval_level").val();

	$('#modal-approve-data').modal('hide');
	
	if(id != ''){ 
		$.ajax({
			type: "POST",
	        url : module_path+'/approveIjin',
			data: { id: id, approval_level: approval_level },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				/*if(data != false){ 	
					alert("The data has been successfully approved.");
				} else { 
					alert("Failed to approve the data!");
				}*/

				if (data != false) {
				    Swal.fire({
				        icon: 'success',
				        title: 'Success!',
				        text: 'The data has been successfully approved.',
				        timer: 5000,
				        showConfirmButton: false
				    }).then(() => {
				        location.reload();
				    });
				} else {
				    Swal.fire({
				        icon: 'error',
				        title: 'Failed!',
				        text: 'Failed to approve the data!',
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

	
}


function approvalLog() {
    $('#modalApprovalLog').modal('show'); // buka modal

    var id = $("#hdnid-approvallog").val();

    if (id != '') { 
        $.ajax({
            type: "POST",
            url: module_path + '/getApprovalLog',
            data: { id: id },
            cache: false,
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                // tampilkan hasil ke tabel
                $('#approvalLogContent tbody').html(response.html);
            },
            error: function (jqXHR, textStatus, errorThrown) {
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
    } else {
        alert("Data not found");
    }
}




$('#leave_type').on('change', function () { 
	var leave_type 	= $("#leave_type option:selected").val();



	//if(leave_type == '5'){ //sick leave
		//$(function() {
	   		/*$( "#date_start" ).datepicker({
	        	startDate: _endDate
	        });

	        $( "#date_end" ).datepicker({
	        	startDate: _endDate
	    	});*/

	    	//$('#date_start').datepicker({autoclose: true, startDate: '+3d' });
		//});
		/*alert('sick');
		var minDate = new Date();
	    $('#enddate').datepicker('setStartDate', minDate);
	    $('#enddate').datepicker('setDate', minDate);


		$('#date_start').val('').datepicker("refresh");

	}else{ 
		$(function() {
			$( "#date_start" ).datepicker({
	        	startDate: '+1d'
	        });

	        $( "#date_end" ).datepicker({
	        	startDate: '+1d'
	    	});
		});
	}*/

});



<?php } ?>




</script>