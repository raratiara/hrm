<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var myTable; var validator; var save_method; var idx; var ldx;

<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {
	myTable = $('#dynamic-table').DataTable({
		fixedHeader: { headerOffset: $('.page-header').outerHeight() },
		responsive: true, bAutoWidth: false,
		"aoColumnDefs": [{ "bSortable": false, "aTargets": [0,1] }, { "sClass": "text-center", "aTargets": [0,1] }],
		"aaSorting": [[2,'asc']],
		"sAjaxSource": module_path+"/get_data",
		"bProcessing": true, "bServerSide": true,
		"pagingType": "bootstrap_full_number", "colReorder": true
	});

	<?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
	validator = $("#frmInputData").validate({
		errorElement: 'span', errorClass: 'help-block help-block-error', focusInvalid: false, ignore: "",
		rules: { status_code: { required: true }, category: { required: true } },
		messages: {},
		errorPlacement: function (error, element) {
			if (element.parent(".input-group").size() > 0) error.insertAfter(element.parent(".input-group"));
			else if (element.attr("data-error-container")) error.appendTo(element.attr("data-error-container"));
			else error.insertAfter(element);
		},
		highlight: function (element) { $(element).closest('.form-group').addClass('has-error'); },
		unhighlight: function (element) { $(element).closest('.form-group').removeClass('has-error'); },
		success: function (label) { label.closest('.form-group').removeClass('has-error'); }
	});
	<?php } ?>

	<?php if  (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
	$("#check-all").click(function () { $(".data-check").prop('checked', $(this).prop('checked')); });
	<?php } ?>
})
<?php $this->load->view(_TEMPLATE_PATH . "common_module_js"); ?>
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_UPDATE == "1" || _USER_ACCESS_LEVEL_DETAIL == "1")) { ?>
function load_data() {
	$.ajax({
		type: "POST", url: module_path+'/get_detail_data', data: { id: idx }, cache: false, dataType: "JSON",
		success: function(data) {
			if(data != false){
				if(save_method == 'update'){
					$('[name="id"]').val(data.id);
					$('[name="status_code"]').val(data.status_code);
					$('[name="category"]').val(data.category);
					$('[name="description"]').val(data.description);
					$('[name="effective_year"]').val(data.effective_year);
					$('[name="marital_status_id"]').val(data.marital_status_id);
					$.uniform.update(); $('#mfdata').text('Update'); $('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){
					$('span.status_code').html(data.status_code);
					$('span.category').html(data.category);
					$('span.description').html(data.description);
					$('span.effective_year').html(data.effective_year);
					$('span.marital_status_name').html(data.marital_status_name);
					$('#modal-view-data').modal('show');
				}
			} else {
				bootbox.dialog({ message: '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div><center><p>Gagal peroleh data.</p><br/><button class="btn blue" data-dismiss="modal">OK</button></center>' });
			}
		},
		error: function (jqXHR) { bootbox.dialog({ title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText, message: jqXHR.responseText, buttons: { confirm: { label: 'Ok', className: 'btn blue' } } }); }
	});
}
<?php } ?>
</script>
