


<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string



$(document).ready(function() {
   	$(function() {
   		
   		$( "#join_date" ).datepicker({
        	//startDate: '+1d'
        });

        $( "#contract_sign_date" ).datepicker({
        	//startDate: '+1d'
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
					
					$('[name="position"]').val(data.position_name);
					$('[name="name"]').val(data.full_name);
					$('[name="email"]').val(data.email);
					$('[name="phone"]').val(data.phone);
					$('select#status').val(data.status_id).trigger('change.select2');
					/*$('[name="join_date"]').val(data.join_date);
					$('[name="contract_sign_date"]').val(data.contract_sign_date);*/

					var join_date = dateFormat(data.join_date);
					$('[name="join_date"]').datepicker('setDate', join_date);
					var contract_sign_date = dateFormat(data.contract_sign_date);
					$('[name="contract_sign_date"]').datepicker('setDate', contract_sign_date);
					var end_prob_date = dateFormat(data.end_prob_date);
					$('[name="end_prob_date"]').datepicker('setDate', end_prob_date);

					$('[name="hdnfile"]').val(data.cv);

					const fileName = data.cv; // ini bisa dari PHP atau hasil upload
				    const fileUrl = baseUrl+"/uploads/documents/" + fileName;

				    // CLEAR link sebelumnya
					// document.getElementById("file-link").innerHTML = '<i class="fa fa-download"></i>';

				    // const link = document.createElement('a');
				    // link.href = fileUrl;
				    // link.textContent = " ";
				    // link.target = "_blank";

				    // document.getElementById("file-link").appendChild(link);


				    const link = document.createElement('a');
					link.href = fileUrl;
					link.target = "_blank";
					link.innerHTML = '<i class="fa fa-download"></i>'; // pakai icon sebagai isi link
					link.style.textDecoration = "none";
					link.style.color = "#007bff"; // warna biru (atau sesuaikan)

					document.getElementById("file-link").innerHTML = "";
					document.getElementById("file-link").appendChild(link);


				  	getStep(data.id, save_method);
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.position').html(data.position_name);
					$('span.full_name').html(data.full_name);
					$('span.phone').html(data.phone);
					$('span.email').html(data.email);
					$('span.status').html(data.status_name);
					$('span.join_date').html(data.join_date);
					$('span.contract_sign_date').html(data.contract_sign_date);
					$('span.end_prob_date').html(data.end_prob_date);


					const fileName = data.cv; // ini bisa dari PHP atau hasil upload
				    const fileUrl = baseUrl+"/uploads/documents/" + fileName;

				    const link = document.createElement('a');
					link.href = fileUrl;
					link.target = "_blank";
					link.innerHTML = '<i class="fa fa-download"></i>'; // pakai icon sebagai isi link
					link.style.textDecoration = "none";
					link.style.color = "#007bff"; // warna biru (atau sesuaikan)

					$('span.file-link-view').html();
					$('span.file-link-view').html(link);


					getStep(data.id, save_method);
					
					
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


function downloadFile(filename) { 
    const link = document.createElement('a');
    link.href = module_path+'/downloadFile?file=' + encodeURIComponent(filename);

    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}


function getStep(id, save_method) {

		$.ajax({
			type: "POST",
			url: module_path + '/getDataStep',
			data: {id: id, save_method: save_method },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != null) {
					if (save_method == 'detail') {
						$('span#tblstep_detail').html(data.tblstep);
					} else {
						$('span#tblstep').html(data.tblstep);
					}

				} else {
					if (save_method == 'detail') {
						$('span#tblstep_detail').html('');
					} else {
						$('span#tblstep').html('');
					}
				}

			},
			error: function (jqXHR, textStatus, errorThrown) {
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


</script>