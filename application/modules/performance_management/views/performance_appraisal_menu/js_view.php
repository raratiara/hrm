
<!-- Modal Reject Data -->
<div id="modal-rfu-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-rfu-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content">
			<form class="form-horizontal" id="frmRfuData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Request For Update 
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to request for update this Data?</p>
				<input type="hidden" name="id" id="id" value="">
				Reason :
				<input type="text" name="rfu_reason" id="rfu_reason" value="">
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-rfu-data" onclick="save_rfu()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn blue" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>


<!-- Modal Reject Data -->
<div id="modal-approve-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-approve-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content">
			<form class="form-horizontal" id="frmApproveData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Approval 
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to approve this Data?</p>
				<input type="hidden" name="id" id="id" value="">
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-approve-data" onclick="save_approve()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn blue" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>



<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var modloc = '/_hrm/performance_management/performance_appraisal_menu/';
var opsForm = 'form#frmInputData';
var locate = 'table.ca-list';
var dlocate = 'table.dca-list';
var wcount = 0; //for ca list row identify




$(document).ready(function() {
   	$(function() {
   		
        $( "#training_date" ).datetimepicker();

        /*var acc = document.getElementsByClassName("accordion");
		var i;

		for (i = 0; i < acc.length; i++) {
		  acc[i].addEventListener("click", function() {
		    this.classList.toggle("active");
		    var panel = this.nextElementSibling;
		    if (panel.style.display === "block") {
		      panel.style.display = "none";
		    } else {
		      panel.style.display = "block";
		    }
		  });
		}*/


		const acc = document.querySelector('.accordion');
		const panel = document.querySelector('.panel');

		  acc.addEventListener('click', function() {
		    acc.classList.toggle('active');
		    panel.classList.toggle('show');
		  });



		const acc2 = document.querySelector('#accordion_softskill');
		const panel2 = document.querySelector('#tabsoftskill');

		  acc2.addEventListener('click', function() {
		    acc2.classList.toggle('active');
		    panel2.classList.toggle('show');
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
					
					$('select#employee').val(data.employee_id).trigger('change.select2');
					$('[name="year"]').val(data.year);
					$('[name="hdnttl_final_score"]').val(data.total_final_score);
					$('span#ttl_final_score').html(data.total_final_score);
					

					$.ajax({type: 'post',url: modloc+'genhardskillrow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

					getSoftskill(data.employee_id,data.id,save_method);
					
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.employee').html(data.full_name);
					$('span.year').html(data.year);
					$('span#ttl_final_score').html(data.total_final_score);

					$.ajax({type: 'post',url: modloc+'genhardskillrow',data: { id:data.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

					getSoftskill(data.employee_id,data.id,save_method);

					
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


function rfu(id){

	$('#modal-rfu-data').modal('show');
	$('[name="id"]').val(id);

}

function approve(id){

	$('#modal-approve-data').modal('show');
	$('[name="id"]').val(id);

}


function save_rfu(){
	var id 		= $("#id").val();
	var reason 	= $("#rfu_reason").val();

	$('#modal-rfu-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/rfu',
			data: { id: id, reason:reason },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if(data != false){ 	
					alert("The data has been successfully rfu.");
				} else { 
					alert("Failed to rfu the data!");
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
		alert("Data not found!");
	}

	location.reload();


}


function save_approve(){
	var id 	= $("#id").val();

	$('#modal-approve-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/approve',
			data: { id: id },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if(data != false){ 	
					alert("The data has been successfully approved.");
				} else { 
					alert("Failed to approve the data!");
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
		alert("Data not found!");
	}

	location.reload();


}


$("#addhardskill").on("click", function () { 
	
	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/genhardskillrow',data: { count:wcount },success: function (response) {
			newRow.append(response);
			$(locate).append(newRow);
			wcount++;
			
		}
	}).done(function() {
		tSawBclear('table.order-list');
	});

	
});


function del(idx,hdnid){
	
	if(hdnid != ''){ //delete dr table

		$.ajax({type: 'post',url: module_path+'/delrowDetailHardskill',data: { id:hdnid },success: function (response) {
				
			}
		}).done(function() {
			tSawBclear('table.order-list');
		});

	}

	//delete tampilan row

	var table = document.getElementById("tblDetailHardskill");
	table.deleteRow(idx);
	

}


function getSoftskill(employee,id,save_method){
		
	$.ajax({
		type: "POST",
        url : module_path+'/getDataSoftskill',
		data: { employee: employee, id:id, save_method:save_method },
		cache: false,		
        dataType: "JSON",
        success: function(data)
        {   
			if(data != null){ 	
				if(save_method == 'detail'){
					$('span#tblsoftskill_detail').html(data.tblsoftskill);
				}else{
					$('span#tblsoftskill').html(data.tblsoftskill);
				}

			} else { 
				if(save_method == 'detail'){
					$('span#tblsoftskill_detail').html('');
				}else{
					$('span#tblsoftskill').html('');
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


$('#employee').on('change', function () { 
 	var employee 	= $("#employee option:selected").val();
 	var id 			= $("#id").val();
 
 
 	getSoftskill(employee,id,save_method);
 	
});



function set_score_emp(val){ 
	var row = val.dataset.id;  
	var score_emp = val.value;
	var weight = $('[name="weight['+row+']"]').val();

	var final_score = (weight*score_emp)/100;
	
	$('[name="final_score['+row+']"]').val(final_score);
	$('[name="score_emp['+row+']"]').val(score_emp);


	var sum=0; 
    $(".final_score").each(function(index, element){ 
        sum += +$(this).val(); 
    });
    $('span#ttl_final_score').html(sum/2); //dibagi 2 karna rownya munculnya 2x lipat?
    $('[name="hdnttl_final_score"]').val(sum/2);

    
}




function set_score_direct(val){ 
	var row = val.dataset.id;  
	var score_direct = val.value;
	var weight = $('[name="weight['+row+']"]').val();

	var final_score = (weight*score_direct)/100;
	/*console.log('weight: '+weight+' & score: '+score_emp+'');*/
	
	$('[name="final_score['+row+']"]').val(final_score);
	$('[name="score_direct['+row+']"]').val(score_direct);


	var sum=0; 
	$(".final_score").each(function(index, element){ 
        sum += +$(this).val(); 
    });
    $('span#ttl_final_score').html(sum/2); //dibagi 2 karna rownya munculnya 2x lipat?
    $('[name="hdnttl_final_score"]').val(sum/2);

}


function set_weight(val){ 
	var row = val.dataset.id;  
	var weight = val.value;
	var score_emp = $('[name="score_emp['+row+']"]').val();
	var score_direct = $('[name="score_direct['+row+']"]').val();

	var score = score_emp;
	if(score_direct != ''){
		var score = score_direct;
	}
	
	

	var final_score = (weight*score)/100;
	//console.log('weight: '+weight+' & score: '+score_emp+'');
	
	$('[name="final_score['+row+']"]').val(final_score);
	$('[name="weight['+row+']"]').val(weight);



	var sum=0; 
	$(".final_score").each(function(index, element){ 
        sum += +$(this).val(); 
    });
    $('span#ttl_final_score').html(sum/2); //dibagi 2 karna rownya munculnya 2x lipat?
    $('[name="hdnttl_final_score"]').val(sum/2);

}



</script>