<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Modal Reject Data -->
<div id="modal-reject-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-reject-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="text-align:center;">
			<form class="form-horizontal" id="frmRejectData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Reject Training
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to reject this data?</p>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Reason</label>
					<div class="col-md-8">
						<?=$reject_reason;?>
						<input type="hidden" name="approval_id_reject" id="approval_id_reject" value="">
						<input type="hidden" name="approval_level_reject" id="approval_level_reject" value="">
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

<!-- Modal Approve Data -->
<div id="modal-approve-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-approve-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content">
			<form class="form-horizontal" id="frmApproveData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Approve Training
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to approve this data?</p>
				<input type="hidden" name="approval_id_approve" id="approval_id_approve" value="">
				<input type="hidden" name="approval_level_approve" id="approval_level_approve" value="">
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

<!-- Modal RFU Data -->
<div id="modal-rfu-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-rfu-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="text-align:center;">
			<form class="form-horizontal" id="frmRFUData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Request For Update
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to request update for this data?</p>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Reason</label>
					<div class="col-md-8">
						<?=$rfu_reason;?>
						<input type="hidden" name="approval_id_rfu" id="approval_id_rfu" value="">
						<input type="hidden" name="approval_level_rfu" id="approval_level_rfu" value="">
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

<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var TRAINING_UI = {
	page: 1,
	pageSize: 9,
	search: '',
	status: '',
	isLoading: false
};




$(document).ready(function() {
   	$(function() {
   		
        $( "#training_date" ).datetimepicker();
		
	});
});

function escapeHtml(text) {
	return $('<div>').text(text == null ? '' : text).html();
}

function getTrainingStatusBadge(status) {
	var label = status || '-';
	var style = 'background:#eef2f7;color:#475569;';

	if (label === 'Waiting Approval') {
		style = 'background:#f3e8ff;color:#7c3aed;';
	} else if (label === 'Approved') {
		style = 'background:#dcfce7;color:#166534;';
	} else if (label === 'Rejected') {
		style = 'background:#fee2e2;color:#b91c1c;';
	} else if (label === 'Request for Update') {
		style = 'background:#ffedd5;color:#c2410c;';
	}

	return '<span class="badge-status" style="' + style + '">' + escapeHtml(label) + '</span>';
}

function buildTrainingCard(row) {
	var checkboxHtml = row[0] || '';
	var actionHtml = row[1] || '';
	var id = row[2] || '';
	var trainingName = row[3] || '-';
	var trainingDate = row[4] || '-';
	var location = row[5] || '-';
	var trainer = row[6] || '-';
	var notes = row[7] || '-';
	var status = row[8] || '-';
	var participants = row[9] || '-';
	var createdBy = row[10] || '-';

	return '' +
		'<div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom:16px;">' +
			'<div class="lms-card">' +
				'<div class="cover">' +
					getTrainingStatusBadge(status) +
					'<div style="position:absolute;left:14px;bottom:12px;font-size:22px;color:#112D80;">' +
						'<i class="fa fa-graduation-cap"></i>' +
					'</div>' +
				'</div>' +
				'<div class="body">' +
					'<div class="title">' + escapeHtml(trainingName) + '</div>' +
					'<div class="meta">' +
						'<div class="row-meta"><i class="fa fa-calendar"></i><span>' + escapeHtml(trainingDate) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-map-marker"></i><span>' + escapeHtml(location) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-user"></i><span>' + escapeHtml(trainer) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-users"></i><span>' + escapeHtml(participants) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-pencil-square-o"></i><span>' + escapeHtml(createdBy) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-sticky-note-o"></i><span>' + escapeHtml(notes) + '</span></div>' +
					'</div>' +
				'</div>' +
				'<div class="footer">' +
					'<div class="select-box">' + checkboxHtml + ' <span>#' + escapeHtml(id) + '</span></div>' +
					'<div class="actions">' + actionHtml + '</div>' +
				'</div>' +
			'</div>' +
		'</div>';
}

function renderTrainingPagination(totalRecords) {
	var $p = $('#lmsPagination');
	if (!$p.length) return;

	$p.empty();

	var totalPages = Math.ceil((totalRecords || 0) / TRAINING_UI.pageSize);
	if (totalPages <= 1) return;

	function addItem(page, label, active, disabled) {
		$p.append(
			'<li class="' + (active ? 'active' : '') + ' ' + (disabled ? 'disabled' : '') + '">' +
				'<a href="javascript:void(0);" data-page="' + page + '">' + label + '</a>' +
			'</li>'
		);
	}

	addItem(TRAINING_UI.page - 1, '&laquo;', false, TRAINING_UI.page <= 1);

	var start = Math.max(1, TRAINING_UI.page - 2);
	var end = Math.min(totalPages, TRAINING_UI.page + 2);

	if (start > 1) {
		addItem(1, '1', TRAINING_UI.page === 1, false);
		if (start > 2) $p.append('<li class="disabled"><span>...</span></li>');
	}

	for (var i = start; i <= end; i++) {
		addItem(i, i, TRAINING_UI.page === i, false);
	}

	if (end < totalPages) {
		if (end < totalPages - 1) $p.append('<li class="disabled"><span>...</span></li>');
		addItem(totalPages, totalPages, TRAINING_UI.page === totalPages, false);
	}

	addItem(TRAINING_UI.page + 1, '&raquo;', false, TRAINING_UI.page >= totalPages);
}

function loadCards() {
	if (TRAINING_UI.isLoading) return;
	TRAINING_UI.isLoading = true;

	var $grid = $('#lmsGrid');
	if (!$grid.length) {
		TRAINING_UI.isLoading = false;
		return;
	}

	var start = (TRAINING_UI.page - 1) * TRAINING_UI.pageSize;
	var ajaxData = {
		sEcho: 1,
		iDisplayStart: start,
		iDisplayLength: TRAINING_UI.pageSize,
		sSearch: TRAINING_UI.search,
		iSortCol_0: 2,
		sSortDir_0: 'desc',
		iSortingCols: 1
	};

	if (TRAINING_UI.status !== '') {
		ajaxData['bSearchable_8'] = 'true';
		ajaxData['sSearch_8'] = TRAINING_UI.status;
	}

	showLoading();

	$.ajax({
		url: module_path + '/get_data',
		type: 'GET',
		dataType: 'json',
		data: ajaxData,
		success: function(res) {
			var rows = (res && res.aaData) ? res.aaData : [];
			var totalRecords = res && typeof res.iTotalDisplayRecords !== 'undefined' ? parseInt(res.iTotalDisplayRecords, 10) : 0;
			var totalAll = res && typeof res.iTotalRecords !== 'undefined' ? parseInt(res.iTotalRecords, 10) : totalRecords;
			var waitingCount = 0;
			var approvedCount = 0;

			$grid.empty();

			$('#stat_total').text(isNaN(totalAll) ? 0 : totalAll);

			for (var i = 0; i < rows.length; i++) {
				if (rows[i][8] === 'Waiting Approval') waitingCount++;
				if (rows[i][8] === 'Approved') approvedCount++;
				$grid.append(buildTrainingCard(rows[i]));
			}

			$('#stat_waiting').text(waitingCount);
			$('#stat_approved').text(approvedCount);

			if (!rows.length) {
				$grid.html(
					'<div class="col-md-12">' +
						'<div class="alert alert-info" style="border-radius:12px;">Belum ada data training yang cocok.</div>' +
					'</div>'
				);
			}

			renderTrainingPagination(totalRecords);
		},
		error: function(xhr) {
			$grid.html(
				'<div class="col-md-12">' +
					'<div class="alert alert-danger" style="border-radius:12px;">' +
						'<b>Gagal memuat data.</b><br>' + (xhr.responseText || 'Unknown error') +
					'</div>' +
				'</div>'
			);
			$('#lmsPagination').empty();
		},
		complete: function() {
			hideLoading();
			TRAINING_UI.isLoading = false;
		}
	});
}


<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {
	if ($('#lmsGrid').length) {
		myTable = {
			ajax: {
				reload: function() {
					loadCards();
				}
			}
		};

		loadCards();

		$(document).off('input.trainingSearch', '#lmsSearch').on('input.trainingSearch', '#lmsSearch', function() {
			TRAINING_UI.search = $(this).val() || '';
			TRAINING_UI.page = 1;
			loadCards();
		});

		$(document).off('click.trainingFilter', '.lms-filters .btn').on('click.trainingFilter', '.lms-filters .btn', function() {
			$('.lms-filters .btn').removeClass('active');
			$(this).addClass('active');
			TRAINING_UI.status = $(this).data('status') || '';
			TRAINING_UI.page = 1;
			loadCards();
		});

		$(document).off('click.trainingPage', '#lmsPagination a[data-page]').on('click.trainingPage', '#lmsPagination a[data-page]', function() {
			var page = parseInt($(this).data('page'), 10);
			if (isNaN(page) || page < 1) return;
			TRAINING_UI.page = page;
			loadCards();
		});
	} else {
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
	}

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

					$('[name="hdndoc_sertifikat"]').val(data.file_sertifikat);
					if(data.file_sertifikat != '' && data.file_sertifikat != null){
						$('span.file_sertifikat').html('<img src="'+baseUrl+'/uploads/'+data.emp_code+'/'+data.file_sertifikat+'" width="150" height="150" >');
					}else{
						$('span.file_sertifikat').html('');
					}


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
					$('span.employee').html(data.full_name);
					$('span.training_name').html(data.training_name);
					$('span.training_date').html(data.training_date);
					$('span.location').html(data.location);
					$('span.trainer').html(data.trainer);
					$('span.notes').html(data.notes);

					if(data.file_sertifikat != '' && data.file_sertifikat != null){
						$('span.file_sertifikat').html('<img src="'+baseUrl+'/uploads/'+data.emp_code+'/'+data.file_sertifikat+'" width="150" height="150" >');
					}else{
						$('span.file_sertifikat').html('');
					}



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
	$('#approval_id_reject').val(id);
	$('#approval_level_reject').val(approval_level);

}

function approve(id,approval_level){

	$('#modal-approve-data').modal('show');
	$('#approval_id_approve').val(id);
	$('#approval_level_approve').val(approval_level);

}

function rfu(id, approval_level){

	$('#modal-rfu-data').modal('show');
	$('#approval_id_rfu').val(id);
	$('#approval_level_rfu').val(approval_level);

}


function save_reject(){
	var id 	= $("#approval_id_reject").val();
	var approval_level 	= $("#approval_level_reject").val();
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
	var id 	= $("#approval_id_approve").val();
	var approval_level 	= $("#approval_level_approve").val();

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
	var id 		= $("#approval_id_rfu").val();
	var reason 	= $("#rfu_reason").val();
	var approval_level 	= $("#approval_level_rfu").val();

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
