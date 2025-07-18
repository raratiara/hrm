<!-- <div class="chart_monthly_att_summ">
		<h2>Monthly Attendance Summary</h2>
		<canvas id="monthly_att_summ"></canvas>
	  </div> -->



<!-- <div class="dashboard">
		<div class="info-box" style="width:180px; height: 60px;">
		  <div class="info-title">Select Date</div>
		  <div class="info-value"> </div>
		  <div class="info-footer"></div>
		</div>
		<div class="info-box" style="width:180px; height: 60px">
		  <div class="info-title">Select Employee</div>
		  <div class="info-value"> </div>
		  <div class="info-footer"></div>
		</div>
		<div class="info-box" style="width:180px; height: 60px">
		  <div class="info-title">Total Employee</div>
		  <div class="info-value"> </div>
		  <div class="info-footer"></div>
		</div>
		<div class="info-box" style="width:180px; height: 60px">
		  <div class="info-title">Projects</div>
		  <div class="info-value"> </div>
		  <div class="info-footer"></div>
		</div>
		<div class="info-box" style="width:180px; height: 100px">
		  <div class="info-title">Attendance Percentage</div>
		  <div class="info-value"> </div>
		  <div class="info-footer"></div>
		</div> -->


<!-- <div class="info-box">
		  <div class="info-title">Total Employee</div>
		  <div class="info-value">12,450 </div>
		  <div class="info-footer">Updated 5 mins ago</div>
		</div>

		<div class="info-box">
		  <div class="info-title">Projects</div>
		  <div class="info-value">340 </i></div>
		  <div class="info-footer"></div>
		</div>
		
		<div class="info-box">
		  <div class="info-title">Attendance</div>
		  <div class="info-value">2,345</div>
		  <div class="info-footer">This month</div>
		</div>

		<div class="info-box">
		  <div class="info-title">Leave Taken</div>
		  <div class="info-value">895</div>
		  <div class="info-footer"></div>
		</div>

		<div class="info-box">
		  <div class="info-title">Reimbursement Amount</div>
		  <div class="info-value">Rp 121.750.000 </div>
		  <div class="info-footer">This month</div>
		</div>

		<div class="info-box">
		  <div class="info-title">Overtime</div>
		  <div class="info-value">327 hrs </div>
		  <div class="info-footer"></div>
		</div> -->

<!-- </div> -->




<!-- <div class="chart_att_statistic">
		<h2>Attendance Statistics</h2>
		<canvas id="att_statistic"></canvas>
	  </div>


	  <div class="chart_empbydeptgender">
		<h2>Employees by Department & Gender</h2>
		<canvas id="empby_dept_gender"></canvas>
	  </div>


	  
	<div class="chart_empbygen">
		<h2>Employees by Generation</h2>
		<canvas id="empby_gen"></canvas>
	  </div> -->


<!-- <div class="chart_attpercentage">
		<h2>Attendance Percentage</h2>
		<canvas id="att_percentage"></canvas>
	  </div> -->

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





<!-- <div class="dashboard-flex">
	  
	  <div class="column">
		<div class="box box-1">Box 1</div>
		<div class="box box-6">Box 6</div>
		<div class="box box-11">11</div>
	  </div>

	  <div class="column">
		<div class="box box-2">Box 2</div>
		<div class="box box-7">Box 7</div>
		<div class="box box-12">Box 12</div>
	  </div>

	  <div class="column">
		<div class="box box-3">Box 3</div>
		<div class="box box-8">Box 8</div>
		<div class="box box-13">Box 13</div>
		<div class="box box-16">Box 16</div>
	  </div>

	  <div class="column">
		<div class="box box-4">Box 4</div>
		<div class="box box-9">Box 9</div>
		<div class="box box-14">Box 14</div>
	  </div>

	  <div class="column">
		<div class="box box-5">Box 5</div>
		<div class="box box-10">Box 10</div>
		<div class="box box-15">Box 15</div>
	  </div>
	</div>
 -->