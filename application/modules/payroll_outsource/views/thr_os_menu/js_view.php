<!-- Modal Approval Log -->
<div class="modal fade" id="modalApprovalLogBonusThr" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Approval Log</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
			</div>
			<div class="modal-body" id="approvalLogContentBonusThr">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:50px;">Level</th>
							<th>Approver</th>
							<th>Status</th>
							<th>Approval Date</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="modal-rfu-bonusthr" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog vertical-align-center">
			<div class="modal-content" style="width:80%; text-align:center;">
				<form class="form-horizontal" id="frmRfuBonusThr">
					<div class="modal-header bg-blue bg-font-blue no-padding">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<div class="table-header">Request For Update</div>
					</div>
					<div class="modal-body" style="min-height:100px; margin:10px">
						<p class="text-center">Are you sure to request for update this Data?</p>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Reason</label>
							<div class="col-md-8">
								<?=$rfu_reason;?>
								<input type="hidden" id="approval_action_id" value="">
								<input type="hidden" id="approval_action_level" value="">
							</div>
						</div>
					</div>
				</form>
				<div class="modal-footer no-margin-top">
					<center>
						<button class="btn blue" onclick="save_rfu()"><i class="fa fa-check"></i> Ok</button>
						<button class="btn blue" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal-reject-bonusthr" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog vertical-align-center">
			<div class="modal-content" style="width:80%; text-align:center;">
				<form class="form-horizontal" id="frmRejectBonusThr">
					<div class="modal-header bg-blue bg-font-blue no-padding">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<div class="table-header">Reject</div>
					</div>
					<div class="modal-body" style="min-height:100px; margin:10px">
						<p class="text-center">Are you sure to Reject this Data?</p>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Reason</label>
							<div class="col-md-8">
								<?=$reject_reason;?>
							</div>
						</div>
					</div>
				</form>
				<div class="modal-footer no-margin-top">
					<center>
						<button class="btn blue" onclick="save_reject()"><i class="fa fa-check"></i> Ok</button>
						<button class="btn blue" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var myTable;
var validator;
var save_method;
var idx;
var ldx;
var currentApprovalLogId = '';

$(document).ready(function() {
	initFilterProject();

	$('#filterEmployeeEdit_bonusthr').on('keyup', function() {
		var value = $(this).val().toLowerCase();
		$('#tblDetailBonusThr tbody tr').filter(function() {
			$(this).toggle($(this).find('td').eq(1).text().toLowerCase().indexOf(value) > -1);
		});
	});

	$('#filterEmployeeView_bonusthr').on('keyup', function() {
		var value = $(this).val().toLowerCase();
		$('#tblDetailBonusThrView tbody tr').filter(function() {
			$(this).toggle($(this).find('td').eq(1).text().toLowerCase().indexOf(value) > -1);
		});
	});
});

<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {
	myTable =
	$('#dynamic-table').DataTable({
		fixedHeader: {
			headerOffset: $('.page-header').outerHeight()
		},
		responsive: true,
		bAutoWidth: false,
		"aoColumnDefs": [
		  { "bSortable": false, "aTargets": [0,1] },
		  { "sClass": "text-center", "aTargets": [0,1] }
		],
		"aaSorting": [
		  	[2,'asc']
		],
		"sAjaxSource": module_path+"/get_data",
		"bProcessing": true,
		"bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
	});

	<?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
	validator = $("#frmInputData").validate({
		errorElement: 'span',
		errorClass: 'help-block help-block-error',
		focusInvalid: false,
		ignore: "",
		rules: {
			project_id: { required: true },
			periode_bulan: { required: true },
			periode_tahun: { required: true, digits: true, minlength: 4, maxlength: 4 }
		},
		errorPlacement: function (error, element) {
			if (element.parent(".input-group").size() > 0) {
				error.insertAfter(element.parent(".input-group"));
			} else if (element.parents('.radio-list').size() > 0) {
				error.appendTo(element.parents('.radio-list'));
			} else {
				error.insertAfter(element);
			}
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		success: function (label) {
			label.closest('.form-group').removeClass('has-error');
		}
	});
	<?php } ?>

	<?php if  (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
	$("#check-all").click(function () {
		$(".data-check").prop('checked', $(this).prop('checked'));
	});
	<?php } ?>
});

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
					resetApprovalButtons();
					$('[name="id"]').val(data.id);
					$('select#project_id').val(data.project_id).trigger('change.select2');
					$('select#periode_bulan').val(data.periode_bulan).trigger('change.select2');
					$('[name="periode_tahun"]').val(data.periode_tahun);
					$('[name="notes"]').val(data.notes);
					$('[name="action_type"]').val('');

					loadBonusThrRows(data.id, data.project_id, false);
					configureApprovalButtons(data);
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}

				if(save_method == 'detail'){
					currentApprovalLogId = data.id;
					if(data.current_approval_level) {
						$('#btnApprovalLogView').show();
					} else {
						$('#btnApprovalLogView').hide();
					}
					$('span.project').html(data.project_name);
					$('span.periode').html(data.periode);
					$('span.notes').html(data.notes || '-');
					$('span.status_name').html(data.status_name || '-');

					loadBonusThrRows(data.id, data.project_id, true);
					$('#modal-view-data').modal('show');
				}
			} else {
				bootbox.dialog({
					message: '<center><p>Gagal peroleh data.</p><br/><button class="btn blue" data-dismiss="modal">OK</button></center>'
				});
			}
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			bootbox.dialog({
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

function loadBonusThrRows(id, project, view)
{
	var locate = view ? 'table.bonusthr-view-list' : 'table.bonusthr-list';
	$.ajax({
		type: 'post',
		url: module_path+'/genbonusrowthr',
		data: { id: id, project: project, view: view ? 1 : 0 },
		success: function (response) {
			var obj = JSON.parse(response);
			$(locate+' tbody').html(obj[0]);
			wcount = obj[1];
			if(view) {
				setNominalViewTotal();
			} else {
				setNominalTotal();
			}
		}
	}).done(function() {
		tSawBclear(locate);
	});
}

$('#project_id').on('change', function () {
	if(save_method == 'add'){
		var project = $("#project_id option:selected").val();
		if(project != ''){
			loadBonusThrRows(0, project, false);
		}
	}
});

$('#modal-form-data').on('shown.bs.modal', function () {
	initSelect2(this);
	if(save_method == 'add'){
		$('table.bonusthr-list tbody').html('<tr><td colspan="4" class="center">Pilih project terlebih dahulu</td></tr>');
		setNominalTotal();
	}
});

$('#modal-form-data').on('hide.bs.modal', function () {
	initFilterProject();
});

function initFilterProject() {
	if ($('#flproject').hasClass('select2-hidden-accessible')) {
		$('#flproject').select2('destroy');
	}

	$('#flproject').select2({
		theme: 'bootstrap',
		width: '100%'
	});
}

function initSelect2(scope) {
	$(scope).find('.select2me').each(function () {
		if ($(this).hasClass("select2-hidden-accessible")) {
			$(this).select2('destroy');
		}

		$(this).select2({
			theme: 'bootstrap',
			width: '100%',
			dropdownParent: $(scope)
		});
	});
}

function toNumber(value) {
	value = (value || '').toString().trim();
	if(/^\d+\.\d{1,2}$/.test(value)) {
		return Number(value);
	}
	value = value.replace(/\./g, '').replace(/,/g, '');
	var number = Number(value);
	return isNaN(number) ? 0 : number;
}

function setNominalTotal() {
	var totalNominal = 0;

	$('input[name^="nominal_amount"]').each(function() {
		totalNominal += toNumber($(this).val());
	});

	$('#total_nominal_text').text(totalNominal.toLocaleString('id-ID'));
}

function setNominalViewTotal() {
	var totalNominal = 0;

	$('#tblDetailBonusThrView tbody tr').each(function() {
		totalNominal += toNumber($(this).find('td').eq(2).text());
	});

	$('#total_nominal_view_text').text(totalNominal.toLocaleString('id-ID'));
}

function subFilter(){
	var flproject = $("#flproject option:selected").val();
	if(flproject == '') flproject = 0;

	$('#dynamic-table').DataTable().clear().destroy();
	$('#dynamic-table').DataTable({
		fixedHeader: {
			headerOffset: $('.page-header').outerHeight()
		},
		responsive: true,
		bAutoWidth: false,
		"aoColumnDefs": [
		  { "bSortable": false, "aTargets": [0,1] },
		  { "sClass": "text-center", "aTargets": [0,1] }
		],
		"aaSorting": [
		  	[2,'asc']
		],
		"sAjaxSource": module_path+"/get_data?flproject="+flproject,
		"bProcessing": true,
		"bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
	});
}

function resetApprovalButtons() {
	$('[name="action_type"]').val('');
	$('#submit-data').text('Save').removeClass('btn-success green').addClass('blue');
	$('#submit-data').css({'background-color':'#112D80','border-color':'#112D80','color':'#fff','margin-right':'5px'});
	$('#idbtnRfu, #idbtnReject').remove();
	$('#btnApprovalLog, #btnApprovalLogView').hide();
	currentApprovalLogId = '';
}

function configureApprovalButtons(data) {
	currentApprovalLogId = data.id;
	if(data.status_id == 1 && data.is_approver == 1) {
		$('[name="action_type"]').val('approval');
		$('#submit-data').text('Approve').removeClass('blue').addClass('green');
		$('#submit-data').css({'background-color':'#26A65B','border-color':'#26A65B','color':'#fff','margin-right':'8px'});

		var approveBtn = document.getElementById('submit-data');
		if(!document.getElementById('idbtnRfu')) {
			var rfuButton = document.createElement('button');
			rfuButton.type = 'button';
			rfuButton.innerText = 'RFU';
			rfuButton.className = 'btn btn-warning';
			rfuButton.style.marginLeft = '8px';
			rfuButton.style.marginRight = '8px';
			rfuButton.id = 'idbtnRfu';
			approveBtn.insertAdjacentElement('afterend', rfuButton);
			rfuButton.addEventListener('click', function() {
				$('#approval_action_id').val(data.id);
				$('#approval_action_level').val(data.current_approval_level || 1);
				$('#modal-rfu-bonusthr').modal('show');
			});
		}

		if(!document.getElementById('idbtnReject')) {
			var rejectButton = document.createElement('button');
			rejectButton.type = 'button';
			rejectButton.innerText = 'Reject';
			rejectButton.className = 'btn btn-danger';
			rejectButton.style.marginLeft = '8px';
			rejectButton.style.marginRight = '18px';
			rejectButton.id = 'idbtnReject';
			document.getElementById('idbtnRfu').insertAdjacentElement('afterend', rejectButton);
			rejectButton.addEventListener('click', function() {
				$('#approval_action_id').val(data.id);
				$('#approval_action_level').val(data.current_approval_level || 1);
				$('#modal-reject-bonusthr').modal('show');
			});
		}
	}

	if(data.current_approval_level) {
		$('#btnApprovalLog').show();
	}
}

function save_rfu() {
	saveApprovalDecision('rfu', $('#rfu_reason').val());
}

function save_reject() {
	saveApprovalDecision('reject', $('#reject_reason').val());
}

function saveApprovalDecision(action, reason) {
	var id = $('#approval_action_id').val();
	var approvalLevel = $('#approval_action_level').val();
	var modal = action == 'rfu' ? '#modal-rfu-bonusthr' : '#modal-reject-bonusthr';
	$(modal).modal('hide');

	if(id == '') {
		alert('Data not found!');
		return;
	}

	$.ajax({
		type: 'POST',
		url: module_path + '/' + action,
		data: { id: id, reason: reason, approval_level: approvalLevel },
		cache: false,
		dataType: 'JSON',
		success: function(data) {
			if(data != false) {
				reload_table();
				$('#modal-form-data').modal('hide');
			} else {
				alert('Failed to process the data!');
			}
		}
	});
}

function approvalLog(id) {
	id = id || currentApprovalLogId;
	$('#modalApprovalLogBonusThr').modal('show');
	$.ajax({
		type: 'POST',
		url: module_path + '/getApprovalLog',
		data: { id: id },
		cache: false,
		dataType: 'JSON',
		success: function(response) {
			$('#approvalLogContentBonusThr tbody').html(response.html);
		}
	});
}
</script>
