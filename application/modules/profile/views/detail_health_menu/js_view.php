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

			$('input[name="fldateperiod"]').daterangepicker({
			    autoUpdateInput: false, // <-- ini kuncinya
			    /*locale: {
			        cancelLabel: 'Clear'
			    }*/
			});



			dataTotal();
			getSteps();
			getSleeps();
			vitalSigns();

		});
	});


	// Event saat user memilih tanggal
	$('input[name="fldateperiod"]').on('apply.daterangepicker', function(ev, picker) { 
	    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

	    var flemp = $('#flemp').val();
	    var fldateperiod = $('#fldateperiod').val();
	    //getMaps(fldashemp,fldashperiod);


	    dataTotal(flemp, fldateperiod);
		getSteps(flemp, fldateperiod);
		getSleeps(flemp, fldateperiod);
		vitalSigns(flemp, fldateperiod);
	});

	// Event saat user klik tombol "Cancel" (Clear)
	$('input[name="fldashperiod"]').on('cancel.daterangepicker', function(ev, picker) {
	    $(this).val('');
	});

	
	function dataTotal(empid = '', dateperiod = '') {

		//var flemp = $("#flemp option:selected").val();

		$.ajax({
			type: "POST",
			url: module_path + '/get_data_total',
			data: { flemp: empid, fldateperiod: dateperiod },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {
					$('span#bpm').html(data.bpm);
					$('span#bpm_desc').html(data.bpm_desc);
					$('span#spo2').html(data.spo2);
					$('span#spo2_desc').html(data.spo2_desc);
					$('span#sleep').html(data.sleep_hours);
					$('span#sleep_mins').html(data.sleep_mins);
					$('span#sleep_desc').html(data.sleep_percent+'% quality');
					$('span#fatigue').html(data.fatigue_percentage);
					$('span#fatigue_category').html(data.fatigue_category);
					$('span#avg_bpm').html(data.hr_avg_bpm);
					$('span#avg_spo2').html(data.spo2_avg_pct);
					
				} else { 
					var valnull = 0; var descnull = '-';
					$('span#bpm').html(valnull);
					$('span#bpm_desc').html(descnull);
					$('span#spo2').html(valnull);
					$('span#spo2_desc').html(descnull);
					$('span#sleep').html(valnull);
					$('span#sleep_mins').html(valnull);
					$('span#sleep_desc').html(descnull);
					$('span#fatigue').html(valnull);
					$('span#fatigue_category').html(descnull);
					$('span#avg_bpm').html(valnull);
					$('span#avg_spo2').html(valnull);
					
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


	function getSteps(empid = '', dateperiod = '') {

		//var flemp = $("#flemp option:selected").val();



		$.ajax({
			type: "POST",
			url: module_path + '/get_data_steps',
			data: { flemp: empid, fldateperiod: dateperiod },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('canvas_steps').getContext('2d');

					var chartExist = Chart.getChart("canvas_steps"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.date,
							datasets: [{
								label: 'Steps',
								data: data.steps,
								backgroundColor: '#FFE16D',//'#F178A1',//'#A47B64',
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


	function getSleeps(empid = '', dateperiod = '') {

		//var flemp = $("#flemp option:selected").val();



		$.ajax({
			type: "POST",
			url: module_path + '/get_data_sleeps',
			data: { flemp: empid, fldateperiod: dateperiod },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					const ctx = document.getElementById('canvas_sleeps').getContext('2d');

					var chartExist = Chart.getChart("canvas_sleeps"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const barChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.date,
							datasets: [{
								label: 'hours',
								data: data.sleeps,
								backgroundColor: '#6EABC6',//'#7FBBDD',//'#5B88B2',
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


	function vitalSigns(empid = '', dateperiod = '') {
	    

	    $.ajax({
	        type: "POST",
	        url: module_path + '/get_data_vitalSigns',
	        data: { flemp: empid, fldateperiod: dateperiod },
	        cache: false,
	        dataType: "JSON",
	        success: function (data) {
	            if (data != false) {

	                const ctx = document.getElementById('canvas_vitalsigns').getContext('2d');

	                var chartExist = Chart.getChart("canvas_vitalsigns"); // <canvas> id
	                if (chartExist != undefined)
	                    chartExist.destroy();

	                // const barChart = new Chart(ctx, {
	                //     type: 'bar',
	                //     data: {
	                //         labels: data.ts_utc, // bulan (2025-01, 2025-02, dst)
	                //         datasets: [
	                //             {
	                //                 // label: 'Trip',
	                //                 // type: 'line',
	                //                 // data: data.jumlah_trip,
	                //                 // backgroundColor: '#FED24B',
	                //                 // borderRadius: 3,
	                //                 // yAxisID: 'y'

	                //                 label: 'Spo2',
					// 			    type: 'line',
					// 			    data: data.spo2,
					// 			    borderColor: '#ED1B24',
					// 			    backgroundColor: '#ED1B24',
					// 			    borderWidth: 2,
					// 			    tension: 0.3,   // <-- bikin garis halus
					// 			    fill: false,
					// 			    yAxisID: 'y'/*,
					// 			    borderDash: [4, 4]*/  // <-- bikin putus-putus

	                //             },
	                //             {
	                //                 label: 'BPM',
	                //                 type: 'line',
	                //                 data: data.bpm, 
	                //                 borderColor: '#3939FF',
	                //                 backgroundColor: '#3939FF', //'#0059A9',
	                //                 borderWidth: 2,
	                //                 tension: 0.3,
	                //                 fill: false,
	                //                 yAxisID: 'y1'

	                //             }
	                //         ]
	                //     },
	                //     options: {
	                //         responsive: true,
	                //         maintainAspectRatio: false,
	                //         plugins: {
	                //             datalabels: {
	                //                 formatter: (value, context) => {
	                //                     if (parseFloat(value) === 0) {
	                //                         return ''; // tidak ditampilkan
	                //                     }
	                //                     //return value;
	                //                     return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	                //                 },
	                //                 /*color: '#333',
	                //                 font: {
	                //                     size: 14,
	                //                     weight: 'bold'
	                //                 }*/
	                //                 color: '#000',  //fff
					// 			    textStrokeColor: '#fff',   //000
					// 			    textStrokeWidth: 1,
					// 			    font: {
					// 			        size: 12,
					// 			        weight: 'bold'
					// 			    }
	                //             },
	                //             legend: {
	                //                 display: true,
	                //                 position: 'top',
	                //                 labels: {
	                //                     font: { size: 11 }
	                //                 }
	                //             },
	                //             tooltip: {
	                //                 backgroundColor: '#333',
	                //                 titleColor: '#fff',
	                //                 bodyColor: '#fff',
	                //                 padding: 10,
	                //                 borderRadius: 6,
	                //                 callbacks: {
	                //                     label: function (context) {
	                //                         let value = context.raw;
	                //                         if (context.dataset.label.includes("Rp")) {
	                //                             return context.dataset.label + ": Rp " + new Intl.NumberFormat().format(value);
	                //                         }
	                //                         return context.dataset.label + ": " + value;
	                //                     }
	                //                 }
	                //             }
	                //         },
	                //         scales: {
	                //             y: {
	                //                 beginAtZero: true,
	                //                 position: 'left',
	                //                 title: {
	                //                     display: true,
	                //                     text: 'Jumlah BPM'
	                //                 },
	                //                 grid: {
	                //                     color: '#eee'
	                //                 },
	                //                 ticks: {
	                //                 	stepSize: 1,   // <-- Biar kelipatan 1
	                //                     color: '#666',
	                //                     font: { size: 10 }
	                //                 }
	                //             },
	                //             y1: {
	                //                 beginAtZero: true,
	                //                 position: 'right',
	                //                 title: {
	                //                     display: false,
	                //                     text: 'Jumlah spo2'
	                //                 },
	                //                 grid: {
	                //                     drawOnChartArea: false
	                //                 }/*,
	                //                 ticks: {
	                //                 	stepSize: 1,   // <-- Biar kelipatan 1
	                //                     color: '#666',
	                //                     font: { size: 10 }
	                //                 }*/
	                //             },
	                //             x: {
	                //                 grid: { display: false },
	                //                 ticks: {
	                //                     color: '#666',
	                //                     font: { size: 10 }
	                //                 }
	                //             }
	                //         }
	                //     },
	                //     plugins: [ChartDataLabels]
	                // });


	                const barChart = new Chart(ctx, {
					    type: 'line',
					    data: {
					        labels: data.ts_utc,
					        datasets: [
					            {
					                label: 'Spo2',
					                data: data.spo2,
					                borderColor: '#FED24B',//'#ED1B24',
					                backgroundColor: '#FED24B',//'#ED1B24',
					                borderWidth: 2,
					                tension: 0.4,
					                fill: false,
					                pointRadius: 4,
					                pointHoverRadius: 6,
					                yAxisID: 'y'
					            },
					            {
					                label: 'BPM',
					                data: data.bpm, 
					                borderColor: '#38406F',//'#3939FF',
					                backgroundColor: '#38406F',//'#3939FF',
					                borderWidth: 2,
					                tension: 0.4,
					                fill: false,
					                pointRadius: 4,
					                pointHoverRadius: 6,
					                yAxisID: 'y'
					            }
					        ]
					    },
					    options: {
					        responsive: true,
					        maintainAspectRatio: false,
					        plugins: {
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
					                borderRadius: 6
					            }
					        },
					        scales: {
					            y: {
					                beginAtZero: true,
					                position: 'left',
					                title: {
					                    display: false,
					                    text: 'Jumlah BPM & SpO2'
					                },
					                grid: {
					                    color: 'rgba(200,200,200,0.2)' // grid lebih soft
					                },
					                ticks: {
					                    color: '#666',
					                    font: { size: 10 }
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
					    /*plugins: [ChartDataLabels]*/
					    plugins: []
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
	



	$('#flemp').on('change', function () {
		var flemp = $('#flemp').val(); 
		var fldateperiod = $('#fldateperiod').val();

		dataTotal(flemp,fldateperiod);
		getSteps(flemp,fldateperiod);
		getSleeps(flemp,fldateperiod);
		vitalSigns(flemp,fldateperiod);

	});

	


</script>

<script>
	

</script>