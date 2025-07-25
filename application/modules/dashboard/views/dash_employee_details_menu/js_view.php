<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->


<script type="text/javascript">
	var module_path = "<?php echo base_url($folder_name); ?>"; //for save method string

	$(document).ready(function () {
		$(function () {

			/*$('#fldashemp').select2({
		        width: 'resolve', // atau bisa diganti 'style' atau '100%'
		        placeholder: "Select Employee",
		        allowClear: true
		    });*/

			empbyDivGender();
			empbyStatus();
			empbyGen();
			empbyMaritalStatus();
			projectSummary();
			dataTotal();

		});
	});


	function dataTotal() {

		var fldiv = $("#fldiv option:selected").val();
		

		$.ajax({
			type: "POST",
			url: module_path + '/get_data_total',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					$('span.ttl_reguler').html(data.total_reguler);
					$('span.ttl_shift').html(data.total_shift);
					$('span.ttl_managerial').html(data.total_managerial);
					$('span.ttl_nonmanagerial').html(data.total_nonmanagerial);
					$('p.ttl_grade_a').html(data.total_grade_a);
					$('p.ttl_grade_b').html(data.total_grade_b);
					$('p.ttl_grade_c').html(data.total_grade_c);
					$('p.ttl_grade_d').html(data.total_grade_d);

					
				} else {
					var valnull = 0;
					$('span.ttl_reguler').html(valnull);
					$('span.ttl_shift').html(valnull);
					$('span.ttl_managerial').html(valnull);
					$('span.ttl_nonmanagerial').html(valnull);
					$('p.ttl_grade_a').html(valnull);
					$('p.ttl_grade_b').html(valnull);
					$('p.ttl_grade_c').html(valnull);
					$('p.ttl_grade_d').html(valnull);

				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
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



	function empbyStatus() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_empStatus',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('empby_status').getContext('2d');

					var chartExist = Chart.getChart("empby_status"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();
									
					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.status,
							datasets: [{
								label: 'Employee',
								data: data.total,
								backgroundColor: [
									'#74DCE0',
									'#74DCE0',
									'#74DCE0',
									'#74DCE0',
									'#74DCE0'
								],
								borderRadius: 3
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							plugins: {
								datalabels: {
			                        formatter: (value, context) => {
			                            /*let percentage = (value / context.chart._metasets
			                            [context.datasetIndex].total * 100)
			                                .toFixed(2) + '%';*/
			                            /*return percentage + '\n' + value;*/
			                            if (parseFloat(value) === 0) {
								            return ''; // tidak ditampilkan
								        }
			                            return value;
			                        },
			                        color: '#fff',
			                        font: {
			                            size: 12,
			                        }
			                    },
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
						,plugins: [ChartDataLabels]
					});

				} else {


				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
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


	function projectSummary() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_projectSummary',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('project_summary').getContext('2d');

					var chartExist = Chart.getChart("project_summary"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const rawData = [
						{ label: 'Open', data: data.total_open, backgroundColor: '#D9F103', borderRadius: 3 },
						{ label: 'In Progress', data: data.total_inprogress, backgroundColor: '#FF99DC', borderRadius: 3 },
						{ label: 'Done', data: data.total_closed, backgroundColor: '#D2C7FF', borderRadius: 3 },
				
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
							
							labels: data.division_name,
							datasets: percentageData
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							plugins: {
								tooltip: {
									callbacks: {
										label: function (context) {
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
			error: function (jqXHR, textStatus, errorThrown) {
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


	function empbyDivGender() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_empbyDivGender',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('empby_div_gender').getContext('2d');
					var chartExist = Chart.getChart("empby_div_gender"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.divisions, /*['HRGA', 'IT', 'Marketing'],*/
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
										label: function (context) {
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
			error: function (jqXHR, textStatus, errorThrown) {
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


	function empbyGen() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_empbyGen',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('empby_gen').getContext('2d');
					var chartExist = Chart.getChart("empby_gen"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'pie',
						data: {
							labels: ['Gen X', 'Millenial', 'Gen Z', 'Boomer'],
							datasets: [{
								label: 'Generation',
								data: [data.ttl_gen_x, data.ttl_gen_mill, data.ttl_gen_z, data.ttl_boomer],
								backgroundColor: [
									'#FED24B',
									'#38406F',
									'#74DCE0',
									'#D9CAAA'
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
										label: function (context) {
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
			error: function (jqXHR, textStatus, errorThrown) {
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


	function empbyMaritalStatus() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_empbyMaritalStatus',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('empby_maritalStatus').getContext('2d');
					var chartExist = Chart.getChart("empby_maritalStatus"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'pie',
						data: {
							labels: ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'],
							datasets: [{
								label: 'Generation',
								data: [data.ttl_tk0, data.ttl_tk1, data.ttl_tk2, data.ttl_tk3,data.ttl_k0, data.ttl_k1, data.ttl_k2, data.ttl_k3],
								backgroundColor: [
									'#99B7F5',
									'#267F53',
									'#F5793B',
									'#F296BD',
									'#FCCA59',
									'#B9D440',
									'#BC9BF3',
									'#736DF9'
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
										label: function (context) {
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
			error: function (jqXHR, textStatus, errorThrown) {
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


	function setFilter() {

		empbyDivGender();
		empbyStatus();
		empbyGen();
		empbyMaritalStatus();
		projectSummary();
		dataTotal();
		
	}


	$('#fldiv').on('change', function () {

		empbyDivGender();
		empbyStatus();
		empbyGen();
		empbyMaritalStatus();
		projectSummary();
		dataTotal();

	});


</script>

<script>
	

</script>