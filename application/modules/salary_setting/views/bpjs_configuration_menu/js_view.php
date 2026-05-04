<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var myTable;
var validator;
var save_method;
var idx;
var ldx;

<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {
	myTable =
	$('#dynamic-table')
	.DataTable( {
		fixedHeader: { headerOffset: $('.page-header').outerHeight() },
		responsive: true,
		bAutoWidth: false,
		"aoColumnDefs": [
		  { "bSortable": false, "aTargets": [ 0,1 ] },
		  { "sClass": "text-center", "aTargets": [ 0,1 ] }
		],
		"aaSorting": [[2,'asc']],
		"sAjaxSource": module_path+"/get_data",
		"bProcessing": true,
        "bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
    } );

	<?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
	validator = $("#frmInputData").validate({
		errorElement: 'span',
		errorClass: 'help-block help-block-error',
		focusInvalid: false,
		ignore: "",
		rules: {
			bpjs_type: { required: true }
		},
		messages: {},
		errorPlacement: function (error, element) {
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
				error.insertAfter(element);
			}
		},
		highlight: function (element) { $(element).closest('.form-group').addClass('has-error'); },
		unhighlight: function (element) { $(element).closest('.form-group').removeClass('has-error'); },
		success: function (label) { label.closest('.form-group').removeClass('has-error'); }
	});
	<?php } ?>

	<?php if  (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
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
					$('[name="id"]').val(data.id);
					$('[name="bpjs_type"]').val(data.bpjs_type);
					$('[name="employee_percentage"]').val(data.employee_percentage);
					$('[name="employer_percentage"]').val(data.employer_percentage);
					$('[name="salary_cap"]').val(data.salary_cap);
					$('[name="tax_ded"]').val(data.tax_ded);
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.bpjs_type').html(data.bpjs_type);
					$('span.employee_percentage').html(data.employee_percentage);
					$('span.employer_percentage').html(data.employer_percentage);
					$('span.salary_cap').html(parseFloat(data.salary_cap || 0).toLocaleString('id-ID'));
					$('span.tax_ded').html(parseFloat(data.tax_ded || 0).toLocaleString('id-ID'));
					$('#modal-view-data').modal('show');
				}
			} else {
				title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
				btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
				msg = '<p>Gagal peroleh data.</p>';
				var dialog = bootbox.dialog({ message: title+'<center>'+msg+btn+'</center>' });
			}
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
			var dialog = bootbox.dialog({
				title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
				message: jqXHR.responseText,
				buttons: { confirm: { label: 'Ok', className: 'btn blue' } }
			});
        }
    });
}
<?php } ?>
</script>
