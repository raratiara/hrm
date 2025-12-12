
<div id="modal-unbind" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-unbind" aria-hidden="true" style="padding-left: 600px">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:40%; text-align:center;">
			<form class="form-horizontal" id="frmUnbindData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					unBind  
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to unBind this Device?</p>
				<div class="form-group">
					<input type="hidden" name="id" id="id" value="">
					<!-- <label class="col-md-4 control-label no-padding-right">Reason</label>
					<div class="col-md-8">
						<?=$reject_reason;?>
						<input type="hidden" name="id" id="id" value="">
						<input type="hidden" name="approval_level" id="approval_level" value="">
					</div> -->
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-unbind" onclick="save_unbind()">
					<i class="fa fa-check"></i>
					Yes
				</button>
				<button class="btn red" data-dismiss="modal">
					<i class="fa fa-times"></i>
					No
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
			name: {
				required: true
			},
			username: {
				required: true,
				minlength: 3
			},
			id_groups: {
				required: true
			},
			passwd: "required",
			repasswd: {
			  equalTo: "#passwd"
			}
		},
		messages: { // custom messages
			username:{ remote: "This username is already taken! Try another." },
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
					// disabling required password field
					$(".mk").hide();
					$('[name="passwd"]').rules('remove',  'required')
					$('[name="username"]').rules('remove',  'required')
					$('[name="username"]').rules('remove',  'remote')

					$('[name="id"]').val(data.user_id);
					$('[name="name"]').val(data.name);
					$('[name="username"]').prop("readonly", true);
					$('[name="username"]').val(data.username);
					$('select#id_karyawan').val(data.id_karyawan).trigger('change.select2');
					$('[name="email"]').val(data.email);
					$('select#id_groups').val(data.id_groups).trigger('change.select2');
					$('[name="base_menu"][value="'+data.base_menu+'"]').prop('checked', true);
					$('[name="isaktif"][value="'+data.isaktif+'"]').prop('checked', true);
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){
					$('span.name').html(data.name);
					$('span.username').html(data.username);
					$('span.link').html(data.link);
					$('span.email').html(data.email);
					$('span.id_groups').html(data.role);
					var bmenu = 'Default';
					if (data.base_menu == 'custom'){
						bmenu = 'User';
					} else if (data.base_menu == 'role'){
						bmenu = 'Role';
					}
					$('span.bmenu').html(bmenu);
					var isaktif = 'New - Non Aktif';
					if (data.isaktif == 2){
						isaktif = 'Aktif';
					} else if (data.isaktif == 3){
						isaktif = 'Non Aktif';
					} else if (data.isaktif == 4){
						isaktif = 'Suspended';
					}
					$('span.isaktif').html(isaktif);
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



function to_unBind(id){
	if(id != ''){
		$('#modal-unbind').modal('show');
		$('[name="id"]').val(id);
	}else{
		alert("No data selected!");
	}
}


function save_unbind(){
	var id 		= $("#id").val();
	

	$('#modal-unbind').modal('hide');
	
	if(id != ''){
		$.ajax({
			type: "POST",
	    	url : module_path+'/unBind',
			data: { id: id },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        { 
	        	
				if(data != false){ 	
					alert("The device has been successfully unBind.");
				} else { 
					alert("Failed to unBind the device!");
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
		alert("Data not found!");
	}

	location.reload();


}



</script>


