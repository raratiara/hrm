

<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var modloc = '/_hrm/cash_advance/fpu_menu/';
var opsForm = 'form#frmInputData';
var locate = 'table.fpu-list';
var wcount = 0; //for ca list row identify






$(document).ready(function() {
   	$(function() {
   		
        $( "#date" ).datepicker();
		
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
					
					$('select#requested_by').val(data.requested_by).trigger('change.select2');
					
					$('[name="total_biaya"]').val(data.total_biaya);
					$('[name="total_biaya_terbilang"]').val(data.total_biaya);
					


					$.ajax({type: 'post',url: module_path+'/genfpurow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
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
					$('span.employee').html(data.employee_name);
					$('span.date').html(data.date_reimbursment);
					$('span.reimburs_for').html(data.reimburse_for_name);
					$('span.nominal_reimburs').html(data.nominal_reimburse);
					$('span.atas_nama').html(data.atas_nama);
					$('span.diagnosa').html(data.diagnosa);
					$('span.nominal_billing').html(data.nominal_billing);
					$('span.type').html(data.reimburs_type_name);
					

					$.ajax({type: 'post',url: module_path+'/genfpurow',data: { id:data.id, view:true },success: function (response) { 
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



$("#addfpurow").on("click", function () { 
	
	expire();
	var newRow = $("<tr>");
	$.ajax({type: 'post',url: module_path+'/genfpurow',data: { count:wcount },success: function (response) {
			newRow.append(response);
			$(locate).append(newRow);
			wcount++;
			
		}
	}).done(function() {
		tSawBclear('table.fpu-list');
	});

});


function del(idx,hdnid){
	
	if(hdnid != ''){ //delete dr table
		$.ajax({type: 'post',url: module_path+'/delrowDetailFpu',data: { id:hdnid },success: function (response) {
				
			}
		}).done(function() {
			tSawBclear('table.fpu-list');
		});
	}

	//delete tampilan row
	var table = document.getElementById("tblDetailFpu");
	table.deleteRow(idx);
}


$(document).on("keyup", ".total_amount", function() {
    var sum = 0;
    $(".total_amount").each(function(){
        sum += +$(this).val();
    });
    $("#total_biaya").val(sum);
    
});



function set_total_amount(val){ 
	var row = val.dataset.id;  
	var amount = val.value;
	var pajak = $('[name="ppn_pph['+row+']"]').val();

	if(pajak == 0){
		var total_amount = amount;
	}else{
		var amount_pajak = pajak/100;
		var total_amount = Number((amount*amount_pajak))+Number(amount);
	}
	

	$('[name="total_amount['+row+']"]').val(total_amount);

	var sum=0; 
    $(".total_amount").each(function(index, element){ 
        sum += +$(this).val(); 
    });
    
    $('[name="total_biaya"]').val(sum);
    updateTerbilang();

    
}

function set_total_amount2(val){ 
	var row = val.dataset.id;  
	var pajak = val.value;
	var amount = $('[name="amount['+row+']"]').val();

	if(pajak == 0){
		var total_amount = amount;
	}else{ 
		var amount_pajak = pajak/100; 
		var total_amount = Number((amount*amount_pajak))+Number(amount);
	}
	

	$('[name="total_amount['+row+']"]').val(total_amount);

	var sum=0; 
    $(".total_amount").each(function(index, element){ 
        sum += +$(this).val(); 
    });
    
    $('[name="total_biaya"]').val(sum);
    updateTerbilang();

    
}



function terbilang(n) {
    const angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

    function toWords(n) {
        n = Math.floor(n);
        if (n < 12) return angka[n];
        if (n < 20) return toWords(n - 10) + " Belas";
        if (n < 100) return toWords(n / 10) + " Puluh " + toWords(n % 10);
        if (n < 200) return "Seratus " + toWords(n - 100);
        if (n < 1000) return toWords(n / 100) + " Ratus " + toWords(n % 100);
        if (n < 2000) return "Seribu " + toWords(n - 1000);
        if (n < 1000000) return toWords(n / 1000) + " Ribu " + toWords(n % 1000);
        if (n < 1000000000) return toWords(n / 1000000) + " Juta " + toWords(n % 1000000);
        if (n < 1000000000000) return toWords(n / 1000000000) + " Miliar " + toWords(n % 1000000000);
        return "Angka terlalu besar";
    }

    return toWords(n).replace(/\s+/g, ' ').trim() + " Rupiah";
}


function updateTerbilang() { 
    const nilai = document.getElementById("total_biaya").value;
   
    if (nilai) {
      total_biaya_terbilang = terbilang(nilai);
      $('[name="total_biaya_terbilang"]').val(total_biaya_terbilang);
    } else {
      $('[name="total_biaya_terbilang"]').val("");
    }
  }


</script>