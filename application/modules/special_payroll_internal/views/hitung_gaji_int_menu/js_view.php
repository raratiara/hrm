
<div id="modal-report-gaji-int" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-report-gaji-int" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportGajiInt" enctype="multipart/form-data">
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

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadGajiInt_pdf()">
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



<div id="modal-report-gaji-peremployee-int" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-report-gaji-peremployee-int" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportGajiperEmpInt" enctype="multipart/form-data">
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

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadGaji_perEmployee_pdf_int()">
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


<div id="modal-report-lembur-int" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-report-lembur-int" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportLemburInt" enctype="multipart/form-data">
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

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadLembur_pdf_int()">
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


<div id="modal-reportabsengaji-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-reportabsengaji-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportDataAbsenGaji" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Absensi
					
					<input type="hidden" id="hdnpayrollid_absen" name="hdnpayrollid_absen" />
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				
				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReport_absengaji_pdf()">
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



<div id="modal-rekapgaji-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-rekapgaji-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmRekapGaji" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Rekap Gaji
					
					<input type="hidden" id="hdnpayrollid_rekapgaji" name="hdnpayrollid_rekapgaji" />
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				
				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadRekapGajiInt_pdf()">
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
					
					
					$('[name="penggajian_year"]').val(data.tahun_penggajian);
					$('select#penggajian_month').val(data.bulan_penggajian).trigger('change.select2');
					var tgl_start = dateFormat(data.tgl_start_absen);
					var tgl_end = dateFormat(data.tgl_end_absen);
					
					$('[name="period_start"]').datepicker('setDate', tgl_start);
					$('[name="period_end"]').datepicker('setDate', tgl_end);

				
					document.getElementById("inp_is_all_employee_gaji").style.display = "none";
					document.getElementById("inputEmployee_gaji").style.display = "none";
					document.getElementById("inpAbsenInt_gaji").style.display = "block";
					

					var locate = 'table.absen_gaji-list';
					$.ajax({type: 'post',url: module_path+'/gengajirow',data: { id:data.id, bln: data.bulan_penggajian, thn: data.tahun_penggajian },success: function (response) { 
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
					$('span.penggajian_year').html(data.tahun_penggajian);
					$('span.penggajian_month').html(data.month_name);
					$('span.period_start').html(data.tgl_start_absen);
					$('span.period_end').html(data.tgl_end_absen);

					document.getElementById("inpGajiInt_view").style.display = "block";
					/*document.getElementById("inpAbsenOS_edit_gaji").style.display = "none";*/

					var locate = 'table.gaji-view-list';
					$.ajax({type: 'post',url: module_path+'/gengajirow',data: { id:data.id, bln: data.bulan_penggajian, thn: data.tahun_penggajian, view:true },success: function (response) { 
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


document.querySelectorAll('input[name="is_all_employee"]').forEach(function(radio) {
  radio.addEventListener('click', function() {
  	
	  	if(this.value == 'Karyawan'){
	  		document.getElementById("inputEmployee_gaji").style.display = "block";
	  	}else{
	  		document.getElementById("inputEmployee_gaji").style.display = "none";
	  	}
    
  });
});




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



$('#modal-form-data').on('hide.bs.modal', function () {
  initFilterEmployee();
});


function subFilter(){
	var flproject = $("#flproject option:selected").val();
	//var perioddate = $("#perioddate").val();

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



function getReportGaji(payroll_id){
	
	$('#modal-report-gaji-int').modal('show');

	$('[name="hdnpayrollid"]').val(payroll_id);

}

function getReportGaji_perEmployee(id,employee_id){

	$('#modal-report-gaji-peremployee-int').modal('show');

	$('[name="hdnpayrollid_emp"]').val(id);
	$('[name="hdnempid"]').val(employee_id);
}

function downloadGajiInt_pdf(){

	var payroll_id = $("#hdnpayrollid").val();
	

	if(payroll_id != ''){
		send_url = module_path+'/getPayrollReport_pdf?payroll_id='+payroll_id+'';
		formData = $('#frmReportGajiInt').serialize();
		window.location = send_url+'&'+formData;
		
		$('#modal-report-gaji-int').modal('hide');
	}else{
		alert("Data tidak ditemukan");
	}

	


	
}


function downloadGaji_perEmployee_pdf_int(){

	var id = $("#hdnpayrollid_emp").val();
	var emp_id = $("#hdnempid").val();
	
	

	if(emp_id != '' && id != ''){
		send_url = module_path+'/getPayrollReport_perEmployee_pdf?id='+id+'&emp_id='+emp_id+'';
		formData = $('#frmReportGajiperEmp').serialize();
		window.location = send_url+'&'+formData;
		$('#modal-report-gaji-peremployee-int').modal('hide');
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

function getReportAbsen_gaji(payroll_id){
	
	$('#modal-reportabsengaji-data').modal('show');

	$('[name="hdnpayrollid_absen"]').val(payroll_id);
}

function downloadReport_absengaji_pdf(){

	
	var payroll_id = $("#hdnpayrollid_absen").val();

	if(payroll_id != ''){

  		// send_url = 
		send_url = module_path+'/getAbsenceReportGaji_pdf?payroll_id='+payroll_id+'';
		formData = $('#frmReportDataAbsenGaji').serialize();
		window.location = send_url+'&'+formData;
		$('#modal-reportabsengaji-data').modal('hide');


	}else{
		alert("Data tidak ditemukan");
	}

	
}


function getRekapGaji(payroll_id){
	
	$('#modal-rekapgaji-data').modal('show');

	$('[name="hdnpayrollid_rekapgaji"]').val(payroll_id);
}

function downloadRekapGajiInt_pdf(){

	
	var payroll_id = $("#hdnpayrollid_rekapgaji").val();

	if(payroll_id != ''){

  		// send_url = 
		send_url = module_path+'/getRekapGaji_pdf?payroll_id='+payroll_id+'';
		formData = $('#frmRekapGaji').serialize();
		window.location = send_url+'&'+formData;
		$('#modal-rekapgaji-data').modal('hide');


	}else{
		alert("Data tidak ditemukan");
	}

	
}


</script>