

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
						<input type="hidden" name="approval_level" id="approval_level" value="">
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


<!-- Modal RFU Data -->
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
						<input type="hidden" name="approval_level" id="approval_level" value="">
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


<!-- Modal approve Data -->
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



<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string




$(document).ready(function() {
   	$(function() {
   		
        $( "#training_date" ).datetimepicker();
		
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
					$('[name="training_name"]').val(data.training_name);
					$('[name="training_date"]').val(data.training_date);
					$('[name="location"]').val(data.location);
					$('[name="trainer"]').val(data.trainer);
					$('[name="notes"]').val(data.notes);

					/*$('[name="hdndoc_sertifikat"]').val(data.file_sertifikat);
					if(data.file_sertifikat != '' && data.file_sertifikat != null){
						$('span.file_sertifikat').html('<img src="'+baseUrl+'/uploads/'+data.emp_code+'/'+data.file_sertifikat+'" width="150" height="150" >');
					}else{
						$('span.file_sertifikat').html('');
					}*/


					$('[name="hdnid-approvallog"]').val(data.id);
					document.getElementById('btnApprovalLog').style.display = 'block';

					if(data.status_id == 4){ //RFU
						document.getElementById('rfuReasonEdit').style.display = 'block';
						$('span.rfu_reason_edit').html(data.rfu_reason);
						
					}else{
						document.getElementById('rfuReasonEdit').style.display = 'none';
					}
					
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.participant').html(data.participant_names);
					$('span.training_name').html(data.training_name);
					$('span.training_date').html(data.training_date);
					$('span.location').html(data.location);
					$('span.trainer').html(data.trainer);
					$('span.notes').html(data.notes);
					$('span.created_by_name').html(data.created_by_name);
					

					// if(data.file_sertifikat != '' && data.file_sertifikat != null){
					// 	$('span.file_sertifikat').html('<img src="'+baseUrl+'/uploads/'+data.emp_code+'/'+data.file_sertifikat+'" width="150" height="150" >');
					// }else{
					// 	$('span.file_sertifikat').html('');
					// }



					$('[name="hdnid-approvallog"]').val(data.id);
					document.getElementById('btnApprovalLogView').style.display = 'block';

					if(data.status_id == 4){ //RFU
						document.getElementById('rfuReason').style.display = 'block';
						$('span.rfu_reason').html(data.rfu_reason);
					}else{
						document.getElementById('rfuReason').style.display = 'none';
					}

					if(data.status_id == 3){ //Reject
						document.getElementById('rejectReason').style.display = 'block';
						$('span.reject_reason').html(data.reject_reason);
					}else{
						document.getElementById('rejectReason').style.display = 'none';
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

function rfu(id, approval_level){

	$('#modal-rfu-data').modal('show');
	$('[name="id"]').val(id);
	$('[name="approval_level"]').val(approval_level);

}


function save_reject(){
	var id 	= $("#id").val();
	var approval_level 	= $("#approval_level").val();
	var reject_reason 	= $("#reject_reason").val();

	$('#modal-reject-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/reject',
			data: { id: id, approval_level: approval_level, reject_reason:reject_reason },
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
		//alert("Data not found!");
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
	        url : module_path+'/approve',
			data: { id: id, approval_level:approval_level },
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
		//alert("Data not found!");
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


function save_rfu(){
	var id 		= $("#id").val();
	var reason 	= $("#rfu_reason").val();
	var approval_level 	= $("#approval_level").val();

	$('#modal-rfu-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/rfu',
			data: { id: id, reason:reason, approval_level:approval_level },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if (data != false) {
				    Swal.fire({
				        icon: 'success',
				        title: 'Success!',
				        text: 'The data has been successfully rfu.',
				        timer: 5000,
				        showConfirmButton: false
				    }).then(() => {
				        location.reload();
				    });
				} else {
				    Swal.fire({
				        icon: 'error',
				        title: 'Failed!',
				        text: 'Failed to rfu the data!',
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


</script>