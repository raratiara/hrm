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
			dataTotal();

		});
	});


	function dataTotal() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_total',
			data: { fldiv: fldiv },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					$('span#ttlrequest').html(data.ttl_request);

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
			data: { fldiv: fldiv },
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
						, plugins: [ChartDataLabels]
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
			data: { fldiv: fldiv },
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
									'#FED24B',
									'#38406F',
									'#BC9BF3',
									'#D9CAAA',
									'#D48331',
									'#B9D440',
									'#74DCE0',
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
			data: { fldiv: fldiv },
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
									'#FED24B',
									'#BC9BF3',
									'#D9CAAA',
									'#D48331',
									'#74DCE0',
									'#38406F'
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
			data: { fldiv: fldiv },
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
									'#FED24B',
									'#D48331',
									'#38406F',
									'#D9CAAA'/*,
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

		openPostionByDiv();
		byJobLevel();
		byStatusPengajuan();
		byStatusEmployee();
		dataTotal();

	}


	$('#fldiv').on('change', function () {

		openPostionByDiv();
		byJobLevel();
		byStatusPengajuan();
		byStatusEmployee();
		dataTotal();

	});


</script>

<script>


</script>