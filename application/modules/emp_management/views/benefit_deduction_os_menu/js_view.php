<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var myTable;
var validator;
var save_method;
var idx;
var ldx;

$(document).ready(function() {
	$('#btnAddData, #btnBulkData, #btnImportData, #btnEksportData').hide();
});

<?php if(_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {
	myTable = $('#dynamic-table').DataTable({
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

	<?php if(_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
	validator = $("#frmInputData").validate({
		errorElement: 'span',
		errorClass: 'help-block help-block-error',
		focusInvalid: false,
		ignore: "",
		rules: {},
		messages: {}
	});
	<?php } ?>
});

<?php $this->load->view(_TEMPLATE_PATH . "common_module_js"); ?>
<?php } ?>

function formatComponentLabel(component) {
	var label = $('<div/>').text(component.name || '').html();
	return label;
}

function renderBenefitDeductionComponents(components) {
	var earningHeader = '<th scope="col">Component</th>';
	var earningRow = '<td>Amount</td>';
	var deductionHeader = '<th scope="col">Component</th>';
	var deductionRow = '<td>Amount</td>';
	var earningCount = 0;
	var deductionCount = 0;

	if(!components || components.length === 0){
		$('#earningHeader').html(earningHeader);
		$('#earningAmountRow').html('<td>No earning data</td>');
		$('#deductionHeader').html(deductionHeader);
		$('#deductionAmountRow').html('<td>No deduction data</td>');
		return;
	}

	$.each(components, function(index, component) {
		var cell = '<th scope="col">'+formatComponentLabel(component)+'</th>';
		var input = '<td>' +
			'<input type="hidden" name="component_id['+index+']" value="'+component.id+'">' +
			'<input type="text" class="form-control amount-component" name="amount['+index+']" value="'+(component.amount || '')+'">' +
		'</td>';

		if(component.type && component.type.toLowerCase() === 'deduction'){
			deductionHeader += cell;
			deductionRow += input;
			deductionCount++;
		} else {
			earningHeader += cell;
			earningRow += input;
			earningCount++;
		}
	});

	$('#earningHeader').html(earningHeader);
	$('#earningAmountRow').html(earningCount > 0 ? earningRow : '<td>No earning data</td>');
	$('#deductionHeader').html(deductionHeader);
	$('#deductionAmountRow').html(deductionCount > 0 ? deductionRow : '<td>No deduction data</td>');
}

function load_data()
{
	$.ajax({
		type: "POST",
		url: module_path+'/get_detail_data',
		data: { id: idx },
		cache: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data != false){
				$('[name="id"]').val(data.employee.id);
				$('[name="emp_code"]').val(data.employee.emp_code);
				$('[name="full_name"]').val(data.employee.full_name);
				$('[name="project_name"]').val(data.employee.project_name);
				$('[name="job_title_name"]').val(data.employee.job_title_name);
				renderBenefitDeductionComponents(data.components);
				$.uniform.update();
				$('#mfdata').text('Edit');
				$('#modal-form-data').modal('show');
			}else{
				bootbox.dialog({
					message: '<center>Gagal peroleh data.</center>'
				});
			}
		},
		error: function(jqXHR)
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
</script>
