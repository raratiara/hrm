
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



<div id="modal-rekapitulasi-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-rekapitulasi-data" aria-hidden="true" style="padding-left: 600px">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:80%; text-align:center;">
			<form class="form-horizontal" id="frmRekapitulasiData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Rekapitulasi 
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Apakah anda yakin akan merekapitulasi data absen ini?</p>
				<div class="form-group">
					<input type="hidden" name="id" id="id" value="">
					
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-rfu-data" onclick="save_rekapitulasi()">
					<i class="fa fa-check"></i>
					Ya
				</button>
				<button class="btn blue" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Tidak
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>


<div id="modal-report-summary_absen_os" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-report-summary_absen_os" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px">
			<form class="form-horizontal" id="frmReportSummaryAbsenOS" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Summary Absensi Outsource
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>

				<button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReportSummaryAbsenOS()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button>

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReportSummaryAbsenOS_pdf()">
					<i class="fa fa-download"></i>
					Download PDF
				</button>
				
				<button class="btn" style="background-color: #fc596b; color: black; border-radius: 2px !important;" data-dismiss="modal">
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
   	$('input[name="period_start"]').datepicker();
   	$('input[name="period_end"]').datepicker();
   	//$('input[name="perioddate"]').daterangepicker();
   	$('input[name="period_start_edit"]').datepicker();
   	$('input[name="period_end_edit"]').datepicker();


   	initFilterEmployee();

   	
   	
});

$('input[name="perioddate"]').daterangepicker({
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Clear'
    }
});

$('input[name="perioddate"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(
        picker.startDate.format('MM/DD/YYYY') + ' - ' +
        picker.endDate.format('MM/DD/YYYY')
    );
});

$('input[name="perioddate"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});


function toYYYYMMDD(dateStr) {
  const parts = dateStr.split('/');
  const date = new Date(`${parts[2]}-${parts[0]}-${parts[1]}`); // YYYY-MM-DD
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}


function subFilter(){
	var flemployee = $("#flemployee option:selected").val();
	var perioddate = $("#perioddate").val();

	if(flemployee == ''){
		flemployee=0;
	}

	fldatestart=0;
	fldateend=0;
	if(perioddate != ''){
		var myArray = perioddate.split(" - ");
		var start = myArray[0];
		var end = myArray[1];

		fldatestart=toYYYYMMDD(start);
		fldateend=toYYYYMMDD(end);
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
		"sAjaxSource": module_path+"/get_data?flemployee="+flemployee+"&fldatestart="+fldatestart+"&fldateend="+fldateend+"",
		"bProcessing": true,
        "bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
    } );

}


function getReport_summ_absen_os(){
	
	$('#modal-report-summary_absen_os').modal('show');

}


function downloadReportSummaryAbsenOS(){ 

	
	send_url = module_path+'/getAbsenceReportSummaryAbsenOS';
	
	window.location = send_url;
	$('#modal-report-summary_absen_os').modal('hide');
	
}


function downloadReportSummaryAbsenOS_pdf(){ 

	
	send_url = module_path+'/getAbsenceReportSummaryAbsenOS_pdf';
	
	window.location = send_url;
	$('#modal-report-summary_absen_os').modal('hide');
	
}



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
		  { "sClass": "text-center", "aTargets": [ 0,1 ] },
		  { "flemployee": 12,},
		],
		"aaSorting": [
		  	[2,'asc'] 
		],
		/*"sAjaxSource": module_path+"/get_data?flemployee=12",*/
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
					$('[name="penggajian_year"]').val(data.tahun);
					$('select#penggajian_month').val(data.bulan).trigger('change.select2');
					var tgl_start = dateFormat(data.tgl_start);
					var tgl_end = dateFormat(data.tgl_end);
					
					$('[name="period_start"]').datepicker('setDate', tgl_start);
					$('[name="period_end"]').datepicker('setDate', tgl_end);

					
					/*document.getElementById("inp_is_all_employee").style.display = "none";*/
					document.getElementById("inp_is_all_project").style.display = "none";
					document.getElementById("inputEmployee").style.display = "none";
					document.getElementById("inputProject").style.display = "none";
					
					document.getElementById("inpEmp").style.display = "block";
					$('span.employee_name').html(data.full_name);

					
					document.getElementById("inpAbsenOS").style.display = "block";
					document.getElementById("inpAbsenOS_edit").style.display = "none";

					var locate = 'table.absenos-list';
					$.ajax({type: 'post',url: module_path+'/genabsenosrow',data: { id:data.id },success: function (response) { 
							var obj = JSON.parse(response); console.log(obj);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});


					// Create a new button
					var submitBtn = document.getElementById('submit-data');
					if (!document.getElementById('idbtnRekapitulasi')) {
						var rekapitulasiButton = document.createElement('button');
						rekapitulasiButton.innerText = 'Rekapitulasi';
						rekapitulasiButton.className = 'btn btn-success btnRekapitulasi';
						rekapitulasiButton.id = 'idbtnRekapitulasi';
						// Append the button to the footer
						/*modalFooter.appendChild(rfuButton);*/
						// Sisipkan setelah Save
    					/*submitBtn.insertAdjacentElement('afterend', rekapitulasiButton);*/
    					submitBtn.insertAdjacentElement('beforebegin', rekapitulasiButton);


						rekapitulasiButton.addEventListener('click', function() {
							$('#modal-rekapitulasi-data').modal('show');
							$('[name="id"]').val(data.id);
						});
					}
				
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.penggajian_year').html(data.tahun);
					$('span.penggajian_month').html(data.month_name);
					$('span.period_start').html(data.tgl_start);
					$('span.period_end').html(data.tgl_end);
					$('span.employee').html(data.full_name);

					document.getElementById("inpAbsenOSView").style.display = "block";
					document.getElementById("inpAbsenOS_edit").style.display = "none";

					var locate = 'table.absenos-list-view';
					$.ajax({type: 'post',url: module_path+'/genabsenosrow',data: { id:data.id, view:true },success: function (response) { 
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


/*document.querySelectorAll('input[name="is_all_employee"]').forEach(function(radio) {
  radio.addEventListener('click', function() {
  	
	  	if(this.value == 'Tidak'){
	  		document.getElementById("inputEmployee").style.display = "block";
	  	}else{
	  		document.getElementById("inputEmployee").style.display = "none";
	  	}
    
  });
});*/

document.querySelectorAll('input[name="is_all_project"]').forEach(function(radio) {
  radio.addEventListener('click', function() {
  	
	  	if(this.value == 'Karyawan'){
	  		document.getElementById("inputEmployee").style.display = "block";
	  		document.getElementById("inputProject").style.display = "none";
	  	}else if(this.value == 'Sebagian'){
	  		document.getElementById("inputProject").style.display = "block";
	  		document.getElementById("inputEmployee").style.display = "none";
	  	}else{
	  		document.getElementById("inputEmployee").style.display = "none";
	  		document.getElementById("inputProject").style.display = "none";
	  	}
    
  });
});



function save_rekapitulasi(){
	var id = $("#id").val();
	var penggajian_month 	= $("#penggajian_month").val();
	var penggajian_year 	= $("#penggajian_year").val();
	var period_start 		= $("#period_start").val();
	var period_end 			= $("#period_end").val();
	var ttl_hari_kerja 		= $("#ttl_hari_kerja").val();
	var ttl_masuk 			= $("#ttl_masuk").val();
	var ttl_ijin 			= $("#ttl_ijin").val();
	var ttl_cuti 			= $("#ttl_cuti").val();
	var ttl_alfa 			= $("#ttl_alfa").val();
	var ttl_lembur 			= $("#ttl_lembur").val();
	var ttl_jam_kerja 		= $("#ttl_jam_kerja").val();
	var ttl_jam_lembur 		= $("#ttl_jam_lembur").val();



	$('#modal-rekapitulasi-data').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	        url : module_path+'/rekapitulasi',
			data: { id: id, penggajian_month: penggajian_month, penggajian_year: penggajian_year, period_start: period_start, period_end: period_end, ttl_hari_kerja: ttl_hari_kerja, ttl_masuk: ttl_masuk, ttl_ijin: ttl_ijin, ttl_cuti: ttl_cuti, ttl_alfa: ttl_alfa, ttl_lembur: ttl_lembur, ttl_jam_kerja: ttl_jam_kerja, ttl_jam_lembur: ttl_jam_lembur},
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if(data != false){ 	
					alert("Data berhasil di rekapitulasi.");
				} else { 
					alert("Data gagal di rekapitulasi!");
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
		alert("Data tidak ditemukan!");
	}

	location.reload();


}



$('#project_edit').on('change', function () { 
 	var project = $("#project_edit option:selected").val();
 	var bln 	= $("#penggajian_month_edit option:selected").val();
	var thn 	= $("#penggajian_year_edit").val();
 	
 	if(project != '' && bln != '' && thn != '' && thn.length === 4){

 		$.ajax({
			type: "POST",
	        url : module_path+'/getAbsenProject',
			data: { project: project, bln: bln, thn: thn },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != ''){ 	
					
					document.getElementById("inpAbsenOS_edit").style.display = "block";

					var locate = 'table.absenos_edit-list';
					$.ajax({type: 'post',url: module_path+'/geneditabsenrow',data: { project: project, bln: bln, thn: thn },success: function (response) {
						var obj = JSON.parse(response);
						$(locate+' tbody').html(obj[0]);
						
						wcount=obj[1];
					}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

				} else {  
					document.getElementById("inpAbsenOS_edit").style.display = "none";
					alert("Data Absen di data Project, Bulan & Tahun penggajian tersebut tidak ditemukan. Mohon untuk Hitung Absen terlebih dahulu");

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

});


$('#penggajian_month_edit').on('change', function () { 
 	var project = $("#project_edit option:selected").val();
 	var bln 	= $("#penggajian_month_edit option:selected").val();
	var thn 	= $("#penggajian_year_edit").val();
 	
 	if(project != '' && bln != '' && thn != '' && thn.length === 4){

 		$.ajax({
			type: "POST",
	        url : module_path+'/getAbsenProject',
			data: { project: project, bln: bln, thn: thn },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != ''){ 	
					
					document.getElementById("inpAbsenOS_edit").style.display = "block";

					var locate = 'table.absenos_edit-list';
					$.ajax({type: 'post',url: module_path+'/geneditabsenrow',data: { project: project, bln: bln, thn: thn },success: function (response) {
						var obj = JSON.parse(response);
						$(locate+' tbody').html(obj[0]);
						
						wcount=obj[1];
					}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

				} else {  
					document.getElementById("inpAbsenOS_edit").style.display = "none";
					alert("Data Absen di data Project, Bulan & Tahun penggajian tersebut tidak ditemukan. Mohon untuk Hitung Absen terlebih dahulu");

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

});

$('#penggajian_year_edit').on('keyup', function () { 
 	var project = $("#project_edit option:selected").val();
 	var bln 	= $("#penggajian_month_edit option:selected").val();
	var thn 	= $("#penggajian_year_edit").val();
 	
 	if(project != '' && bln != '' && thn != '' && thn.length === 4){

 		$.ajax({
			type: "POST",
	        url : module_path+'/getAbsenProject',
			data: { project: project, bln: bln, thn: thn },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != ''){ 	
					
					document.getElementById("inpAbsenOS_edit").style.display = "block";

					var locate = 'table.absenos_edit-list';
					$.ajax({type: 'post',url: module_path+'/geneditabsenrow',data: { project: project, bln: bln, thn: thn },success: function (response) {
						var obj = JSON.parse(response);
						$(locate+' tbody').html(obj[0]);
						
						wcount=obj[1];
					}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

				} else {  
					document.getElementById("inpAbsenOS_edit").style.display = "none";
					alert("Data Absen di data Project, Bulan & Tahun penggajian tersebut tidak ditemukan. Mohon untuk Hitung Absen terlebih dahulu");

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

});


function edit_per_project() {
    var formData = $('#frmInputDataEditProject').serialize();

    $.ajax({
        url : module_path+'/save_edit_per_project',
        type: 'POST',
        data: formData,
        success: function (res) {
		    var data = JSON.parse(res);

		    if (data.status) {
		        alert(data.message);
		    } else {
		        alert('Error: ' + data.message);
		    }

		    location.reload();
		}
    });
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


$('#modal-form-editperproject').on('shown.bs.modal', function () {
  initSelect2(this);
});


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


function resetEditProjectForm() {
  var modal = $('#modal-form-editperproject');

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

</script>