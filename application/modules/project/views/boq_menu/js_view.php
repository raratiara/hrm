<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string



$(document).ready(function() {
   	$(function() {
   		
        $( "#show_date_start" ).datepicker();
        $( "#show_date_end" ).datepicker();
		
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
				var periode = "";
				if(data.periode_start != '' && data.periode_end != '' && data.periode_start != null && data.periode_end != null){
					periode=  data.periode_start+' s/d '+data.periode_end;
				}

				if(save_method == 'update'){ 
					$('[name="id"]').val(data.id);
					
					$('[name="periode"]').val(periode);
					$('select#customer_boq').val(data.customer_id).trigger('change.select2');
					//$('select#project_boq').val(data.project_outsource_id).trigger('change.select2');
					$('select#template_boq').val(data.master_boq_template_id).trigger('change.select2');
					getProject(data.customer_id,'selected',data.project_outsource_id);
					
					

					getListCurrentBoq(
					  data.id,
					  data.project_outsource_id,
					  data.customer_id,
					  data.master_boq_template_id,
					  function(){
					    recalcAllRowJumlahHarga(); 
					  }
					);

					

					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.periode').html(periode);
					$('span.customer_boq').html(data.customer_name);
					$('span.project_boq').html(data.project_name);
					$('span.template_boq').html(data.template_name);


					var locate = 'table.boq-list_view';
					$.ajax({type: 'post',url: module_path+'/genboqrow',data: { id:data.id, customer: data.customer_id, project: data.project_outsource_id, template: data.master_boq_template_id, view:true },success: function (response) {
						var obj = JSON.parse(response); 
						$(locate+' tbody').html(obj[0]);
						
						wcount=obj[1];

						$('span#management_fee').html(formatNumber(data.management_fee_harga));
						$('span#jumlah_total').html(formatNumber(data.jumlah_total));
						$('span#ppn_percen').html(data.ppn_percen);
						$('span#pph_percen').html(data.pph_percen);
						$('span#ppn_harga').html(formatNumber(data.ppn_harga));
						$('span#pph_harga').html(formatNumber(data.pph_harga));
						$('span#grand_total').html(formatNumber(data.grand_total));
						
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





$('#customer_boq').on('change', function () {
    var customer = $(this).val();

    $('#project_boq')
        .val(null)
        .empty()
        .append('<option value=""></option>')
        .trigger('change.select2');

    if (customer) {
        getProject(customer);
    }
});



function getProject(customer,selected='',idVal=''){ 

	if(customer != ''){
 		
 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataProject',
			data: { customer: customer },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){ 
					
					var $el = $("#project_boq");

					$el.empty(); // remove old options
					$el.append($("<option></option>").attr("value", "").text(""));
					$.each(data.msproject, function(key,value) {
						$el.append($("<option></option>")
				     	.attr("value", value.id).text(value.project_name));
					  	
					});

					if(selected=='selected'){
						$('select#project_boq').val(idVal).trigger('change.select2');
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


$('#project_boq').on('change', function () { 
 	var project = $("#project_boq option:selected").val();
 	var customer 	= $("#customer_boq option:selected").val();
 	var template_boq 	= $("#template_boq option:selected").val();
	
 	
 	if(project != '' && customer != '' && template_boq != ''){

 		document.getElementById("divBoq").style.display = "block";

 		$.ajax({
			type: "POST",
	        url : module_path+'/getDataProjectOutsource',
			data: { project: project },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {  
				if(data != null){  
					var periode = '';
					if(data[0].periode_start != '' && data[0].periode_end != '' && data[0].periode_start != null && data[0].periode_end != null){
						var periode = data[0].periode_start+' s/d '+data[0].periode_end;
					}

					$(`input[name="periode"]`).val(periode);
					
					var locate = 'table.boq-list';
					$.ajax({type: 'post',url: module_path+'/genboqrow',data: { id: 0, customer: customer, project: project, template: template_boq},success: function (response) {
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
					$(`input[name="periode"]`).val('');

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



$('#customer_boq').on('change', function () { 
 	var project = $("#project_boq option:selected").val();
 	var customer 	= $("#customer_boq option:selected").val();
 	var template_boq 	= $("#template_boq option:selected").val();
	
 	
 	if(project != '' && customer != '' && template_boq != ''){

 		document.getElementById("divBoq").style.display = "block";

		var locate = 'table.boq-list';
		$.ajax({type: 'post',url: module_path+'/genboqrow',data: { id: 0, customer: customer, project: project, template: template_boq},success: function (response) {
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

});


$('#template_boq').on('change', function () { 
 	var project = $("#project_boq option:selected").val();
 	var customer 	= $("#customer_boq option:selected").val();
 	var template_boq 	= $("#template_boq option:selected").val();
	
 	
 	if(project != '' && customer != '' && template_boq != ''){

 		document.getElementById("divBoq").style.display = "block";

		var locate = 'table.boq-list';
		$.ajax({type: 'post',url: module_path+'/genboqrow',data: { id: 0, customer: customer, project: project, template: template_boq},success: function (response) {
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

});



function getListCurrentBoq(id,project,customer,template,callback){ 
	
 	
 	if(project != '' && customer != '' && template != ''){ 

 		document.getElementById("divBoq").style.display = "block";

		var locate = 'table.boq-list';
		$.ajax({type: 'post',url: module_path+'/genboqrow',data: { id: id, customer: customer, project: project, template: template},success: function (response) {
			var obj = JSON.parse(response);
			$(locate+' tbody').html(obj[0]);
			
			wcount=obj[1];
		}
		}).done(function() {
			//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
			tSawBclear(locate);
			///expenseviewadjust(lstatus);

			if (typeof callback === 'function') {
		        callback();
		      }

		});

 	}
}



/*function parseNumber(val){
  if(!val) return 0;
  return parseFloat(val.toString().replace(/,/g, '')) || 0;
}

function formatNumber(num){
  return num.toLocaleString('en-US');
}*/


function formatNumber(num) {
    num = Number(num) || 0;

    let result = num.toLocaleString('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // hapus ,00 kalau bulat
    return result.replace(/,00$/, '');
}


function parseNumber(val){
    if(!val) return 0;

    return parseFloat(
        val.toString()
           .replace(/\./g, '') // hapus ribuan
           .replace(',', '.')  // koma → desimal
    ) || 0;
}





function set_jumlah_harga(el){
  let row = $(el).data('id');

  let jumlah        = parseNumber($(`input[name="jumlah[${row}]"]`).val());
  let satuan_harga  = parseNumber($(`input[name="satuan_harga[${row}]"]`).val());

  let total = jumlah * satuan_harga;

  $(`input[name="jumlah_harga[${row}]"]`).val(formatNumber(total));

  recalcBoqTotals();
}


function set_jumlah_harga2(el){
  let row = $(el).data('id');

  let jumlah        = parseNumber($(`input[name="jumlah[${row}]"]`).val());
  let satuan_harga  = parseNumber($(`input[name="satuan_harga[${row}]"]`).val());

  let total = jumlah * satuan_harga;

  $(`input[name="jumlah_harga[${row}]"]`).val(formatNumber(total));

  recalcBoqTotals();
}




function recalcBoqTotals(){ 

	let headerTotals = {};
	let parentTotals = {};
	let grandTotalJumlah = 0;
	let grandTotalHarga  = 0;

  	$('.boq-item').each(function(){

	    let header = $(this).data('header') || '';
	    let parent = $(this).data('parent') || '';

	    let jumlahVal = parseNumber($(this).find('input[name^="jumlah["]').val());
	    let hargaVal  = parseNumber($(this).find('input[name^="jumlah_harga["]').val());

	    // parent key unik per header
	    let parentKey = header + '||' + parent;

	    if(!parentTotals[parentKey]){
	      parentTotals[parentKey] = { jumlah: 0, harga: 0 };
	    }

	    if(!headerTotals[header]){
	      headerTotals[header] = { jumlah: 0, harga: 0 };
	    }

	    /*parentTotals[parentKey].jumlah += jumlahVal;
	    parentTotals[parentKey].harga  += hargaVal;

	    headerTotals[header].jumlah += jumlahVal;
	    headerTotals[header].harga  += hargaVal;

	    grandTotalJumlah += jumlahVal;
	    grandTotalHarga  += hargaVal;*/

	    parentTotals[parentKey].jumlah += jumlahVal;
		parentTotals[parentKey].harga  += hargaVal;

		// ================= HEADER =================
		/*if (header.toUpperCase().trim() === 'gaji pokok') {*/
		if (header === 'gaji pokok') {

		    // JUMLAH hanya dari parent "Gaji Pokok"
		    /*if (parent.toUpperCase().trim() === 'gaji pokok') {*/
		    if (parent === 'gaji pokok') {
		        headerTotals[header].jumlah += jumlahVal;
		        grandTotalJumlah += jumlahVal;
		    }

		} else {

		    // header lain normal
		    headerTotals[header].jumlah += jumlahVal;
		    grandTotalJumlah += jumlahVal;
		}

		// JUMLAH_HARGA tetap normal
		headerTotals[header].harga += hargaVal;
		grandTotalHarga += hargaVal;



  	});

	 // render ke row total parent
  	$('.boq-total-parent').each(function(){

	  let parent = $(this).data('parent') || '';
	  let header = $(this).data('header') || '';

	  let key = header + '||' + parent;

	  let t = parentTotals[key] || { jumlah: 0, harga: 0 };

	  // kolom ke-3 & ke-5 (index 2 dan 4)
	  $(this).find('td:eq(1)').text(formatNumber(t.jumlah));
	  $(this).find('td:eq(3)').text(formatNumber(t.harga));
	});

  	// render ke row total header
  	$('.boq-total-header').each(function(){
	    let header = $(this).data('header');

	    let t = headerTotals[header] || { jumlah: 0, harga: 0 };

	    $(this).find('td:eq(1)').text(formatNumber(t.jumlah));
	    $(this).find('td:eq(3)').text(formatNumber(t.harga));
  	});

  	// render grand total
  	$('.boq-total-all[data-type="grand"]').each(function(){
	    $(this).find('td:eq(1)').text(formatNumber(grandTotalJumlah));
	    $(this).find('td:eq(3)').text(formatNumber(grandTotalHarga));
  	});

  	$('[name="hdnjumlah_harga"]').val(grandTotalHarga);

  	var hdnmanagementfee_percen = $("#hdnmanagementfee_percen").val();
  	var percen = hdnmanagementfee_percen/100;
  	
  	var total_management_fee = Number((percen*grandTotalHarga));
  	$('[name="hdnmanagement_fee"]').val(total_management_fee);
  	$('span#management_fee').html(formatNumber(total_management_fee));

  	var jml_total = Number(grandTotalHarga)+Number(total_management_fee);
  	$('[name="hdnjumlah_total"]').val(jml_total);
  	$('span#jumlah_total').html(formatNumber(jml_total));



  	var total_ppn = 0; var total_pph = 0;
  	var with_ppn = $('#with_ppn').is(':checked');
  	var with_pph = $('#with_pph').is(':checked');

  	if(with_ppn){
  		var hdnppn_percen = $("#hdnppn_percen").val();
	  	var percen_ppn = hdnppn_percen/100;
	  	
	  	/*var total_ppn = Number((percen_ppn*jml_total));*/
	  	var total_ppn = Math.ceil((percen_ppn * jml_total) * 100) / 100;
	  	$('[name="hdnppn_harga"]').val(total_ppn);
	  	$('span#ppn_harga').html(formatNumber(total_ppn));
  	}


  	if(with_pph){
  		var hdnpph_percen = $("#hdnpph_percen").val();
	  	var percen_pph = hdnpph_percen/100;
	  	
	  	/*var total_pph = Number((percen_pph*jml_total));*/
	  	var total_pph = Math.ceil((percen_pph * jml_total) * 100) / 100;
	  	$('[name="hdnpph_harga"]').val(total_pph);
	  	$('span#pph_harga').html(formatNumber(total_pph));
  	}
  	




  	/*var jml_grand_total = (Number(jml_total)+Number(total_ppn))-Number(total_pph);*/
  	var jml_grand_total_raw = (Number(jml_total) + Number(total_ppn)) - Number(total_pph);
	var jml_grand_total = Math.ceil(jml_grand_total_raw * 100) / 100;

  	$('[name="hdngrand_total"]').val(jml_grand_total);
  	$('span#grand_total').html(formatNumber(jml_grand_total));
  	
  	

}


function recalcAllRowJumlahHarga(){
  $('.boq-item').each(function(){
    let row = $(this).data('id');

    let jumlah = parseNumber($(`input[name="jumlah[${row}]"]`).val());
    let satuan = parseNumber($(`input[name="satuan_harga[${row}]"]`).val());

    let total = jumlah * satuan;

    $(`input[name="jumlah_harga[${row}]"]`).val(formatNumber(total));
  });

  // setelah SEMUA row dihitung
  recalcBoqTotals();
}



function set_harga_ppn(){
  
	var hdnppn_percen = $("#hdnppn_percen").val();
  	var percen_ppn = hdnppn_percen/100;

  	var jml_total = $("#hdnjumlah_total").val();
  	var total_pph = $("#hdnpph_harga").val();

  	
  	/*var total_ppn = Number((percen_ppn*jml_total));*/
  	var total_ppn = Math.ceil((percen_ppn * jml_total) * 100) / 100;
  	$('[name="hdnppn_harga"]').val(total_ppn);
  	$('span#ppn_harga').html(formatNumber(total_ppn));


  	/*var jml_grand_total = (Number(jml_total)+Number(total_ppn))-Number(total_pph);*/
  	var jml_grand_total_raw = (Number(jml_total) + Number(total_ppn)) - Number(total_pph);
	var jml_grand_total = Math.ceil(jml_grand_total_raw * 100) / 100;

  	$('[name="hdngrand_total"]').val(jml_grand_total);
  	$('span#grand_total').html(formatNumber(jml_grand_total));


}


function set_harga_pph(){
  
	var hdnpph_percen = $("#hdnpph_percen").val();
  	var percen_pph = hdnpph_percen/100;

  	var jml_total = $("#hdnjumlah_total").val();
  	var total_ppn = $("#hdnppn_harga").val();
  	
  	/*var total_pph = Number((percen_pph*jml_total));*/
  	var total_pph = Math.ceil((percen_pph * jml_total) * 100) / 100;
  	$('[name="hdnpph_harga"]').val(total_pph);
  	$('span#pph_harga').html(formatNumber(total_pph));


  	/*var jml_grand_total = (Number(jml_total)+Number(total_ppn))-Number(total_pph); */
  	var jml_grand_total_raw = (Number(jml_total) + Number(total_ppn)) - Number(total_pph);
	var jml_grand_total = Math.ceil(jml_grand_total_raw * 100) / 100;

  	$('[name="hdngrand_total"]').val(jml_grand_total);
  	$('span#grand_total').html(formatNumber(jml_grand_total));


}
 
function set_with_ppn(){
	var isChecked = $('#with_ppn').is(':checked');

    if (isChecked) { 
        // munculkan input persen
        /*$('#hdnppn_percen').show();*/
        document.getElementById("hdnppn_percen").style.display = "block";

        // default 11 kalau kosong
        if ($('#hdnppn_percen').val() === '') {
            $('#hdnppn_percen').val(11);
        }

        set_harga_ppn();

    } else { 
        // sembunyikan input persen
        document.getElementById("hdnppn_percen").style.display = "none";
        /*$('#hdnppn_percen').hide();*/
        $('#hdnppn_percen').val('');
        $('span#ppn_harga').html('');


        // reset nilai PPN
        $('#ppn_harga').text('0');
        $('#hdnppn_harga').val(0);

        set_harga_ppn();
    }
}


function set_with_pph(){
	var isChecked = $('#with_pph').is(':checked');

    if (isChecked) { 
        // munculkan input persen
        /*$('#hdnppn_percen').show();*/
        document.getElementById("hdnpph_percen").style.display = "block";

        // default 11 kalau kosong
        if ($('#hdnpph_percen').val() === '') {
            $('#hdnpph_percen').val(11);
        }

        set_harga_pph();

    } else { 
        // sembunyikan input persen
        document.getElementById("hdnpph_percen").style.display = "none";
        /*$('#hdnppn_percen').hide();*/
        $('#hdnpph_percen').val('');
        $('span#pph_harga').html('');

        // reset nilai PPN
        $('#pph_harga').text('0');
        $('#hdnpph_harga').val(0);

        set_harga_pph();
    }
}



function print_pdf(id){
    if(!id){
        alert('ID tidak valid');
        return;
    }
 
    var url = module_path + '/print_pdf/' + id; // sesuaikan controller
    window.open(url, '_blank');
}





</script>