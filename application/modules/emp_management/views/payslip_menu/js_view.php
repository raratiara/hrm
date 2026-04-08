


<div id="modal-report-gaji-peremployee" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-report-gaji-peremployee" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportGajiperEmp" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Gaji
					<input type="hidden" id="hdnempid" name="hdnempid" />
					<input type="hidden" id="hdnpayrollid_emp" name="hdnpayrollid_emp" />
					
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadGaji_perEmployee_pdf()">
					<i class="fa fa-download"></i>
					Download PDF
				</button>
				
				<button class="btn" style="background-color: #fc596b; color: white; border-radius: 2px !important;" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Close
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>










<script type="text/javascript">
var baseUrl = "<?php echo base_url(); ?>";
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string



$(document).ready(function() {
   	$(function() {
   		
        $( "#due_date" ).datepicker();
		
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
		  	[3,'desc'] //submit date desc
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
					
				   
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.employee').html(data.full_name);
					$('span.period').html(data.period_name);

					const fileName 	= data.payslip_pdf_path; 
				    const fileUrl 	= baseUrl+"/" + fileName;

				    const link = document.createElement('a');
				    link.href = fileUrl;
				    link.textContent = 'Download';//fileName;
				    link.target = "_blank";

				    document.getElementById("file-link-view").appendChild(link);
					
					
					
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


function downloadFile(empcode,filename) { 
    const link = document.createElement('a');
    link.href = module_path+'/downloadFile?empcode='+empcode+'&file=' + encodeURIComponent(filename);

    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}



function getReportGaji_perEmployee(id,employee_id){

	$('#modal-report-gaji-peremployee').modal('show');

	$('[name="hdnpayrollid_emp"]').val(id);
	$('[name="hdnempid"]').val(employee_id);
}

function downloadGaji_perEmployee_pdf(){

	var id = $("#hdnpayrollid_emp").val();
	var emp_id = $("#hdnempid").val();
	
	

	if(emp_id != '' && id != ''){
		send_url = module_path+'/getPayrollReport_perEmployee_pdf?id='+id+'&emp_id='+emp_id+'';
		formData = $('#frmReportGajiperEmp').serialize();
		window.location = send_url+'&'+formData;
		$('#modal-report-gaji-peremployee').modal('hide');
	}else{
		alert("Data tidak ditemukan");
	}

	
}


</script>