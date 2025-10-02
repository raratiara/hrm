<script type="text/javascript">


var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var opsForm = 'form#frmInputData';
var locate = 'table.approvalpic-list';
var wcount = 0; //for ca list row identify


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
					$('[name="approval_name"]').val(data.approval_name);
					$('select#location').val(data.work_location_id).trigger('change.select2');
					$('select#approval_type').val(data.approval_type_id).trigger('change.select2');
					
					if(data.approval_type_id == 1){ //Absence
				 		$('#divAbsenceType').show();
				 		$('select#absence_type').val(data.leave_type_id).trigger('change.select2');
				 	}else{
				 		$('#divAbsenceType').hide();
				 	}

				 	$('[name="min"]').val(data.min);
				 	$('[name="max"]').val(data.max);
				 	$('[name="description"]').val(data.description);


				 	var locate = 'table.approvalpic-list';
					$.ajax({type: 'post',url: module_path+'/genpicrow',data: {location:data.work_location_id, id:data.id },success: function (response) {
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
					$('span.approval_name').html(data.approval_name);
					$('span.location').html(data.work_location_name);
					$('span.approval_type').html(data.approval_type_name);

					if(data.approval_type_id == 1){ //Absence
				 		$('#divAbsenceTypeView').show();
				 		$('span.absence_type').html(data.leave_type_name);
				 	}else{
				 		$('#divAbsenceTypeView').hide();
				 	}

					$('span.min').html(data.min);
					$('span.max').html(data.max);
					$('span.description').html(data.description);

					
					var locate = 'table.approvalpic-list-view';
					$.ajax({type: 'post',url: module_path+'/genpicrow',data: {location:data.work_location_id, id:data.id, view:true },success: function (response) { 
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
<?php } ?>



$("#addapprovalpicrow").on("click", function () { 

	var location	= $("#location").val();
	if(location != ''){ 
		expire();
		var newRow = $("<tr>");
		$.ajax({type: 'post',url: module_path+'/genpicrow',data: {location:location, count:wcount },success: function (response) {
				newRow.append(response);
				$(locate).append(newRow);
				wcount++;
				
			}
		}).done(function() {
			tSawBclear('table.approvalpic-list');
		});
	}else{
		alert("Please choose Location");
	}
	
	

});


function del(idx,hdnid){

	if(hdnid != ''){ 
		$.ajax({type: 'post',url: module_path+'/delrowDetailPic',data: { id:hdnid },success: function (response) {}
		}).done(function() {
			tSawBclear('table.approvalpic-list');

		});
	}


	//delete tampilan row
	var table = document.getElementById("tblDetailApprovalPic");
	table.deleteRow(idx);
}


function getRole(location){
	if(location != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataRole',
			data: { location: location },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 	
					var $el = $(".approval_role");
					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msrole, function(key,value) {
					  	$el.append($("<option></option>")
					     .attr("value", value.id).text(value.role_name));
					});
					//$('select#approval_role').val(joborderid).trigger('change.select2');

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


 	}else{
 		alert("Please choose Location");
 	}


}


$('#location').on('change', function () { 
 	var location = $("#location option:selected").val();
 	

 	getRole(location);
 	

});


$('#approval_type').on('change', function () { 
 	var approval_type = $("#approval_type option:selected").val();
 	

 	if(approval_type == 1){ //Absence
 		$('#divAbsenceType').show();
 	}else{
 		$('#divAbsenceType').hide();
 	}

});



</script>