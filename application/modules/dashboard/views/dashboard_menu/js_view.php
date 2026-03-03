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

			dailyAttSumm();
			monthlyAttSumm();
			attStatistic();
			/*empbyDeptGender();*/
			/*empbyGen();*/
			workLoc();
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

	function getInitials(name){
  if(!name) return "NA";
  const parts = name.trim().split(/\s+/).filter(Boolean);

  // kalau 2 kata: ambil huruf depan kata 1 & 2
  if(parts.length >= 2){
    return (parts[0][0] + parts[1][0]).toUpperCase();
  }

  // kalau 1 kata: ambil 2 huruf pertama
  return parts[0].substring(0,2).toUpperCase();
}


	function dataTotal() {

		var dateperiod = $("#fldashdateperiod").val();
		var employee = $("#fldashemp option:selected").val();
		//alert(dateperiod); 

		$.ajax({
			type: "POST",
			url: module_path + '/get_data_total',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					$('span#ttl_employee').html(data.ttl_emp);
					$('span#ttl_attendance').html(data.ttl_attendance);
					$('span.ttl_earlylogin').html(data.ttl_earlylogin);
					$('span.ttl_latelogin').html(data.ttl_latelogin);
					$('span#ttl_leave').html(data.ttl_leaves);
					$('span#ttl_overtime').html(data.ttl_overtimes);
					$('span#ttl_holidays').html(data.ttl_holidays);


					/*const employees = [
						{
							name: "Diana Putri",
							email: "dianaputri@gmail.com",
							department: "Web Developer",
							present: "100%",
							late: 0,
							img: "https://i.pravatar.cc/40?img=1"
						},
						{
							name: "Budi Santoso",
							email: "budi@gmail.com",
							department: "UI Designer",
							present: "98%",
							late: 1,
							img: "https://i.pravatar.cc/40?img=2"
						}
						
					];*/

					var getUrl = window.location;
					//local=> 
					//var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
					var baseUrl = getUrl.protocol + "//" + getUrl.host;

					const tbody = document.getElementById("employeeBody");
					tbody.innerHTML = ""; // Ini akan menghapus semua baris sebelumnya

					data.topEmp.forEach(emp => {
						const row = document.createElement("tr");

						const hasPhoto = emp.emp_photo && emp.emp_photo.trim() !== "";
const photoUrl = hasPhoto
  ? `${baseUrl}/uploads/employee/${emp.emp_code}/${emp.emp_photo}`
  : "";

const initials = getInitials(emp.full_name);

const avatarHtml = hasPhoto
  ? `<img src="${photoUrl}" alt="profile" onerror="this.style.display='none'; this.insertAdjacentHTML('afterend','<div class=&quot;avatar-initial&quot;>${initials}</div>');" />`
  : `<div class="avatar-initial">${initials}</div>`;

row.innerHTML = `
  <td class="user">
    ${avatarHtml}
    <div>
      <strong>${emp.full_name ?? ''}</strong><br>
      <span>${emp.personal_email ?? ''}</span>
    </div>
  </td>
  <td>${emp.divname ?? ''}</td>
  <td>${emp.total_jam_kerja ?? ''}</td>
  <td>${emp.total_late ?? ''}</td>
`;


						tbody.appendChild(row);
					});


					

				} else {
					var valnull = 0;
					$('span#ttl_employee').html(valnull);
					$('span#ttl_attendance').html(valnull);
					$('span.ttl_earlylogin').html(valnull);
					$('span.ttl_latelogin').html(valnull);
					$('span#ttl_leave').html(valnull);
					$('span#ttl_overtime').html(valnull);
					$('span#ttl_holidays').html(valnull);

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


	function dailyAttSumm() {
	    var dateperiod = $("#fldashdateperiod").val();
	    var employee = $("#fldashemp option:selected").val();

	    $.ajax({
	        type: "POST",
	        url: module_path + '/get_data_dailyAttSumm',
	        data: { dateperiod: dateperiod, employee: employee },
	        cache: false,
	        dataType: "JSON",
	        success: function (data) {
	            if (data != false) {

	            	// ubah 0 jadi null supaya line chart tidak digambar di titik itu
					const absenData = data.total_absen.map(v => v === 0 ? null : v);
					const tidakAbsenData = data.total_tidak_absen.map(v => v === 0 ? null : v);

	                const ctx = document.getElementById('daily_att_summ').getContext('2d');
	                var chartExist = Chart.getChart("daily_att_summ");
	                if (chartExist != undefined) chartExist.destroy();

	                new Chart(ctx, {
	                    type: 'line',
	                    data: {
	                        labels: data.date_attendance,
	                        datasets: [
	                            {
	                                label: 'Absen',
	                                /*data: data.total_absen,*/
	                                data: absenData,
	                                borderColor: '#95c7f3',
	                                backgroundColor: '#95c7f3',
	                                fill: false,
	                                borderWidth: 2,
	                                tension: 0.5,
	                                pointRadius: 0,
	                                pointHoverRadius: 4,
	                                spanGaps: true,
	                                yAxisID: 'y1'
	                            },
	                            {
	                                label: 'Tidak Absen',
	                                /*data: data.total_tidak_absen,*/
	                                data: tidakAbsenData,
	                                borderColor: '#fbe162',
	                                backgroundColor: '#fbe162',
	                                fill: false,
	                                borderWidth: 2,
	                                tension: 0.5,
	                                pointRadius: 0,
	                                pointHoverRadius: 4,
	                                spanGaps: true,
	                                yAxisID: 'y1'
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
	                                formatter: (value) => {
	                                    if (parseFloat(value) === 0) {
	                                        return ''; // hide kalau 0
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
	                                    font: { size: 8 },
	                                    boxWidth: 12,
	                                    boxHeight: 8,
	                                    borderRadius: 4,
	                                    usePointStyle: true
	                                },
	                                position: 'bottom'
	                            },
	                            tooltip: {
	                                callbacks: {
	                                    label: function (context) {
	                                        return context.dataset.label + ': ' + context.formattedValue;
	                                    }
	                                }
	                            }
	                        },
	                        scales: {
	                            x: {
	                                /*ticks: {
	                                    autoSkip: true,
	                                    maxTicksLimit: 10,
	                                    maxRotation: 0,
	                                    minRotation: 0
	                                },*/
	                                grid: {
	                                    display: true
	                                }
	                            },
	                            y1: {
	                                grid: {
	                                    display: true
	                                }
	                            }
	                        }
	                    },
	                    plugins: [] // bisa tambahkan ChartDataLabels kalau mau aktifkan
	                });
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


	function monthlyAttSumm() {

		var dateperiod = $("#fldashdateperiod").val();
		var employee = $("#fldashemp option:selected").val();



		$.ajax({
			type: "POST",
			url: module_path + '/get_data_monthlyAttSumm',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('monthly_att_summ').getContext('2d');

					var chartExist = Chart.getChart("monthly_att_summ"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					function makeVerticalGradient(ctx, topColor, bottomColor) {
						const chartHeight = ctx.canvas.height || 300;
						const gradient = ctx.createLinearGradient(0, 0, 0, chartHeight);
						gradient.addColorStop(0, topColor);     // atas
						gradient.addColorStop(1, bottomColor);  // bawah
						return gradient;
					}



					const rawData = [
  {
    label: 'Absen',
    data: data.total_absen,
    backgroundColor: (context) => {
      const c = context.chart.ctx;
      return makeVerticalGradient(c, '#837DEB', '#CFCDFF'); // atas → bawah
    },
    borderRadius: 3
  },
  {
    label: 'Tidak Absen',
    data: data.total_tidak_absen,
    backgroundColor: (context) => {
      const c = context.chart.ctx;
      return makeVerticalGradient(c, '#E2B2ED', '#FBECFF'); // atas → bawah
    },
    borderRadius: 3
  }
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
							labels: data.periode,
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


	function monthlyAttSumm_old() {

		var dateperiod = $("#fldashdateperiod").val();
		var employee = $("#fldashemp option:selected").val();



		$.ajax({
			type: "POST",
			url: module_path + '/get_data_monthlyAttSumm',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

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
									'#FED24B',
									'#FED24B',
									'#FED24B',
									'#FED24B',
									'#FED24B',
									'#FED24B',
									'#FED24B',
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


	function attStatistic() {

		var dateperiod = $("#fldashdateperiod").val();
		var employee = $("#fldashemp option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_attStatistic',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('att_statistic').getContext('2d');

					var chartExist = Chart.getChart("att_statistic"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					// ====== LIMIT POINTS (misal tampilkan 30 hari terakhir) ======
					const MAX_POINTS = 30;

					// labels/hari
					const labelsLimited = data.hari.slice(-MAX_POINTS);

					// semua dataset juga ikut di-slice biar sinkron
					const onWorkLimited     = data.total_on_work_time.slice(-MAX_POINTS);
					const lateLimited       = data.total_late.slice(-MAX_POINTS);
					const leaveLimited      = data.total_leave.slice(-MAX_POINTS);
					const overtimeLimited   = data.total_overtime.slice(-MAX_POINTS);
					const earlyLimited      = data.total_leaving_early.slice(-MAX_POINTS);
					const absentLimited     = data.total_absent.slice(-MAX_POINTS);



					const rawData = [
					{ label: 'On Work Time', data: onWorkLimited,   backgroundColor: '#BFF39D', borderRadius: 3 },
					{ label: 'Late',         data: lateLimited,     backgroundColor: '#FFC0DA', borderRadius: 3 },
					{ label: 'Leave',        data: leaveLimited,    backgroundColor: '#E2B2ED', borderRadius: 3 },
					{ label: 'Overtime',     data: overtimeLimited, backgroundColor: '#FFEF8B', borderRadius: 3 },
					{ label: 'Leaving Early',data: earlyLimited,    backgroundColor: '#CBECFF', borderRadius: 3 },
					{ label: 'No Attendance',data: absentLimited,   backgroundColor: '#837DEB', borderRadius: 3 },
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
							labels: labelsLimited,
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
  grid: { display: false },
  ticks: {
    autoSkip: true,
    maxTicksLimit: 15,          // cuma tampil 8 label
    maxRotation: 45,
    minRotation: 45,
    font: { size: 9 },
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


	function empbyDeptGender() {


		var dateperiod = "";
		var employee = "";


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_empbyDeptGender',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

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

		var dateperiod = "";
		var employee = "";


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_empbyGen',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('empby_gen').getContext('2d');

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

	let workLocationChart; // deklarasi di luar fungsi atau global scope
	function workLoc() {

		var dateperiod = $("#fldashdateperiod").val();
		var employee = $("#fldashemp option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_workLoc',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('workLocation').getContext('2d');

					if (workLocationChart) {
						workLocationChart.destroy();
					}

					workLocationChart = new Chart(ctx, {
						type: 'pie',
						data: {
							labels: ['WFO', 'WFH'],
							datasets: [{
								label: 'Work Location',
								data: [data.ttl_wfo, data.ttl_wfh],
								backgroundColor: [
									'#837DEB',
									'#E2B2ED'
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
			                            size: 12,
			                        }
			                    },
								legend: {
									labels: {
										font: {
											size: 6  // kecilkan ukuran legend text
										},
										boxWidth: 8,        // kecilkan ukuran kotak warna
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

	let attPercentageChart; // deklarasi di luar fungsi atau global scope
	function attPercentage() {

		var dateperiod = $("#fldashdateperiod").val();
		var employee = $("#fldashemp option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_attPercentage',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {
					
					const ctx = document.getElementById('att_percentage').getContext('2d');
					// Hapus chart sebelumnya jika sudah ada
					if (attPercentageChart) {
						attPercentageChart.destroy();
					}

					attPercentageChart = new Chart(ctx, {
						type: 'doughnut',
						data: {
							labels: ['Submit Attendance', 'No Attendance'],
							datasets: [{
								data: [data[0].persen_hadir, data[0].persen_tidak_hadir],
								backgroundColor: ['#8983ED', '#C6C2FD'],
								borderWidth: 2,
								borderColor: '#fff',
								hoverOffset: 8,
								borderRadius: 5
								
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							rotation: -90,       // Mulai dari atas (setengah lingkaran)
							circumference: 180,  // Hanya setengah lingkaran
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
			                            size: 12,
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


	let workhrsPercentageChart; // deklarasi di luar fungsi atau global scope
	function workhrsPercentage() {

		var dateperiod = $("#fldashdateperiod").val();
		var employee = $("#fldashemp option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_workhrsPercentage',
			data: { dateperiod: dateperiod, employee: employee },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('workhrs_percentage').getContext('2d');
					// Hapus chart sebelumnya jika sudah ada
					if (workhrsPercentageChart) {
						workhrsPercentageChart.destroy();
					}

					workhrsPercentageChart = new Chart(ctx, {
						type: 'doughnut',
						data: {
							/*labels: ['Working hours', 'Idle hours'],*/
							labels: ['Working hours'],
							datasets: [{
								data: [data[0].avg_jam_kerja, data[0].sisa],
								backgroundColor: ['#FFD963', '#FFEFC0'],
								borderWidth: 2,
								borderColor: '#fff',
								hoverOffset: 8,
								borderRadius: 5
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							rotation: -90,       // Mulai dari atas (setengah lingkaran)
							circumference: 180,  // Hanya setengah lingkaran
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
			                            size: 12,
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
		dailyAttSumm();
		monthlyAttSumm();
		attStatistic();
		dataTotal();
		workLoc();
		attPercentage();
		workhrsPercentage();

	}


	$('#fldashemp').on('change', function () {
		dailyAttSumm();
		monthlyAttSumm();
		attStatistic();
		dataTotal();
		workLoc();
		attPercentage();
		workhrsPercentage();

	});


</script>

<script>
	

</script>