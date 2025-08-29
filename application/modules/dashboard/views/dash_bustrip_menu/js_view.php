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

			dataTotal();
			bustripbyDiv();
			costbyType();
			monthlyTripSummary();

		});
	});


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
			data: { fldiv: fldiv },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {
console.log(data);
					var ttl_budget = 'Rp. ' + formatRupiah(data.ttl_budget);

					$('span#ttl_trip').html(data.ttl_trip);
					$('span#ttl_budget').html(ttl_budget);
					$('span.total_waitingapproval').html(data.total_waitingapproval);
					$('span.total_approved').html(data.total_approved);
					$('span.total_rejected').html(data.total_rejected);
					$('span.avg_days').html(data.avgDays);
					var txtdays ='';
					if(data.avgDays == 1){
						var txtdays = ' day';
					}else if(data.avgDays > 1){
						var txtdays = ' days';
					}
					$('span.txtdays').html(txtdays);
					
				} else {
					var valnull = 0;
					$('span#ttl_trip').html(valnull);
					$('span#ttl_budget').html(valnull);
					$('span.total_waitingapproval').html(valnull);
					$('span.total_approved').html(valnull);
					$('span.total_rejected').html(valnull);
					$('span.avg_days').html(valnull);
					
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


	function bustripbyDiv() {

		var fldiv = $("#fldiv option:selected").val();



		$.ajax({
			type: "POST",
			url: module_path + '/get_data_bustripbyDiv',
			data: { fldiv: fldiv },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('bustripbyDiv').getContext('2d');

					var chartExist = Chart.getChart("bustripbyDiv"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.division_name,
							datasets: [{
								label: 'Trip',
								data: data.total,
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


	function costbyType() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_costbyType',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('costbyType').getContext('2d');
					var chartExist = Chart.getChart("costbyType"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'pie',
						data: {
							labels: data.type_name,
							datasets: [{
								label: 'Generation',
								data: data.total,
								backgroundColor: [
									'#FED24B',
									'#8ECAE6',
									'#219EBC',
									'#126782'
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
			                            //return parseInt(value);
			                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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


	function monthlyTripSummary() {
	    var fldiv = $("#fldiv option:selected").val();

	    $.ajax({
	        type: "POST",
	        url: module_path + '/get_data_monthlyTripSummary',
	        data: { fldiv: fldiv },
	        cache: false,
	        dataType: "JSON",
	        success: function (data) {
	            if (data != false) {

	                const ctx = document.getElementById('monthlyTripSummary').getContext('2d');

	                var chartExist = Chart.getChart("monthlyTripSummary"); // <canvas> id
	                if (chartExist != undefined)
	                    chartExist.destroy();

	                const barChart = new Chart(ctx, {
	                    type: 'bar',
	                    data: {
	                        labels: data.periode, // bulan (2025-01, 2025-02, dst)
	                        datasets: [
	                            {
	                                // label: 'Trip',
	                                // type: 'line',
	                                // data: data.jumlah_trip,
	                                // backgroundColor: '#FED24B',
	                                // borderRadius: 3,
	                                // yAxisID: 'y'

	                                label: 'Trip',
								    type: 'line',
								    data: data.jumlah_trip,
								    borderColor: '#FED24B',
								    backgroundColor: '#FED24B',
								    borderWidth: 2,
								    tension: 0.3,   // <-- bikin garis halus
								    fill: false,
								    yAxisID: 'y',
								    borderDash: [4, 4]  // <-- bikin putus-putus

	                            },
	                            {
	                                label: 'Cost',
	                                type: 'line',
	                                data: data.total_cost, 
	                                borderColor: '#38406F',
	                                backgroundColor: '#38406F',
	                                borderWidth: 2,
	                                tension: 0.3,
	                                fill: false,
	                                yAxisID: 'y1'

	                            }
	                        ]
	                    },
	                    options: {
	                        responsive: true,
	                        maintainAspectRatio: false,
	                        plugins: {
	                            datalabels: {
	                                formatter: (value, context) => {
	                                    if (parseFloat(value) === 0) {
	                                        return ''; // tidak ditampilkan
	                                    }
	                                    //return value;
	                                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	                                },
	                                /*color: '#333',
	                                font: {
	                                    size: 14,
	                                    weight: 'bold'
	                                }*/
	                                color: '#000',  //fff
								    textStrokeColor: '#fff',   //000
								    textStrokeWidth: 1,
								    font: {
								        size: 12,
								        weight: 'bold'
								    }
	                            },
	                            legend: {
	                                display: true,
	                                position: 'top',
	                                labels: {
	                                    font: { size: 11 }
	                                }
	                            },
	                            tooltip: {
	                                backgroundColor: '#333',
	                                titleColor: '#fff',
	                                bodyColor: '#fff',
	                                padding: 10,
	                                borderRadius: 6,
	                                callbacks: {
	                                    label: function (context) {
	                                        let value = context.raw;
	                                        if (context.dataset.label.includes("Rp")) {
	                                            return context.dataset.label + ": Rp " + new Intl.NumberFormat().format(value);
	                                        }
	                                        return context.dataset.label + ": " + value;
	                                    }
	                                }
	                            }
	                        },
	                        scales: {
	                            y: {
	                                beginAtZero: true,
	                                position: 'left',
	                                title: {
	                                    display: true,
	                                    text: 'Jumlah CA'
	                                },
	                                grid: {
	                                    color: '#eee'
	                                },
	                                ticks: {
	                                	stepSize: 1,   // <-- Biar kelipatan 1
	                                    color: '#666',
	                                    font: { size: 10 }
	                                }
	                            },
	                            y1: {
	                                beginAtZero: true,
	                                position: 'right',
	                                title: {
	                                    display: true,
	                                    text: 'Outstanding Amount (Rp)'
	                                },
	                                grid: {
	                                    drawOnChartArea: false
	                                },
	                                ticks: {
	                                    color: '#38406F',
	                                    font: { size: 10 },
	                                    callback: function (value) {
	                                        //return "Rp " + (value / 1000000) + "M"; // tampilkan singkat juta
	                                        return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	                                    }
	                                }
	                            },
	                            x: {
	                                grid: { display: false },
	                                ticks: {
	                                    color: '#666',
	                                    font: { size: 10 }
	                                }
	                            }
	                        }
	                    },
	                    plugins: [ChartDataLabels]
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


	function setFilter() {

		dataTotal();
		bustripbyDiv();
		costbyType();
		monthlyTripSummary();

	}


	$('#fldiv').on('change', function () {

		dataTotal();
		bustripbyDiv();
		costbyType();
		monthlyTripSummary();

	});


</script>

<script>
	

</script>