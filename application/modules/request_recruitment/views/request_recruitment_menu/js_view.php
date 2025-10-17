



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



<!-- Modal approve Data -->
<div id="modal-approve-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-approve-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px; margin-left:400px">
			<form class="form-horizontal" id="frmApproveData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Approval 
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




<!-- Modal Reject Data -->
<div id="modal-reject-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-reject-data" aria-hidden="true" style="padding-left: 600px">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px; margin-left:400px">
			<form class="form-horizontal" id="frmRejectData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Reject 
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to reject this Data?</p>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Reason</label>
					<div class="col-md-8">
						<?=$txtrejectreason;?>
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
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
//in LOCAL  var modloc = '/_hrm/performance_management/performance_appraisal_menu/';
var modloc = '/request_recruitment/request_recruitment_menu/';
var opsForm = 'form#frmInputData';
var locate = 'table.requirement-list';
var locate2 = 'table.job-list';
var wcount = 0; //for ca list row identify
var wcount2 = 0;



$(document).ready(function() {
   	$(function() {
   		
       	$('input[name="request_date"]').datepicker();
       	$('input[name="required_date"]').datepicker();

				const acc2 = document.querySelector('#accordion_requirement');
				const panel2 = document.querySelector('#tabrequirement');
		  	acc2.addEventListener('click', function() {
			    acc2.classList.toggle('active');
			    panel2.classList.toggle('show');
		  	});

	  		const acc3 = document.querySelector('#accordion_job');
				const panel3 = document.querySelector('#tabjob');
		  	acc3.addEventListener('click', function() {
			    acc3.classList.toggle('active');
			    panel3.classList.toggle('show');
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

					if(data.rowdata.status == 'draft'){
						document.getElementById("submit-data").style.display = "";
					} else{
						document.getElementById("submit-data").style.display = "none";
					}

					/*if(data.rowdata.status == 'waiting_approval' && data.isdirect == 1){*/
					if(data.rowdata.status == 'waiting_approval' && (data.isdirect == 1 || data.rowdata.is_approver == 1)){

						//var modalFooter =  document.getElementById('mdlFooter');

						// Cek apakah tombol Reject sudah ada
						// var existingReject = modalFooter.querySelector('.btnReject');
					 	// if (!existingReject) {
					 	// 	// Create a new button
						// 	var rejectButton = document.createElement('button');
						// 	rejectButton.innerText = 'Reject';
						// 	rejectButton.id = 'btn-reject'; 
						// 	rejectButton.className = 'btn btn-danger btnReject';
						// 	rejectButton.style.marginLeft = '8px';

						// 	// Append the button to the footer
						// 	modalFooter.appendChild(rejectButton);

						// 	rejectButton.addEventListener('click', function() {
						// 		$('#modal-reject-data').modal('show');
						// 		$('[name="id"]').val(data.rowdata.id);
						// 	});
					 	// }

					 	var container = document.querySelector('.act-container-btn');

						if (!document.getElementById('btn-reject')) { 
								//button Approve
								var ApproveButton = document.createElement('button');
						    ApproveButton.id = 'btn-approve';
						    ApproveButton.className = 'btn btn-success btnApprove';
						    ApproveButton.innerHTML = 'Approve';
						    ApproveButton.style.marginLeft = '8px';

						    // Tambahkan ke sisi kiri bersama tombol-tombol utama
						    container.appendChild(ApproveButton);

						    ApproveButton.addEventListener('click', function () {
						        $('#modal-approve-data').modal('show');
						        $('[name="id"]').val(data.rowdata.id);
						        $('[name="approval_level"]').val(data.rowdata.current_approval_level);
						    });


								//button Reject									
						    var rejectButton = document.createElement('button');
						    rejectButton.id = 'btn-reject';
						    rejectButton.className = 'btn btn-danger btnReject';
						    rejectButton.innerHTML = 'Reject';
						    rejectButton.style.marginLeft = '8px';

						    // Tambahkan ke sisi kiri bersama tombol-tombol utama
						    container.appendChild(rejectButton);

						    rejectButton.addEventListener('click', function () {
						        $('#modal-reject-data').modal('show');
						        $('[name="id"]').val(data.rowdata.id);
						        $('[name="approval_level"]').val(data.rowdata.current_approval_level);
						    });
						}else{
							document.getElementById("btn-reject").style.display = "";
						}

						
					}


					$('[name="id"]').val(data.rowdata.id);
					$('[name="req_number"]').val(data.rowdata.request_number);
					$('[name="subject"]').val(data.rowdata.subject);
					var request_date = dateFormat(data.rowdata.request_date);
					$('[name="request_date"]').datepicker('setDate', request_date);
					var required_date = dateFormat(data.rowdata.required_date);
					$('[name="required_date"]').datepicker('setDate', required_date);
					$('[name="headcount"]').val(data.rowdata.headcount);
					$('[name="justification"]').val(data.rowdata.justification);
					var status_emp = ucwords(data.rowdata.status_emp);
					$('select#empstatus').val(status_emp).trigger('change.select2');
					$('select#section').val(data.rowdata.section_id).trigger('change.select2');
					$('select#joblevel').val(data.rowdata.job_level_id).trigger('change.select2');
					$('select#request_by').val(data.rowdata.requested_by).trigger('change.select2');


					$.ajax({type: 'post',url: module_path+'/genreqrow',data: { id:data.rowdata.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						
						tSawBclear(locate);
						
					});

					$.ajax({type: 'post',url: module_path+'/genjobrow',data: { id:data.rowdata.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate2+' tbody').html(obj[0]);
							
							wcount2=obj[1];
						}
					}).done(function() {
						
						tSawBclear(locate2);
						
					});
				
				
					if(data.rowdata.status == 'draft'){
						document.getElementById("btnDraft").style.display = "";
					}else{
						document.getElementById("btnDraft").style.display = "none";
					}


					$('[name="hdnid-approvallog"]').val(data.rowdata.id);
					document.getElementById('btnApprovalLog').style.display = 'block';
				
				
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.request_number').html(data.rowdata.request_number);
					$('span.subject').html(data.rowdata.subject);
					$('span.request_date').html(data.rowdata.request_date);
					$('span.required_date').html(data.rowdata.required_date);
					$('span.section').html(data.rowdata.section_name);
					$('span.headcount').html(data.rowdata.headcount);
					$('span.job_level').html(data.rowdata.job_level_name);
					var status_emp = ucwords(data.rowdata.status_emp);
					$('span.emp_status').html(status_emp);
					$('span.justification').html(data.rowdata.justification);
					$('span.request_by').html(data.rowdata.requested_by_name);


					$.ajax({type: 'post',url: module_path+'/genreqrow',data: { id:data.rowdata.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});


					$.ajax({type: 'post',url: module_path+'/genjobrow',data: { id:data.rowdata.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate2+' tbody').html(obj[0]);
							
							wcount2=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate2);
						///expenseviewadjust(lstatus);
					});


					$('[name="hdnid-approvallog"]').val(data.rowdata.id);
					document.getElementById('btnApprovalLogView').style.display = 'block';

					if(data.rowdata.status_id == 3){ //Reject
						document.getElementById('rejectReason').style.display = 'block';
						$('span.reject_reason').html(data.rowdata.reject_reason);
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


function ucwords(str) {
  return str
    .toLowerCase()
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
}


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


$("#addrequirement").on("click", function () { 
	
	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/genreqrow',data: { count:wcount },success: function (response) {
			newRow.append(response);
			$(locate).append(newRow);
			wcount++;
		}
	}).done(function() {
		tSawBclear('table.requirement-list');
	});
	
});


$("#addjob").on("click", function () { 
	
	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/genjobrow',data: { count:wcount2 },success: function (response) {
			newRow.append(response);
			$(locate2).append(newRow);
			wcount2++;
		}
	}).done(function() {
		tSawBclear('table.job-list');
	});
	
});


function del(idx,hdnid){
	
	if(hdnid != ''){ //delete dr table
		$.ajax({type: 'post',url: module_path+'/delrowDetailReq',data: { id:hdnid },success: function (response) {
				
			}
		}).done(function() {
			tSawBclear('table.requirement-list');
		});
	}

	//delete tampilan row
	var table = document.getElementById("tblDetailRequirement");
	table.deleteRow(idx);
	
}


function delJob(idx,hdnid){
	
	if(hdnid != ''){ //delete dr table
		$.ajax({type: 'post',url: module_path+'/delrowDetailJob',data: { id:hdnid },success: function (response) {
				
			}
		}).done(function() {
			tSawBclear('table.job-list');
		});
	}

	//delete tampilan row
	var table = document.getElementById("tblDetailJob");
	table.deleteRow(idx);
	
}



function save_reject(){
	var id 				= $("#id").val();
	var reason 			= $("#reject_reason").val();
	var approval_level 	= $("#approval_level").val();

	$('#modal-reject-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
      		url : module_path+'/reject',
			data: { id: id, reason:reason, approval_level:approval_level },
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



function save_approve(){
	var id 			= $("#id").val();
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
				if(data != false){ 	
					alert("The data has been successfully approve.");
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