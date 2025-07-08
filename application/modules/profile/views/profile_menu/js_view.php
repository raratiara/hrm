<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
	var module_path = "<?php echo base_url($folder_name);?>";

	$(document).ready(function() { 
	   	$(function() {
	   		
	        load_data();
	        monthlyAttSumm();
			
	   	});
	});


	<?php if  (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_UPDATE == "1" || _USER_ACCESS_LEVEL_DETAIL == "1")) { ?>

	function load_data()
	{ 
		var getUrl = window.location;
		//local=> 
		var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
		//var baseUrl = getUrl .protocol + "//" + getUrl.host;


	    $.ajax({
			type: "POST",
	        url : module_path+'/get_detail_data',
			data: { id: <?=_USER_EMPLOYEE_ID?> },
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {
				if(data != false){
					
					$('span.nik').html(data.dtEmp.emp_code);
					$('span.name').html(data.dtEmp.full_name);
					$('span.gender').html(data.dtEmp.gender_name);
					$('span.date_of_birth').html(data.dtEmp.date_of_birth);
					$('span.address').html(data.dtEmp.address_residen);
					$('span.division').html(data.dtEmp.division_name);
					$('span.department').html(data.dtEmp.department_name);
					$('span.job_title').html(data.dtEmp.job_title_name);
					$('span.job_level').html(data.dtEmp.job_level_name);
					$('span.status').html(data.dtEmp.emp_status_name);
					$('span.date_of_hired').html(data.dtEmp.date_of_hire);
					$('span.phone').html(data.dtEmp.personal_phone);
					$('span.email').html(data.dtEmp.personal_email);
					$('span.shift_type').html(data.dtEmp.shift_type);
					$('span.direct').html(data.dtEmp.direct_name);
					$('span.ttl_leave').html(data.ttl_leave);
					$('span.ttl_workhours').html(data.ttl_workhours);
					$('span.ttl_tasklist_open').html(data.ttl_tasklist_open);
					$('span.ttl_tasklist_inprogress').html(data.ttl_tasklist_inprogress);
					$('span.ttl_tasklist_closed').html(data.ttl_tasklist_closed);

					//emp_photo
					if(data.dtEmp.emp_photo != '' && data.dtEmp.emp_photo != null){
						$('span.emp_photo').html('<img src="'+baseUrl+'/uploads/employee/'+data.dtEmp.emp_code+'/'+data.dtEmp.emp_photo+'" width="200" height="200" >');
					}else{
						$('span.emp_photo').html('');
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



	function monthlyAttSumm(){

		$.ajax({
			type: "POST",
		    url : module_path+'/get_data_monthlyAttendanceSumm',
			data: { employee: <?=_USER_EMPLOYEE_ID?> },
			cache: false,		
	    	dataType: "JSON",
		    success: function(data)
		    {
				if(data != false){
					
					$('span.clyear').html('('+data.thn+')');
					const ctx = document.getElementById('monthly_attendance_summ').getContext('2d');

					var chartExist = Chart.getChart("monthly_attendance_summ"); // <canvas> id
			    	if (chartExist != undefined)  
			      	chartExist.destroy(); 


			    	const rawData = [
				      { label: 'Ontime', data: data.total_ontime, backgroundColor: '#2e3267',borderRadius: 3 },
				      { label: 'Late', data: data.total_late, backgroundColor: '#fddb5c',borderRadius: 3 },
				      { label: 'Leaving Early', data: data.total_leaving_early, backgroundColor: '#9b9fd2',borderRadius: 3 },
				      { label: 'No Attendance', data: data.total_noattendance, backgroundColor: '#b3b3b3',borderRadius: 3 },
			    	];

			        // Convert data to 100% scale per group
			    	const percentageData = rawData.map(dataset => ({ ...dataset }));
			    	const groupCount = rawData[0].data.length;

			    	for (let i = 0; i < groupCount; i++) {
				      const groupTotal = rawData.reduce((sum, ds) => sum + ds.data[i], 0);
				      percentageData.forEach(ds => {
				        /*ds.data[i] = +(ds.data[i] / groupTotal * 100).toFixed(2);*/
				        ds.data[i] = +(ds.data[i]);
				      });
			    	}

			    	new Chart(ctx, {
				      	type: 'bar',
				      	data: {
					        /*labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10','11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],*/
					        labels: data.bln,
					        datasets: percentageData
				      	},
				      	options: {
				        	responsive: true,
				        	maintainAspectRatio: false,
				        	plugins: {
					          tooltip: {
					            callbacks: {
					              label: function(context) {
					                /*return `${context.dataset.label}: ${context.parsed.y}%`;*/
					                return `${context.dataset.label}: ${context.parsed.y}`;
					              }
					            }
					          },
					          legend: {
					          	labels: {
							          font: {
							            size: 8  // kecilkan ukuran legend text
							          },
							          boxWidth: 12,        // kecilkan ukuran kotak warna
							          boxHeight: 8,        // atur tinggi (Chart.js 4.x ke atas)
							          borderRadius: 4,     // ubah jadi bulat (opsional)
							          usePointStyle: true // ubah ke true jika ingin lingkaran, segitiga, dll.
							        },
					            position: 'bottom'
					          },
					          title: {
					            display: false
					          }
					        },
					        scales: {
					          x: {
					            stacked: true,
					            ticks: {
					              color: '#333',
					              font: { size: 10 }
					            },
					            grid: { display: false }
					          },
					          y: {
					            stacked: true,
					            beginAtZero: true,
					            /*max: 20,*/
					            ticks: {
					            	stepSize: 1, //kelipatan 1
					              /*callback: (value) => value + '%',*/
					            	callback: (value) => value,
					              color: '#333',
					              font: { size: 10 }
					            },
					            grid: { color: '#eee' }
					          }
					        }
				      	}
			    	});

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


	function downloadFile(filename) { 
	    const link = document.createElement('a');
	    link.href = module_path+'/downloadFile?file=' + encodeURIComponent(filename);

	    link.setAttribute('download', filename);
	    document.body.appendChild(link);
	    link.click();
	    document.body.removeChild(link);
	}



</script>