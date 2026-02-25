
<div id="modal-daftar-lembur" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-daftar-lembur" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmDaftarLembur" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Daftar Pembayaran Lembur
					<input type="hidden" id="hdnpayrollid" name="hdnpayrollid" />
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>

				<button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadDaftarLembur()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button>

				<!-- <button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadGaji_pdf()">
					<i class="fa fa-download"></i>
					Download PDF
				</button> -->
				
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
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string





$(document).ready(function() {
   	/*$('input[name="period_start"]').datepicker();
   	$('input[name="period_end"]').datepicker();*/
   	
   	initFilterEmployee();

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
					
					$('[name="start_absen"]').val(data.tgl_start_absen);
					$('[name="end_absen"]').val(data.tgl_end_absen);
					$('select#project').val(data.project_id).trigger('change.select2');
					$('select#status').val(data.status).trigger('change.select2');
					getPeriodeGaji(data.project_id,'selected',data.payroll_slip_id)

					document.getElementById("inpStatus").style.display = "block";
					

					var locate = 'table.absenos_gaji-list';
					$.ajax({type: 'post',url: module_path+'/gengajiosrow',data: { id:data.id, project: data.project_id, bln: data.bulan_penggajian, thn: data.tahun_penggajian },success: function (response) { 
							var obj = JSON.parse(response); console.log(obj);
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
					$('span.project').html(data.project_name);
					$('span.start_absen').html(data.tgl_start_absen);
					$('span.status').html(data.status_payroll);
					$('span.periode_penggajian').html(data.periode_penggajian);
					$('span.end_absen').html(data.tgl_end_absen);

					
				
					
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



function initFilterEmployee() {
  if ($('#flemployee').hasClass('select2-hidden-accessible')) {
    $('#flemployee').select2('destroy');
  }

  $('#flemployee').select2({
    theme: 'bootstrap',
    width: '100%'
  });
}



function initSelect2(scope) { 
  $(scope).find('.select2me').each(function () {

    if ($(this).hasClass("select2-hidden-accessible")) {
      $(this).select2('destroy');
    }

    $(this).select2({
      theme: 'bootstrap',
      width: '100%',
      dropdownParent: $(scope) 
    });
  });
}


function resetEditGajiProjectForm() {
  var modal = $('#modal-form-editgajiaperproject');

  // 1. Clear input text, number, date, dll
  modal.find('input[type="text"], input[type="number"], input[type="date"], textarea').val('');

  // 2. Clear select biasa
  modal.find('select').val('');

  // 3. Clear Select2
  modal.find('.select2me').each(function () {
    if ($(this).hasClass('select2-hidden-accessible')) {
      $(this).val(null).trigger('change'); // reset value + UI
    }
  });

  // 4. (Opsional) Clear hidden id / mode edit
  modal.find('input[type="hidden"]').val('');
}


$('#modal-form-data').on('hide.bs.modal', function () {
  initFilterEmployee();
});


function subFilter(){
	var flproject = $("#flproject option:selected").val();
	

	if(flproject == ''){
		flproject=0;
	}

	
	
	$('#dynamic-table').DataTable().clear().destroy(); 
	$('#dynamic-table')
	.DataTable( {
		fixedHeader: {
			headerOffset: $('.page-header').outerHeight()
		},
		responsive: true,
		bAutoWidth: false,
		"aoColumnDefs": [
		  { "bSortable": false, "aTargets": [ 0,1 ] },
		  { "sClass": "text-center", "aTargets": [ 0,1 ] },
		],
		"aaSorting": [
		  	[2,'asc'] 
		],
		"sAjaxSource": module_path+"/get_data?flproject="+flproject+"",
		"bProcessing": true,
        "bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
    } );

}





$('#penggajian_year').on('keyup', function () { 
 	
 	var bln 	= $("#penggajian_month option:selected").val();
	var thn 	= $("#penggajian_year").val();
 	
 	if(bln != '' && thn != '' && thn.length === 4){

 		$.ajax({
			type: "POST",
	        url : module_path+'/getSummaryAbsen',
			data: { bln: bln, thn: thn },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != ''){ 	
					
					$('[name="period_start"]').val(data[0].tgl_start_absen);
					$('[name="period_end"]').val(data[0].tgl_end_absen);

				} else {  
					$('[name="period_start"]').val('');
					$('[name="period_end"]').val('');

					alert("Summary Absen di Bulan & Tahun penggajian tersebut tidak ditemukan. Mohon untuk Hitung Summary Absen terlebih dahulu");

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
		$('[name="period_start"]').val('');
		$('[name="period_end"]').val('');
 	}

});


$('#penggajian_month').on('change', function () { 
 	
 	var bln 	= $("#penggajian_month option:selected").val();
	var thn 	= $("#penggajian_year").val();
 	
 	if(bln != '' && thn != '' && thn.length === 4){

 		$.ajax({
			type: "POST",
	        url : module_path+'/getSummaryAbsen',
			data: { bln: bln, thn: thn },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != ''){ 	
					
					$('[name="period_start"]').val(data[0].tgl_start_absen);
					$('[name="period_end"]').val(data[0].tgl_end_absen);

				} else {  
					$('[name="period_start"]').val('');
					$('[name="period_end"]').val('');

					alert("Summary Absen di Bulan & Tahun penggajian tersebut tidak ditemukan. Mohon untuk Hitung Summary Absen terlebih dahulu");

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
		$('[name="period_start"]').val('');
		$('[name="period_end"]').val('');
 	}

});



function getDaftarLemburOS(payroll_id){
	
	$('#modal-daftar-lembur').modal('show');

	$('[name="hdnpayrollid"]').val(payroll_id);
}

function downloadDaftarLembur (){

	
	var payroll_id = $("#hdnpayrollid").val();

	if(payroll_id != ''){

  		// send_url = 
		send_url = module_path+'/getDaftarLemburOS?payroll_id='+payroll_id+'';
		formData = $('#frmDaftarLembur').serialize();
		window.location = send_url+'&'+formData;
		$('#modal-daftar-lembur').modal('hide');


	}else{
		alert("Data tidak ditemukan");
	}

	
}



$('#project').on('change', function () {
    var project = $(this).val();

    $('#periode_gaji')
        .val(null)
        .empty()
        .append('<option value=""></option>')
        .trigger('change.select2');

    $('[name="start_absen"]').val('');
	$('[name="end_absen"]').val('');

    if (project) {
        getPeriodeGaji(project);
    }
});



function getPeriodeGaji(project,selected='',idVal=''){ 

	if(project != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataPeriodeGaji',
			data: { project: project },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					
					var $el = $("#periode_gaji");

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msperiode, function(key,value) {
						$el.append($("<option></option>")
				     	.attr("value", value.id).text(value.periode_penggajian));
					  	
					});

					if(selected=='selected'){
						$('select#periode_gaji').val(idVal).trigger('change.select2');
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


$('#periode_gaji').on('change', function () {
    var payroll_id = $(this).val();


    $('[name="start_absen"]').val('');
	$('[name="end_absen"]').val('');

    if (payroll_id) {
        getPayroll(payroll_id);
    }
});



function getPayroll(payroll_id,selected='',idVal=''){ 

	if(payroll_id != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataPayroll',
			data: { payroll_id: payroll_id },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					
					$('[name="start_absen"]').val(data[0].tgl_start_absen);
					$('[name="end_absen"]').val(data[0].tgl_end_absen);
					
				} else { 
					
					$('[name="start_absen"]').val('');
					$('[name="end_absen"]').val('');
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




</script>