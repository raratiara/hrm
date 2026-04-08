
<div id="modal-report-gaji" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-report-gaji" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportGaji" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Gaji
					<input type="hidden" id="hdnpayrollid" name="hdnpayrollid" />
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>

				<!-- <button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadGaji()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button> -->

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadGaji_pdf()">
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

				<!-- <button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadGaji()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button> -->

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


<div id="modal-report-lembur" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-report-lembur" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportLembur" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Lembur
					<input type="hidden" id="hdnpayrollid_lembur" name="hdnpayrollid_lembur" />
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>

				<!-- <button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadGaji()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button> -->

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadLembur_pdf()">
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


<div id="modal-reportosabsengaji-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-reportosabsengaji-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportDataOSAbsenGaji" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Absensi OS
					
					<input type="hidden" id="hdnpayrollid_absen" name="hdnpayrollid_absen" />
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				
				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReportOS_absengaji_pdf()">
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



<div id="modal-rekapgajios-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-rekapgajios-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmRekapGajiOS" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Rekap Gaji OS
					
					<input type="hidden" id="hdnpayrollid_rekapgajios" name="hdnpayrollid_rekapgajios" />
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				
				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadRekapGajiOS_pdf()">
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
					
					$('[name="projectviewgaji"]').val(data.project_name);
					$('[name="penggajian_year"]').val(data.tahun_penggajian);
					$('select#penggajian_month').val(data.bulan_penggajian).trigger('change.select2');
					var tgl_start = dateFormat(data.tgl_start_absen);
					var tgl_end = dateFormat(data.tgl_end_absen);
					
					$('[name="period_start"]').datepicker('setDate', tgl_start);
					$('[name="period_end"]').datepicker('setDate', tgl_end);

				
					document.getElementById("inp_is_all_project_gaji").style.display = "none";
					document.getElementById("inputEmployee_gaji").style.display = "none";
					document.getElementById("inputProject_gaji").style.display = "none";
					document.getElementById("inpAbsenOS_gaji").style.display = "block";
					document.getElementById("projectViewGaji").style.display = "block";
					

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


					// Create a new button
					/*var submitBtn = document.getElementById('submit-data');
					if (!document.getElementById('idbtnRekapitulasi')) {
						var rekapitulasiButton = document.createElement('button');
						rekapitulasiButton.innerText = 'Rekapitulasi';
						rekapitulasiButton.className = 'btn btn-success btnRekapitulasi';
						rekapitulasiButton.id = 'idbtnRekapitulasi';
						
    					submitBtn.insertAdjacentElement('beforebegin', rekapitulasiButton);


						rekapitulasiButton.addEventListener('click', function() {
							$('#modal-rekapitulasi-data').modal('show');
							$('[name="id"]').val(data.id);
						});
					}*/
				
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.penggajian_year').html(data.tahun_penggajian);
					$('span.penggajian_month').html(data.month_name);
					$('span.period_start').html(data.tgl_start_absen);
					$('span.period_end').html(data.tgl_end_absen);
					$('span.project').html(data.project_name);

					document.getElementById("inpGajiOS_view").style.display = "block";
					/*document.getElementById("inpAbsenOS_edit_gaji").style.display = "none";*/

					var locate = 'table.gajios-view-list';
					$.ajax({type: 'post',url: module_path+'/gengajiosrow',data: { id:data.id, project: data.project_id, bln: data.bulan_penggajian, thn: data.tahun_penggajian, view:true },success: function (response) { 
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


document.querySelectorAll('input[name="is_all_project"]').forEach(function(radio) {
  radio.addEventListener('click', function() {
  	
	  	if(this.value == 'Karyawan'){
	  		document.getElementById("inputEmployee_gaji").style.display = "block";
	  		document.getElementById("inputProject_gaji").style.display = "none";
	  	}else if(this.value == 'Sebagian'){
	  		document.getElementById("inputProject_gaji").style.display = "block";
	  		document.getElementById("inputEmployee_gaji").style.display = "none";
	  	}else{
	  		document.getElementById("inputEmployee_gaji").style.display = "none";
	  		document.getElementById("inputProject_gaji").style.display = "none";
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



$('#project_edit_gaji').on('change', function () { 
 	var project = $("#project_edit_gaji option:selected").val();
 	var bln 	= $("#penggajian_month_edit_gaji option:selected").val();
	var thn 	= $("#penggajian_year_edit_gaji").val();
 	
 	if(project != '' && bln != '' && thn != '' && thn.length === 4){

 		$.ajax({
			type: "POST",
	        url : module_path+'/getGaji',
			data: { project: project, bln: bln, thn: thn },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != ''){ 	
					
					document.getElementById("inpAbsenOS_edit_gaji").style.display = "block";

					$('[name="period_start_edit_gaji"]').val(data[0].tgl_start_absensi);
					$('[name="period_end_edit_gaji"]').val(data[0].tgl_end_absensi);

					var locate = 'table.absenos_edit_gaji-list';
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
					document.getElementById("inpAbsenOS_edit_gaji").style.display = "none";
					alert("Data Gaji di Project, Bulan & Tahun tersebut tidak ditemukan. Mohon untuk Hitung Gaji terlebih dahulu");

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


$('#penggajian_month_edit_gaji').on('change', function () { 
 	var project = $("#project_edit_gaji option:selected").val();
 	var bln 	= $("#penggajian_month_edit_gaji option:selected").val();
	var thn 	= $("#penggajian_year_edit_gaji").val();
 	
 	if(project != '' && bln != '' && thn != '' && thn.length === 4){

 		$.ajax({
			type: "POST",
	        url : module_path+'/getGaji',
			data: { project: project, bln: bln, thn: thn },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  

				if(data != ''){ 	
					
					document.getElementById("inpAbsenOS_edit_gaji").style.display = "block";

					$('[name="period_start_edit_gaji"]').val(data[0].tgl_start_absensi);
					$('[name="period_end_edit_gaji"]').val(data[0].tgl_end_absensi);

					var locate = 'table.absenos_edit_gaji-list';
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
					document.getElementById("inpAbsenOS_edit_gaji").style.display = "none";
					alert("Data Gaji di data Project, Bulan & Tahun tersebut tidak ditemukan. Mohon untuk Hitung Gaji terlebih dahulu");

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

$('#penggajian_year_edit_gaji').on('keyup', function () { 
 	var project = $("#project_edit_gaji option:selected").val();
 	var bln 	= $("#penggajian_month_edit_gaji option:selected").val();
	var thn 	= $("#penggajian_year_edit_gaji").val();
 	
 	if(project != '' && bln != '' && thn != '' && thn.length === 4){

 		$.ajax({
			type: "POST",
	        url : module_path+'/getGaji',
			data: { project: project, bln: bln, thn: thn },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != ''){ 	
					
					document.getElementById("inpAbsenOS_edit_gaji").style.display = "block";

					$('[name="period_start_edit_gaji"]').val(data[0].tgl_start_absensi);
					$('[name="period_end_edit_gaji"]').val(data[0].tgl_end_absensi);

					var locate = 'table.absenos_edit_gaji-list';
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
					document.getElementById("inpAbsenOS_edit_gaji").style.display = "none";
					alert("Data Gaji di data Project, Bulan & Tahun tersebut tidak ditemukan. Mohon untuk Hitung Gaji terlebih dahulu");

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


function edit_gaji_per_project() {
    var formData = $('#frmInputDataEditGajiProject').serialize();

    $.ajax({
        url : module_path+'/save_edit_gaji_per_project',
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


$('#modal-form-editgajiperproject').on('shown.bs.modal', function () {
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
	//var perioddate = $("#perioddate").val();

	if(flproject == ''){
		flproject=0;
	}

	/*fldatestart=0;
	fldateend=0;
	if(perioddate != ''){
		var myArray = perioddate.split(" - ");
		var start = myArray[0];
		var end = myArray[1];

		fldatestart=toYYYYMMDD(start);
		fldateend=toYYYYMMDD(end);
	}*/
	

	
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



function getReportGaji(payroll_id){
	
	$('#modal-report-gaji').modal('show');

	$('[name="hdnpayrollid"]').val(payroll_id);

}

function getReportGaji_perEmployee(id,employee_id){

	$('#modal-report-gaji-peremployee').modal('show');

	$('[name="hdnpayrollid_emp"]').val(id);
	$('[name="hdnempid"]').val(employee_id);
}

function downloadGaji_pdf(){

	var payroll_id = $("#hdnpayrollid").val();
	

	if(payroll_id != ''){
		send_url = module_path+'/getPayrollReport_pdf?payroll_id='+payroll_id+'';
		formData = $('#frmReportGaji').serialize();
		window.location = send_url+'&'+formData;
		
		$('#modal-report-gaji').modal('hide');
	}else{
		alert("Data tidak ditemukan");
	}

	/*
	var perioddate = $("#perioddate").val();

	fldatestart=0;
	fldateend=0;
	if(perioddate != ''){
		var myArray = perioddate.split(" - ");
		var start = myArray[0];
		var end = myArray[1];

		fldatestart=toYYYYMMDD(start);
		fldateend=toYYYYMMDD(end);
	}
	
	
	send_url = module_path+'/getAbsenceReport?flemployee='+flemployee+'&fldatestart='+fldatestart+'&fldateend='+fldateend+'';
	formData = $('#frmReportData').serialize();
	window.location = send_url+'&'+formData;
	$('#modal-invoice').modal('hide');*/


	
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


function getReportLembur(payroll_id){
	
	$('#modal-report-lembur').modal('show');

	$('[name="hdnpayrollid_lembur"]').val(payroll_id);

}

function downloadLembur_pdf(){

	var payroll_id = $("#hdnpayrollid_lembur").val();
	

	if(payroll_id != ''){

		send_url = module_path+'/getOvertimeReport_pdf?payroll_id='+payroll_id+'';
		formData = $('#frmReportLembur').serialize();
		window.location = send_url+'&'+formData;
		$('#report-lembur').modal('hide');

	}else{
		alert("Data tidak ditemukan");
	}

	/*
	var perioddate = $("#perioddate").val();
	fldatestart=0;
	fldateend=0;
	if(perioddate != ''){
		var myArray = perioddate.split(" - ");
		var start = myArray[0];
		var end = myArray[1];

		fldatestart=toYYYYMMDD(start);
		fldateend=toYYYYMMDD(end);
	}
	
	
	send_url = module_path+'/getAbsenceReport?flemployee='+flemployee+'&fldatestart='+fldatestart+'&fldateend='+fldateend+'';
	formData = $('#frmReportData').serialize();
	window.location = send_url+'&'+formData;
	$('#modal-invoice').modal('hide');*/


	
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


function roundUp2Smart(num) {
    const rounded = Math.ceil(num * 100) / 100;
    return Number.isInteger(rounded) ? rounded : rounded.toFixed(2);
}


function setTotalPendapatan(val){ 
	var row = val.dataset.id;  
	///var tunjangan = val.value;
	var tunj_jabatan = $('[name="tunj_jabatan_gaji['+row+']"]').val();
	var tunj_transport = $('[name="tunj_transport_gaji['+row+']"]').val();
	var tunj_konsumsi = $('[name="tunj_konsumsi_gaji['+row+']"]').val();
	var tunj_komunikasi = $('[name="tunj_komunikasi_gaji['+row+']"]').val();
	var gaji = $('[name="gaji_gaji['+row+']"]').val();
	
	if(gaji == ''){
		gaji=0;
	}
	if(tunj_jabatan == ''){
		tunj_jabatan=0;
	}
	if(tunj_transport == ''){
		tunj_transport=0;
	}
	if(tunj_konsumsi == ''){
		tunj_konsumsi=0;
	}
	if(tunj_komunikasi == ''){
		tunj_komunikasi=0;
	}


	var total_pendapatan = Number(gaji)
    + Number(tunj_jabatan)
    + Number(tunj_transport)
    + Number(tunj_konsumsi)
    + Number(tunj_komunikasi);

	total_pendapatan = roundUp2Smart(total_pendapatan);

	$('[name="ttl_pendapatan_gaji['+row+']"]').val(total_pendapatan);


	setSubTotal(val);

    
}



function setSubTotal(val){ 
	var row = val.dataset.id;  
	///var tunjangan = val.value;
	var ttl_pendapatan 	= $('[name="ttl_pendapatan_gaji['+row+']"]').val();
	var seragam 		= $('[name="seragam_gaji['+row+']"]').val();
	var pelatihan 		= $('[name="pelatihan_gaji['+row+']"]').val();
	var lainlain 		= $('[name="lainlain_gaji['+row+']"]').val();
	var hutang 			= $('[name="hutang_gaji['+row+']"]').val();
	var sosial 			= $('[name="sosial_gaji['+row+']"]').val();

	
	
	if(seragam == ''){
		seragam=0;
	}
	if(pelatihan == ''){
		pelatihan=0;
	}
	if(lainlain == ''){
		lainlain=0;
	}
	if(hutang == ''){
		hutang=0;
	}
	if(sosial == ''){
		sosial=0;
	}


	var subTotal = Number(ttl_pendapatan)-(Number(seragam)+Number(pelatihan)+Number(lainlain)+Number(hutang)+Number(sosial));

	subTotal = roundUp2Smart(subTotal);

/*console.log('ttl_pendapatan :'+ttl_pendapatan);
console.log('absen :'+absen);
console.log('seragam :'+seragam);
console.log('pelatihan :'+pelatihan);
console.log('lainlain :'+lainlain);
console.log('hutang :'+hutang);
console.log('sosial :'+sosial);*/

	$('[name="subtotal_gaji['+row+']"]').val(subTotal);


    setGajiBersih(val);

}



function setGajiBersih(val){ 
	var row = val.dataset.id;  
	///var tunjangan = val.value;
	var subtotal 	= $('[name="subtotal_gaji['+row+']"]').val();
	var bpjs_kes	= $('[name="bpjs_kes_gaji['+row+']"]').val();
	var bpjs_tk 	= $('[name="bpjs_tk_gaji['+row+']"]').val();
	var payroll 	= $('[name="payroll_gaji['+row+']"]').val();
	var pph120 		= $('[name="pph120_gaji['+row+']"]').val();
	
	
	if(bpjs_kes == ''){
		bpjs_kes=0;
	}
	if(bpjs_tk == ''){
		bpjs_tk=0;
	}
	if(payroll == ''){
		payroll=0;
	}
	if(pph120 == ''){
		pph120=0;
	}
	


	var GajiBersih = Number(subtotal)-(Number(bpjs_kes)+Number(bpjs_tk)+Number(payroll)+Number(pph120));

	GajiBersih = roundUp2Smart(GajiBersih);

	$('[name="gaji_bersih_gaji['+row+']"]').val(GajiBersih);

    
}

function getReportAbsenOS_gaji(payroll_id){
	
	$('#modal-reportosabsengaji-data').modal('show');

	$('[name="hdnpayrollid_absen"]').val(payroll_id);
}

function downloadReportOS_absengaji_pdf(){

	
	var payroll_id = $("#hdnpayrollid_absen").val();

	if(payroll_id != ''){

  		// send_url = 
		send_url = module_path+'/getAbsenceReportGaji_pdf?payroll_id='+payroll_id+'';
		formData = $('#frmReportDataOSAbsenGaji').serialize();
		window.location = send_url+'&'+formData;
		$('#modal-reportosabsengaji-data').modal('hide');


	}else{
		alert("Data tidak ditemukan");
	}

	
}


function getRekapGajiOS(payroll_id){
	
	$('#modal-rekapgajios-data').modal('show');

	$('[name="hdnpayrollid_rekapgajios"]').val(payroll_id);
}

function downloadRekapGajiOS_pdf(){

	
	var payroll_id = $("#hdnpayrollid_rekapgajios").val();

	if(payroll_id != ''){

  		// send_url = 
		send_url = module_path+'/getRekapGajiOS_pdf?payroll_id='+payroll_id+'';
		formData = $('#frmRekapGajiOS').serialize();
		window.location = send_url+'&'+formData;
		$('#modal-rekapgajios-data').modal('hide');


	}else{
		alert("Data tidak ditemukan");
	}

	
}


</script>