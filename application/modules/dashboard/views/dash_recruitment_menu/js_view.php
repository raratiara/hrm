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

		    openPostionByDiv();
		    byJobLevel();
		    byStatusPengajuan();
		    byStatusEmployee();


			empbyDivGender();
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

					$('span.ttlrequest').html(data.ttl_request);
					
				} else {
					var valnull = 0;
					$('span#ttlrequest').html(valnull);

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



	function byJobLevel() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_byJobLevel',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('byJobLevel').getContext('2d');

					var chartExist = Chart.getChart("byJobLevel"); // <canvas> id
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
									'#B26CC4',
									'#B26CC4',
									'#B26CC4',
									'#B26CC4',
									'#B26CC4'
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
									backgroundColor: '#3F51B5', /*'#3381fc',*/
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
									backgroundColor: '#FF4081',/*'#fc3381',*/
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


	function openPostionByDiv() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_openByDiv',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('open_bydiv').getContext('2d');
					var chartExist = Chart.getChart("open_bydiv"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'pie',
						data: {
							/*labels: ['Gen X', 'Millenial', 'Gen Z', 'Boomer'],*/
							labels: data.labels,
							datasets: [{
								label: 'Generation',
								/*data: [data.ttl_gen_x, data.ttl_gen_mill, data.ttl_gen_z, data.ttl_boomer],*/
								data: data.values,
								backgroundColor: [
									'#B99DD9',
									'#F83F98',
									'#00BDC9',
									'#FFC226',
									'#FF6E53',
									'#4378C6',
									'#75D2C1',
									'#FFED76'
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


	function byStatusPengajuan() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_byStatusPengajuan',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('byStatusPengajuan').getContext('2d');
					var chartExist = Chart.getChart("byStatusPengajuan"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'pie',
						data: {
							/*labels: ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'],*/
							labels: data.labels,
							datasets: [{
								label: 'Generation',
								/*data: [data.ttl_tk0, data.ttl_tk1, data.ttl_tk2, data.ttl_tk3,data.ttl_k0, data.ttl_k1, data.ttl_k2, data.ttl_k3],*/
								data: data.values,
								backgroundColor: [
									'#CDB4DB',
									'#FFC8DD',
									'#FFAFCC',
									'#BDE0FE',
									'#A2D2FF',
									'#5784E6'
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


	function byStatusEmployee() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_byStatusEmployee',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('byStatusEmployee').getContext('2d');
					var chartExist = Chart.getChart("byStatusEmployee"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'pie',
						data: {
							/*labels: ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'],*/
							labels: data.labels,
							datasets: [{
								label: 'Generation',
								/*data: [data.ttl_tk0, data.ttl_tk1, data.ttl_tk2, data.ttl_tk3,data.ttl_k0, data.ttl_k1, data.ttl_k2, data.ttl_k3],*/
								data: data.values,
								backgroundColor: [
									'#F8D152',
									'#F7AB3E',
									'#5E6CB3',
									'#91ABDA'/*,
									'#FCCA59',
									'#B9D440',
									'#BC9BF3',
									'#736DF9'*/
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