

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<div class="dashboard-container">


	<div class="top-bar">

		<div class="date-picker-wrapper">
			<span class="date-icon"><i class="fas fa-calendar-alt"></i></span>
			<input type="month" id="fldashdateperiod" name="fldashdateperiod" placeholder="Select Date"
				class="date-input" />
		</div>


		<div class="employee-select-wrapper">
			<span class="employee-icon"><i class="fas fa-user"></i></span>
			<!-- <select id="fldashemp" name="fldashemp" class="dropdown-select" required>
				<option value="">Select Employee</option>
				<?php foreach ($master_emp as $row): ?>
					<option value="<?= $row->id ?>"><?= $row->full_name ?></option>
				<?php endforeach; ?>
			</select> -->
			<?=$selemp?>
		</div>
	</div>


	<div class="summary-container">
		<div class="summary-card navy">
			<div class="card-content">
				<div>
					<div class="title">Total Employee</div>
					<div class="value"><span id="ttl_employee"></span></div>
				</div>
				<i class="icon fas fa-users"></i>
			</div>
		</div>



		<div class="summary-card yellow">
			<div class="card-content">
				<div>
					<div class="title">Absences Days</div>
					<div class="value"><span id="ttl_attendance"></span></div>
				</div>
				<i class="icon fas fa-calendar-alt"></i>
				
			</div>
		</div>

		<div class="summary-card beige">
			<div class="card-content">
				<div>
					<div class="title">Early vs Late Logins</div>
					<!-- <div class="value"><span id="ttl_projects"></span></div> -->
				 	<div class="earlylogin-line">
	                    <div><strong><span class="ttl_earlylogin"></span></strong> Early</div>
	                    <div><strong><span class="ttl_latelogin"></span></strong> Late</div>
	                </div>
				</div>
				<i class="icon fas fa-chart-line"></i>
			</div>
		</div>

		<div class="summary-card white">
			<div class="card-content">
				<div>
					<div class="title">Leave Taken</div>
					<div class="value"><span id="ttl_leave"></span></div>
				</div>
				<div class="icon-2">
					<i class="fas fa-user"></i><i class="fas fa-arrow-down" style="font-size: 15px;"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="chart-container">
		<div class="box">
			<div class="box-title">Daily Attendance Summary</div>
			<div class="box-value">
				<canvas id="daily_att_summ" style="margin-top: 15px;"></canvas>
			</div>
		</div>
		
	</div>

	<div class="chart-container">
		<div class="box">
			<div class="box-title">Monthly Attendance Summary</div>
			<div class="box-value">
				<canvas id="monthly_att_summ"></canvas>
			</div>
		</div>
		<div class="box">
			<div class="box-title">Attendance Statistics</div>
			<div class="box-value">
				<canvas id="att_statistic" style="margin-top: 15px;"></canvas>
			</div>
		</div>
	</div>

	<div class="chart-row">
		<div class="chart-boxes">
			<div class="box">
				<div class="box-title">Attendance Percentage</div>
				<div class="box-value">
					<canvas id="att_percentage"></canvas>
				</div>
			</div>
			<div class="box">
				<div class="box-title">Average working hours</div>
				<div class="box-value">
					<canvas id="workhrs_percentage"></canvas>
				</div>
			</div>
		</div>

		<div class="right-container">
			<div class="summary-card grey">
				<div class="card-content">
					<div>
						<div class="title">Overtimes</div>
						<div class="value"><span id="ttl_overtime"></span></div>
					</div>
					<i class="icon-2 fas fa-clock"></i>
				</div>
			</div>
			<div class="summary-card grey">
				<div class="card-content">
					<div>
						<div class="title">Public Holidays</div>
						<div class="value"><span id="ttl_holidays"></span></div>
					</div>
					<div class="icon-2">
						<i class="fa fa-calendar-check-o"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="employee-container">

		<div class="box chart-box">
			<div class="box-title">Working Location</div>
			<div class="box-value">
				<canvas id="workLocation"></canvas>
			</div>
		</div>

		<div class="box-1 table-box">
			<div class="title">5 Top Employees by Attendance</div>
			<div class="table-container">
				<table>
					<thead>
						<tr>
							<th style="width: 40%;">Full Name & Email</th>
							<th style="width: 25%;">Division</th>
							<th style="width: 15%;">Work Hours</th>
							<th style="width: 10%;">Late</th>
						</tr>
					</thead>
					<tbody id="employeeBody">
						<!-- Data will be injected here by JS -->
					</tbody>
				</table>

			</div>
		</div>
	</div>


</div>






<!-- <div class="box box-15">
		<div class="box-title">Employees by Department & Gender</div>
		<div class="box-value">
			<canvas id="empby_dept_gender"></canvas>
		</div>
	</div> -->

</div>



