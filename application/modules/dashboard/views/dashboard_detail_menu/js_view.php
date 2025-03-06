<style type="text/css">
	#map {
	  width: 1400px;
	  height: 400px;
	}

	
</style>




<!-- Modal Form Data -->
<!-- <div id="modal-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-form-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:1000px; height:500px; margin-left:-160px">
			<form class="form-horizontal" id="frmInputData" enctype="multipart/form-data">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					<span id="mfdata"></span> Activity Monitor
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<input type="hidden" name="id" value="">
				<?php $this->load->view("_detail"); ?>
			</div>
			</form>

			<div class="modal-footer no-margin-top">
				
				<button class="btn blue" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Close
				</button>
			</div>
		</div>
	</div>
	</div>
</div> -->
<!-- 
<script src="//code.jquery.com/jquery-1.9.1.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>


<!-- <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script> -->



<script type="text/javascript">

var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var url = new URL(window.location.href);
arrayOfStrings = url.toString().split('=');
var idfc = arrayOfStrings[1];

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
		"sAjaxSource": module_path+"/get_data_activity?idx="+idfc+"",
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

	
	getCctv(idfc);
	jobGraph(idfc);


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



function getCctv(idfc){
	

	$.ajax({
		type: "POST",
        url : module_path+'/get_cctv',
		data: { cctv: idfc},
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


function jobGraph(idfc){ 

	$.ajax({
		type: "POST",
        url : module_path+'/get_detailJobGraph',
		data: { cctv: idfc},
		cache: false,		
        dataType: "JSON",
        success: function(data)
        { 
			if(data != false){ 
				
				//// get Job Graph
				var arrDate = []; 
				var total_time_1 = [];
				var total_time_2 = [];
				var dateP = '';
				for(var i=0; i<data.length; i++){
					var exists = arrDate.includes(data[i].date);
					if (!exists) {
					    arrDate.push(data[i].date);
					    var dateP = data[i].date;
					}

					
						/*if(dateP == data[i].date && data[i].order_name == 'Perpindahan 2'){
							//alert("ada");
							var val = data[i].date_time_total;
						}else{ //alert("kosong");
							var val = '0';
						}
						total_time_2.push(val);*/
					

					if(data[i].order_name == 'Perpindahan Batubara'){
						total_time_1.push(data[i].date_time_total);
					}

					if(data[i].order_name == 'Perpindahan 2'){
						total_time_2.push(data[i].date_time_total);
					}


					/*if(data[i].date == dateP && data[i].order_name == 'Perpindahan 2'){
						var val = data[i].date_time_total;
					}else{
						var val = '0';
					}


					total_time_2.push(val);*/

					/*if(data[i].order_name == 'Perpindahan 2'){
						
						
						var val = '';
						if(data[i].date != '' && data[i].order_name != '' && data[i].date_time_total != '' ){
							var val = data[i].date_time_total;
						}
						
					}*/
					
				}


				/*for(var j=0; j<data.length; j++){

					for(var k=0; k<arrDate.length; k++){
						if(data[j].date == arrDate[k] && data[j].order_name == 'Perpindahan 2'){
							var val = data[j].date_time_total;
						}else{
							var val = '0';
						}
						alert(val);
					}
					
					total_time_2.push(val);
				}*/

				/*var xx = []; var yy = [];
				var ttl = 3;*/
				/*for(var k=0; k<ttl; k++){ 
					for(var j=0; j<data.length; j++){
						
						if(arrDate[k] == data[j].date){ 
							if(data[j].order_name == 'Perpindahan 2'){ 
								total_time_2.push(data[j].date_time_total);
							}
							else{
								total_time_2.push('0');
							}
							
						}
					}
				}

				console.log(xx); console.log(yy);*/
					
				
				
				var ctx = document.getElementById("chartjs_bar").getContext('2d');
			    var myChart = new Chart(ctx, {
			        type: 'bar',
			        data: {
			            labels: arrDate, 
			            datasets: [
				            {
						      label: 'Perpindahan Batubara',
						      data: total_time_1,
						      //borderColor: '#36A2EB',
						      backgroundColor: '#5969ff',
						    },
						    {
						      label: 'Perpindahan 2',
						      data: total_time_2,
						      //borderColor: '#36A2EB',
						      backgroundColor: '#ffef59',
						    }
			        	]
			        },
			        options: {
			               legend: {
						            display: true,
						            position: 'bottom',

						            labels: {
						                fontColor: '#71748d',
						                fontFamily: 'Circular Std Book',
						                fontSize: 14,
						            }
						        },
						       
						    }
			    });

				
				//// END get Job Graph
				
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


</script>