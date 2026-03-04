
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



<div id="modal-report-summary_absen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-report-summary_absen" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:500px !important">
			<form class="form-horizontal" id="frmReportSummaryAbsen" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Report Summary Absensi
					<input type="hidden" id="hdnsummaryid" name="hdnsummaryid" />
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>

				<button class="btn" style="background-color: #8ec1f5; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReportSummaryAbsen()">
					<i class="fa fa-download"></i>
					Download EXCEL
				</button>

				<button class="btn" style="background-color: #f5f58e; color: black; border-radius: 2px !important;" id="submit-report-data" onclick="downloadReportSummaryAbsen_pdf()">
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


function getReport_summ_absen(summary_id){
	
	$('#modal-report-summary_absen').modal('show');

	$('[name="hdnsummaryid"]').val(summary_id);

}


function downloadReportSummaryAbsen(){ 
	var summary_id = $("#hdnsummaryid").val();

	if(summary_id != ''){
		send_url = module_path+'/getAbsenceReportSummaryAbsen?summary_id='+summary_id+'';
		formData = $('#frmReportSummaryAbsen').serialize();
		window.location = send_url+'&'+formData;
		
		
		//window.location = send_url;
		$('#modal-report-summary_absen').modal('hide');

	}else{
		alert("Data tidak ditemukan");
	}

	
	
}


function downloadReportSummaryAbsen_pdf(){ 

	
	var summary_id = $("#hdnsummaryid").val();

	if(summary_id != ''){
		send_url = module_path+'/getAbsenceReportSummaryAbsen_pdf?summary_id='+summary_id+'';
		formData = $('#frmReportSummaryAbsen').serialize();
		window.location = send_url+'&'+formData;
		
		
		//window.location = send_url;
		$('#modal-report-summary_absen').modal('hide');
		
	}else{
		alert("Data tidak ditemukan");
	}

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
					$('[name="penggajian_year"]').val(data.tahun_penggajian);
					$('select#penggajian_month').val(data.bulan_penggajian).trigger('change.select2');
					var tgl_start = dateFormat(data.tgl_start_absen);
					var tgl_end = dateFormat(data.tgl_end_absen);
					
					$('[name="period_start"]').datepicker('setDate', tgl_start);
					$('[name="period_end"]').datepicker('setDate', tgl_end);

					
					document.getElementById("inp_is_all_employee").style.display = "none";
					document.getElementById("inputEmployee").style.display = "none";
					document.getElementById("inpEmp").style.display = "none";
					document.getElementById("inpAbsen").style.display = "block";


					var locate = 'table.absen-list';
					$.ajax({type: 'post',url: module_path+'/genabsenrow',data: { id:data.id},success: function (response) { 
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

					
					var locate = 'table.absen-list-view';
					$.ajax({type: 'post',url: module_path+'/genabsenrow',data: { id:data.id, view:true },success: function (response) { 
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
	  		document.getElementById("inputEmployee").style.display = "block";
	  	}else{
	  		document.getElementById("inputEmployee").style.display = "none";
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

</script>