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

	$(document).ready(function() {
		$(function() {

			/*$('#fldashemp').select2({
		        width: 'resolve', // atau bisa diganti 'style' atau '100%'
		        placeholder: "Select Employee",
		        allowClear: true
		    });*/

			monthlyReimbSummary();
			reimbByDiv();
			reimbursFor();
			dataTotal();
			reimbBySubtype();
			monthlyReimbAmount();

		});
	});


	/*function formatRupiah(angka) {
	    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}*/

	function formatRupiah(angka) {
		if (angka == null || angka === "") return "0,00";

		// pastikan angka float (hilangkan karakter non angka dulu)
		let number = parseFloat(angka.toString().replace(/[^0-9.-]/g, ""));
		if (isNaN(number)) number = 0;

		// fixed 2 decimal → diganti koma
		let parts = number.toFixed(2).toString().split(".");

		// format ribuan
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");

		return parts.join(",");
	}





	function dataTotal() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_total',
			data: {
				fldiv: fldiv
			},
			cache: false,
			dataType: "JSON",
			success: function(data) {
				if (data != false) {

					$('span#ttl_reimburs').html(data.ttl_reimburs);
					/*var ttl_amount_reimburs = 'Rp. '+data.ttl_amount_reimburs;*/
					var ttl_amount_reimburs = 'Rp. ' + formatRupiah(data.ttl_amount_reimburs);
					$('span#ttl_amount_reimburs').html(ttl_amount_reimburs);

					$('p.total_rawatinap').html(data.total_rawatinap);
					$('p.total_kacamata').html(data.total_kacamata);
					$('p.total_persalinan').html(data.total_persalinan);
					$('p.total_rawatjalan').html(data.total_rawatjalan);


				} else {
					var valnull = 0;
					$('span#ttl_reimburs').html(valnull);
					$('span#ttl_amount_reimburs').html(valnull);

					$('p.total_rawatinap').html(valnull);
					$('p.total_kacamata').html(valnull);
					$('p.total_persalinan').html(valnull);
					$('p.total_rawatjalan').html(valnull);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
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



	function reimbByDiv() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_byDiv',
			data: {
				fldiv: fldiv
			},
			cache: false,
			dataType: "JSON",
			success: function(data) {
				if (data != false) {

					const ctx = document.getElementById('reimbyDiv').getContext('2d');


					// bikin gradient hijau pastel (atas -> bawah)
					const h = ctx.canvas.height || 300;
					const gradientGreen = ctx.createLinearGradient(0, 0, 0, h);
					gradientGreen.addColorStop(0, '#d3ebc3'); // atas
					gradientGreen.addColorStop(1, '#f3faef'); // bawah (lebih light)

					var chartExist = Chart.getChart("reimbyDiv"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();



					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.division_name,
							datasets: [{
								label: 'Reimbursement',
								data: data.total,
								backgroundColor: gradientGreen,
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
										font: {
											size: 10
										}
									}
								},
								x: {
									grid: {
										display: false
									},
									ticks: {
										color: '#666',
										font: {
											size: 10
										}
									}
								}
							}
						},
						plugins: [ChartDataLabels]
					});

				} else {


				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
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
			data: {
				fldiv: fldiv
			},
			cache: false,
			dataType: "JSON",
			success: function(data) {
				if (data != false) {

					const ctx = document.getElementById('project_summary').getContext('2d');

					var chartExist = Chart.getChart("project_summary"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const rawData = [{
							label: 'Open',
							data: data.total_open,
							backgroundColor: '#D9F103',
							borderRadius: 3
						},
						{
							label: 'In Progress',
							data: data.total_inprogress,
							backgroundColor: '#FF99DC',
							borderRadius: 3
						},
						{
							label: 'Done',
							data: data.total_closed,
							backgroundColor: '#D2C7FF',
							borderRadius: 3
						},

					];

					// Convert data to 100% scale per group
					const percentageData = rawData.map(dataset => ({
						...dataset
					}));
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
										label: function(context) {
											/*return `${context.dataset.label}: ${context.parsed.y}%`;*/
											return `${context.dataset.label}: ${context.parsed.y}`;
										}
									}
								},
								legend: {
									labels: {
										font: {
											size: 8 // kecilkan ukuran legend text
										},
										boxWidth: 12, // kecilkan ukuran kotak warna
										boxHeight: 8, // atur tinggi (Chart.js 4.x ke atas)
										borderRadius: 4, // ubah jadi bulat (opsional)
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
										font: {
											size: 10
										}
									},
									grid: {
										display: false
									}
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
										font: {
											size: 10
										}
									},
									grid: {
										color: '#eee'
									}
								}
							}
						},
						plugins: [ChartDataLabels]
					});

				} else {

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
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


	function monthlyReimbSummary() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_monthlyReimbSummary',
			data: {
				fldiv: fldiv
			},
			cache: false,
			dataType: "JSON",
			success: function(data) {
				if (data != false) {

					const ctx = document.getElementById('monthly_reimb_summ').getContext('2d');
					var chartExist = Chart.getChart("monthly_reimb_summ"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					const chartHeight = ctx.canvas.height;

					const gradientBlue = ctx.createLinearGradient(0, 0, 0, chartHeight);
					gradientBlue.addColorStop(0, "#9ccef9");
					gradientBlue.addColorStop(1, "#EFF5F9");

					const gradientYellow = ctx.createLinearGradient(0, 0, 0, chartHeight);
					gradientYellow.addColorStop(0, "#FED24B");
					gradientYellow.addColorStop(1, "#FFF4D2");

					const gradientPink = ctx.createLinearGradient(0, 0, 0, chartHeight);
					gradientPink.addColorStop(0, "#fff1f2");
					gradientPink.addColorStop(1, "#fecdea");

					new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.date_reimbursment,
							/*['HRGA', 'IT', 'Marketing'],*/
							datasets: [{
									label: 'Waiting Approval',
									type: 'bar',
									data: data.total_waitingapproval,
									backgroundColor: gradientBlue,
									borderRadius: 6
								},
								{
									label: 'Approved',
									type: 'bar',
									data: data.total_approve,
									backgroundColor: gradientYellow,
									borderRadius: 6
								},
								{
									label: 'Rejected',
									type: 'bar',
									data: data.total_reject,
									backgroundColor: gradientPink,
									borderRadius: 6
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
											size: 8 // kecilkan ukuran legend text
										},
										boxWidth: 12, // kecilkan ukuran kotak warna
										boxHeight: 8, // atur tinggi (Chart.js 4.x ke atas)
										borderRadius: 4, // ubah jadi bulat (opsional)
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
							}
							/*,
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
			error: function(jqXHR, textStatus, errorThrown) {
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


	function reimbursFor() {
		var fldiv = $("#fldiv option:selected").val();

		$.ajax({
			type: "POST",
			url: module_path + '/get_data_reimFor',
			data: {
				fldiv: fldiv
			},
			cache: false,
			dataType: "JSON",
			success: function(data) {
				if (data && data.length > 0) {
					const labels = data.map(item => item.label);
					const values = data.map(item => item.value);

					const ctx = document.getElementById('reimFor').getContext('2d');
					var chartExist = Chart.getChart("reimFor");
					if (chartExist) chartExist.destroy();

					new Chart(ctx, {
						type: 'pie',
						data: {
							labels: labels,
							datasets: [{
								label: 'Reimburse For',
								data: values,
								backgroundColor: [
									'#FED24B',
									'#9dc1ff',
									'#cdf4a7',
									'#D9CAAA',
									'#74DCE0',
									'#F28482',
									'#84A59D',
									'#F6BD60'
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
									formatter: (value) => {
										return value === 0 ? '' : value;
									},
									color: '#fff',
									font: {
										size: 10
									}
								},
								legend: {
									labels: {
										font: {
											size: 8
										},
										boxWidth: 12,
										boxHeight: 8,
										borderRadius: 4,
										usePointStyle: true
									},
									position: 'bottom'
								},
								tooltip: {
									callbacks: {
										label: function(context) {
											let label = context.label || '';
											let value = context.parsed;
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
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				bootbox.dialog({
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



	function reimbursFor_old() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_reimFor',
			data: {
				fldiv: fldiv
			},
			cache: false,
			dataType: "JSON",
			success: function(data) {
				if (data != false) {

					const ctx = document.getElementById('reimFor').getContext('2d');
					var chartExist = Chart.getChart("reimFor"); // <canvas> id
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
											size: 8 // kecilkan ukuran legend text
										},
										boxWidth: 12, // kecilkan ukuran kotak warna
										boxHeight: 8, // atur tinggi (Chart.js 4.x ke atas)
										borderRadius: 4, // ubah jadi bulat (opsional)
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
						},
						plugins: [ChartDataLabels]
					});



				} else {



				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
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
			data: {
				fldiv: fldiv
			},
			cache: false,
			dataType: "JSON",
			success: function(data) {
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
								data: [data.ttl_tk0, data.ttl_tk1, data.ttl_tk2, data.ttl_tk3, data.ttl_k0, data.ttl_k1, data.ttl_k2, data.ttl_k3],
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
											size: 8 // kecilkan ukuran legend text
										},
										boxWidth: 12, // kecilkan ukuran kotak warna
										boxHeight: 8, // atur tinggi (Chart.js 4.x ke atas)
										borderRadius: 4, // ubah jadi bulat (opsional)
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
						},
						plugins: [ChartDataLabels]
					});



				} else {



				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
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


	function reimbBySubtype() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_bySubtype',
			data: {
				fldiv: fldiv
			},
			cache: false,
			dataType: "JSON",
			success: function(data) {
				if (data != false) {

					const ctx = document.getElementById('reimbySubtype').getContext('2d');

					var chartExist = Chart.getChart("reimbySubtype"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					const h = ctx.canvas.height || 300;

					const gradientYellow = ctx.createLinearGradient(0, 0, 0, h);
					gradientYellow.addColorStop(0, '#fcda73'); // atas (kuning lebih kuat)
					gradientYellow.addColorStop(1, '#fff6da'); // bawah (lebih soft/cream)

					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.subtypename,
							datasets: [{
								label: 'Reimbursement',
								data: data.total,
								backgroundColor: gradientYellow,
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
										font: {
											size: 10
										}
									}
								},
								x: {
									grid: {
										display: false
									},
									ticks: {
										color: '#666',
										font: {
											size: 10
										}
									}
								}
							}
						},
						plugins: [ChartDataLabels]
					});

				} else {


				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
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


	function monthlyReimbAmount() {

		var fldiv = $("#fldiv option:selected").val();



		$.ajax({
			type: "POST",
			url: module_path + '/get_data_monthlyReimbAmount',
			data: {
				fldiv: fldiv
			},
			cache: false,
			dataType: "JSON",
			success: function(data) {
				if (data != false) {

					const ctx = document.getElementById('monthly_reimb_amount').getContext('2d');

					var chartExist = Chart.getChart("monthly_reimb_amount"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.periode,

							datasets: [{
								label: 'Amount',
								data: data.nominal_raw,
								backgroundColor: Array(12).fill('#b8cbff'),
								borderRadius: 3
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							plugins: {
								datalabels: {
									// formatter: (value, context) => {
									//     /*let percentage = (value / context.chart._metasets
									//     [context.datasetIndex].total * 100)
									//         .toFixed(2) + '%';*/
									//     /*return percentage + '\n' + value;*/
									//     if (parseFloat(value) === 0) {
									//         return ''; // tidak ditampilkan
									//     }
									//     return value;
									// },
									formatter: (value, context) => {
										if (parseFloat(value) === 0) {
											return ''; // tidak ditampilkan
										}
										// Format angka pakai titik ribuan
										return parseFloat(value).toLocaleString('id-ID', {
											minimumFractionDigits: 2,
											maximumFractionDigits: 2
										});
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
										font: {
											size: 10
										}
									}
								},
								x: {
									grid: {
										display: false
									},
									ticks: {
										color: '#666',
										font: {
											size: 10
										}
									}
								}
							}
						},
						plugins: [ChartDataLabels]
					});

				} else {


				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
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

		monthlyReimbSummary();
		reimbByDiv();
		reimbBySubtype();
		reimbursFor();
		empbyMaritalStatus();
		projectSummary();
		dataTotal();
		monthlyReimbAmount();
	}


	$('#fldiv').on('change', function() {

		monthlyReimbSummary();
		reimbByDiv();
		reimbBySubtype();
		reimbursFor();
		empbyMaritalStatus();
		projectSummary();
		dataTotal();
		monthlyReimbAmount();

	});
</script>

<script>


</script>