
/* reload table list */
/* reload table list / card list */
function reload_table()
{
    expire();

    // ====== Kalau masih pakai DataTables ======
    if (typeof myTable !== 'undefined' && myTable && myTable.ajax) {
        myTable.ajax.reload(null, false);
        return;
    }

    // ====== Kalau pakai Card View (LMS modern UI) ======
    if (typeof window.loadCards === 'function') {
        window.loadCards();
        return;
    }

    // ====== Fallback terakhir ======
    location.reload();
}

/* checking session */
function expire()
{
    $.post( "<?=_URL;?>login/hassession", { id: "check" }, function( data ) {
		if(data=='false') location.reload();
		//if(!data) location.reload();
	});
}

// Fallback global agar pemanggilan showLoading/hideLoading di common module
// tidak error pada halaman yang belum mendefinisikan fungsi tersebut.
if (typeof window.showLoading !== 'function') {
	window.showLoading = function() {
		if ($("#loadingOverlay").length) {
			$("#loadingOverlay").show();
		}
	};
}

if (typeof window.hideLoading !== 'function') {
	window.hideLoading = function() {
		if ($("#loadingOverlay").length) {
			$("#loadingOverlay").hide();
		}
	};
}

// Global modal scroll lock: keep main page from scrolling while any modal is open.
var __tplModalScrollLock = {
	count: 0,
	prevBodyOverflow: '',
	prevHtmlOverflow: ''
};

$(document)
	.on('show.bs.modal', '.modal', function () {
		if (__tplModalScrollLock.count === 0) {
			__tplModalScrollLock.prevBodyOverflow = $('body').css('overflow') || '';
			__tplModalScrollLock.prevHtmlOverflow = $('html').css('overflow') || '';
			$('body, html').css('overflow', 'hidden');
		}
		__tplModalScrollLock.count++;
	})
	.on('hidden.bs.modal', '.modal', function () {
		__tplModalScrollLock.count = Math.max(0, __tplModalScrollLock.count - 1);
		if (__tplModalScrollLock.count === 0) {
			$('body').css('overflow', __tplModalScrollLock.prevBodyOverflow);
			$('html').css('overflow', __tplModalScrollLock.prevHtmlOverflow);
		}
	});


<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
/* open add form modal */
$( "#btnAddData" ).on('click', function(){
	var module_name = '<?=$this->module_name?>'; 

	

	if(module_name == 'hitung_summary_absen_os_menu'){
		<!-- document.getElementById("inp_is_all_employee").style.display = "block"; -->
		document.getElementById("inp_is_all_project").style.display = "block";
		document.getElementById("inpEmp").style.display = "none";
		document.getElementById("inputEmployee").style.display = "none";
		document.getElementById("inputProject").style.display = "none";

		document.getElementById("inpAbsenOS").style.display = "none";
		document.getElementById("inpAbsenOS_edit").style.display = "none";
		document.getElementById("projectView").style.display = "none";

	}

	if(module_name == 'request_recruitment_menu'){
		document.getElementById("btnDraft").style.display = "";
		document.getElementById("submit-data").style.display = "";

		var modalFooter =  document.getElementById('mdlFooter');
		var existingReject = modalFooter.querySelector('.btnReject');
	 	if (existingReject) {
	 		document.getElementById("btn-reject").style.display = "none";
	 	}


	 	unlockSubmitDraft();

	}


	$("#employee ").prop('disabled', false);
	expire();
	save_method = 'add'; 
	reset();
	$('#mfdata').text('Add');
	if(module_name == 'absensi_menu' || module_name == 'absensi_os_menu'){ 
		getLocation();
		
		var hdnempid = $("#hdnempid").val();
		$("#location ").prop('disabled', false);
		$('#mfdata').text('Form Check-IN');
		document.getElementById("submit-data").innerText = "Check In";


		var locate = 'table.task-list';
		var wcount = 0;
		$.ajax({type: 'post',url: module_path+'/gettasklistrow',data: { id:hdnempid, checkin:true },success: function (response) { 
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

	if(module_name == 'ijin_menu'){ 
		document.getElementById('btnApprovalLog').style.display = 'none';
	}

	if(module_name == 'data_karyawan_os_menu' || module_name == 'data_karyawan_menu'){ 
		document.getElementById("inpUsername").style.display = "block";
		document.getElementById("inpPassword").style.display = "block";
		$('select#company').val(3).trigger('change.select2');
		$('[name="password"]').val('112233').prop('disabled', false);

	}

	if(module_name == 'hitung_gaji_os_menu'){ 
		document.getElementById("projectViewGaji").style.display = "none";
		document.getElementById("inpAbsenOS_gaji").style.display = "none";
		

		var now = new Date();

		var monthNow = now.getMonth() + 1; // 1–12
		var yearNow  = now.getFullYear();  // contoh: 2026

		$('select#penggajian_month').val(monthNow).trigger('change.select2');
		$('[name="penggajian_year"]').val(yearNow);

		///isi otomatis juga periode absennya
		$.ajax({
			type: "POST",
	        url : module_path+'/getSummaryAbsen',
			data: { bln: monthNow, thn: yearNow },
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

	}


	if(module_name == 'pembayaran_gaji_os_menu'){
		document.getElementById("inpStatus").style.display = "none";
	}
	

	unlockSubmit();



	$('#modal-form-data').modal('show');
});
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_UPDATE == "1") { ?>
/* open edit form modal */
function edit(id)
{ 
	var module_name = '<?=$this->module_name?>'; 

	unlockSubmit();

	if(module_name == 'request_recruitment_menu'){
		unlockSubmitDraft();
	}

	expire();
    save_method = 'update';
	idx = id;
	reset();
}

$( "#btnEditPerProject" ).on('click', function(){
	var module_name = '<?=$this->module_name?>'; 

	if(module_name == 'hitung_summary_absen_os_menu'){ 

		<!-- tutup semua dropdown aktif dulu -->
		$('.select2me').select2('close');

		<!-- clear semua field dulu -->
    	resetEditProjectForm();

		$('#mfdata').text('Edit Perhitungan');
		$('#modal-form-editperproject').modal('show');

	}


});


$( "#btnEditGajiPerProject" ).on('click', function(){
	var module_name = '<?=$this->module_name?>'; 

	if(module_name == 'hitung_gaji_os_menu'){ 

		<!-- tutup semua dropdown aktif dulu -->
		$('.select2me').select2('close');

		<!-- clear semua field dulu -->
    	resetEditGajiProjectForm();


    	var now = new Date();

		var monthNow = now.getMonth() + 1; // 1–12
		var yearNow  = now.getFullYear();  // contoh: 2026

		$('select#penggajian_month_edit_gaji').val(monthNow).trigger('change.select2');
		$('[name="penggajian_year_edit_gaji"]').val(yearNow);
		
		$('#mfdata').text('Edit Gaji');
		$('#modal-form-editgajiperproject').modal('show');

	}


});

<?php 

} ?>


<?php if  (_USER_ACCESS_LEVEL_DETAIL == "1") { ?>
/* open detail modal */
function detail(id)
{
	expire();
    save_method = 'detail';
	idx = id;
	load_data();
}
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
$('#frmInputData select').on('change', function (e) {
    $("#frmInputData").valid();
});

/* reset form data */
function reset(){
	validator.resetForm();
	$('#frmInputData')[0].reset();
	$(".select2me").select2({dropdownParent:$('#modal-form-data'),placeholder:"Select",width:"auto",allowClear:!0});
	$('#frmInputData select').val('').trigger('change.select2');
	$.uniform.update();
	if(save_method == 'update') {
		load_data();
	}
}
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
/* do delete command */
function deleting(id)
{
	expire();
    save_method = 'delete';
	$('span#ids').html(id);
	$('#frmDeleteData [name="id"]').val(id);
	$('#modal-delete-data').modal('show');
	//save();
}	

/* do bulk delete command */
$( "#btnBulkData" ).on('click', function(){
	expire();
    save_method = 'bulk';
	ldx = [];
    $(".data-check:checked").each(function() {
            ldx.push(this.value);
    });
	
    if(ldx.length > 0)
    {
		var ids = ldx.join(", ")
		$('span#ids').html(ids);
		$('#modal-delete-bulk-data').modal('show');
	} else {
		title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
		var dialog = bootbox.dialog({
			message: title+'<center>No data selected</center>'
		});
		setTimeout(function(){
			dialog.modal('hide');
		}, 1500);
	}
});
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_EKSPORT == "1") { ?>
/* open export modal */
$( "#btnEksportData" ).on('click', function(){
	expire();
	save_method = 'export';
	$('#modal-eksport-data').modal('show');
});
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_IMPORT == "1") { ?>
/* open import modal */
$( "#btnImportData" ).on('click', function(){
	expire();
	save_method = 'import';
	$('#frmImportData')[0].reset();
	$(".progress-bar").width('0%');
	$(".progress-bar").html('0%');
	$('#modal-import-data').modal('show');
});
<?php } ?>

/* processing action */
function save(status='')
{
	if(status != ''){
		$('[name="status"]').val(status);
	}
	

	expire();
    var title;
    var send_url;
    var form_check;
    var post_type = 'POST';
    var smsg;
	var formData;

<?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
	if(save_method == 'add' || save_method == 'update') {
		form_check = $("#frmInputData").valid();
		if(!form_check) return false;
	}
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
	if(save_method == 'add') {
		send_url = module_path+'/add';
		formData = new FormData($('#frmInputData')[0]);
	}
<?php } ?>
	
<?php if  (_USER_ACCESS_LEVEL_UPDATE == "1") { ?>
	if(save_method == 'update'){
		send_url = module_path+'/edit';
		formData = new FormData($('#frmInputData')[0]);
	} 
<?php } ?>
	
<?php if  (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
	if(save_method == 'delete'){
		send_url = module_path+'/delete';
		formData = new FormData($('#frmDeleteData')[0]);
	}

	if(save_method == 'bulk'){
		send_url = module_path+'/bulk';
		formData = new FormData($('#frmListData')[0]);
	}


	if(save_method == 'add' || save_method == 'update'){
		if(status == 'draft'){
			lockSubmitDraft();
		}else{
			lockSubmit();
		}
	    
	}


<?php } ?>
	
<?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1" || _USER_ACCESS_LEVEL_DELETE == "1") { ?>
	if((save_method == 'add') || (save_method == 'update') || (save_method == 'delete') || (save_method == 'bulk')) {
		var module_name = '<?=$this->module_name?>'; 


		$.ajax({
			type: post_type,
			url: send_url,
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			dataType: "JSON",
		 	beforeSend: function() {
		        showLoading();   //muncul loading disini
		    },
			success: function( response ) {
				if(response.status){
					title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-check-circle-o fa-5x" style="color:green"></i></div>';
					btn = '';
					if(save_method == 'add' || save_method == 'update') {
						unlockSubmit();

						if(module_name == 'request_recruitment_menu'){
							unlockSubmitDraft();
						}

						$('#frmInputData')[0].reset();
						$('#modal-form-data').modal('hide');
					} else if(save_method == 'delete'){
						$('#modal-delete-data').modal('hide');
					} else if(save_method == 'bulk'){
						$('#modal-delete-bulk-data').modal('hide');
					}
					reload_table();
				} else { 
					unlockSubmit();

					if(module_name == 'request_recruitment_menu'){ 
						unlockSubmitDraft();
					}

					title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
					btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
				}
				var dialog = bootbox.dialog({
					message: title+'<center>'+response.msg+btn+'</center>'
				});
				if(response.status){
					setTimeout(function(){
						dialog.modal('hide');
					}, 1500);
				}

			},
			error: function (jqXHR, textStatus, errorThrown) {
				unlockSubmit();

				if(module_name == 'request_recruitment_menu'){ 
					unlockSubmitDraft();
				}

			
				var dialog = bootbox.dialog({
					title: 'Gagal menyimpan Data', ///'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
					message: 'Terjadi kesalahan saat menyimpan data',///jqXHR.responseText,
					buttons: {
						confirm: {
							label: 'Ok',
							className: 'btn blue'
						}
					}
				});
			}
			,complete: function() {
		        hideLoading();   // hilangkan loading setelah selesai
		    }
		});
	}
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_EKSPORT == "1") { ?>
	if(save_method == 'export'){
		send_url = module_path+'/eksport';
		formData = $('#frmEksportData').serialize();
		window.location = send_url+'?'+formData;
		$('#modal-eksport-data').modal('hide');
	}
<?php } ?>

<?php if  (_USER_ACCESS_LEVEL_IMPORT == "1") { ?>
	if(save_method == 'import'){
		send_url = module_path+'/import';
		formData = new FormData($('#frmImportData')[0]);
		$.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".progress-bar").width(percentComplete + '%');
                        $(".progress-bar").html(percentComplete+'%');
                    }
                }, false);
                return xhr;
            },
			type: post_type,
			url: send_url,
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			dataType: "JSON",
            beforeSend: function(){
                $(".progress-bar").width('0%');
            },
			success: function( response ) {
				if(response.status){
					title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-check-circle-o fa-5x" style="color:green"></i></div>';
					btn = '';
					$('#frmImportData')[0].reset();
					$('#modal-import-data').modal('hide');
					reload_table();
				} else {
					$('#frmImportData')[0].reset();
					$(".progress-bar").width('0%');
					$(".progress-bar").html('0%');
					title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
					btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
				}
				var dialog = bootbox.dialog({
					message: title+'<center>'+response.msg+btn+'</center>'
				});
				if(response.status){
					setTimeout(function(){
						dialog.modal('hide');
					}, 1500);
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
<?php } ?>	
}

function formatNum(num,fx=2,dec=',',thou='.') {
    var p = parseFloat(num).toFixed(fx).split(".");
    return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num=="-" ? acc : num + (i && !(i % 3) ? thou : "") + acc;
    }, "") + dec + p[1];
}

function goFloat(num){
	if(typeof num == 'string'){
		if(checkStrNumFormat(num,2,',')){
			num = parseFloat(num.replace(/[^\d,-]/g,'').replace(',','.'));
		} else {
			num = parseFloat(num);
		}
	}
	
	return num
}

function checkStrNumFormat(str,pos,chk) {
  var res = str.charAt(str.length-(pos+1));
  if(res == chk){
	  return true;
  } else {
	  return false;
  }
}

function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

function tSawBclear(elem){
	Tablesaw.init(elem);
	var ts = $(elem);
	$(document).off("." + ts.attr("id"));
	$(window).off("." + ts.attr("id"));
	ts.removeData('tablesaw');
}



function lockSubmit() {
	var btn = $('#submit-data');
    
    var loadingText = btn.data('loading') || 'Processing...';

    btn.prop('disabled', true)
       .html('<i class="fa fa-spinner fa-spin"></i> ' + loadingText);
}

function lockSubmitDraft() {
	var btn = $('#btnDraft');
    
    var loadingText = btn.data('loading') || 'Drafting...';

    btn.prop('disabled', true)
       .html('<i class="fa fa-spinner fa-spin"></i> ' + loadingText);
}

function unlockSubmit() {
	
	var btn = $('#submit-data');
    var text = btn.data('text') || 'Save';

    btn.prop('disabled', false)
   .html('<i class="fa fa-check"></i> ' + text);
	
    
}


function unlockSubmitDraft() {
    var btn = $('#btnDraft');
    var text = btn.data('text') || 'Save as Draft';

    btn.prop('disabled', false)
       .html('<i class="fa fa-floppy-o"></i> ' + text);
}

