<!-- Modal Reject Data -->
<div id="modal-reject-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-reject-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content">
			<form class="form-horizontal" id="frmRejectData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Delete Ijin
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to delete this Data?</p>
				<input type="hidden" name="id" id="id" value="">
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
   		
        $( "#date_start" ).datepicker({
        	startDate: '+0d'
        });

        $( "#date_end" ).datepicker({
        	startDate: '+0d'
    	});
		
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
					$('[name="id"]').val(data.id);
					$('[name="reason"]').val(data.reason);
					$('[name="date_start"]').val(data.date_leave_start);
					$('[name="date_end"]').val(data.date_leave_end);
				
					$('select#employee').val(data.employee_id).trigger('change.select2');
					$('select#leave_type').val(data.masterleave_id).trigger('change.select2');
					$("#employee ").val(data.employee_id).prop('disabled', true);
					
					$('span.sisa_cuti').html('');

					$('[name="hdnattachment"]').val(data.photo);
					if(data.photo != '' && data.photo != null){
						$('span.file_attachment').html('<img src="'+baseUrl+'/uploads/ijin/'+data.photo+'" width="150" height="150" >');
					}else{
						$('span.file_attachment').html('');
					}

					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.employee').html(data.full_name);
					$('span.leave_type').html(data.leave_name);
					$('span.date_start').html(data.date_leave_start);
					$('span.date_end').html(data.date_leave_end);
					$('span.reason').html(data.reason);

					if(data.photo != '' && data.photo != null){
						$('span.attachment').html('<img src="'+baseUrl+'/uploads/ijin/'+data.photo+'" width="150" height="150" >');
					}else{
						$('span.attachment').html('');
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

$('#employee').on('change', function () { 
	var employee 	= $("#employee option:selected").val();
 	
 	if(employee != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataSisaCuti',
			data: { employee: employee },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { console.log(data);
				if(data != false){ 	
					$('span.sisa_cuti').html('*Sisa Cuti : '+data[0].ttl_sisa_cuti);
					if(data[0].ttl_sisa_cuti == null){
						$('span.sisa_cuti').html('*Sisa Cuti : 0');
					}
				} else { 
					$('span.sisa_cuti').html('*Sisa Cuti : 0');

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
 		alert("Please choose Employee Name");
 	}

});


function reject(id){

	$('#modal-reject-data').modal('show');
	$('[name="id"]').val(id);

}

function approve(id){

	$('#modal-approve-data').modal('show');
	$('[name="id"]').val(id);

}


function save_reject(){
	var id 	= $("#id").val();

	$('#modal-reject-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/rejectIjin',
			data: { id: id },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if(data != false){ 	
					alert("The data has been successfully rejected.");
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


function save_approve(){
	var id 	= $("#id").val();

	$('#modal-approve-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/approveIjin',
			data: { id: id },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if(data != false){ 	
					alert("The data has been successfully approved.");
				} else { 
					alert("Failed to approve the data!");
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