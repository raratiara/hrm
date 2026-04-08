<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string 

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
			code: {
				required: true
			},
			name: {
				required: true
			},
			contact_name: {
				required: true
			},
			contact_phone: {
				required: true
			},
			contact_email: {
				required: true
			},
			pic_name: {
				required: true
			},
			pic_phone: {
				required: true
			},
			pic_email: {
				required: true
			},
			id_status: {
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

	//initialize datepicker
	$('.date-picker').datepicker({
		rtl: App.isRTL(),
		autoclose: true,
		clearBtn: true,
		todayHighlight: true
	});
	$('.date-picker .form-control').change(function() {
		$("#frmInputData").validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
	})
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
					$('[name="code"]').val(data.code);
					$('[name="name"]').val(data.name);
					$('[name="address"]').val(data.address); 
					$('[name="contact_name"]').val(data.contact_name); 
					$('[name="contact_phone"]').val(data.contact_phone);
					$('[name="contact_email"]').val(data.contact_email);  
					$('[name="id_status"]').val(data.id_status).trigger('change.select2');
					$('[name="customer_npwp"]').val(data.npwp); 
					$('select#province').val(data.province_id).trigger('change.select2');
					//$('select#village').val(data.village_id).trigger('change.select2');
					$('[name="tgl_pembayaran_lembur"]').val(data.tanggal_pembayaran_lembur); 
					$('[name="postal_code"]').val(data.postal_code); 
					$('[name="sistem_lembur"][value="'+data.sistem_lembur+'"]').prop('checked', true);

					getRegency(data.province_id,'selected',data.regency_id);
					getDistrict(data.regency_id,data.province_id,'selected',data.district_id);
					getVillage(data.district_id,data.regency_id,data.province_id,'selected',data.village_id);

					if(data.sistem_lembur == 'tidak_sistem_lembur'){
						document.getElementById("inpNominalLembur").style.display = "block";
						$('[name="nominal_lembur"]').val(data.nominal_lembur);
					}else{
						document.getElementById("inpNominalLembur").style.display = "none";
					}



					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){
					$('span.code').html(data.code);
					$('span.name').html(data.name);
					$('span.address').html(data.address);
					$('span.contact_name').html(data.contact_name); 
					$('span.contact_phone').html(data.contact_phone);
					$('span.contact_email').html(data.contact_email);
					$('span.customer_npwp').html(data.npwp);
					$('span.status').html(data.status_name);
					$('span.regency').html(data.regency_name);
					$('span.village').html(data.village_name);
					$('span.province').html(data.province_name);
					$('span.district').html(data.district_name);
					$('span.sistem_lembur').html(data.sistem_lembur_desc);
					$('span.tgl_pembayaran_lembur').html(data.tanggal_pembayaran_lembur);

					if(data.sistem_lembur == 'tidak_sistem_lembur'){
						document.getElementById("inpNominalLemburView").style.display = "block";
						$('span.nominal_lembur').html(data.nominal_lembur);
					}else{
						document.getElementById("inpNominalLemburView").style.display = "none";
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



document.querySelectorAll('input[name="sistem_lembur"]').forEach(function(radio) {
  radio.addEventListener('click', function() {
  	
	  	if(this.value == 'tidak_sistem_lembur'){
	  		document.getElementById("inpNominalLembur").style.display = "block";
	  	}else{
	  		document.getElementById("inpNominalLembur").style.display = "none";
	  	}
    
  });
});


$('#province').on('change', function () { 
 	var province 	= $("#province option:selected").val();
 	
 	
 	getRegency(province);
});

$('#regency').on('change', function () { 
 	var regency 	= $("#regency option:selected").val();
 	var province 	= $("#province option:selected").val();
 	
 	
 	getDistrict(regency,province);
});


$('#district').on('change', function () { 
 	var district 	= $("#district option:selected").val();
 	var regency		= $("#regency option:selected").val();
 	var province 	= $("#province option:selected").val();
 	
 	
 	getVillage(district,regency,province);
});


function getDistrict(regency,province, selected='',idVal=''){

	if(regency != '' && province != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataDistrict',
			data: { province: province, regency:regency },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					var $el = $(".district");

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msdistrict, function(key,value) {
					  	$el.append($("<option></option>")
					     .attr("value", value.id).text(value.name));
					});
					
					if(selected=='selected'){
						$('select#district').val(idVal).trigger('change.select2');
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


function getRegency(province,selected='',idVal=''){ 

	if(province != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataRegency',
			data: { province: province },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					var $el = $(".regency");

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msregency, function(key,value) {
						$el.append($("<option></option>")
				     	.attr("value", value.id).text(value.name));
					  	
					});

					if(selected=='selected'){
						$('select#regency').val(idVal).trigger('change.select2');
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

function getVillage(district,regency,province, selected='',idVal=''){

	if(district != '' && regency != '' && province != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataVillage',
			data: { province: province, regency:regency, district:district },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){  
					var $el = $(".village");

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msvillage, function(key,value) {
					  	$el.append($("<option></option>")
					     .attr("value", value.id).text(value.name));
					});
					
					if(selected=='selected'){
						$('select#village').val(idVal).trigger('change.select2');
						
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
 
</script>