



<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var modloc = '/_hrm/emp_management/data_karyawan_menu/';
var opsForm = 'form#frmInputData';
var locate = 'table.ca-list';
var dlocate = 'table.dca-list';
var wcount = 0; //for ca list row identify



$(document).ready(function() {
   	$(function() {
   		
        $( "#date_end_prob" ).datepicker();
        $( "#date_resign_letter" ).datepicker();
        $( "#date_resign_active" ).datepicker();
        $( "#date_of_birth" ).datepicker();
        $( "#date_of_hire" ).datepicker();
        $( "#date_permanent" ).datepicker();
		
   	});
});


<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {
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
		  	[2,'asc'] 
		],
		"sAjaxSource": module_path+"/get_data",
		"bProcessing": true,
        "bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
    } );

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
	var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
	

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
					$('[name="emp_code"]').val(data.emp_code);
					$('[name="full_name"]').val(data.full_name);
					$('[name="email"]').val(data.personal_email);
					$('[name="nationality"]').val(data.nationality);
					$('[name="tanggungan"]').val(data.tanggungan);
					$('[name="sim_a"]').val(data.sim_a);
					$('[name="no_npwp"]').val(data.no_npwp);
					$('[name="place_of_birth"]').val(data.place_of_birth);
					$('[name="address1"]').val(data.address_1);
					$('[name="postal_code"]').val(data.postal_code);
					$('[name="work_loc"]').val(data.work_location);
					$('[name="emergency_phone"]').val(data.emergency_contact_phone);
					$('[name="bank_address"]').val(data.bank_address);
					$('[name="bank_acc_no"]').val(data.bank_acc_no);
					$('[name="resign_category"]').val(data.resign_category);
					$('[name="nick_name"]').val(data.nick_name);
					$('[name="phone"]').val(data.personal_phone);
					$('[name="ethnic"]').val(data.ethnic);
					$('[name="no_ktp"]').val(data.no_ktp);
					$('[name="sim_c"]').val(data.sim_c);
					$('[name="no_bpjs"]').val(data.no_bpjs);
					$('[name="address2"]').val(data.address_2);
					$('[name="shift_type"]').val(data.shift_type);
					$('[name="emergency_name"]').val(data.emergency_contact_name);
					$('[name="emergency_email"]').val(data.emergency_contact_email);
					$('[name="emergency_relation"]').val(data.emergency_contact_relation);
					$('[name="bank_name"]').val(data.bank_name);
					$('[name="bank_acc_name"]').val(data.bank_acc_name);
					$('[name="resign_reason"]').val(data.resign_reason);
					$('[name="resign_exit_feedback"]').val(data.resign_exit_interview_feedback);
					$('[name="gender"][value="'+data.gender+'"]').prop('checked', true);
					
					$('[name="hdnempphoto"]').val(data.emp_photo);
					if(data.emp_photo != '' && data.emp_photo != null){
						$('span.file_emp_photo').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_photo+'" width="150" height="150" >');
					}else{
						$('span.file_emp_photo').html('');
					}

					$('[name="hdnempsign"]').val(data.emp_signature);
					if(data.emp_signature != '' && data.emp_signature != null){
						$('span.file_emp_sign').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_signature+'" width="150" height="150" >');
					}else{
						$('span.file_emp_sign').html('');
					}
					
					$('[name="date_of_hire"]').val(data.date_of_hire);
					$('[name="date_permanent"]').val(data.date_permanent);
					$('[name="date_of_birth"]').val(data.date_of_birth);
					$('[name="date_resign_letter"]').val(data.date_resign_letter);
					$('[name="date_resign_active"]').val(data.date_resign_active);
					$('[name="date_end_prob"]').val(data.date_end_probation);

					$('select#indirect').val(data.indirect_id).trigger('change.select2');
					$('select#marital_status').val(data.marital_status_id).trigger('change.select2');
					$('select#province').val(data.province_id).trigger('change.select2');
					$('select#district').val(data.district_id).trigger('change.select2');
					$('select#job_title').val(data.job_title_id).trigger('change.select2');
					$('select#direct').val(data.direct_id).trigger('change.select2');
					$('select#emp_status').val(data.employment_status_id).trigger('change.select2');
					$('select#regency').val(data.regency_id).trigger('change.select2');
					$('select#village').val(data.village_id).trigger('change.select2');
					$('select#department').val(data.department_id).trigger('change.select2');
					$('select#last_education').val(data.last_education_id).trigger('change.select2');
					$('select#branch').val(data.branch_id).trigger('change.select2');
					$('select#company').val(data.company_id).trigger('change.select2');
					$('select#division').val(data.division_id).trigger('change.select2');
					$('select#section').val(data.section_id).trigger('change.select2');


					$.ajax({type: 'post',url: modloc+'genexpensesrow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});
					
					
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.emp_code').html(data.emp_code);
					$('span.full_name').html(data.full_name);
					$('span.email').html(data.email);
					$('span.name').html(data.name);
					$('span.nationality').html(data.nationality);
					$('span.tanggungan').html(data.tanggungan);
					$('span.sim_a').html(data.sim_a);
					$('span.no_npwp').html(data.no_npwp);
					$('span.place_of_birth').html(data.place_of_birth);
					$('span.address1').html(data.address_1);
					$('span.postal_code').html(data.postal_code);
					$('span.date_end_prob').html(data.date_end_probation);
					$('span.work_loc').html(data.work_location);
					$('span.emergency_phone').html(data.emergency_contact_phone);
					$('span.bank_address').html(data.bank_address);
					$('span.bank_acc_no').html(data.bank_acc_no);
					$('span.date_resign_letter').html(data.date_resign_letter);
					$('span.date_resign_active').html(data.date_resign_active);
					$('span.resign_category').html(data.resign_category);
					$('span.nick_name').html(data.nick_name);
					$('span.phone').html(data.personal_phone);
					$('span.ethnic').html(data.ethnic);
					$('span.no_ktp').html(data.no_ktp);
					$('span.sim_c').html(data.sim_c);
					$('span.no_bpjs').html(data.no_bpjs);
					$('span.date_of_birth').html(data.date_of_birth);
					$('span.address2').html(data.address_2);
					$('span.date_of_hire').html(data.date_of_hire);
					$('span.date_permanent').html(data.date_permanent);
					$('span.shift_type').html(data.shift_type);
					$('span.emergency_name').html(data.emergency_contact_name);
					$('span.emergency_email').html(data.emergency_contact_email);
					$('span.bank_name').html(data.bank_name);
					$('span.bank_acc_name').html(data.bank_acc_name);
					$('span.resign_reason').html(data.resign_reason);
					$('span.resign_exit_feedback').html(data.resign_exit_interview_feedback);
					$('span.company').html(data.company_name);
					$('span.division').html(data.division_name);
					$('span.section').html(data.section_name);
					$('span.last_education').html(data.last_education_name);
					$('span.regency').html(data.regency_name);
					$('span.village').html(data.village_name);
					$('span.department').html(data.department_name);
					$('span.emp_status').html(data.emp_status_name);
					$('span.indirect').html(data.indirect_name);
					$('span.branch').html(data.branch_name);
					$('span.marital_status').html(data.marital_status_name);
					$('span.province').html(data.province_name);
					$('span.district').html(data.district_name);
					$('span.job_title').html(data.job_title_name);
					$('span.direct').html(data.direct_name);
					$('[name="gender"][value="'+data.gender+'"]').prop('checked', true);


					if(data.emp_photo != '' && data.emp_photo != null){
						$('span.emp_photo').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_photo+'" width="150" height="150" >');
					}else{
						$('span.emp_photo').html('');
					}

					if(data.emp_signature != '' && data.emp_signature != null){
						$('span.emp_signature').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_signature+'" width="150" height="150" >');
					}else{
						$('span.emp_signature').html('');
					}


					$.ajax({type: 'post',url: modloc+'genexpensesrow',data: { id:data.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

					
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



$("#addcarow").on("click", function () { 
	var type = $("#type option:selected").val();
	

	if(type != ''){
		expire();
		var newRow = $("<tr>");
		$.ajax({type: 'post',url: module_path+'/genexpensesrow',data: { count:wcount, type:type },success: function (response) {
				newRow.append(response);
				$(locate).append(newRow);
				wcount++;
				
			}
		}).done(function() {
			tSawBclear('table.order-list');
		});

		//getSubtype(type);

	}else{
		alert("Please choose Reimburs Type");
	}
	
});


function del(idx,hdnid){
	
	if(hdnid != ''){ //delete dr table

		$.ajax({type: 'post',url: module_path+'/delrowDetailEdu',data: { id:hdnid },success: function (response) {
				
			}
		}).done(function() {
			tSawBclear('table.order-list');
		});

	}

	//delete tampilan row

	var table = document.getElementById("tblDetailEdu");
	table.deleteRow(idx);
	

}




<?php } ?>
</script>