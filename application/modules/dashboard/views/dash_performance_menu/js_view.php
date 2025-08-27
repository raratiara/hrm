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

			topPerformers();
			achieveTarget();
			softskillAnalysis();
			divScore();
			//dataTotal();

		});
	});


	/*function dataTotal() {

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

	}*/



	function achieveTarget() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_achieveTarget',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('chartAchieveTarget').getContext('2d');

					var chartExist = Chart.getChart("chartAchieveTarget"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();
									
					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.division_name,
							datasets: [{
								label: 'Average',
								data: data.total,
								backgroundColor: [
									'#FED24B',
									'#FED24B',
									'#FED24B',
									'#FED24B',
									'#FED24B'
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
			                            size: 10,
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


	function divScore_old() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_divScore',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('chartDivScore').getContext('2d');

					var chartExist = Chart.getChart("chartDivScore"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const rawData = [
						{ label: 'A', data: data.total_a, backgroundColor: '#38406F', borderRadius: 3 },
						{ label: 'B', data: data.total_b, backgroundColor: '#FED24B', borderRadius: 3 },
						{ label: 'C', data: data.total_c, backgroundColor: '#D9CAAA', borderRadius: 3 },
						{ label: 'D', data: data.total_d, backgroundColor: '#BC9BF3', borderRadius: 3 },
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
							indexAxis: 'y',  // <== ini yang bikin horizontal
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
			                            return parseInt(value);
			                        },
			                        color: '#fff',
			                        font: {
			                            size: 10,
			                        }
			                    },
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
					            x: { // sekarang total di bawah
					                stacked: true,
					                beginAtZero: true,
					                ticks: {
					                    stepSize: 1,
					                    callback: (value) => value,
					                    color: '#333',
					                    font: { size: 10 }
					                },
					                grid: { color: '#eee' }
					            },
					            y: { // sekarang division di kiri
					                stacked: true,
					                ticks: {
					                    color: '#333',
					                    font: { size: 10 }
					                },
					                grid: { display: false }
					            }
					        }
						},
						plugins: [ChartDataLabels]
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

	function divScore() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_divScore',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('chartDivScore').getContext('2d');

					var chartExist = Chart.getChart("chartDivScore"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const rawData = [
						{ label: 'A', data: data.total_a, backgroundColor: '#38406F', borderRadius: 3 },
						{ label: 'B', data: data.total_b, backgroundColor: '#FED24B', borderRadius: 3 },
						{ label: 'C', data: data.total_c, backgroundColor: '#D9CAAA', borderRadius: 3 },
						{ label: 'D', data: data.total_d, backgroundColor: '#BC9BF3', borderRadius: 3 },
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
								datalabels: {
			                        formatter: (value, context) => {
			                            /*let percentage = (value / context.chart._metasets
			                            [context.datasetIndex].total * 100)
			                                .toFixed(2) + '%';*/
			                            /*return percentage + '\n' + value;*/
			                            if (parseFloat(value) === 0) {
								            return ''; // tidak ditampilkan
								        }
			                            return parseInt(value);
			                        },
			                        color: '#fff',
			                        font: {
			                            size: 10,
			                        }
			                    },
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
						},
						plugins: [ChartDataLabels]
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


	function topPerformers() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_topPerformers',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('chartTopPerformers').getContext('2d');
					var chartExist = Chart.getChart("chartTopPerformers"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.emp, /*['HRGA', 'IT', 'Marketing'],*/
							datasets: [
								{
									label: 'Hardskill',
									type: 'bar',
									data: data.hardskill,
									borderColor: '#343851',
									backgroundColor: '#343851', /*'#3381fc',*/
									fill: false,
									tension: 0.4,
									yAxisID: 'y1',
									borderRadius: 3
								},
								{
									label: 'Softskill',
									type: 'bar',
									data: data.softskill,
									borderColor: '#FED24B',
									backgroundColor: '#FED24B',/*'#fc3381',*/
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
								datalabels: {
			                        formatter: (value, context) => {
			                            /*let percentage = (value / context.chart._metasets
			                            [context.datasetIndex].total * 100)
			                                .toFixed(2) + '%';*/
			                            /*return percentage + '\n' + value;*/
			                            if (parseFloat(value) === 0) {
								            return ''; // tidak ditampilkan
								        }
			                            return parseInt(value);
			                        },
			                        color: '#fff',
			                        font: {
			                            size: 10,
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
						},
						plugins: [ChartDataLabels]
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



	function softskillAnalysis() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_softskillAnalysis',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('chartsoftskillAnalysis').getContext('2d');
					var chartExist = Chart.getChart("chartsoftskillAnalysis"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'pie',
						data: {
							/*labels: ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'],*/
							labels: data.softskill_name,
							datasets: [{
								label: 'Generation',
								/*data: [data.ttl_tk0, data.ttl_tk1, data.ttl_tk2, data.ttl_tk3,data.ttl_k0, data.ttl_k1, data.ttl_k2, data.ttl_k3],*/
								data: data.total,
								backgroundColor: [
									'#FED24B',
									'#8ECAE6',
									'#219EBC',
									'#126782',
									'#023047',
									'#FFB703',
									'#FB8500',
									'#5DC3AB',
									'#A9D7F3',
									'#FF374B',
									'#D1C4E9',
									'#6D2D59'
									// '#D48331',
									// '#B9D440',
									// '#74DCE0',
									// '#38406F',
									// '#D9CAAA',
									// '#D48331',
									// '#B9D440',
									// '#74DCE0',
									// '#38406F'
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
								datalabels: {
			                        formatter: (value, context) => {
			                            /*let percentage = (value / context.chart._metasets
			                            [context.datasetIndex].total * 100)
			                                .toFixed(2) + '%';*/
			                            /*return percentage + '\n' + value;*/
			                            if (parseFloat(value) === 0) {
								            return ''; // tidak ditampilkan
								        }
			                            return parseInt(value);
			                        },
			                        color: '#fff',
			                        font: {
			                            size: 10,
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
						},
						plugins: [ChartDataLabels]
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

		topPerformers();
		achieveTarget();
		softskillAnalysis();
		divScore();
		//dataTotal();
		
	}


	$('#fldiv').on('change', function () {

		topPerformers();
		achieveTarget();
		softskillAnalysis();
		divScore();
		//dataTotal();

	});


</script>

<script>
	

</script>