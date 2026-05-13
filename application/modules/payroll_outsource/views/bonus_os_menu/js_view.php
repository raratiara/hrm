<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var myTable;
var validator;
var save_method;
var idx;
var ldx;

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
					$('[name="id"]').val(data.id);
					$('select#project_id').val(data.project_id).trigger('change.select2');
					$('select#periode_bulan').val(data.periode_bulan).trigger('change.select2');
					$('[name="periode_tahun"]').val(data.periode_tahun);
					$('[name="notes"]').val(data.notes);

					loadBonusThrRows(data.id, data.project_id, false);
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}

				if(save_method == 'detail'){
					$('span.project').html(data.project_name);
					$('span.periode').html(data.periode);
					$('span.notes').html(data.notes || '-');

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
</script>
