<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<input type="hidden" id="list_birthdays" name="list_birthdays">
<input type="hidden" id="currentIndex" name="currentIndex">

<script type="text/javascript">
	var module_path = "<?php echo base_url($folder_name); ?>";

	$(document).ready(function () {
		$(function () {

			load_data();
			monthlyAttSumm();

		});
	});


	<?php if (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_UPDATE == "1" || _USER_ACCESS_LEVEL_DETAIL == "1")) { ?>

		function load_data() {
			var getUrl = window.location;
			//local=> 
			var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
			//var baseUrl = getUrl.protocol + "//" + getUrl.host;


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
						var emp_status_name = data.dtEmp.department_name;
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
						$('span.ttl_plafon_reimburs').html(data.ttl_plafon_reimburs);
						$('span.ttl_tasklist_open').html(data.ttl_tasklist_open);
						$('span.ttl_tasklist_inprogress').html(data.ttl_tasklist_inprogress);
						$('span.ttl_tasklist_closed').html(data.ttl_tasklist_closed);
						$('span.job_title_department').html(job_title_department);
						
						

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
		var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
		//var baseUrl = getUrl.protocol + "//" + getUrl.host;


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
</script>