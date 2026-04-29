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
	if(component.type){
		label += '<br><small>'+$('<div/>').text(component.type).html()+'</small>';
	}
	return label;
}

function renderBenefitDeductionComponents(components) {
	var header = '<th scope="col">Component</th>';
	var row = '<td>Amount</td>';

	if(!components || components.length === 0){
		$('#benefitDeductionHeader').html(header);
		$('#benefitDeductionAmountRow').html('<td>No component data</td>');
		return;
	}

	$.each(components, function(index, component) {
		header += '<th scope="col">'+formatComponentLabel(component)+'</th>';
		row += '<td>' +
			'<input type="hidden" name="component_id['+index+']" value="'+component.id+'">' +
			'<input type="text" class="form-control amount-component" name="amount['+index+']" value="'+(component.amount || '')+'">' +
		'</td>';
	});

	$('#benefitDeductionHeader').html(header);
	$('#benefitDeductionAmountRow').html(row);
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
