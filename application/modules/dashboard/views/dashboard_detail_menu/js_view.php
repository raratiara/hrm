<style type="text/css">
	#map {
	  width: 1400px;
	  height: 400px;
	}
</style>


<!-- <script src="//code.jquery.com/jquery-1.9.1.js"></script> -->
<!-- js for bar graph -->
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>


<!-- <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script> -->

<!-- js for line chart -->
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>



<script type="text/javascript">

$(document).ready(function() {
   	$(function() {
        $( "#start_date" ).datepicker();
        $( "#end_date" ).datepicker();
   	});
});

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

	$('[name="id_fc"]').val(idfc);

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
	var start_date = document.getElementById("start_date").value;
  	var end_date = document.getElementById("end_date").value;

  	

	$.ajax({
		type: "POST",
        url : module_path+'/get_detailJobGraph',
		data: { cctv: idfc, start_date: start_date, end_date: end_date},
		cache: false,		
        dataType: "JSON",
        success: function(data)
        { 
			if(data != false){ 

				$('span#title_job').html(data[0].floating_crane_name);
				//// get Job Graph
				var arrDate = []; 
				var total_time_1 = []; 
				var total_time_2 = [];
				var date_1 = []; 
				var date_2 = [];
				var arrJob = [];
				var arrColor = ["#5969ff",
                                "#ff407b",
                                "#25d5f2",
                                "#ffc750",
                                "#2ec551",
                                "#7040fa",
                                "#ff004e"];

				

				for(var i=0; i<data.length; i++){
					var exists = arrDate.includes(data[i].date);
					if (!exists) { 
					    arrDate.push(data[i].date);
					}

					var exists_job = arrJob.includes(data[i].order_name);
					if (!exists_job) { 
						arrJob.push(data[i].order_name);
						
					}


					
					
					/*for(var a=0; a<arrJob.length; a++){
						var no = a+1; 
    					document.cookie = "num = " + no;

						<?php
						    $ke= $_COOKIE['num'];
						    
						?>
						console.log(<?=$ke?>);
						if(data[i].order_name == arrJob[a]){
							total_time_<?=$ke?>.push(data[i].date_time_total);
							date_<?=$ke?>.push(data[i].date);
							
						}
						
					} */
					
					
					if(data[i].order_name == 'Perpindahan Batubara'){
						total_time_1.push(data[i].date_time_total);
						date_1.push(data[i].date);
					}

					if(data[i].order_name == 'Perpindahan 2'){
						total_time_2.push(data[i].date_time_total);
						date_2.push(data[i].date);
					}
				} 



				var arrTotaltime_1 = [];
				var arrTotaltime_2 = [];
				for(var m=0; m<arrDate.length; m++){ 

					var exists_1 = date_1.includes(arrDate[m]);
					if (!exists_1) { 
					    arrTotaltime_1.push('0');
					}else{
						var arrayIdx_1 = (date_1.indexOf(arrDate[m]));
						arrTotaltime_1.push(total_time_1[arrayIdx_1]);
					}


					
					var exists_2 = date_2.includes(arrDate[m]);
					if (!exists_2) { 
					    arrTotaltime_2.push('0');
					}else{
						var arrayIdx_2 = (date_2.indexOf(arrDate[m]));
						arrTotaltime_2.push(total_time_2[arrayIdx_2]);
					}
				}


				
				
				const canvas = document.getElementById('chartjs_bar');
				const ctx = canvas.getContext('2d');
				
				//var ctx = document.getElementById("chartjs_bar").getContext('2d');
			    var myChart = new Chart(ctx, {
			        type: 'bar',
			        data: {
			            labels: arrDate, 
			            datasets: [
			            	<?php 
			            	for($aa=0; $aa<2; $aa++){ 
			            		$i=$aa+1;
			            		?>
				            		{
								      label: arrJob[<?=$aa?>],
								      data: arrTotaltime_<?=$i?>,
								      //borderColor: '#36A2EB',
								      backgroundColor: arrColor[<?=$aa?>],//'#5969ff',
								    },
			            		<?php
			            	}
			            	?>
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


			    canvas.onclick = (evt) => {
				  const res = myChart.getElementsAtEventForMode(
				    evt,
				    'nearest',
				    { intersect: true },
				    true
				  );
				  // If didn't click on a bar, `res` will be an empty array
				  if (res.length === 0) {
				    return;
				  }
				  
				  
				  var valClick = res[0]._view.datasetLabel;

				  activityGraph(valClick);
				  //alert('You clicked on ' +valClick);
				  //alert('You clicked on ' + myChart.data.labels[res[0]._view.datasetLabel]);
				};

				
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


function activityGraph(jobId){
	
	$.ajax({
		type: "POST",
        url : module_path+'/get_detailActivityGraph',
		data: { jobId: jobId},
		cache: false,		
        dataType: "JSON",
        success: function(data)
        { 
			if(data != false){ 
				document.getElementById("tblActRpt").style.display = "";
				$('span#title_activity').html(data[0].order_name);

				//// get Activity Graph
				var arrAct = [];
				var arrTotalTime = [];
				for(var i=0; i<data.length; i++){ 
					arrAct.push(data[i].activity_name);
					arrTotalTime.push(data[i].total_date_time);
				}
				//console.log(arrAct);
				
				//var ctx = document.getElementById("chartjs_bar_activity").getContext('2d');
				const canvas = document.getElementById('chartjs_bar_activity');
				const ctx = canvas.getContext('2d');

			    var myChart = new Chart(ctx, {
			        type: 'bar',
			        data: {
			            labels: arrAct, 
			            datasets: [{
                            backgroundColor: [
                               "#5969ff",
                                "#ff407b",
                                "#25d5f2",
                                "#ffc750",
                                "#2ec551",
                                "#7040fa",
                                "#ff004e"
                            ],
                            data: arrTotalTime, //<?php echo json_encode($arrTotalTime); ?>,
                        }]
			        },
			        options: {
			               legend: {
						            display: false,
						            position: 'bottom',

						            labels: {
						                fontColor: '#71748d',
						                fontFamily: 'Circular Std Book',
						                fontSize: 14,
						            }
						        },
						       
						    }
			    });


			    canvas.onclick = (evt) => {
				  const res = myChart.getElementsAtEventForMode(
				    evt,
				    'nearest',
				    { intersect: true },
				    true
				  );
				  // If didn't click on a bar, `res` will be an empty array
				  if (res.length === 0) {
				    return;
				  }
				  
				  
				  var valClick = res[0]._view.label;

				  getLineChart(valClick, jobId);
				  getTblWaktu(valClick, jobId);
				  //alert('You clicked on ' +valClick);
				  //alert('You clicked on ' + myChart.data.labels[res[0]._view.datasetLabel]);
				};

				
				//// END get Activity Graph
				
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

function getDateRange(){
	var id_fc = document.getElementById("id_fc").value;
	var start_date = document.getElementById("start_date").value;
  	var end_date = document.getElementById("end_date").value;


  	jobGraph(id_fc);
	
}


function getLineChart(activity, jobId){ 
	

	$.ajax({
		type: "POST",
        url : module_path+'/get_detailwaktuAct',
		data: { activity: activity, jobId: jobId},
		cache: false,		
        dataType: "JSON",
        success: function(data)
        { 
			if(data != false){ console.log(data);

				document.getElementById("tblDtlWaktu").style.display = "";
				//$('span#title_activity').html(data[0].order_name);

				//// get Activity Graph
				var arrAct = [];
				var arrTotalTime = [];
				for(var i=0; i<data.length; i++){ 
					arrAct.push(data[i].activity_name);
					arrTotalTime.push(data[i].total_time);
				}
				//console.log(arrAct);
				
				const canvas = document.getElementById('chartjs_line');
				const ctx = canvas.getContext('2d');

			    var myChart = new Chart(ctx, {
			        type: 'line',
			        data: {
			            labels: arrAct, 
			            datasets: [{
                            backgroundColor: [
                                "#25d5f2"
                            ],
                            data: arrTotalTime, //<?php echo json_encode($arrTotalTime); ?>,
                        }]
			        },
			        options: {
			               legend: {
						            display: false,
						            position: 'bottom',

						            labels: {
						                fontColor: '#71748d',
						                fontFamily: 'Circular Std Book',
						                fontSize: 14,
						            }
						        },
						       
						    }
			    });

				
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

function getTblWaktu(activity, job){

	myTable =
	$('#tbldetailWaktuAct')
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
		"sAjaxSource": module_path+"/get_data_waktu_activity?job="+job+"&activity="+activity+"",
		"bProcessing": true,
        "bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
    } );

}




</script>