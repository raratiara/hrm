

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<div class="dashboard-container">


	<div class="top-bar">

		<div class="employee-select-wrapper">
			<span class="employee-icon"><i class="fa-solid fa-user"></i></span>
			
			<?=$seldiv?>
		</div>
	</div>

	<div class="summary-container">
		<div class="summary-card navy">
			<div class="card-content">
				<div>
					<div class="title">Total Reimbursement</div>
					<div class="value"><span id="ttl_reimburs">23</span></div> 
					<!-- <div class="earlylogin-line">
	                    <div><strong><span class="ttl_reguler"></span></strong> Reguler</div>
	                    <div><strong><span class="ttl_shift"></span></strong> Shift</div>
	                </div> -->
				</div>
				<i class="icon fas fa-users"></i>
			</div>
		</div>



		<div class="summary-card yellow">
			<div class="card-content">
				<div>
					<div class="title">Total Amount Reimbursement</div>
					<div class="value"><span id="ttl_amount_reimburs">Rp 41.000.000</span></div> 
					<!-- <div class="earlylogin-line">
	                    <div><strong><span class="ttl_managerial"></span></strong> Managerial </div>
	                    <div><strong><span class="ttl_nonmanagerial"></span></strong> Non-Managerial</div>
	                </div> -->
				</div>
				<i class="icon fas fa-calendar-alt"></i>
				
			</div>
		</div>

		<div class="summary-card beige">
			<div class="card-content">
				<div>
					<div class="title">Reimbursement Type</div>
					
	                <div class="tasklist-line">
		                <div class="task-item">
		                    <p class="ttl_grade_a"></p>
		                    <p>Rawat Jalan</p>
		                </div>

		                <div class="task-item">
		                    <p class="ttl_grade_b"></p>
		                    <p>Rawat Inap</p>
		                </div>

		                <div class="task-item">
		                    <p class="ttl_grade_c"></p>
		                    <p>Kacamata</p>
		                </div>
		                <div class="task-item">
		                    <p class="ttl_grade_d"></p>
		                    <p>Persalinan</p>
		                </div>
		            </div>
				</div>
				<i class="icon fas fa-chart-line"></i>
			</div>
		</div>

	</div>

	

	<div class="chart-container">
		<div class="box monthlySumm">
			<div class="box-title">Monthly Reimbursement Summary</div>
			<div class="box-value">
				<canvas id="empby_div_gender" style="margin-top: 15px;"></canvas>
			</div>
		</div>
		<div class="box byDiv">
			<div class="box-title">Reimbursement by Division</div>
			<div class="box-value">
				<canvas id="empby_status" style="margin-top: 15px;"></canvas>
			</div>
		</div>
	</div>


	<div class="employee-container">

		<div class="box chart-box">
			<div class="box-title">Reimbursement For</div>
			<div class="box-value">
				<canvas id="empby_gen" style="margin-top: 15px;"></canvas>
			</div>
		</div>

		<div class="box chart-box">
			<div class="box-title">Reimbursement Type & Subtype</div>
			<div class="box-value">
				<canvas id="empby_maritalStatus" style="margin-top: 15px;"></canvas>
			</div>
		</div>

	</div>
	


</div>


</div>


