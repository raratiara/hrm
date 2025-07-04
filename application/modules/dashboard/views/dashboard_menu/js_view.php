
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->


<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string

$(document).ready(function() {
   	$(function() {


		monthlyAttSumm();
   		attStatistic();
   		empbyDeptGender();
   		empbyGen();
   		attPercentage();
   		workhrsPercentage();
   		dataTotal();

		/*$('input[name="fldashdateperiod"]').daterangepicker();*/
		const picker = document.getElementById('fldashdateperiod');

		picker.addEventListener('change', function () {
		  const value = this.value; // format is "YYYY-MM"
		  const [year, month] = value.split('-');
		  /*console.log("Year:", year);
		  console.log("Month:", month);*/

		  setFilter();
		});		

   	
        
   	});
});


function dataTotal(){

	var dateperiod = "";
	var employee = "";

	$.ajax({
		type: "POST",
    	url : module_path+'/get_data_total',
		data: { dateperiod: dateperiod, employee: employee },
		cache: false,		
    	dataType: "JSON",
    	success: function(data)
    	{
			if(data != false){
				
				$('span#ttl_employee').html(data.ttl_emp);
				$('span#ttl_projects').html(data.ttl_projects);
				$('span#ttl_attendance').html(data.ttl_attendance);
				$('span#ttl_reimbursement').html(data.ttl_reimbursement);
				$('span#ttl_leave').html(data.ttl_leaves);
				$('span#ttl_overtime').html(data.ttl_overtimes);


			} else {

				$('span#ttl_employee').html('0');
				$('span#ttl_projects').html('0');
				$('span#ttl_attendance').html('0');
				$('span#ttl_reimbursement').html('0');
				$('span#ttl_leave').html('0');
				$('span#ttl_overtime').html('0');

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



function monthlyAttSumm(){

	var dateperiod = $("#fldashdateperiod").val();
	var employee = $("#fldashemp option:selected").val();
	


	$.ajax({
		type: "POST",
	    url : module_path+'/get_data_monthlyAttSumm',
		data: { dateperiod: dateperiod, employee: employee },
		cache: false,		
	    dataType: "JSON",
	    success: function(data)
	    {
			if(data != false){
					
				const ctx = document.getElementById('monthly_att_summ').getContext('2d');

				var chartExist = Chart.getChart("monthly_att_summ"); // <canvas> id
			    if (chartExist != undefined)  
		        chartExist.destroy(); 


		    	const barChart = new Chart(ctx, {
		      		type: 'bar',
			      	data: {
			      		labels: data.periode,
				        /*labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],*/
				        datasets: [{
				          	label: 'Attendance',
				          	/*data: [1200, 1900, 3000, 2500, 2700, 3200, 1000, 2500, 1300, 980, 1240, 1600],*/
				          	data: data.total_absensi,
				          	backgroundColor: [
					            '#74dce0',
					            '#74dce0',
					            '#74dce0',
					            '#74dce0',
					            '#74dce0',
					            '#74dce0',
					            '#74dce0',
					            '#74dce0',
					            '#74dce0',
					            '#74dce0',
					            '#74dce0',
					            '#74dce0'
				          	],
				          	borderRadius: 3
				        }]
			      	},
		     		options: {
				        responsive: true,
				        maintainAspectRatio: false,
				        plugins: {
				          legend: {
				            display: false
				          },
				          tooltip: {
				            backgroundColor: '#333',
				            titleColor: '#fff',
				            bodyColor: '#fff',
				            padding: 10,
				            borderRadius: 6
				          }
				        },
				        scales: {
				          	y: {
					            beginAtZero: true,
					            grid: {
					              color: '#eee'
					            },
					            ticks: {
					              color: '#666',
					              font: { size: 10 }
					            }
				          	},
				          	x: {
					            grid: {
					              display: false
					            },
					            ticks: {
					              color: '#666',
					              font: { size: 10 }
					            }
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


function attStatistic(){

	var dateperiod = $("#fldashdateperiod").val();
	var employee = $("#fldashemp option:selected").val();


	$.ajax({
		type: "POST",
	    url : module_path+'/get_data_attStatistic',
		data: { dateperiod: dateperiod, employee: employee },
		cache: false,		
    	dataType: "JSON",
	    success: function(data)
	    {
			if(data != false){
				
				const ctx = document.getElementById('att_statistic').getContext('2d');

				var chartExist = Chart.getChart("att_statistic"); // <canvas> id
		    	if (chartExist != undefined)  
		      	chartExist.destroy(); 


		    	const rawData = [
			      { label: 'On Work Time', data: data.total_on_work_time, backgroundColor: '#357ed2',borderRadius: 3 },
			      { label: 'Overtime', data: data.total_overtime, backgroundColor: '#74dce0',borderRadius: 3 },
			      { label: 'Leave', data: data.total_leave, backgroundColor: '#954ad7',borderRadius: 3 },
			      { label: 'Late', data: data.total_late, backgroundColor: '#f89904',borderRadius: 3 },
			      { label: 'Leaving Early', data: data.total_leaving_early, backgroundColor: '#63f804',borderRadius: 3 },
			      { label: 'Absent', data: data.total_absent, backgroundColor: '#ddf804',borderRadius: 3 },
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
				        labels: data.hari,
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


function empbyDeptGender(){


	var dateperiod = "";
	var employee = "";


	$.ajax({
		type: "POST",
	    url : module_path+'/get_data_empbyDeptGender',
		data: { dateperiod: dateperiod, employee: employee },
		cache: false,		
	    dataType: "JSON",
	    success: function(data)
	    {
			if(data != false){

				const ctx = document.getElementById('empby_dept_gender').getContext('2d');

				new Chart(ctx, {
					type: 'bar',
			    	data: {
			        	labels: data.departments, /*['HRGA', 'IT', 'Marketing'],*/
				        datasets: [
				          	{
					            label: 'Male',
					            type: 'bar',
					            data: data.total_male,
					            borderColor: '#3381fc',
					            backgroundColor: '#3381fc',
					            fill: false,
					            tension: 0.4,
					            yAxisID: 'y1',
					            borderRadius: 3
				          	},
				          	{
					            label: 'Female',
					            type: 'bar',
					            data: data.total_female,
					            borderColor: '#fc3381',
					            backgroundColor: '#fc3381',
					            fill: false,
					            tension: 0.4,
					            yAxisID: 'y1',
					            borderRadius: 3
				          	}
				        ]
			    	},
					options: {
			      		responsive: true,
			      		maintainAspectRatio: false,
				        interaction: {
				          mode: 'index',
				          intersect: false
				        },
			      		stacked: false,
				        plugins: {
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
				          tooltip: {
				            callbacks: {
				              label: function(context) {
				                return context.dataset.label + ': ' + context.formattedValue +
				                  (context.dataset.label.includes('%') ? '%' : '');
				              }
				            }
				          }
				        }/*,
			        	scales: {
				          	y: {
					            type: 'linear',
					            position: 'left',
					            title: {
					              display: true,
					              text: 'Male',
					              color: '#3381fc'
					            },
					            ticks: {
					              color: '#3381fc'
					            },
					            grid: {
					              drawOnChartArea: true
					            }
				          	},
					        y1: {
					            type: 'linear',
					            position: 'right',
					            title: {
					              display: true,
					              text: 'Female',
					              color: '#fc3381'
					            },
					            ticks: {
					              color: '#fc3381'
					            },
					            grid: {
					              drawOnChartArea: false
					            }
				          	}
				        }*/
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


function empbyGen(){

	var dateperiod = "";
	var employee = "";


	$.ajax({
			type: "POST",
	    url : module_path+'/get_data_empbyGen',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,		
	    dataType: "JSON",
	    success: function(data)
	    {
				if(data != false){
					
						const ctx = document.getElementById('empby_gen').getContext('2d');

				    new Chart(ctx, {
				      type: 'pie',
				      data: {
				        labels: ['Gen X', 'Millenial', 'Gen Z', 'Boomer'],
				        datasets: [{
				          label: 'Generation',
				          data: [data.ttl_gen_x, data.ttl_gen_mill, data.ttl_gen_z, data.ttl_boomer],
				          backgroundColor: [
				            '#3381fc',
				            '#fc3381',
				            '#74dce0',
				            '#f6c23e'
				          ],
				          borderWidth: 2,
				          borderColor: '#fff',
				          hoverOffset: 10
				        }]
				      },
				      options: {
				        responsive: true,
				        maintainAspectRatio: false,
				        plugins: {
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
				          tooltip: {
				            callbacks: {
				              label: function(context) {
				                let label = context.label || '';
				                let value = context.parsed;
				                /*return `${label}: ${value}%`;*/
				                return `${label}: ${value}`;
				              }
				            }
				          },
				          title: {
				            display: false
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


function attPercentage(){

		var dateperiod = "";
		var employee = "";


		$.ajax({
				type: "POST",
		    url : module_path+'/get_data_attPercentage',
				data: { dateperiod: dateperiod, employee: employee },
				cache: false,		
		    dataType: "JSON",
		    success: function(data)
		    {
					if(data != false){
						console.log(data[0].persen_hadir);
						const ctx = document.getElementById('att_percentage').getContext('2d');

				    new Chart(ctx, {
				      type: 'doughnut',
				      data: {
				        labels: ['Attendance rate', 'Absent rate'],
				        datasets: [{
				          data: [data[0].persen_hadir, data[0].persen_tidak_hadir],
				          backgroundColor: ['#3381fc', '#97bffd'],
				          borderWidth: 2,
				          borderColor: '#fff',
				          hoverOffset: 8
				        }]
				      },
				      options: {
				      	responsive: true,
				      	maintainAspectRatio: false,
						    rotation: -90,       // Mulai dari atas (setengah lingkaran)
						    circumference: 180,  // Hanya setengah lingkaran
						    plugins: {
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
						      }
						    }
				        /*rotation: -90 * (Math.PI / 180),
				        circumference: 180 * (Math.PI / 180),
				        cutout: '70%',
				        responsive: true,
				        plugins: {
				          legend: {
				            display: false
				          },
				          tooltip: {
				            callbacks: {
				              label: function(context) {
				                return context.label + ': ' + context.parsed + '%';
				              }
				            }
				          },
				          title: {
				            display: false
				          }
				        }*/
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


function workhrsPercentage(){

	var dateperiod = "";
		var employee = "";


		$.ajax({
				type: "POST",
		    url : module_path+'/get_data_workhrsPercentage',
				data: { dateperiod: dateperiod, employee: employee },
				cache: false,		
		    dataType: "JSON",
		    success: function(data)
		    {
					if(data != false){
						
						const ctx = document.getElementById('workhrs_percentage').getContext('2d');

				    new Chart(ctx, {
				      type: 'doughnut',
				      data: {
				        labels: ['Working hours', 'Idle hours'],
				        datasets: [{
				          data: [data[0].persen_worked, data[0].persen_idle],
				          backgroundColor: ['#1d8084', '#74dce0'],
				          borderWidth: 2,
				          borderColor: '#fff',
				          hoverOffset: 8
				        }]
				      },
				      options: {
				      	responsive: true,
				      	maintainAspectRatio: false,
						    rotation: -90,       // Mulai dari atas (setengah lingkaran)
						    circumference: 180,  // Hanya setengah lingkaran
						    plugins: {
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
						      }
						    }
				        /*rotation: -90 * (Math.PI / 180),
				        circumference: 180 * (Math.PI / 180),
				        cutout: '70%',
				        responsive: true,
				        plugins: {
				          legend: {
				            display: false
				          },
				          tooltip: {
				            callbacks: {
				              label: function(context) {
				                return context.label + ': ' + context.parsed + '%';
				              }
				            }
				          },
				          title: {
				            display: false
				          }
				        }*/
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


function setFilter(){

	monthlyAttSumm();
	attStatistic();

}


$('#fldashemp').on('change', function () { 
 	
 	monthlyAttSumm();
	attStatistic();

});


</script>