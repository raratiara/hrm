<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<input type="hidden" id="list_birthdays" name="list_birthdays">
<input type="hidden" id="currentIndex" name="currentIndex">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>  


<!-- Modal add quick link -->
<div id="modal-quicklink" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-rfu-data" aria-hidden="true" style="padding-left: 600px">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="width:50%; text-align:center;">
			<form class="form-horizontal" id="frmRfuData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Add Quick Link 
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Choose the menu</p>
				<div class="form-group">
					<label class="col-md-2 control-label no-padding-right"></label>
					<div class="col-md-8">
						<?=$selmenu;?>
						<!-- <input type="hidden" name="id" id="id" value=""> -->
					</div>
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-rfu-data" onclick="save_quicklink()">
					<i class="fa fa-check"></i>
					Save
				</button>
				<button class="btn red" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>



<!-- Modal Form Data -->
<div id="modal-form-checkin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-form-checkin"
	aria-hidden="true">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog model-dialog-centered vertical-align-center custom-modal">
			<div class="modal-content" style="width:1000px">
				<form class="form-horizontal" id="frmInputData" enctype="multipart/form-data">
					<div class="modal-header bg-blue bg-font-blue no-padding">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<div class="table-header">
							<span id="mfdata"></span> <!-- <?php echo $smodul; ?>  -->
							
						</div>
					</div>

					<div class="modal-body" style="min-height:100px; margin:10px">
						<input type="hidden" name="id" value="">
						<?php $this->load->view("_field"); ?>
					</div>
				</form>


				<div class="modal-footer d-flex justify-content-between flex-wrap no-margin-top" id="mdlFooter">
					<span class="act-container-btn d-flex flex-wrap gap-2">
						<button class="btn btn-info"
							style="background-color: #343851; color: white; border-radius: 4px !important; margin-right: 5px;"
							id="submit-data" onclick="save()">
							<i class="fa fa-check"></i> Save
						</button>
					</span>

					<!-- Container untuk tombol tetap di kanan -->
					<span class="d-flex gap-2 ms-auto">
						<button class="btn"
							style="background-color: #FED24B; color: #343851; border-radius: 4px !important;"
							onclick="reset()" id="btnReset">
							<i class="fa fa-undo"></i> Reset
						</button>
						<button class="btn"
							style="background-color: #A01818; color: white; border-radius: 4px !important;"
							data-dismiss="modal">
							<i class="fa fa-times"></i> Close
						</button>
					</span>
				</div>



			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var module_path = "<?php echo base_url($folder_name); ?>";
	var base_url = "<?php echo base_url($base_url); ?>";

	$(document).ready(function () {
		$(function () {

			loadQuickLinks();

			$('#fltasklistperiod').daterangepicker({
		        locale: {
		            format: 'YYYY-MM-DD', // format tanggal
		            applyLabel: "Apply",
		            cancelLabel: "Cancel"
		        },
		        autoUpdateInput: false, // biar kosong dulu
		    });

		    // Saat user pilih tanggal
		    $('#fltasklistperiod').on('apply.daterangepicker', function(ev, picker) {
		        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
		    });

		    // Saat user cancel
		    $('#fltasklistperiod').on('cancel.daterangepicker', function(ev, picker) {
		        $(this).val('');
		    });


			load_data();
			monthlyAttSumm();
			dailyTasklist();

		});
	});


	<?php if (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_UPDATE == "1" || _USER_ACCESS_LEVEL_DETAIL == "1")) { ?>

		function load_data() {
			var getUrl = window.location;
			//local=> 
			//var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
			var baseUrl = getUrl.protocol + "//" + getUrl.host;


			$.ajax({
				type: "POST",
				url: module_path + '/get_detail_data',
				data: { id: <?= _USER_EMPLOYEE_ID ?> },
				cache: false,
				dataType: "JSON",
				success: function (data) {
					if (data != false) {
						var division_name = data.dtEmp.division_name;
						if(data.dtEmp.division_name == '' || data.dtEmp.division_name == null){
							var division_name = '[Division]';
						}
						var job_title_name = data.dtEmp.job_title_name;
						if(data.dtEmp.job_title_name == '' || data.dtEmp.job_title_name == null){
							var job_title_name = '[Job Title]';
						}
						var department_name = data.dtEmp.department_name;
						if(data.dtEmp.department_name == '' || data.dtEmp.department_name == null){
							var department_name = '[Department]';
						}
						var job_title_department = job_title_name+' - '+department_name;
						var emp_status_name = data.dtEmp.emp_status_name;
						if(data.dtEmp.emp_status_name == '' || data.dtEmp.emp_status_name == null){
							var emp_status_name = '-';
						}
						var gender_name = data.dtEmp.gender_name;
						if(data.dtEmp.gender_name == '' || data.dtEmp.gender_name == null){
							var gender_name = '-';
						}
						var date_of_birth = data.dtEmp.date_of_birth;
						if(data.dtEmp.date_of_birth == '' || data.dtEmp.date_of_birth == null){
							var date_of_birth = '-';
						}
						var shift_type = data.dtEmp.shift_type;
						if(data.dtEmp.shift_type == '' || data.dtEmp.shift_type == null){
							var shift_type = '-';
						}
						var address_residen = data.dtEmp.address_residen;
						if(data.dtEmp.address_residen == '' || data.dtEmp.address_residen == null){
							var address_residen = '-';
						}
						var direct_name = data.dtEmp.direct_name;
						if(data.dtEmp.direct_name == '' || data.dtEmp.direct_name == null){
							var direct_name = '-';
						}
						var job_level_name = data.dtEmp.job_level_name;
						if(data.dtEmp.job_level_name == '' || data.dtEmp.job_level_name == null){
							var job_level_name = '-';
						}
						
						$('span#spo2').html(data.spo2);
						$('span#spo2_desc').html(data.spo2_desc);
						$('span#bpm').html(data.bpm);
						$('span#bpm_desc').html(data.bpm_desc);
						$('span#sleep').html(data.sleep_hours+" h");
						$('span#sleep_percent').html(data.sleep_percent+"% quality");
						$('span#fatigue_percentage').html(data.fatigue_percentage);
						$('span#fatigue_category').html(data.fatigue_category);
						$('span#lastLog').html(data.lastLog);
						

						$('span.nik').html(data.dtEmp.emp_code);
						$('span.name').html(data.dtEmp.full_name);
						$('span.gender').html(gender_name);
						$('span.date_of_birth').html(date_of_birth);
						$('span.address').html(address_residen);
						$('span.division').html(division_name);
						$('span.department').html(data.dtEmp.department_name);
						$('span.job_title').html(job_title_name);
						$('span.job_level').html(job_level_name);
						$('span.status').html(emp_status_name);
						$('span.date_of_hired').html(data.dtEmp.date_of_hire);
						$('span.phone').html(data.dtEmp.personal_phone);
						$('span.email').html(data.dtEmp.personal_email);
						$('span.shift_type').html(shift_type);
						$('span.direct').html(direct_name);
						$('span.ttl_sisa_cuti').html(data.ttl_sisa_cuti);
						$('p.ttl_tasklist_open').html(data.ttl_tasklist_open);
						$('p.ttl_tasklist_inprogress').html(data.ttl_tasklist_inprogress);
						$('p.ttl_tasklist_closed').html(data.ttl_tasklist_closed);
						$('p.job_title_department').html(job_title_department);
						/*$('span.sisaplafon_rawatjalan').html(data.sisaplafon_rawatjalan);
						$('span.sisaplafon_rawatinap').html(data.sisaplafon_rawatinap);
						$('span.sisaplafon_kacamata').html(data.sisaplafon_kacamata);
						$('span.sisaplafon_persalinan').html(data.sisaplafon_persalinan);*/
						/*$('span.sisa_plafon_all').html(data.sisa_plafon_all);*/
						$('span#amount_rawatjalan').html(data.sisaplafon_rawatjalan);
						$('span#amount_rawatinap').html(data.sisaplafon_rawatinap);
						$('span#amount_kacamata').html(data.sisaplafon_kacamata);
						$('span#amount_persalinan').html(data.sisaplafon_persalinan);
						

						//emp_photo
						if (data.dtEmp.emp_photo != '' && data.dtEmp.emp_photo != null) {
							$('span.emp_photo').html('<img src="' + baseUrl + '/uploads/employee/' + data.dtEmp.emp_code + '/' + data.dtEmp.emp_photo + '" alt="Profile Picture" class="profile-image">');
						} else {
							$('span.emp_photo').html('<img src="' + baseUrl + '/public/assets/images/user.jpg" alt="Profile Picture" class="profile-image">');
						}

						/// DATA BIRTHDAY
						if (data.birthdays.length === 0) {
							document.getElementById('birthday-arrow').style.display = 'none';
							document.getElementById('birthday-image').style.display = 'none';
							document.getElementById("birthday-job").textContent = 'No birthdays today!';
						}else{
							document.getElementById('birthday-arrow').style.display = '';
							document.getElementById('birthday-image').style.display = '';
							let currentIndex = 0; 
							$('#currentIndex').val(currentIndex);
							document.getElementById("list_birthdays").value = JSON.stringify(data.birthdays);
							displayBirthday(currentIndex);
						}


						/// DATA EVENTS OR NEWS

						/*const events = [
							{
								label: 'Today',
								time: '17:00',
								color: 'today',
								title: 'Bergen International Film Festival',
								description: 'Films from all over the world gather all film enthusiasts for unique moments.'
							},
							{
								label: '22 - 31 OCT',
								time: '10:00',
								color: 'yellow',
								title: 'Bergen International Film Festival',
								description: 'Films from all over the world gather all film enthusiasts for unique moments.'
							},
							{
								label: '22 - 31 OCT',
								time: '19:00',
								color: 'orange',
								title: 'Bergen International Film Festival',
								description: 'Films from all over the world gather all film enthusiasts for unique moments.'
							},
							{
								label: '13 - 31 DEC',
								time: '10:00',
								color: 'grey',
								title: 'Bergen International Film Festival',
								description: 'Films from all over the world gather all film enthusiasts for unique moments.'
							}

						];*/

						if (data.events.length === 0) {
							$('span.clnoevents').html('No Events / Info');
						}else{
							const container = document.getElementById('event-list');

							data.events.forEach(event => {
								const div = document.createElement('div');
								div.className = 'event';
								div.innerHTML = `
								<div class="date-box ${event.color}">
								  <div>${event.label1}</div>
								  <div class="time">${event.label2}</div>
								</div>
								<div class="info">
								  <h3>${event.title}</h3>
								  <p>${event.description}</p>
								</div>
							  `;
								container.appendChild(div);
							});
						}


						//data tasklist progress
						
				
						/*const tbody = document.getElementById("tasklistBody");
						tbody.innerHTML = "";

						let previousGroupId = null; // Digunakan untuk mendeteksi perpindahan kelompok utama
						let previousTaskType = null;

						data.taskList.forEach((taskrow, index) => {
						    let due_date = taskrow.due_date ?? '-';
						    let tr = document.createElement("tr");

						    let currentGroupId = taskrow.project_id ?? `parent-${index}`; // Kalau bukan bagian project, buat id sendiri

						    // Tentukan apakah perlu garis
						    const isNotFirst = index > 0;
						    const isParentOrProject = taskrow.task_type === 'project' || taskrow.task_type === 'parent';
						    const isDifferentGroup = currentGroupId !== previousGroupId;

						    if (
						        isNotFirst &&
						        isParentOrProject && // hanya jika sedang masuk task utama baru
						        isDifferentGroup &&
						        previousTaskType !== 'project' // JANGAN kasih garis kalau sebelumnya sudah dalam satu project
						    ) {
						        const hrRow = document.createElement("tr");
						        hrRow.innerHTML = `<td colspan="3"><hr style="border-top:1px solid #ccc; margin:4px 0;"></td>`;
						        tbody.appendChild(hrRow);
						    }

						    // Baris Project
						    if (taskrow.task_type === 'project') {
						        tr.innerHTML = `
						            <td colspan="3" style="font-weight: bold; font-size:10px; background:#eef;">📁 ${taskrow.task}</td>
						        `;
						    } else {
						        let indent = '';
						        if (taskrow.task_type === 'child') {
						            indent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ ';
						        } else if (taskrow.task_type === 'parent') {
						            indent = taskrow.project_id !== null ? '&nbsp;&nbsp;&nbsp;' : '';
						        }

						        tr.innerHTML = `
						            <td style="font-size:10px">${indent}${taskrow.task}</td>
						            <td style="font-size:10px;">${taskrow.progress_percentage ?? '-'}</td>
						            <td style="font-size:10px">${due_date}</td>
						        `;
						    }

						    previousGroupId = currentGroupId;
						    previousTaskType = taskrow.task_type;
						    tbody.appendChild(tr);
						});*/


						/*const tbody = document.getElementById("tasklistBody");
						tbody.innerHTML = "";

						let previousGroupId = null;
						let previousTaskType = null;

						data.taskList.forEach((taskrow, index) => {
						    let due_date = taskrow.due_date ?? '-';
						    let tr = document.createElement("tr");

						    let currentGroupId = taskrow.project_id ?? `parent-${index}`; // Grouping

						    const isNotFirst = index > 0;
						    const isParentOrProject = taskrow.task_type === 'project' || taskrow.task_type === 'parent';
						    const isDifferentGroup = currentGroupId !== previousGroupId;

						    // 1. Jika task_type adalah 'standalone', selalu beri garis
						    if (taskrow.task_type === 'standalone') {
						        const hrRow = document.createElement("tr");
						        hrRow.innerHTML = `<td colspan="3"><hr style="border-top:1px solid #ccc; margin:4px 0;"></td>`;
						        tbody.appendChild(hrRow);
						    }

						    // 2. Garis pemisah antar grup (bukan 'project → parent/child' dalam satu project)
						    else if (
						        isNotFirst &&
						        isParentOrProject &&
						        isDifferentGroup &&
						        previousTaskType !== 'project' &&
						        previousTaskType !== 'standalone' // jangan beri garis kalau sebelumnya standalone
						    ) {
						        const hrRow = document.createElement("tr");
						        hrRow.innerHTML = `<td colspan="3"><hr style="border-top:1px solid #ccc; margin:4px 0;"></td>`;
						        tbody.appendChild(hrRow);
						    }

						    // Baris Project
						    if (taskrow.task_type === 'project') {
						        tr.innerHTML = `
						            <td colspan="3" style="font-weight: bold; font-size:10px; background:#EAF3FF;">📁 ${taskrow.task}</td>
						        `;
						    } else {
						        let indent = '';
						        if (taskrow.task_type === 'child') {
						            indent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ ';
						        } else if (taskrow.task_type === 'parent') {
						            indent = taskrow.project_id !== null ? '&nbsp;&nbsp;&nbsp;' : '';
						        }

						        tr.innerHTML = `
						            <td style="font-size:10px">${indent}${taskrow.task}</td>
						            <td style="font-size:10px;">${taskrow.progress_percentage ?? '-'}</td>
						            <td style="font-size:10px">${due_date}</td>
						        `;
						    }

						    previousGroupId = currentGroupId;
						    previousTaskType = taskrow.task_type;
						    tbody.appendChild(tr);
						});*/




					} else {
						title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
						btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
						msg = '<p>Gagal peroleh data.</p>';
						var dialog = bootbox.dialog({
							message: title + '<center>' + msg + btn + '</center>'
						});
						if (response.status) {
							setTimeout(function () {
								dialog.modal('hide');
							}, 1500);
						}
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
	<?php } ?>



	function monthlyAttSumm() {

		$.ajax({
			type: "POST",
			url: module_path + '/get_data_monthlyAttendanceSumm',
			data: { employee: <?= _USER_EMPLOYEE_ID ?> },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != false) {

					$('span.clyear').html('(' + data.thn + ')');
					const ctx = document.getElementById('monthly_attendance_summ').getContext('2d');

					var chartExist = Chart.getChart("monthly_attendance_summ"); // <canvas> id
					if (chartExist != undefined)
						chartExist.destroy();


					const rawData = [
						{ label: 'Ontime', data: data.total_ontime, backgroundColor: '#2e3267', borderRadius: 3 },
						{ label: 'Late', data: data.total_late, backgroundColor: '#fddb5c', borderRadius: 3 },
						{ label: 'Leaving Early', data: data.total_leaving_early, backgroundColor: '#9b9fd2', borderRadius: 3 },
						{ label: 'No Attendance', data: data.total_noattendance, backgroundColor: '#b3b3b3', borderRadius: 3 },
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
							labels: data.bln,
							datasets: percentageData
						},
						options: {
							responsive: true,
							maintainAspectRatio: true,
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
								        return parseInt(value); // tampilkan tanpa desimal
			                        },
			                        color: '#fff',
			                        font: {
			                            size: 12,
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


	function downloadFile(filename) {
		const link = document.createElement('a');
		link.href = module_path + '/downloadFile?file=' + encodeURIComponent(filename);

		link.setAttribute('download', filename);
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}



</script>

<script>
	/*const events = [
		{
			label: 'Today',
			time: '17:00',
			color: 'today',
			title: 'Bergen International Film Festival',
			description: 'Films from all over the world gather all film enthusiasts for unique moments.'
		},
		{
			label: '22 - 31 OCT',
			time: '10:00',
			color: 'yellow',
			title: 'Bergen International Film Festival',
			description: 'Films from all over the world gather all film enthusiasts for unique moments.'
		},
		{
			label: '22 - 31 OCT',
			time: '19:00',
			color: 'orange',
			title: 'Bergen International Film Festival',
			description: 'Films from all over the world gather all film enthusiasts for unique moments.'
		},
		{
			label: '13 - 31 DEC',
			time: '10:00',
			color: 'grey',
			title: 'Bergen International Film Festival',
			description: 'Films from all over the world gather all film enthusiasts for unique moments.'
		},
		{
			label: '13 - 31 DEC',
			time: '10:00',
			color: 'grey',
			title: 'Bergen International Film Festival',
			description: 'Films from all over the world gather all film enthusiasts for unique moments.'
		},
		{
			label: '13 - 31 DEC',
			time: '10:00',
			color: 'grey',
			title: 'Bergen International Film Festival',
			description: 'Films from all over the world gather all film enthusiasts for unique moments.'
		},
		{
			label: '13 - 31 DEC',
			time: '10:00',
			color: 'grey',
			title: 'Bergen International Film Festival',
			description: 'Films from all over the world gather all film enthusiasts for unique moments.'
		},
		{
			label: '13 - 31 DEC',
			time: '10:00',
			color: 'grey',
			title: 'Bergen International Film Festival',
			description: 'Films from all over the world gather all film enthusiasts for unique moments.'
		}


	];

	const container = document.getElementById('event-list');

	events.forEach(event => {
		const div = document.createElement('div');
		div.className = 'event';
		div.innerHTML = `
		<div class="date-box ${event.color}">
		  <div>${event.label}</div>
		  <div class="time">${event.time}</div>
		</div>
		<div class="info">
		  <h3>${event.title}</h3>
		  <p>${event.description}</p>
		</div>
	  `;
		container.appendChild(div);
	});*/
</script>

<script>

	
	/*const birthdays = [
		{
			name: "Diana Putri",
			job: "Web Developer",
			image: "https://randomuser.me/api/portraits/women/1.jpg"
		},
		{
			name: "Budi Santoso",
			job: "UI Designer",
			image: "https://randomuser.me/api/portraits/men/2.jpg"
		},
		{
			name: "Anita Rahma",
			job: "Project Manager",
			image: "https://randomuser.me/api/portraits/women/3.jpg"
		}
	];

	let currentIndex = 0;*/
	

	function displayBirthday(index) { 
		var getUrl = window.location;
		//local=> 
		//var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
		var baseUrl = getUrl.protocol + "//" + getUrl.host;


		let list_birthdays = $("#list_birthdays").val(); 
		
		var birthdays = JSON.parse(list_birthdays);
		const data = birthdays[index]; 

		if (data.emp_photo != '' && data.emp_photo != null) {
			var image = baseUrl + '/uploads/employee/' + data.emp_code + '/' + data.emp_photo;
		} else {
			var image = baseUrl + '/public/assets/images/user.jpg';
		}

		document.getElementById("birthday-image").src = image;
		document.getElementById("birthday-name").textContent = data.name;
		document.getElementById("birthday-job").textContent = data.divname;
	}

	function showNext() { 
		let list_birthdays = $("#list_birthdays").val(); 
		var birthdays = JSON.parse(list_birthdays);
		var currentIndex = $("#currentIndex").val();

		if (currentIndex < birthdays.length - 1) {
			currentIndex++;
			$('#currentIndex').val(currentIndex);
			displayBirthday(currentIndex);
		}
	}

	function showPrevious() {
		
		var currentIndex = $("#currentIndex").val();

		if (currentIndex > 0) {
			currentIndex--;
			$('#currentIndex').val(currentIndex);
			displayBirthday(currentIndex);
		}
	}

	// Init display
	/*window.onload = () => {
		 
		var currentIndex = $("#currentIndex").val();

		displayBirthday(currentIndex);
	};*/


	function removeRepeated100(dataArray) {
	    let found100 = false;
	    return dataArray.map(value => {
	        if (value === 100 && !found100) {
	            found100 = true;
	            return 100;
	        } else if (found100) {
	            return null; // kosongkan setelah sudah pernah 100
	        } else {
	            return value;
	        }
	    });
	}



	// Fungsi untuk mengisi nilai kosong dengan nilai terakhir
	function fillMissingWithLastValue(dataArray) {
	    let lastValue = null;
	    return dataArray.map(value => {
	        if (value != null && value !== 0) {
	            lastValue = value;
	            return value;
	        } else {
	            return lastValue !== null ? lastValue : 0;
	        }
	    });
	}


	$('#fltasklistperiod').on('apply.daterangepicker', function(ev, picker) {
	    let fromDate = picker.startDate.format('YYYY-MM-DD');
	    let toDate = picker.endDate.format('YYYY-MM-DD');
		// set value ke input
		$(this).val(fromDate + ' - ' + toDate);
	   
	    dailyTasklist();

	});


	$('#fltasklistperiod').on('cancel.daterangepicker', function(ev, picker) {
	    $(this).val(''); // kosongkan input

	    dailyTasklist();
	});


	$('#flstatus').on('change', function () {
		dailyTasklist();
	});



	function dailyTasklist() {

		var fltasklistperiod = document.getElementById("fltasklistperiod").value;
		var flstatus = $("#flstatus option:selected").val();


		
	    $.ajax({
	        type: "POST",
	        url: module_path + '/get_data_dailyTasklist',
	        data: { fltasklistperiod: fltasklistperiod, flstatus: flstatus },
	        cache: false,
	        dataType: "JSON",
	        success: function (data) {

	        	//chart tasklist 
	            const ctx = document.getElementById('daily_tasklist').getContext('2d');
	            const chartExist = Chart.getChart("daily_tasklist");
	            if (chartExist !== undefined) chartExist.destroy();

	            const updatedDatasets = data.datasets.map(ds => ({
	                ...ds,
	                /*data: fillMissingWithLastValue(ds.data),*/ // <--- isi otomatis nilai kosong
	                data: removeRepeated100(fillMissingWithLastValue(ds.data)),
	                /*type: 'line',*/
	                type: 'bar',
	                fill: false,
	                borderColor: ds.backgroundColor.hex, // ambil dari controller
	                backgroundColor: ds.backgroundColor.rgba, // opsional, untuk titik
	                tension: 0.3,
	                pointRadius: 3,
	                pointHoverRadius: 5,
	                borderWidth: 2 //tambahkan ketebalan garis di sini

	            }));

	            new Chart(ctx, {
	            	/*type: 'line',*/
	                type: 'bar',
	                data: {
	                    labels: data.dates,
	                    datasets: updatedDatasets
	                },
	                options: {
	                    responsive: true,
	                    maintainAspectRatio: true,
	                    scales: {
	                        y: {
	                            min: 0,
	                            max: 100,
	                            ticks: {
	                                stepSize: 10
	                            },
	                            title: {
	                                display: true,
	                                text: 'Progress (%)'
	                            }
	                        },
	                        x: {
	                            title: {
	                                display: true,
	                                text: ''
	                            }
	                        }
	                    },
	                    plugins: {
	                        legend: {
	                            position: 'bottom',
	                            labels: {
	                                font: { size: 8 }
	                            }
	                        },
	                        datalabels: {
	                            formatter: function(value) {
	                                return value > 0 ? value : '';
	                            },
	                            font: {
	                                size: 9
	                            },
	                            color: '#000'
	                        }
	                    }
	                },
	                plugins: [ChartDataLabels]
	            });



	            //table tasklist progress
	            // const tbody = document.getElementById("tasklistBody");
				// tbody.innerHTML = "";

				// let previousGroupId = null;
				// let previousTaskType = null;

				// data.taskList.forEach((taskrow, index) => {
				//     let due_date = taskrow.due_date ?? '-';
				//     let tr = document.createElement("tr");

				//     let currentGroupId = taskrow.project_id ?? `parent-${index}`; // Grouping

				//     const isNotFirst = index > 0;
				//     const isParentOrProject = taskrow.task_type === 'project' || taskrow.task_type === 'parent';
				//     const isDifferentGroup = currentGroupId !== previousGroupId;

				//     // 1. Jika task_type adalah 'standalone', selalu beri garis
				//     if (taskrow.task_type === 'standalone') {
				//         const hrRow = document.createElement("tr");
				//         hrRow.innerHTML = `<td colspan="3"><hr style="border-top:1px solid #ccc; margin:4px 0;"></td>`;
				//         tbody.appendChild(hrRow);
				//     }

				//     // 2. Garis pemisah antar grup (bukan 'project → parent/child' dalam satu project)
				//     else if (
				//         isNotFirst &&
				//         isParentOrProject &&
				//         isDifferentGroup &&
				//         previousTaskType !== 'project' &&
				//         previousTaskType !== 'standalone' // jangan beri garis kalau sebelumnya standalone
				//     ) {
				//         const hrRow = document.createElement("tr");
				//         hrRow.innerHTML = `<td colspan="3"><hr style="border-top:1px solid #ccc; margin:4px 0;"></td>`;
				//         tbody.appendChild(hrRow);
				//     }

				//     // Baris Project
				//     if (taskrow.task_type === 'project') {
				//         tr.innerHTML = `
				//         <td colspan="3" style="font-weight: bold; font-size:10px; background:#EAF3FF;">📁 ${taskrow.task}</td>
				//         `;
				//     } else {
				//         let indent = '';
				//         if (taskrow.task_type === 'child') {
				//             indent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ ';
				//         } else if (taskrow.task_type === 'parent') {
				//             indent = taskrow.project_id !== null ? '&nbsp;&nbsp;&nbsp;' : '';
				//         }

				//         tr.innerHTML = `
				//             <td style="font-size:10px">${indent}${taskrow.task}</td>
				//             <td style="font-size:10px;">${taskrow.progress_percentage ?? '-'}</td>
				//             <td style="font-size:10px">${due_date}</td>
				//         `;
				//     }

				//     previousGroupId = currentGroupId;
				//     previousTaskType = taskrow.task_type;
				//     tbody.appendChild(tr);
				// });




				const tbody = document.getElementById("tasklistBody");
				tbody.innerHTML = "";

				let previousGroupId = null;
				let previousTaskType = null;

				data.taskList.forEach((taskrow, index) => {
				    let due_date = taskrow.due_date ?? '-';
				    let tr = document.createElement("tr");

				    let currentGroupId = taskrow.project_id ?? `parent-${index}`;

				    const isNotFirst = index > 0;
				    const isParentOrProject = taskrow.task_type === 'project' || taskrow.task_type === 'parent';
				    const isDifferentGroup = currentGroupId !== previousGroupId;

				    // garis standalone
				    if (taskrow.task_type === 'standalone') {
				        const hrRow = document.createElement("tr");
				        hrRow.innerHTML = `<td colspan="3"><hr style="border-top:1px solid #ccc; margin:4px 0;"></td>`;
				        tbody.appendChild(hrRow);
				    }
				    // garis antar grup
				    else if (
				        isNotFirst &&
				        isParentOrProject &&
				        isDifferentGroup &&
				        previousTaskType !== 'project' &&
				        previousTaskType !== 'standalone'
				    ) {
				        const hrRow = document.createElement("tr");
				        hrRow.innerHTML = `<td colspan="3"><hr style="border-top:1px solid #ccc; margin:4px 0;"></td>`;
				        tbody.appendChild(hrRow);
				    }

				    // tentukan warna utk seluruh row
				    let rowStyle = "";
				    if (taskrow.due_flag === "overdue") {
				        rowStyle = "color:red;";
				    } else if (taskrow.due_flag === "near_due") {
				        rowStyle = "color:orange;";
				    }

				    // Project row
				    if (taskrow.task_type === 'project') {
				        tr.innerHTML = `
				            <td colspan="3" style="font-weight: bold; font-size:10px; background:#EAF3FF;">📁 ${taskrow.task}</td>
				        `;
				    } else {
				        let indent = '';
				        if (taskrow.task_type === 'child') {
				            indent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ ';
				        } else if (taskrow.task_type === 'parent') {
				            indent = taskrow.project_id !== null ? '&nbsp;&nbsp;&nbsp;' : '';
				        }

				        tr.innerHTML = `
				            <td style="font-size:10px">${indent}${taskrow.task}</td>
				            <td style="font-size:10px;">${taskrow.progress_percentage ?? '-'}</td>
				            <td style="font-size:10px">${due_date}</td>
				        `;
				    }

				    // apply style ke seluruh baris
				    tr.setAttribute("style", rowStyle);

				    previousGroupId = currentGroupId;
				    previousTaskType = taskrow.task_type;
				    tbody.appendChild(tr);
				});




	        }
	    });
	}


	function dailyTasklist_witarea() {
	    $.ajax({
	        type: "POST",
	        url: module_path + '/get_data_dailyTasklist',
	        dataType: "JSON",
	        success: function (data) {
	            const ctx = document.getElementById('daily_tasklist').getContext('2d');
	            const chartExist = Chart.getChart("daily_tasklist");
	            if (chartExist !== undefined) chartExist.destroy();

	            const updatedDatasets = data.datasets.map(ds => {
	                // Ubah hex color ke rgba dengan transparansi
	                const hex = ds.backgroundColor.replace('#', '');
	                const r = parseInt(hex.substring(0, 2), 16);
	                const g = parseInt(hex.substring(2, 4), 16);
	                const b = parseInt(hex.substring(4, 6), 16);
	                const transparentBg = `rgba(${r}, ${g}, ${b}, 0.2)`; // transparan 20%

	                return {
	                    ...ds,
	                    type: 'line',
	                    fill: true, // ✅ Aktifkan area chart
	                    borderColor: ds.backgroundColor,
	                    backgroundColor: transparentBg, // ✅ Area transparan
	                    tension: 0.3,
	                    pointRadius: 3,
	                    pointHoverRadius: 5,
	                    borderWidth: 2
	                };
	            });

	            new Chart(ctx, {
	                type: 'line',
	                data: {
	                    labels: data.dates,
	                    datasets: updatedDatasets
	                },
	                options: {
	                    responsive: true,
	                    maintainAspectRatio: false,
	                    plugins: {
	                        legend: {
	                            position: 'bottom',
	                            labels: {
	                                font: { size: 8 }
	                            }
	                        },
	                        datalabels: {
	                            formatter: function(value) {
	                                return value > 0 ? value : '';
	                            },
	                            font: {
	                                size: 9
	                            },
	                            color: '#000'
	                        }
	                    }
	                },
	                plugins: [ChartDataLabels]
	            });
	        }
	    });
	}



	$( "#btnAddData" ).on('click', function(){ 
		form_check = $("#frmInputData").valid();
		if(!form_check) return false;

		var module_name = '<?=$this->module_name?>'; 
		
		save_method = 'add'; 
		$('#mfdata').text('Add'); 
		//if(module_name == 'absensi_menu'){ 
			var hdnempid = $("#hdnempid").val();
			$("#location ").prop('disabled', false);
			$('#mfdata').text('Form Check-IN');
			document.getElementById("submit-data").innerText = "Check In";


			var locate = 'table.task-list';
			var wcount = 0;
			$.ajax({type: 'post',url: module_path+'/gettasklistrow',data: { id:hdnempid, checkin:true },success: function (response) { 
					var obj = JSON.parse(response);
					$(locate+' tbody').html(obj[0]);
					
					wcount=obj[1];
				}
			}).done(function() {
				//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
				tSawBclear(locate);
				///expenseviewadjust(lstatus);
			});


		//}
		$('#modal-form-checkin').modal('show'); 
	});


	function getdatacheckout()
	{
		//expire();
	    save_method = 'update';
		//idx = id;
		//reset();


		var getUrl = window.location;
		//local=> 
		var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
		//var baseUrl = getUrl .protocol + "//" + getUrl.host;


	    $.ajax({
			type: "POST",
	        url : module_path+'/get_data_checkout',
			data: {},
			cache: false,		
	        dataType: "JSON",
	        success: function(data)
	        {
				if(data != false){ 
					if(save_method == 'update'){  

						if(data.isupdate == 1){
							var date_attendance_out = getFormattedDateTime();

							$('[name="id"]').val(data.dataabsen[0].id);
							$('[name="date_attendance"]').val(data.dataabsen[0].date_attendance);
							$('[name="hdnempid"]').val(data.dataabsen[0].employee_id);
							$('[name="employee"]').val(data.dataabsen[0].employee_name);
							$('[name="emp_type"]').val(data.dataabsen[0].attendance_type);
							$('[name="time_in"]').val(data.dataabsen[0].time_in);
							$('[name="time_out"]').val(data.dataabsen[0].time_out);
							$('[name="attendance_in"]').val(data.dataabsen[0].date_attendance_in);
							$('[name="attendance_out"]').val(date_attendance_out);
							$('[name="description"]').val(data.dataabsen[0].notes);
							$('select#location').val(data.dataabsen[0].work_location).trigger('change.select2');
							
							var latitude=''; var longitude='';
							if(data.dataabsen[0].lat_checkout != null && data.dataabsen[0].long_checkout != null){
								var latitude = data.dataabsen[0].lat_checkout;
								var longitude = data.dataabsen[0].long_checkout;
							}
							else if(data.dataabsen[0].lat_checkin != null && data.dataabsen[0].long_checkin != null){
								var latitude = data.dataabsen[0].lat_checkin;
								var longitude = data.dataabsen[0].long_checkin;
							}
							


							if(data.dataabsen[0].photo != '' && data.dataabsen[0].photo != null){
								$('span.photo').html('<img src="'+baseUrl+'/uploads/absensi/'+data.dataabsen[0].photo+'" width="150" height="150" >');
							}else{
								$('span.photo').html('');
							}
							
							var locate = 'table.task-list';
							var wcount = 0; //for ca list row identify
							// get data tasklist
							$.ajax({type: 'post',url: module_path+'/gettasklistrow',data: { id:data.dataabsen[0].employee_id },success: function (response) {
									var obj = JSON.parse(response);
									$(locate+' tbody').html(obj[0]);
									
									wcount=obj[1];
								}
							}).done(function() {
								//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
								tSawBclear(locate);
								///expenseviewadjust(lstatus);
							});
							// end get data tasklist

							
							$.uniform.update();
							$('#mfdata').text('Form Check-OUT');
							document.getElementById("submit-data").innerText = "Check Out";

							$('#modal-form-checkin').modal('show');

						}else{
							alert("Cannot Checkout");
						}

					}
				
				} else {
					title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
					btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
					msg = '<p>Gagal peroleh data.</p>';
					var dialog = bootbox.dialog({
						message: title+'<center>'+msg+btn+'</center>'
					});
					if(response.status){
						setTimeout(function(){
							dialog.modal('hide');
						}, 1500);
					}
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


	function tSawBclear(elem){
		Tablesaw.init(elem);
		var ts = $(elem);
		$(document).off("." + ts.attr("id"));
		$(window).off("." + ts.attr("id"));
		ts.removeData('tablesaw');
	}


	function save(status='')
	{ 
		// if(status != ''){
		// 	$('[name="status"]').val(status);
		// }
		

		//xpire();
	    var title;
	    var send_url;
	    var form_check;
	    var post_type = 'POST';
	    var smsg;
		var formData;

		
		if(save_method == 'add' || save_method == 'update') {
			form_check = $("#frmInputData").valid();
			if(!form_check) return false;
		}
		

		if(save_method == 'add') {
			send_url = module_path+'/add';
			formData = new FormData($('#frmInputData')[0]);
		}
		 
		
		if(save_method == 'update'){
			send_url = module_path+'/edit';
			formData = new FormData($('#frmInputData')[0]);
		} 
		




		if((save_method == 'add') || (save_method == 'update')) { 
			$.ajax({
				type: post_type,
				url: send_url,
				data: formData,
				contentType: false,
				processData: false,
				cache: false,
				dataType: "JSON",
				success: function( response ) {
					if(response.status){ 
						title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-check-circle-o fa-5x" style="color:green"></i></div>';
						btn = '';
						if(save_method == 'add' || save_method == 'update') {
							$('#frmInputData')[0].reset();
							$('#modal-form-checkin').modal('hide');
						} 

						//reload_table();
						// redirect ke halaman absensi_menu
    					window.location.href = base_url + "time_attendance/absensi_menu";
    					
					} else {
						title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
						btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
					}
					var dialog = bootbox.dialog({
						message: title+'<center>'+response.msg+btn+'</center>'
					});
					if(response.status){
						setTimeout(function(){
							dialog.modal('hide');
						}, 1500);
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
		
	}


	function getFormattedDateTime() {
	  const now = new Date();

	  const pad = (n) => n.toString().padStart(2, '0');

	  const year = now.getFullYear();
	  const month = pad(now.getMonth() + 1);     // bulan dimulai dari 0
	  const day = pad(now.getDate());
	  const hours = pad(now.getHours());
	  const minutes = pad(now.getMinutes());
	  const seconds = pad(now.getSeconds());

	  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
	}

	function goHealthDetails(){
		var base_url = "<?php echo base_url($base_url); ?>";
	 
		
		window.open(base_url+"profile/detail_health_menu", "_blank");
	}


	function addQuickLink() {
	    $('#modal-quicklink').modal('show');
	}


	function save_quicklink(){
		/*var id 		= $("#id").val();*/
		var menu 	= $("#menu").val();

		$('#modal-quicklink').modal('hide');
		
		if(menu != ''){
			$.ajax({
				type: "POST",
		        url : module_path+'/add_quick_link',
				data: { menu: menu },
				cache: false,		
		        dataType: "JSON",
		        success: function(data)
		        { 
		        	
					if(data != false){ 	
						alert("The menu has been successfully at quick link.");
					} else { 
						alert("Failed to save the data!");
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
		}else{
			alert("Data not found!");
		}

		location.reload();


	}


	function loadQuickLinks() {
		var base_url = "<?php echo base_url($base_url); ?>"; 


	    $.ajax({
	        url: "<?= site_url('profile/profile_menu/get_data_quicklink') ?>",
	        type: "POST",
	        dataType: "json",
	        success: function(res) {
	            let container = $("#quickLinksContainer");
	            container.empty();

	            // Tambahkan button Check-in & Check-out di awal
	            let actionButtons = `
	                <div class="action-button d-flex">
	                    <input type="hidden" id="hdnempid" name="hdnempid" value="<?= $empid ?>" />
	                    <button class="btn btn-checkin" id="btnAddData">
	                        <i class="fa fa-sign-in"></i> Check-in
	                    </button>
	                    <button class="btn btn-checkout" onclick="getdatacheckout()">
	                        <i class="fa fa-sign-out"></i> Check-out
	                    </button>
	                </div>
	            `;
	            container.append(actionButtons);

	            if(res.length > 0){
	                res.forEach(item => {
	                	var url_menu = base_url+item.url;
	                    let div = `
	                        <div class="quick-link-item" onclick="window.location.href='${url_menu}'">
	                            <i class="${item.icon ?? 'fa fa-link'}"></i>
	                            <span title="${item.title}">${item.title}</span>
	                        </div>
	                    `;
	                    container.append(div);
	                });
	            } else {
	                container.html("<small class='text-muted'>Belum ada quick link</small>");
	            }
	        },
	        error: function(xhr, status, err) {
	            console.error(err);
	        }
	    });
	}


</script>