<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
//in LOCAL  var modloc = '/_hrm/performance_management/performance_appraisal_menu/';
var modloc = '/hr_menu/request_recruitment_menu/';
var opsForm = 'form#frmInputData';
var locate = 'table.requirement-list';
var locate2 = 'table.job-list';
var wcount = 0; //for ca list row identify
var wcount2 = 0;



$(document).ready(function() {
   	$(function() {
   		
       	$('input[name="request_date"]').datepicker();
       	$('input[name="required_date"]').datepicker();


		const acc2 = document.querySelector('#accordion_requirement');
		const panel2 = document.querySelector('#tabrequirement');
	  	acc2.addEventListener('click', function() {
		    acc2.classList.toggle('active');
		    panel2.classList.toggle('show');
	  	});


	  	const acc3 = document.querySelector('#accordion_job');
		const panel3 = document.querySelector('#tabjob');
	  	acc3.addEventListener('click', function() {
		    acc3.classList.toggle('active');
		    panel3.classList.toggle('show');
	  	});


		
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
					$('[name="req_number"]').val(data.request_number);
					$('[name="subject"]').val(data.subject);
					var request_date = dateFormat(data.request_date);
					$('[name="request_date"]').datepicker('setDate', request_date);
					var required_date = dateFormat(data.required_date);
					$('[name="required_date"]').datepicker('setDate', required_date);
					$('[name="headcount"]').val(data.headcount);
					$('[name="justification"]').val(data.justification);
					var status_emp = ucwords(data.status_emp);
					$('select#empstatus').val(status_emp).trigger('change.select2');
					$('select#section').val(data.section_id).trigger('change.select2');
					$('select#joblevel').val(data.job_level_id).trigger('change.select2');
					$('select#request_by').val(data.requested_by).trigger('change.select2');


					$.ajax({type: 'post',url: module_path+'/genreqrow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

					$.ajax({type: 'post',url: module_path+'/genjobrow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate2+' tbody').html(obj[0]);
							
							wcount2=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate2);
						///expenseviewadjust(lstatus);
					});
					
					
					if(data.status == 'draft'){
						document.getElementById("btnDraft").style.display = "";
					}else{
						document.getElementById("btnDraft").style.display = "none";
					}
					
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.request_number').html(data.request_number);
					$('span.subject').html(data.subject);
					$('span.request_date').html(data.request_date);
					$('span.required_date').html(data.required_date);
					$('span.section').html(data.section_name);
					$('span.headcount').html(data.headcount);
					$('span.job_level').html(data.job_level_name);
					var status_emp = ucwords(data.status_emp);
					$('span.emp_status').html(status_emp);
					$('span.justification').html(data.justification);
					$('span.request_by').html(data.requested_by_name);


					$.ajax({type: 'post',url: module_path+'/genreqrow',data: { id:data.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});


					$.ajax({type: 'post',url: module_path+'/genjobrow',data: { id:data.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate2+' tbody').html(obj[0]);
							
							wcount2=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate2);
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
<?php } ?>


function ucwords(str) {
  return str
    .toLowerCase()
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
}


function dateFormat(tanggal){
	/*let tanggal = "2025-06-24";*/

	let parts = tanggal.split("-"); // ['2025', '06', '24']
	let hasil = `${parts[1]}/${parts[2]}/${parts[0]}`;

	return hasil;
}


$("#addrequirement").on("click", function () { 
	
	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/genreqrow',data: { count:wcount },success: function (response) {
			newRow.append(response);
			$(locate).append(newRow);
			wcount++;
		}
	}).done(function() {
		tSawBclear('table.requirement-list');
	});
	
});


$("#addjob").on("click", function () { 
	
	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/genjobrow',data: { count:wcount2 },success: function (response) {
			newRow.append(response);
			$(locate2).append(newRow);
			wcount2++;
		}
	}).done(function() {
		tSawBclear('table.job-list');
	});
	
});


function del(idx,hdnid){
	
	if(hdnid != ''){ //delete dr table
		$.ajax({type: 'post',url: module_path+'/delrowDetailReq',data: { id:hdnid },success: function (response) {
				
			}
		}).done(function() {
			tSawBclear('table.requirement-list');
		});
	}

	//delete tampilan row
	var table = document.getElementById("tblDetailRequirement");
	table.deleteRow(idx);
	
}


function delJob(idx,hdnid){
	
	if(hdnid != ''){ //delete dr table
		$.ajax({type: 'post',url: module_path+'/delrowDetailJob',data: { id:hdnid },success: function (response) {
				
			}
		}).done(function() {
			tSawBclear('table.job-list');
		});
	}

	//delete tampilan row
	var table = document.getElementById("tblDetailJob");
	table.deleteRow(idx);
	
}




</script>