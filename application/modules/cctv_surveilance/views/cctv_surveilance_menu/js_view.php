<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string


<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>


$(document).ready(function() {
   getCctv('all', '');

   	
});


function cari(){

	var floating_crane = $("#floating_crane option:selected").val();
	var jmlcctv = document.getElementById("txtjmlcctv").value;

	
	
	getCctv(floating_crane, jmlcctv);
}

function getCctv(floating_crane, jmlcctv){
	

	$.ajax({
		type: "POST",
        url : module_path+'/get_cctv',
		data: { floating_crane: floating_crane, jmlcctv: jmlcctv },
		cache: false,		
        dataType: "JSON",
        success: function(data)
        { 
			if(data != false){ 
				

				$('span.tblCctv').html(data);
				
				
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
				title: '',//'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
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
</script>