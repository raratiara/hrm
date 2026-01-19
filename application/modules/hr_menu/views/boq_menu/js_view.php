<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string



$(document).ready(function() {
   	$(function() {
   		
        $( "#show_date_start" ).datepicker();
        $( "#show_date_end" ).datepicker();
		
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
					
					$('[name="label1"]').val(data.label1);
					$('[name="label2"]').val(data.label2);
					$('[name="title"]').val(data.title);
					$('[name="description"]').val(data.description);

					var show_date_start = getFormattedDateTime(data.show_date_start);
					var show_date_end = getFormattedDateTime(data.show_date_end);
					$('[name="show_date_start"]').val(show_date_start);
					$('[name="show_date_end"]').val(show_date_end);
					$('[name="info_type"][value="'+data.type+'"]').prop('checked', true);

					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.employee').html(data.employee_name);
					$('span.task').html(data.task);
					$('span.progress').html(data.progress_percentage);
					$('span.status').html(data.status_name);
					$('span.task_parent').html(data.parent_name);
					$('span.due_date').html(data.due_date);
					$('span.solve_date').html(data.solve_date);
					
					
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


$('#customer_boq').on('change', function () {
    var customer = $(this).val();

    $('#project_boq')
        .val(null)
        .empty()
        .append('<option value=""></option>')
        .trigger('change.select2');

    if (customer) {
        getProject(customer);
    }
});



function getProject(customer,selected='',idVal=''){ 

	if(customer != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataProject',
			data: { customer: customer },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					
					var $el = $("#project_boq");

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msproject, function(key,value) {
						$el.append($("<option></option>")
				     	.attr("value", value.id).text(value.project_desc));
					  	
					});

					if(selected=='selected'){
						$('select#project_boq').val(idVal).trigger('change.select2');
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


$('#project_boq').on('change', function () { 
 	var project = $("#project_boq option:selected").val();
 	var customer 	= $("#customer_boq option:selected").val();
	
 	
 	if(project != '' && customer != ''){

 		document.getElementById("divBoq").style.display = "block";

		var locate = 'table.boq-list';
		$.ajax({type: 'post',url: module_path+'/genboqrow',data: { id: 0},success: function (response) {
			var obj = JSON.parse(response); console.log(obj);
			$(locate+' tbody').html(obj[0]);
			
			wcount=obj[1];
		}
		}).done(function() {
			//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
			tSawBclear(locate);
			///expenseviewadjust(lstatus);
		});

 	}

});



$('#customer_boq').on('change', function () { 
 	var project = $("#project_boq option:selected").val();
 	var customer 	= $("#customer_boq option:selected").val();
	
 	
 	if(project != '' && customer != ''){

 		document.getElementById("divBoq").style.display = "block";

		var locate = 'table.boq-list';
		$.ajax({type: 'post',url: module_path+'/genboqrow',data: { id: 0},success: function (response) {
			var obj = JSON.parse(response);
			$(locate+' tbody').html(obj[0]);
			
			wcount=obj[1];
		}
		}).done(function() {
			//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
			tSawBclear(locate);
			///expenseviewadjust(lstatus);
		});

 	}

});



</script>