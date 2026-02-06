



<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var modloc = '/_hrm/emp_management/data_karyawan_menu/';
var opsForm = 'form#frmInputData';

var dlocate = 'table.dca-list';
var wcount = 0; //for ca list row identify
var wcount_training = 0; //for ca list row identify
var wcount_org = 0; //for ca list row identify
var wcount_workexp = 0; //for ca list row identify




$(document).ready(function() {
   	$(function() {
   		
        $( "#date_end_prob" ).datepicker();
        $( "#date_resign_letter" ).datepicker();
        $( "#date_resign_active" ).datepicker();
        $( "#date_of_birth" ).datepicker();
        $( "#date_of_hire" ).datepicker();
        $( "#date_permanent" ).datepicker();
        $( "#start_pkwt" ).datepicker();
        $( "#end_pkwt" ).datepicker();
		
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
	//local=> 
	//var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
	var baseUrl = getUrl .protocol + "//" + getUrl.host;
	

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
					$('[name="address1"]').val(data.address_ktp);
					$('[name="address2"]').val(data.address_residen);
					$('[name="postal_code1"]').val(data.postal_code_ktp);
					$('[name="postal_code2"]').val(data.postal_code_residen);
					/*$('[name="work_loc"]').val(data.work_location);*/
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
					$('[name="no_bpjs_ketenagakerjaan"]').val(data.no_bpjs_ketenagakerjaan);
					
					$('[name="emergency_name"]').val(data.emergency_contact_name);
					$('[name="emergency_email"]').val(data.emergency_contact_email);
					$('[name="emergency_relation"]').val(data.emergency_contact_relation);
					$('[name="bank_name"]').val(data.bank_name);
					$('[name="bank_acc_name"]').val(data.bank_acc_name);
					$('[name="resign_reason"]').val(data.resign_reason);
					$('[name="resign_exit_feedback"]').val(data.resign_exit_interview_feedback);
					$('[name="gender"][value="'+data.gender+'"]').prop('checked', true);
					$('[name="status"][value="'+data.status_id+'"]').prop('checked', true);
					$('[name="shift_type"][value="'+data.shift_type+'"]').prop('checked', true);
					$('[name="is_tracking"][value="'+data.is_tracking+'"]').prop('checked', true);
					
					$('[name="hdnempphoto"]').val(data.emp_photo);
					if(data.emp_photo != '' && data.emp_photo != null){
						$('span.file_emp_photo').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.emp_photo+'" width="150" height="150" >');
					}else{
						$('span.file_emp_photo').html('');
					}

					$('[name="hdnempsign"]').val(data.emp_signature);
					if(data.emp_signature != '' && data.emp_signature != null){
						$('span.file_emp_sign').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.emp_signature+'" width="150" height="150" >');
					}else{
						$('span.file_emp_sign').html('');
					}

					$('[name="hdnfotoktp"]').val(data.foto_ktp);
					if(data.foto_ktp != '' && data.foto_ktp != null){
						document.getElementById("form_file_ktp").style.display = "";
						$('span.file_ktp').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_ktp+'" width="150" height="150" >');
					}else{
						document.getElementById("form_file_ktp").style.display = "none";
						$('span.file_ktp').html('');
					}

					$('[name="hdnfotonpwp"]').val(data.foto_npwp);
					if(data.foto_npwp != '' && data.foto_npwp != null){
						document.getElementById("form_file_npwp").style.display = "";
						$('span.file_npwp').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_npwp+'" width="150" height="150" >');
					}else{
						document.getElementById("form_file_npwp").style.display = "none";
						$('span.file_npwp').html('');
					}

					$('[name="hdnfotobpjs"]').val(data.foto_bpjs);
					if(data.foto_bpjs != '' && data.foto_bpjs != null){
						document.getElementById("form_file_bpjs").style.display = "";
						$('span.file_bpjs').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_bpjs+'" width="150" height="150" >');
					}else{
						document.getElementById("form_file_bpjs").style.display = "none";
						$('span.file_bpjs').html('');
					}

					$('[name="hdnfotobpjs_ketenagakerjaan"]').val(data.foto_bpjs_ketenagakerjaan);
					if(data.foto_bpjs_ketenagakerjaan != '' && data.foto_bpjs_ketenagakerjaan != null){
						document.getElementById("form_file_bpjs_ketenagakerjaan").style.display = "";
						$('span.file_bpjs_ketenagakerjaan').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_bpjs_ketenagakerjaan+'" width="150" height="150" >');
					}else{
						document.getElementById("form_file_bpjs_ketenagakerjaan").style.display = "none";
						$('span.file_bpjs_ketenagakerjaan').html('');
					}

					$('[name="hdnfotosima"]').val(data.foto_sima);
					if(data.foto_sima != '' && data.foto_sima != null){
						document.getElementById("form_file_sima").style.display = "";
						$('span.file_sima').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_sima+'" width="150" height="150" >');
					}else{
						document.getElementById("form_file_sima").style.display = "none";
						$('span.file_sima').html('');
					}

					$('[name="hdnfotosimc"]').val(data.foto_simc);
					if(data.foto_simc != '' && data.foto_simc != null){
						document.getElementById("form_file_simc").style.display = "";
						$('span.file_simc').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_simc+'" width="150" height="150" >');
					}else{
						document.getElementById("form_file_simc").style.display = "none";
						$('span.file_simc').html('');
					}
					
					var date_of_hire = dateFormat(data.date_of_hire);
					$('[name="date_of_hire"]').datepicker('setDate', date_of_hire);
					var date_permanent = dateFormat(data.date_permanent);
					$('[name="date_permanent"]').datepicker('setDate', date_permanent);
					var date_of_birth = dateFormat(data.date_of_birth);
					$('[name="date_of_birth"]').datepicker('setDate', date_of_birth);
					var date_resign_letter = dateFormat(data.date_resign_letter);
					$('[name="date_resign_letter"]').datepicker('setDate', date_resign_letter);
					var date_resign_active = dateFormat(data.date_resign_active);
					$('[name="date_resign_active"]').datepicker('setDate', date_resign_active);
					var date_end_prob = dateFormat(data.date_end_probation);
					$('[name="date_end_prob"]').datepicker('setDate', date_end_prob);


					//$('[name="date_of_hire"]').val(data.date_of_hire);
					//$('[name="date_permanent"]').val(data.date_permanent);
					//$('[name="date_of_birth"]').val(data.date_of_birth);
					//$('[name="date_resign_letter"]').val(data.date_resign_letter);
					//$('[name="date_resign_active"]').val(data.date_resign_active);
					//$('[name="date_end_prob"]').val(data.date_end_probation);

					$('select#indirect').val(data.indirect_id).trigger('change.select2');
					$('select#marital_status').val(data.marital_status_id).trigger('change.select2');
					$('select#province1').val(data.province_id_ktp).trigger('change.select2');
					$('select#province2').val(data.province_id_residen).trigger('change.select2');
					$('select#job_title').val(data.job_title_id).trigger('change.select2');
					$('select#direct').val(data.direct_id).trigger('change.select2');
					$('select#emp_status').val(data.employment_status_id).trigger('change.select2');
					/*$('select#regency').val(data.regency_id).trigger('change.select2');
					$('select#village').val(data.village_id).trigger('change.select2');*/
					$('select#department').val(data.department_id).trigger('change.select2');
					$('select#last_education').val(data.last_education_id).trigger('change.select2');
					$('select#branch').val(data.branch_id).trigger('change.select2');
					$('select#company').val(data.company_id).trigger('change.select2');
					$('select#division').val(data.division_id).trigger('change.select2');
					$('select#section').val(data.section_id).trigger('change.select2');
					$('select#job_level').val(data.job_level_id).trigger('change.select2');
					$('select#grade').val(data.grade_id).trigger('change.select2');
					$('select#work_loc').val(data.work_location).trigger('change.select2');

					getRegency(data.province_id_ktp,'1','selected',data.regency_id_ktp);
					getDistrict(data.regency_id_ktp,data.province_id_ktp,'1','selected',data.district_id_ktp);
					getVillage(data.district_id_ktp,data.regency_id_ktp,data.province_id_ktp,'1','selected',data.village_id_ktp);

					getRegency(data.province_id_residen,'2','selected',data.regency_id_residen);
					getDistrict(data.regency_id_residen,data.province_id_residen,'2','selected',data.district_id_residen);
					getVillage(data.district_id_residen,data.regency_id_residen,data.province_id_residen,'2','selected',data.village_id_residen);


					var locate = 'table.ca-list';
					$.ajax({type: 'post',url: module_path+'/genexpensesrow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});



					var locate_training = 'table.ca-list-training';
					$.ajax({type: 'post',url: module_path+'/gentrainingrow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate_training+' tbody').html(obj[0]);
							
							wcount_training=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate_training);
						///expenseviewadjust(lstatus);
					});


					var locate_org = 'table.ca-list-org';
					$.ajax({type: 'post',url: module_path+'/genorgrow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate_org+' tbody').html(obj[0]);
							
							wcount_org=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate_org);
						///expenseviewadjust(lstatus);
					});


					var locate_workexp = 'table.ca-list-workexp';
					$.ajax({type: 'post',url: module_path+'/genworkexprow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate_workexp+' tbody').html(obj[0]);
							
							wcount_workexp=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate_workexp);
						///expenseviewadjust(lstatus);
					});
					
					$('[name="username"]').val(data.username);
					$('[name="gaji_bulanan"]').val(data.gaji_bulanan);
					$('[name="gaji_harian"]').val(data.gaji_harian);
					$('[name="ttl_hari_kerja"]').val(data.total_hari_kerja);
					$('[name="status_bpjs_kes"][value="'+data.status_bpjs_kesehatan+'"]').prop('checked', true);
					$('[name="status_bpjs_ket"][value="'+data.status_bpjs_ketenagakerjaan+'"]').prop('checked', true);
					document.getElementById("inpUsername").style.display = "block";
					document.getElementById("inpPassword").style.display = "block";
					$('[name="password"]')
					  .val('******')
					  .prop('disabled', true);

					
					
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
					$('span.address1').html(data.address_ktp);
					$('span.address2').html(data.address_residen);
					$('span.postal_code1').html(data.postal_code_ktp);
					$('span.postal_code2').html(data.postal_code_residen);

					var date_end_probation = data.date_end_probation;
					if(data.date_end_probation == '1970-01-01'){
						date_end_probation = '';
					}
					$('span.date_end_prob').html(date_end_probation);
					$('span.work_loc').html(data.work_location_name);
					$('span.emergency_phone').html(data.emergency_contact_phone);
					$('span.bank_address').html(data.bank_address);
					$('span.bank_acc_no').html(data.bank_acc_no);

					var date_resign_letter = data.date_resign_letter;
					if(data.date_resign_letter == '1970-01-01'){
						date_resign_letter = '';
					}
					$('span.date_resign_letter').html(date_resign_letter);
					var date_resign_active = data.date_resign_active;
					if(data.date_resign_active == '1970-01-01'){
						date_resign_active = '';
					}
					$('span.date_resign_active').html(date_resign_active);
					$('span.resign_category').html(data.resign_category);
					$('span.nick_name').html(data.nick_name);
					$('span.phone').html(data.personal_phone);
					$('span.ethnic').html(data.ethnic);
					$('span.no_ktp').html(data.no_ktp);
					$('span.sim_c').html(data.sim_c);
					$('span.no_bpjs').html(data.no_bpjs);
					$('span.no_bpjs_ketenagakerjaan').html(data.no_bpjs_ketenagakerjaan);

					var date_of_birth = data.date_of_birth;
					if(data.date_of_birth == '1970-01-01'){
						date_of_birth = '';
					}
					$('span.date_of_birth').html(date_of_birth);
					$('span.address2').html(data.address_2);

					var date_of_hire = data.date_of_hire;
					if(data.date_of_hire == '1970-01-01'){
						date_of_hire = '';
					}
					$('span.date_of_hire').html(date_of_hire);

					var date_permanent = data.date_permanent;
					if(data.date_permanent == '1970-01-01'){
						date_permanent = '';
					}
					$('span.date_permanent').html(date_permanent);
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
					$('span.regency1').html(data.regency_name_ktp);
					$('span.regency2').html(data.regency_name_residen);
					$('span.village1').html(data.village_name_ktp);
					$('span.village2').html(data.village_name_residen);
					$('span.department').html(data.department_name);
					$('span.emp_status').html(data.emp_status_name);
					$('span.indirect').html(data.indirect_name);
					$('span.branch').html(data.branch_name);
					$('span.marital_status').html(data.marital_status_name);
					$('span.province1').html(data.province_name_ktp);
					$('span.province2').html(data.province_name_residen);
					$('span.district1').html(data.district_name_ktp);
					$('span.district2').html(data.district_name_residen);
					$('span.job_title').html(data.job_title_name);
					$('span.direct').html(data.direct_name);
					$('span.gender').html(data.gender_name);
					$('span.status').html(data.status_name);
					$('span.job_level').html(data.job_level_name);
					$('span.grade').html(data.grade_name);
					$('span.emp_source').html(data.emp_source);
					$('span.is_tracking').html(data.is_tracking_name);
					$('span.username').html(data.username);
					$('span.start_pkwt').html(data.start_pkwt);
					$('span.end_pkwt').html(data.end_pkwt);
					$('span.ttl_hari_kerja').html(data.total_hari_kerja);
					$('span.status_bpjs_kes').html(data.status_bpjs_kesehatan_desc);
					$('span.status_bpjs_ket').html(data.status_bpjs_ketenagakerjaan_desc);
					$('span.gaji_bulanan').html(data.gaji_bulanan);
					$('span.gaji_harian').html(data.gaji_harian);


					if(data.emp_photo != '' && data.emp_photo != null){
						$('span.emp_photo').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.emp_photo+'" width="150" height="150" >');
					}else{
						$('span.emp_photo').html('');
					}

					if(data.emp_signature != '' && data.emp_signature != null){
						$('span.emp_signature').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.emp_signature+'" width="150" height="150" >');
					}else{
						$('span.emp_signature').html('');
					}

					
					if(data.foto_ktp != '' && data.foto_ktp != null){
						document.getElementById("view_foto_ktp").style.display = "";
						$('span.foto_ktp').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_ktp+'" width="150" height="150" >');
					}else{
						document.getElementById("view_foto_ktp").style.display = "none";
						$('span.foto_ktp').html('');
					}

					
					if(data.foto_npwp != '' && data.foto_npwp != null){
						document.getElementById("view_foto_npwp").style.display = "";
						$('span.foto_npwp').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_npwp+'" width="150" height="150" >');
					}else{
						document.getElementById("view_foto_npwp").style.display = "none";
						$('span.foto_npwp').html('');
					}

					
					if(data.foto_bpjs != '' && data.foto_bpjs != null){
						document.getElementById("view_foto_bpjs").style.display = "";
						$('span.foto_bpjs').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_bpjs+'" width="150" height="150" >');
					}else{
						document.getElementById("view_foto_bpjs").style.display = "none";
						$('span.foto_bpjs').html('');
					}

					if(data.foto_bpjs_ketenagakerjaan != '' && data.foto_bpjs_ketenagakerjaan != null){
						document.getElementById("view_foto_bpjs_ketenagakerjaan").style.display = "";
						$('span.foto_bpjs_ketenagakerjaan').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_bpjs_ketenagakerjaan+'" width="150" height="150" >');
					}else{
						document.getElementById("view_foto_bpjs_ketenagakerjaan").style.display = "none";
						$('span.foto_bpjs_ketenagakerjaan').html('');
					}

						
					if(data.foto_sima != '' && data.foto_sima != null){
						document.getElementById("view_foto_sima").style.display = "";
						$('span.foto_sima').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_sima+'" width="150" height="150" >');
					}else{
						document.getElementById("view_foto_sima").style.display = "none";
						$('span.foto_sima').html('');
					}

					
					if(data.foto_simc != '' && data.foto_simc != null){
						document.getElementById("view_foto_simc").style.display = "";
						$('span.foto_simc').html('<img src="'+baseUrl+'/uploads/employee/'+data.emp_code+'/'+data.foto_simc+'" width="150" height="150" >');
					}else{
						document.getElementById("view_foto_simc").style.display = "none";
						$('span.foto_simc').html('');
					}


					var locate = 'table.ca-list-detail';
					$.ajax({type: 'post',url: module_path+'/genexpensesrow',data: { id:data.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});



					var locate_training = 'table.ca-list-trainingdtl';
					$.ajax({type: 'post',url: module_path+'/gentrainingrow',data: { id:data.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate_training+' tbody').html(obj[0]);
							
							wcount_training=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate_training);
						///expenseviewadjust(lstatus);
					});


					var locate_org = 'table.ca-list-orgdtl';
					$.ajax({type: 'post',url: module_path+'/genorgrow',data: { id:data.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate_org+' tbody').html(obj[0]);
							
							wcount_org=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate_org);
						///expenseviewadjust(lstatus);
					});


					var locate_workexp = 'table.ca-list-workexpdtl';
					$.ajax({type: 'post',url: module_path+'/genworkexprow',data: { id:data.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate_workexp+' tbody').html(obj[0]);
							
							wcount_workexp=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate_workexp);
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


function dateFormat(tanggal) {
    if (!tanggal || tanggal === "0000-00-00") {
        return ""; // kosongkan saja, jangan ditampilkan
    }

    let parts = tanggal.split("-");
    if (parts.length !== 3) {
        return tanggal; // fallback kalau format aneh
    }

    return `${parts[1]}/${parts[2]}/${parts[0]}`;
}


$("#addcarow").on("click", function () { 
	var locate = 'table.ca-list';

	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/genexpensesrow',data: { count:wcount},success: function (response) {
			newRow.append(response);
			$(locate).append(newRow);
			wcount++;
			
		}
	}).done(function() {
		tSawBclear('table.order-list');
	});

	
});


$("#addcarow-training").on("click", function () { 
	var locate = 'table.ca-list-training';
	
	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/gentrainingrow',data: { count:wcount_training},success: function (response) {
			newRow.append(response);
			$(locate).append(newRow);
			wcount_training++;
			
		}
	}).done(function() {
		tSawBclear('table.order-list');
	});

	
});


$("#addcarow-org").on("click", function () { 
	var locate = 'table.ca-list-org';
	
	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/genorgrow',data: { count:wcount_org},success: function (response) {
			newRow.append(response);
			$(locate).append(newRow);
			wcount_org++;
			
		}
	}).done(function() {
		tSawBclear('table.order-list');
	});

	
});


$("#addcarow-workexp").on("click", function () { 
	var locate = 'table.ca-list-workexp';
	
	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/genworkexprow',data: { count:wcount_workexp},success: function (response) {
			newRow.append(response);
			$(locate).append(newRow);
			wcount_workexp++;
			
		}
	}).done(function() {
		tSawBclear('table.order-list');
	});

	
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


$('#address1').on('keyup', function () { 
	var chksame = $('input[name=is_same_address]:checked').val();
	var address1 = $("#address1").val();
 	
 	if(chksame == 'Y'){
		$('[name="address2"]').val(address1);
 	}
 	
});

$('#postal_code1').on('keyup', function () { 
	var chksame 		= $('input[name=is_same_address]:checked').val();
	var postal_code1 	= $("#postal_code1").val();
 	
 	if(chksame == 'Y'){
		$('[name="postal_code2"]').val(postal_code1);
 	}
 	
});


$('#is_same_address').change(function() { 
	var chksame 	= $('input[name=is_same_address]:checked').val();
	var address1 	= $("#address1").val();
	var province1 	= $("#province1 option:selected").val();
	var regency1 	= $("#regency1 option:selected").val();
	var district1 	= $("#district1 option:selected").val();
	var village1 	= $("#village1 option:selected").val();
 	var postal_code1 = $("#postal_code1").val();

	if(chksame == 'Y'){ 
		$('[name="address2"]').val(address1);
		$('[name="postal_code2"]').val(postal_code1);
		$('select#province2').val(province1).trigger('change.select2');

		getRegency(province1,'2','selected',regency1);
		getDistrict(regency1,province1,'2','selected',district1);
		getVillage(district1,regency1,province1,'2','selected',village1);
		
 	}
  
});


function getDistrict(regency,province, addresstype, selected='',idVal=''){

	if(regency != '' && province != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataDistrict',
			data: { province: province, regency:regency },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					if(addresstype == 1){ //ktp address
						var $el = $(".district1");
					}else{ //residential address
						var $el = $(".district2");
					}	

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msdistrict, function(key,value) {
					  	$el.append($("<option></option>")
					     .attr("value", value.id).text(value.name));
					});
					
					if(selected=='selected'){
						if(addresstype == 1){
							$('select#district1').val(idVal).trigger('change.select2');
						}else{
							$('select#district2').val(idVal).trigger('change.select2');
						}
					}
					
				} else { 
					

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

}


function getRegency(province, addresstype,selected='',idVal=''){ 

	if(province != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataRegency',
			data: { province: province },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					if(addresstype == 1){ //ktp address
						var $el = $(".regency1");
					}else{ //residential address
						var $el = $(".regency2");
					}	

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msregency, function(key,value) {
						$el.append($("<option></option>")
				     	.attr("value", value.id).text(value.name));
					  	
					});

					if(selected=='selected'){
						if(addresstype == 1){
							$('select#regency1').val(idVal).trigger('change.select2');
						}else{
							$('select#regency2').val(idVal).trigger('change.select2');
						}
					}
					
				} else { 
					

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

}

function getVillage(district,regency,province, addresstype, selected='',idVal=''){

	if(district != '' && regency != '' && province != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataVillage',
			data: { province: province, regency:regency, district:district },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					if(addresstype == 1){ //ktp address
						var $el = $(".village1");
					}else{ //residential address
						var $el = $(".village2");
					}	

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msvillage, function(key,value) {
					  	$el.append($("<option></option>")
					     .attr("value", value.id).text(value.name));
					});
					
					if(selected=='selected'){
						if(addresstype == 1){
							$('select#village1').val(idVal).trigger('change.select2');
						}else{
							$('select#village2').val(idVal).trigger('change.select2');
						}
						
					}

					
				} else { 
					

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

}



$('#province1').on('change', function () { 
 	var province1 	= $("#province1 option:selected").val();
 	var chksame 	= $('input[name=is_same_address]:checked').val();

	if(chksame == 'Y'){
		$('select#province2').val(province1).trigger('change.select2');
 	}

 	
 	getRegency(province1,'1');
});

$('#province2').on('change', function () { 
 	var province2 = $("#province2 option:selected").val();
 	
 	getRegency(province2,'2');
});

$('#regency1').on('change', function () { 
 	var regency1 	= $("#regency1 option:selected").val();
 	var province1 	= $("#province1 option:selected").val();
 	var chksame 	= $('input[name=is_same_address]:checked').val();

 	if(chksame == 'Y'){
		getRegency(province1,'2','selected');
 	}
 	
 	getDistrict(regency1,province1,'1');
});

$('#regency2').on('change', function () { 
 	var regency2 	= $("#regency2 option:selected").val();
 	var province2 	= $("#province2 option:selected").val();
 	
 	getDistrict(regency2,province2,'2');
});

$('#district1').on('change', function () { 
 	var district1 	= $("#district1 option:selected").val();
 	var regency1 	= $("#regency1 option:selected").val();
 	var province1 	= $("#province1 option:selected").val();
 	var chksame 	= $('input[name=is_same_address]:checked').val();

 	if(chksame == 'Y'){
		getDistrict(regency1,province1,'2','selected');
 	}
 	
 	getVillage(district1,regency1,province1,'1');
});

$('#district2').on('change', function () { 
 	var district2 	= $("#district2 option:selected").val();
 	var regency2 	= $("#regency2 option:selected").val();
 	var province2 	= $("#province2 option:selected").val();
 	
 	getVillage(district2,regency2,province2,'2');
});


$('#village1').on('change', function () { 
 	var village1 	= $("#village1 option:selected").val();
 	var chksame 	= $('input[name=is_same_address]:checked').val();

 	var district1 	= $("#district1 option:selected").val();
 	var regency1 	= $("#regency1 option:selected").val();
 	var province1 	= $("#province1 option:selected").val();

 	if(chksame == 'Y'){
		getVillage(district1,regency1,province1,'2','selected');
 	}
 	
});


function generateUsername(fullName) { 
	
	if (!fullName) return '';

    // strtolower + trim
    let username = fullName.toLowerCase().trim();

    // explode by space
    let words = username.split(' ');

    // kalau lebih dari 1 kata → ganti spasi jadi underscore
    if (words.length > 1) {
        username = username.replace(/\s+/g, '_');
    }

    return username;
	

    
}

// trigger saat diketik / diubah
document.getElementById('full_name').addEventListener('input', function () { 
	
	if(save_method == 'add'){ ///add
	    document.getElementById('username').value = generateUsername(this.value);
	}
});




<?php } ?>
</script>