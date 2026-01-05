

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string




$(document).ready(function() {
   	$(function() {
   	
		
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
	/*local=>*/ var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
	//var baseUrl = getUrl .protocol + "//" + getUrl.host;

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
					
					$('select#course').val(data.lms_course_id).trigger('change.select2');
					$('[name="title_materi"]').val(data.title);
					$('select#type').val(data.type).trigger('change.select2');


					/*var deptIds = [];
					if (data.department_ids) {
					    deptIds = data.department_ids.split(','); // ["1","3"]
					}

					$('select#departments')
					    .val(deptIds)
					    .trigger('change');*/

					var deptIds = [];
					if (data.department_ids) {
					    deptIds = data.department_ids.split(',');
					}

					var $deptSelect = $('#departments');

					deptIds.forEach(function(id){
					    // cek apakah option sudah ada
					    if ($deptSelect.find('option[value="' + id + '"]').length === 0) {
					        // kalau belum ada, tambahin option dummy
					        var newOption = new Option('Department ' + id, id, true, true);
					        $deptSelect.append(newOption);
					    }
					});

					$deptSelect.val(deptIds).trigger('change.select2');


					if(data.type == 1){ /// PDF
				   		document.getElementById('inpFile').style.display = 'block';
				   		document.getElementById('inpUrl').style.display = 'none';

				   		$('[name="hdnfile"]').val(data.file_pdf);
						const fileName = data.file_pdf; // ini bisa dari PHP atau hasil upload
					    const fileUrl = baseUrl+"/uploads/lms_materi/" + fileName;

					    // CLEAR link sebelumnya
						document.getElementById("file-link").innerHTML = '';

					    const link = document.createElement('a');
					    link.href = fileUrl;
					    link.textContent = "Current PDF";
					    link.target = "_blank";

					    document.getElementById("file-link").appendChild(link);

				 	}else{ //Youtube
				 		document.getElementById('inpFile').style.display = 'none';
				   		document.getElementById('inpUrl').style.display = 'block';
				 		
				 		$('[name="youtube_url"]').val(data.url_youtube);
				 	}

					
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.departments').html(data.department_names);
					$('span.course').html(data.course_name);
					$('span.title_materi').html(data.title);
					$('span.type').html(data.type_name);
					

					if(data.type == 1){ /// PDF
				   		document.getElementById('inpFileView').style.display = 'block';
				   		document.getElementById('inpUrlView').style.display = 'none';

				   		
						const fileName = data.file_pdf; // ini bisa dari PHP atau hasil upload
					    const fileUrl = baseUrl+"/uploads/lms_materi/" + fileName;

					    // CLEAR link sebelumnya
						document.getElementById("file-link-view").innerHTML = '';

					    const link = document.createElement('a');
					    link.href = fileUrl;
					    link.textContent = "Current PDF";
					    link.target = "_blank";

					    document.getElementById("file-link").appendChild(link);

				 	}else{ //Youtube
				 		document.getElementById('inpFileView').style.display = 'none';
				   		document.getElementById('inpUrlView').style.display = 'block';
				 		
				 		$('span.youtube_url').html(data.url_youtube);
				 	}

					
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



$('select[name="type"]').on('change', function () { 
  	var type = $("#type").val();
  
  	if(type == 1){ /// PDF
 		
   		document.getElementById('inpFile').style.display = 'block';
   		document.getElementById('inpUrl').style.display = 'none';

 	}else{ //Youtube

 		document.getElementById('inpFile').style.display = 'none';
   		document.getElementById('inpUrl').style.display = 'block';
 		
 	}

});


function downloadFile(filename) { 
    const link = document.createElement('a');
    link.href = module_path+'/downloadFile?file=' + encodeURIComponent(filename);

    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}




</script>