<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string



$(document).ready(function() {
   	$(function() {
   		
        $( "#period_start_fcast" ).datepicker();
        $( "#period_end_fcast" ).datepicker();
		
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
					
					$('[name="label1"]').val(data.label1);
					$('[name="label2"]').val(data.label2);
					$('[name="title"]').val(data.title);
					$('[name="description"]').val(data.description);

					var show_date_start = getFormattedDateTime(data.show_date_start);
					var show_date_end = getFormattedDateTime(data.show_date_end);
					$('[name="show_date_start"]').val(show_date_start);
					$('[name="show_date_end"]').val(show_date_end);
					$('[name="info_type"][value="'+data.type+'"]').prop('checked', true);

					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.penggajian_month_fcast').html(data.bulan_penggajian_name);
					$('span.penggajian_year_fcast').html(data.tahun_penggajian);
					

					list_fcast_view(data.id, data.bulan_penggajian, data.tahun_penggajian, project='');
					
					
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



function getFormattedDateTime(inputDate) { 
  	
	const date = new Date(inputDate);

	// Format jadi MM/DD/YYYY
	const formattedDate = 
	  String(date.getMonth() + 1).padStart(2, '0') + '/' +
	  String(date.getDate()).padStart(2, '0') + '/' +
	  date.getFullYear();

	console.log(formattedDate); // Output: 07/13/2025
	 return `${formattedDate}`;
}


function resetTableFcast() {
    $('#tblDetailListFcast tbody').html('');
    $('#listFcast').hide(); // sekalian sembunyikan
}



$('input[name="is_all_project_fcast"]').on('change', function () {

    let val = $(this).val();
    let penggajian_month = $("#penggajian_month_fcast").val();
    let penggajian_year  = $("#penggajian_year_fcast").val();

    if (penggajian_month === '' || penggajian_year === '') {
        alert('Bulan & Tahun Penggajian harap diisi terlebih dahulu');
        $(this).prop('checked', false);
        return;
    }

    // RESET setiap ganti radio
    resetTableFcast();

    if (val === 'Sebagian') {

        // tampilkan select project
        $("#inputProject_fcast").show();

        // reset pilihan project
        $('#projectIds_fcast').val(null).trigger('change');

    } else {

        // SEMUA
        $("#inputProject_fcast").hide();

        let project = '';
        list_fcast(penggajian_month, penggajian_year, project);
    }
});




$('#projectIds_fcast').on('change', function () {

    let val = $(this).val(); // array project_id

    if (!val || val.length === 0) {
        resetTableFcast();
        return;
    }

    let penggajian_month = $("#penggajian_month_fcast").val();
    let penggajian_year  = $("#penggajian_year_fcast").val();

    if (penggajian_month === '' || penggajian_year === '') {
        alert('Bulan & Tahun Penggajian harap diisi terlebih dahulu');
        $(this).val(null).trigger('change');
        resetTableFcast();
        return;
    }

    list_fcast(penggajian_month, penggajian_year, val);
});




function list_fcast(penggajian_month, penggajian_year, project){

    document.getElementById("listFcast").style.display = "block";

    var locate = 'table.listfcast-list';

    
    $(locate + ' tbody').html(`
        <tr>
            <td colspan="20" style="text-align:center;">
                <i class="fa fa-spinner fa-spin"></i> Loading data...
            </td>
        </tr>
    `);

    $.ajax({
        type: 'post',
        url: module_path + '/genfcastrow',
        data: {
            id: 0,
            penggajian_month: penggajian_month,
            penggajian_year: penggajian_year,
            project: project
        },
        success: function (response) {
            var obj = JSON.parse(response);
            $(locate + ' tbody').html(obj[0]);
            wcount = obj[1];
        },
        error: function () {
            $(locate + ' tbody').html(`
                <tr>
                    <td colspan="20" style="text-align:center;color:red;">
                        Gagal memuat data
                    </td>
                </tr>
            `);
        },
        complete: function () {
            tSawBclear(locate);
        }
    });
}




function list_fcast_view(id, penggajian_month, penggajian_year, project){



	var locate = 'table.listfcast-list_view';
	$.ajax({type: 'post',url: module_path+'/genfcastrow',data: { id:id, penggajian_month: penggajian_month, penggajian_year: penggajian_year, project: project, view: true },success: function (response) {
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







</script>