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

			monthlyCASummary();
			CAByDiv();
			fppType();
			dataTotal();
			outstandingSett();
			monthlyCAAmount();

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
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					$('span#ttl_ca').html(data.ttl_ca);
					/*var ttl_amount_reimburs = 'Rp. '+data.ttl_amount_reimburs;*/
					var ttl_amount_ca = 'Rp. ' + formatRupiah(data.ttl_amount_ca);
					$('span#ttl_amount_ca').html(ttl_amount_ca);
					
					$('p.total_fpu').html(data.total_fpu);
					$('p.total_fpp').html(data.total_fpp);
					$('p.total_settlement').html(data.total_settlement);

					
				} else {
					var valnull = 0;
					$('span#ttl_ca').html(valnull);
					$('span#ttl_amount_ca').html(valnull);
					
					$('p.total_fpu').html(valnull);
					$('p.total_fpp').html(valnull);
					$('p.total_settlement').html(valnull);

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



	function CAByDiv() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_byDiv',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('cabyDiv').getContext('2d');

					var chartExist = Chart.getChart("cabyDiv"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();
									
					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.division_name,
							datasets: [{
								label: 'Cash Advance',
								data: data.total,
								backgroundColor: [
									'#9EE06F',
									'#9EE06F',
									'#9EE06F',
									'#9EE06F',
									'#9EE06F'
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


	function monthlyCASummary() {

		var fldiv = $("#fldiv option:selected").val();


		$.ajax({
			type: "POST",
			url: module_path + '/get_data_monthlyCASummary',
			data: { fldiv: fldiv},
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('monthly_ca_summ').getContext('2d');
					var chartExist = Chart.getChart("monthly_ca_summ"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();

					new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.request_date, 
							datasets: [
								{
									label: 'Waiting Approval',
									type: 'bar',
									data: data.total_waitingapproval,
									borderColor: '#3381fc',
									backgroundColor: '#3F51B5', /*'#3381fc',*/
									fill: false,
									tension: 0.4,
									yAxisID: 'y1',
									borderRadius: 3
								},
								{
									label: 'Approved',
									type: 'bar',
									data: data.total_approve,
									borderColor: '#81fc33',
									backgroundColor: '#81fc33',/*'#fc3381',*/
									fill: false,
									tension: 0.4,
									yAxisID: 'y1',
									borderRadius: 3
								},
								{
									label: 'Rejected',
									type: 'bar',
									data: data.total_reject,
									borderColor: '#FF4081',
									backgroundColor: '#FF4081',/*'#fc3381',*/
									fill: false,
									tension: 0.4,
									yAxisID: 'y1',
									borderRadius: 3
								},
								{
									label: 'RFU',
									type: 'bar',
									data: data.total_rfu,
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


	function fppType() {
	    var fldiv = $("#fldiv option:selected").val();

	    $.ajax({
	        type: "POST",
	        url: module_path + '/get_data_fppType',
	        data: { fldiv: fldiv },
	        cache: false,
	        dataType: "JSON",
	        success: function (data) {
			    
			    var chartExist = Chart.getChart("fppType");
			    if (chartExist) chartExist.destroy();

			    if (data && data.length > 0) {
			        const labels = data.map(item => item.label);
			        const values = data.map(item => item.value);

			        const ctx = document.getElementById('fppType').getContext('2d');

			        new Chart(ctx, {
			            type: 'pie',
			            data: {
			                labels: labels,
			                datasets: [{
			                    label: 'FPP Type',
			                    data: values,
			                    backgroundColor: [
			                        '#D0E3FF',
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
			                        formatter: (value) => {
			                            return value === 0 ? '' : value;
			                        },
			                        color: '#fff',
			                        font: { size: 10 }
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
			                                let label = context.label || '';
			                                let value = context.parsed;
			                                return `${label}: ${value}`;
			                            }
			                        }
			                    },
			                    title: { display: false }
			                }
			            },
			            plugins: [ChartDataLabels]
			        });
			    } else {
			        // kosongkan canvas chart
			        const ctx = document.getElementById('fppType').getContext('2d');
			        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);

			        // optional: tampilkan text "No Data"
			        ctx.font = "12px Arial";
			        ctx.fillStyle = "#666";
			        ctx.textAlign = "center";
			        ctx.fillText("No Data", ctx.canvas.width / 2, ctx.canvas.height / 2);
			    }
			},
	        error: function (jqXHR, textStatus, errorThrown) {
	            bootbox.dialog({
	                title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
	                message: jqXHR.responseText,
	                buttons: {
	                    confirm: { label: 'Ok', className: 'btn blue' }
	                }
	            });
	        }
	    });
	}


	function outstandingSett() {
	    var fldiv = $("#fldiv option:selected").val();

	    $.ajax({
	        type: "POST",
	        url: module_path + '/get_data_outstandingTrend',
	        data: { fldiv: fldiv },
	        cache: false,
	        dataType: "JSON",
	        success: function (data) {
	            if (data != false) {

	                const ctx = document.getElementById('outstandingSett').getContext('2d');

	                var chartExist = Chart.getChart("outstandingSett"); // <canvas> id
	                if (chartExist != undefined)
	                    chartExist.destroy();

	                const barChart = new Chart(ctx, {
	                    type: 'bar',
	                    data: {
	                        labels: data.periode, // bulan (2025-01, 2025-02, dst)
	                        datasets: [
	                            {
	                                label: 'Jumlah CA Belum Settlement',
	                                type: 'bar',
	                                data: data.belum_settlement,
	                                backgroundColor: '#FED24B',
	                                borderRadius: 3,
	                                yAxisID: 'y'
	                            },
	                            {
	                                label: 'Outstanding Amount (Rp)',
	                                type: 'line',
	                                data: data.outstanding,
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
	                                color: '#fff',
								    textStrokeColor: '#000', 
								    textStrokeWidth: 1,
								    font: {
								        size: 14,
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



	

	function monthlyCAAmount() {

		var fldiv = $("#fldiv option:selected").val();



		$.ajax({
			type: "POST",
			url: module_path + '/get_data_monthlyCAAmount',
			data: { fldiv: fldiv },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('monthly_ca_amount').getContext('2d');

					var chartExist = Chart.getChart("monthly_ca_amount"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.periode,
							
							datasets: [{
									label: 'FPU',
									data: data.nominal_raw_fpu,
									backgroundColor: Array(12).fill('#081F5C'),
									borderRadius: 3
								},
								{
									label: 'FPP',
									data: data.nominal_raw_fpp,
									backgroundColor: Array(12).fill('#FED24B'),
									borderRadius: 3
								}
							]
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
			                        /*color: '#fff',
			                        font: {
			                            size: 8,
			                        }*/
			                        color: '#fff',
								    textStrokeColor: '#000',
								    textStrokeWidth: 2,
								    font: {
								        size: 9,
								        weight: 'bold'
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


	function setFilter() {

		monthlyCASummary();
		CAByDiv();
		outstandingSett();
		fppType();
		dataTotal();
		monthlyCAAmount();
	}


	$('#fldiv').on('change', function () {

		monthlyCASummary();
		CAByDiv();
		outstandingSett();
		fppType();
		dataTotal();
		monthlyCAAmount();

	});


</script>

<script>
	

</script>