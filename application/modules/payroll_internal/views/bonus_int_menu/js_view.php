<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var myTable;
var validator;
var save_method;
var idx;
var ldx;

$(document).ready(function() {
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
					$('select#periode_bulan').val(data.periode_bulan).trigger('change.select2');
					$('[name="periode_tahun"]').val(data.periode_tahun);
					$('[name="notes"]').val(data.notes);

					loadBonusThrRows(data.id, false);
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}

				if(save_method == 'detail'){
					$('span.periode').html(data.periode);
					$('span.notes').html(data.notes || '-');

					loadBonusThrRows(data.id, true);
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

function loadBonusThrRows(id, view)
{
	var locate = view ? 'table.bonusthr-view-list' : 'table.bonusthr-list';
	$.ajax({
		type: 'post',
		url: module_path+'/genbonusrowthr',
		data: { id: id, view: view ? 1 : 0 },
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

$('#modal-form-data').on('shown.bs.modal', function () {
	initSelect2(this);
	if(save_method == 'add'){
		loadBonusThrRows(0, false);
	}
});

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

</script>
