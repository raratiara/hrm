

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<div class="dashboard-container">


	<div class="top-bar">

		<div class="employee-select-wrapper">
			<span class="employee-icon"><i class="fa-solid fa-user"></i></span>
			
			<?=$seldiv?>
		</div>
	</div>

	<!-- <div class="summary-container">
		<div class="summary-card navy">
			<div class="card-content">
				<div>
					<div class="title">Shift Type</div>
					
					<div class="earlylogin-line">
	                    <div><strong><span class="ttl_reguler"></span></strong> Reguler</div>
	                    <div><strong><span class="ttl_shift"></span></strong> Shift</div>
	                </div>
				</div>
				<i class="icon fas fa-users"></i>
			</div>
		</div>



		<div class="summary-card yellow">
			<div class="card-content">
				<div>
					<div class="title">Job Level</div>
					<div class="earlylogin-line">
	                    <div><strong><span class="ttl_managerial"></span></strong> Managerial </div>
	                    <div><strong><span class="ttl_nonmanagerial"></span></strong> Non-Managerial</div>
	                </div>
				</div>
				<i class="icon fas fa-calendar-alt"></i>
				
			</div>
		</div>

		<div class="summary-card beige">
			<div class="card-content">
				<div>
					<div class="title">Grade</div>
					
	                <div class="tasklist-line">
		                <div class="task-item">
		                    <p class="ttl_grade_a"></p>
		                    <p>Grade A</p>
		                </div>

		                <div class="task-item">
		                    <p class="ttl_grade_b"></p>
		                    <p>Grade B</p>
		                </div>

		                <div class="task-item">
		                    <p class="ttl_grade_c"></p>
		                    <p>Grade C</p>
		                </div>
		                <div class="task-item">
		                    <p class="ttl_grade_d"></p>
		                    <p>Grade D</p>
		                </div>
		            </div>
				</div>
				<i class="icon fas fa-chart-line"></i>
			</div>
		</div>

	</div> -->

	

	<div class="chart-container">
		<div class="box">
			<div class="box-title">Top 5 Performers</div>
			<div class="box-value">
				<canvas id="chartTopPerformers" style="margin-top: 15px;"></canvas>
			</div>
		</div>
		<div class="box">
			<div class="box-title">Achievement Average (%)</div>
			<div class="box-value">
				<canvas id="chartAchieveTarget" style="margin-top: 15px;"></canvas>
			</div>
		</div>
	</div>


	<div class="employee-container">

		<!-- <div class="box chart-box">
			<div class="box-title">Employees by Generation</div>
			<div class="box-value">
				<canvas id="empby_gen" style="margin-top: 15px;"></canvas>
			</div>
		</div> -->

		<div class="box chart-box">
			<div class="box-title">SoftSkill Gap Analysis</div>
			<div class="box-value">
				<canvas id="chartsoftskillAnalysis" style="margin-top: 15px;"></canvas>
			</div>
		</div>

		<div class="box chart-box">
			<div class="box-title">Score by Division</div>
			<div class="box-value">
				<canvas id="chartDivScore"></canvas>
			</div>
		</div>

		
		
	</div>
	


</div>


</div>


