<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var myTable;
var validator;
var save_method;
var idx;
var ldx;

<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {
	myTable = $('#dynamic-table').DataTable({
		fixedHeader: { headerOffset: $('.page-header').outerHeight() },
		responsive: true,
		bAutoWidth: false,
		"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0,1 ] },
			{ "sClass": "text-center", "aTargets": [ 0,1 ] }
		],
		"aaSorting": [[2,'desc']],
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
			proses_ke_bulan_penggajian: { required: true },
			proses_ke_tahun_penggajian: { required: true, digits: true, minlength: 4, maxlength: 4 },
			status: { required: true }
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
				var type = data.type == 'refund' ? 'Refund' : 'Kurang Bayar';
				var amount = Number(data.amount || 0).toLocaleString('id-ID');
				var kurangLebih = Number(data.kurang_lebih_bayar || 0).toLocaleString('id-ID');

				if(save_method == 'update'){
					$('[name="id"]').val(data.id);
					$('[name="employee_view"]').val(data.emp_code + ' - ' + data.full_name);
					$('[name="type_view"]').val(type);
					$('[name="amount_view"]').val(amount);
					$('select#proses_ke_bulan_penggajian').val(data.proses_ke_bulan_penggajian).trigger('change.select2');
					$('[name="proses_ke_tahun_penggajian"]').val(data.proses_ke_tahun_penggajian);
					$('select#status').val(data.status).trigger('change.select2');
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){
					$('span.tahun_pajak').html(data.tahun_pajak);
					$('span.employee').html(data.emp_code + ' - ' + data.full_name);
					$('span.type').html(type);
					$('span.amount').html(amount);
					$('span.kurang_lebih_bayar').html(kurangLebih);
					$('span.status').html(data.status);
					$('span.periode_proses').html(data.periode_proses);
					$('#modal-view-data').modal('show');
				}
			}
		}
	});
}
<?php } ?>

$('#btnAddData').off('click').on('click', function(e) {
	e.preventDefault();
	alert('PPh21 Adjustment dibuat otomatis saat SPT difinalkan.');
});
</script>
