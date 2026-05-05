<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var myTable;
var validator;
var save_method;
var idx;
var ldx;

// Store all available components from server
var allComponents = [];
var bpjsConfig = [];
var earningRowIndex = 0;
var deductionRowIndex = 0;

$(document).ready(function() {
	$('#btnAddData, #btnBulkData, #btnImportData, #btnEksportData').hide();

	// Add Earning row
	$(document).on('click', '#btnAddEarning', function(){
		addRow('earning');
	});

	// Add Deduction row
	$(document).on('click', '#btnAddDeduction', function(){
		addRow('deduction');
	});

	// Delete row
	$(document).on('click', '.btn-delete-row', function(){
		var $tr = $(this).closest('tr');
		var type = $tr.data('type');
		$tr.remove();
		toggleEmptyRow(type);
	});

	// When component select changes, auto-fill default_amount or calculate
	$(document).on('change', '.select-component', function(){
		var compId = $(this).val();
		var $row = $(this).closest('tr');
		var $amount = $row.find('.input-amount');
		if(compId){
			var comp = getComponentById(compId);
			if(comp){
				// If has calculate_percentage, auto-calculate from base
				if(comp.calculate_percentage && comp.calculate_from && parseFloat(comp.calculate_percentage) > 0){
					var baseValue = getBaseValueByCode(comp.calculate_from);
					if(baseValue > 0){
						var calculated = Math.ceil(baseValue * parseFloat(comp.calculate_percentage));
						$amount.val(formatNumber(calculated));
					} else if(comp.default_amount && parseFloat(comp.default_amount) > 0){
						$amount.val(formatNumber(comp.default_amount));
					}
				} else if(comp.default_amount && parseFloat(comp.default_amount) > 0){
					$amount.val(formatNumber(comp.default_amount));
				}
			}
		}
	});

	// When amount changes, recalculate dependent components
	$(document).on('change blur', '.input-amount', function(){
		var $row = $(this).closest('tr');
		var compId = $row.find('.select-component').val();
		if(compId){
			var comp = getComponentById(compId);
			if(comp && comp.code){
				recalculateDependents(comp.code);
			}
		}
	});
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
		messages: {},
		errorPlacement: function(error, element) {
			error.insertAfter(element);
		},
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		success: function(label) {
			label.closest('.form-group').removeClass('has-error');
		}
	});
	<?php } ?>
});

<?php $this->load->view(_TEMPLATE_PATH . "common_module_js"); ?>
<?php } ?>

function getComponentById(id){
	for(var i=0; i<allComponents.length; i++){
		if(allComponents[i].id == id) return allComponents[i];
	}
	return null;
}

function getComponentByCode(code){
	for(var i=0; i<allComponents.length; i++){
		if(allComponents[i].code == code) return allComponents[i];
	}
	return null;
}

// Get the current amount value of a component by its code (from the form rows)
function getBaseValueByCode(code){
	var baseComp = getComponentByCode(code);
	if(!baseComp) return 0;

	var value = 0;
	$('.select-component').each(function(){
		if($(this).val() == baseComp.id){
			var amountStr = $(this).closest('tr').find('.input-amount').val() || '0';
			value = parseFloat(amountStr.replace(/,/g, '')) || 0;
		}
	});
	return value;
}

// Recalculate all components that depend on a given code
function recalculateDependents(baseCode){
	$('.select-component').each(function(){
		var compId = $(this).val();
		if(!compId) return;
		var comp = getComponentById(compId);
		if(comp && comp.calculate_from == baseCode && comp.calculate_percentage && parseFloat(comp.calculate_percentage) > 0){
			var baseValue = getBaseValueByCode(baseCode);
			if(baseValue > 0){
				var calculated = Math.ceil(baseValue * parseFloat(comp.calculate_percentage));
				$(this).closest('tr').find('.input-amount').val(formatNumber(calculated));
			}
		}
	});
}

function getComponentOptions(type, selectedId){
	var html = '<option value="">-- Pilih Komponen --</option>';
	$.each(allComponents, function(i, comp){
		if(comp.type && comp.type.toLowerCase() === type){
			var sel = (comp.id == selectedId) ? ' selected' : '';
			var label = $('<div/>').text(comp.name || '').html();
			html += '<option value="'+comp.id+'"'+sel+'>'+label+'</option>';
		}
	});
	return html;
}

function addRow(type, componentId, amount){
	var idx = (type === 'earning') ? earningRowIndex++ : deductionRowIndex++;
	var tbody = (type === 'earning') ? '#earningBody' : '#deductionBody';
	var emptyRow = (type === 'earning') ? '#earningEmptyRow' : '#deductionEmptyRow';
	var selectType = (type === 'earning') ? 'earning' : 'deduction';

	$(emptyRow).hide();

	var amountVal = amount ? formatNumber(amount) : '';
	var row = '<tr data-type="'+type+'">' +
		'<td>' +
			'<select class="form-control select-component" name="component_id[]">' +
				getComponentOptions(selectType, componentId) +
			'</select>' +
		'</td>' +
		'<td>' +
			'<input type="text" class="form-control input-amount text-right" name="amount[]" value="'+amountVal+'" placeholder="0">' +
		'</td>' +
		'<td class="text-center">' +
			'<button type="button" class="btn btn-xs btn-danger btn-delete-row"><i class="fa fa-trash"></i></button>' +
		'</td>' +
	'</tr>';

	$(tbody).append(row);
}

function toggleEmptyRow(type){
	var tbody = (type === 'earning') ? '#earningBody' : '#deductionBody';
	var emptyRow = (type === 'earning') ? '#earningEmptyRow' : '#deductionEmptyRow';
	var dataRows = $(tbody).find('tr[data-type]');
	if(dataRows.length === 0){
		$(emptyRow).show();
	} else {
		$(emptyRow).hide();
	}
}

function formatNumber(val){
	if(!val || val === '' || val == 0) return '';
	var num = parseFloat(String(val).replace(/,/g, ''));
	if(isNaN(num)) return val;
	return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function renderBenefitDeductionData(components, saved, employee, bpjs_config, saved_bpjs){
	// Reset
	$('#earningBody').find('tr[data-type]').remove();
	$('#deductionBody').find('tr[data-type]').remove();
	$('#earningEmptyRow').show();
	$('#deductionEmptyRow').show();
	earningRowIndex = 0;
	deductionRowIndex = 0;

	allComponents = components || [];
	bpjsConfig = bpjs_config || [];

	// Render saved earning rows first
	if(saved && saved.length > 0){
		$.each(saved, function(i, item){
			var comp = getComponentById(item.component_id);
			if(comp && comp.type && comp.type.toLowerCase() === 'earning'){
				addRow('earning', item.component_id, item.amount);
			}
		});
	}

	// BPJS rows paling atas di deduction section
	if(employee && bpjsConfig.length > 0){
		autoAddBpjsRows(employee, saved_bpjs);
	}

	// Render saved deduction component rows (di bawah BPJS)
	if(saved && saved.length > 0){
		$.each(saved, function(i, item){
			var comp = getComponentById(item.component_id);
			if(comp && comp.type && comp.type.toLowerCase() === 'deduction'){
				addRow('deduction', item.component_id, item.amount);
			}
		});
	}
}

// Auto-add BPJS deduction rows based on employee status
function autoAddBpjsRows(employee, saved_bpjs){
	// Get gaji bulanan from earning rows
	var gajiBulanan = getGajiBulananFromRows();

	// Determine which BPJS categories to add
	var addKesehatan = (employee.status_bpjs_kesehatan !== 'ditanggung_perusahaan');
	var addKetenagakerjaan = (employee.status_bpjs_ketenagakerjaan !== 'ditanggung_perusahaan');

	$.each(bpjsConfig, function(i, bpjs){
		var shouldAdd = false;
		if(bpjs.category === 'kesehatan' && addKesehatan){
			shouldAdd = true;
		} else if(bpjs.category === 'ketenagakerjaan' && addKetenagakerjaan){
			shouldAdd = true;
		}

		if(!shouldAdd) return;

		var empPercentage = parseFloat(bpjs.employee_percentage) || 0;
		if(empPercentage <= 0) return; // Skip if employee doesn't pay (e.g. JKK, JKM)

		// Check if already saved with this bpjs_id
		var alreadySaved = false;
		var savedAmount = 0;
		if(saved_bpjs && saved_bpjs.length > 0){
			$.each(saved_bpjs, function(j, item){
				if(String(item.bpjs_id) === String(bpjs.id)){
					alreadySaved = true;
					savedAmount = item.amount;
				}
			});
		}

		// Calculate amount: employee_percentage * gaji_bulanan (with salary_cap)
		var baseGaji = gajiBulanan;
		var salaryCap = parseFloat(bpjs.salary_cap) || 0;
		if(salaryCap > 0 && baseGaji > salaryCap){
			baseGaji = salaryCap;
		}
		var amount = alreadySaved ? savedAmount : Math.ceil(baseGaji * empPercentage);

		if(amount > 0 || alreadySaved){
			addBpjsRow(bpjs, amount);
		}
	});
}

// Add a BPJS-specific row (non-deletable, label instead of select)
function addBpjsRow(bpjs, amount){
	var tbody = '#deductionBody';
	$('#deductionEmptyRow').hide();

	var amountVal = amount ? formatNumber(amount) : '';
	var row = '<tr data-type="deduction" data-bpjs="1">' +
		'<td>' +
			'<input type="hidden" name="bpjs_id[]" value="'+bpjs.id+'">' +
			'<span class="form-control" style="background:#f5f5f5; border:1px solid #ddd;">'+bpjs.bpjs_type+' <small class="text-muted">('+bpjs.category+')</small></span>' +
		'</td>' +
		'<td>' +
			'<input type="text" class="form-control input-amount text-right" name="bpjs_amount[]" value="'+amountVal+'" placeholder="0" readonly style="background:#f5f5f5;">' +
		'</td>' +
		'<td class="text-center">' +
			'<span class="text-muted"><i class="fa fa-lock"></i></span>' +
		'</td>' +
	'</tr>';

	$(tbody).append(row);
}

// Get gaji bulanan value from the earning rows (look for component with code 'gaji_bulanan')
function getGajiBulananFromRows(){
	var gajiBulanan = 0;
	$('#earningBody .select-component').each(function(){
		var compId = $(this).val();
		if(compId){
			var comp = getComponentById(compId);
			if(comp && comp.code === 'gaji_bulanan'){
				var amountStr = $(this).closest('tr').find('.input-amount').val() || '0';
				gajiBulanan = parseFloat(amountStr.replace(/,/g, '')) || 0;
			}
		}
	});
	// If not found in rows, try from allComponents default_amount
	if(gajiBulanan === 0){
		var comp = getComponentByCode('gaji_bulanan');
		if(comp && comp.default_amount){
			gajiBulanan = parseFloat(comp.default_amount) || 0;
		}
	}
	return gajiBulanan;
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
				$('[name="job_title_name"]').val(data.employee.job_title_name);
				$('[name="department_name"]').val(data.employee.department_name);
				renderBenefitDeductionData(data.components, data.saved, data.employee, data.bpjs_config, data.saved_bpjs);
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
