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
				var arrDate		= []; 
				var arrJob 		= [];
				var arrDataJob	= [];	

				var arrColor 	= ["#5969ff",
	                                "#ff407b",
	                                "#25d5f2",
	                                "#ffc750",
	                                "#2ec551",
	                                "#7040fa",
	                                "#ff004e"];

	
	
				let total = {}; 
				for(var i=0; i<data.length; i++){
					var exists = arrDate.includes(data[i].date);
					if (!exists) { 
					    arrDate.push(data[i].date);
					}

					var exists_job = arrJob.includes(data[i].order_name);
					if (!exists_job) { 
						arrJob.push(data[i].order_name);
						
					}

					for(let a=0; a<arrJob.length; a++){
						let no = a+1; 
						<?php $ke = 'no'; ?>

						if(data[i].order_name == arrJob[a]){ 
							var obj={};
							obj['name'] = <?=$ke?>;
							obj['time'] = data[i].date_time_total;
							obj['date'] = data[i].date;
							arrDataJob.push(obj);
						}
					}
					
				} 

				document.cookie = "totalJob = " + arrJob.length;
				<?php
				    $ttlJob= $_COOKIE['totalJob'];
				?>


				var groupedJob = arrDataJob
				  .reduce((acc, curr) => {
				    var key = curr.name;
				    (acc[key] = acc[key] || [])
				      .push(curr.time);
				    return acc;
				  }, {});


				var groupedJobDate = arrDataJob
				  .reduce((acc, curr) => {
				    var key = curr.name;
				    (acc[key] = acc[key] || [])
				      .push(curr.date);
				    return acc;
				  }, {});


				for(let s=1; s<=arrJob.length; s++){
				  	for(let t=0; t<groupedJob[s].length; t++){
				  		total[`total_time_${s}`] = groupedJob[s];
				  		total[`date_${s}`] = groupedJobDate[s];
				  	}
				}
				var arrtotal=[];

				for(let u=1; u<=arrJob.length; u++){
					var uNo=u-1;
					
					for(var m=0; m<arrDate.length; m++){ 
						var arrobj={};
						arrobj['valName'] = 'xData_'+uNo;
						arrobj['valDate'] = arrDate[m];

						var exists_x = groupedJobDate[u].includes(arrDate[m]);
						if (!exists_x) { 
							arrobj['valTime'] = 0;
						}else{
							var arrayIdx = (groupedJobDate[u].indexOf(arrDate[m]));
							arrobj['valTime'] = total[`total_time_${u}`][arrayIdx];
						}
						arrtotal.push(arrobj);
						

					}
				}

				var groupedArrTotal = arrtotal
				  .reduce((acc, curr) => {
				    var key = curr.valName;
				    (acc[key] = acc[key] || [])
				      .push(curr.valTime);
				    return acc;
				  }, {});

  
				
				
				const canvas = document.getElementById('chartjs_bar');
				const ctx = canvas.getContext('2d');
				
				//var ctx = document.getElementById("chartjs_bar").getContext('2d');
			    var myChart = new Chart(ctx, {
			        type: 'bar',
			        data: {
			            labels: arrDate, 
			            datasets: [
			            	<?php 
			            	for($aa=0; $aa<$ttlJob; $aa++){ 
			            		//$i=$aa+1;
			            		?>
				            		{
								      label: arrJob[<?=$aa?>],
								      data: groupedArrTotal.xData_<?=$aa?>,
								      //borderColor: '#36A2EB',
								      backgroundColor: arrColor[<?=$aa?>],
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

function getEksportActivityMonitor(){
	var id_fc = document.getElementById("id_fc").value;
	
	$.ajax({
		type: "POST",
        url : module_path+'/eksport_activity_monitor',
		data: { id: id_fc },
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




</script>