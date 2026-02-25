

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
					<div class="title">Total Cash Advance</div>
					<div class="value"><span id="ttl_ca"></span></div> 
				
				</div>
				<i class="icon fas fa-users"></i>
			</div>
		</div>



		<div class="summary-card purple">
			<div class="card-content">
				<div>
					<div class="title">Total Amount Cash Advance</div>
					<div class="value"><span id="ttl_amount_ca"></span></div> 
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
					<div class="title">Cash Advance Type</div>
					
	                <div class="tasklist-line">
		                <div class="task-item">
		                    <p class="total_fpu"></p>
		                    <p>FPU</p>
		                </div>

		                <div class="task-item">
		                    <p class="total_fpp"></p>
		                    <p>FPP</p>
		                </div>

		                <div class="task-item">
		                    <p class="total_settlement"></p>
		                    <p>Settlement</p>
		                </div>
		               
		            </div>
				</div>
				<i class="icon fas fa-chart-line"></i>
			</div>
		</div>

	</div>

	

	<div class="chart-container">
		<div class="box monthlySumm">
			<div class="box-title">Monthly Cash Advance Summary</div>
			<div class="box-value">
				<canvas id="monthly_ca_summ" style="margin-top: 15px;"></canvas>
			</div>
		</div>
		<div class="box byDiv">
			<div class="box-title">Cash Advance by Division</div>
			<div class="box-value">
				<canvas id="cabyDiv" style="margin-top: 15px;"></canvas>
			</div>
		</div>
	</div>


	<div class="employee-container">

		<div class="box typeSubtype">
			<div class="box-title">Outstanding Settlement</div>
			<div class="box-value">
				<canvas id="outstandingSett" style="margin-top: 15px;"></canvas>
			</div>
		</div>


		<div class="box reimbursfor">
			<div class="box-title">FPP Type</div>
			<div class="box-value">
				<canvas id="fppType" style="margin-top: 15px;"></canvas>
			</div>
		</div>

	</div>

	<div class="employee-container">

		<div class="box typeSubtype">
			<div class="box-title">Monthly Cash Advance Amount</div>
			<div class="box-value">
				<canvas id="monthly_ca_amount" style="margin-top: 15px;"></canvas>
			</div>
		</div>


	</div>
	


</div>


</div>


